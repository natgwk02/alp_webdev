<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        if (!session()->has('checkout_data')) {
            return redirect()->route('cart.index')->with('error', 'Silakan tambahkan produk ke keranjang terlebih dahulu.');
        }

        $checkoutData = session('checkout_data');

        $total = $checkoutData['subtotal'] + $checkoutData['shipping'] + $checkoutData['tax'] - $checkoutData['voucher_discount'];

        $defaultData = [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'phone' => '+56912345678',
            'address' => '123 Main St, Santiago, Chile',
            'city' => 'Santiago',
            'zip' => '8320000',
            'country' => 'Chile'
        ];

        $shippingFee = ($defaultData['country'] === 'Indonesia') ? 50000 : 150000;

        $subtotal = $checkoutData['subtotal'];
        $tax = $checkoutData['tax'];
        $voucherDiscount = $checkoutData['voucher_discount'];
        $total = $subtotal + $shippingFee + $tax - $voucherDiscount;

        return view('customer.checkout', [
            'cartItems' => $checkoutData['items'],
            'subtotal' => $checkoutData['subtotal'],
            'shippingFee' => $checkoutData['shipping'],
            'tax' => $checkoutData['tax'],
            'total' => $total,
            'voucherDiscount' => $checkoutData['voucher_discount'],
            'defaultData' => $defaultData
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);

        if (!session()->has('checkout_data')) {
            return redirect()->route('cart.index')->with('error', 'Sesi checkout telah kadaluarsa.');
        }

        $checkoutData = session('checkout_data');
        $userId = Auth::id();
        $user = Auth::user();

        $shippingFee = ($validated['country'] === 'Indonesia') ? 50000 : 150000;
        $subtotal = $checkoutData['subtotal'];
        $tax = $checkoutData['tax'];
        $voucherDiscount = $checkoutData['voucher_discount'];
        $total = $subtotal + $shippingFee + $tax - $voucherDiscount;

        DB::beginTransaction();

        try {
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(uniqid());

            $order = Order::create([
                'users_id' => $userId,
                'invoice_number' => $invoiceNumber,
                'orders_date' => now(),
                'orders_status' => 'pending',
                'first_name' => $validated['firstName'],
                'last_name' => $validated['lastName'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'zip' => $validated['zip'],
                'country' => $validated['country'],
                'payment_status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'tax' => $tax,
                'voucher_discount' => $voucherDiscount,
                'total' => $total,
                'orders_total_price' => $total,
                'notes' => $request->input('sellerNotes'),
                'status_del' => false,
                'payment_url' => null,
            ]);

            foreach ($checkoutData['items'] as $item) {
                OrderDetail::create([
                    'orders_id' => $order->orders_id,
                    'products_id' => $item['id'],
                    'order_details_quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['quantity'],
                    'status_del' => false,
                ]);
            }

            // Bersihkan item cart user
            $cart = \App\Models\Cart::where('users_id', $userId)->first();
            if ($cart) {
                \App\Models\CartItem::where('cart_id', $cart->id)
                    ->whereIn('products_id', collect($checkoutData['items'])->pluck('id'))
                    ->delete();
            }

            session()->forget('checkout_data');

            // Midtrans Config
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $invoiceNumber,
                    'gross_amount' => $total,
                ],
                'customer_details' => [
                    'first_name' => $validated['firstName'],
                    'last_name' => $validated['lastName'],
                    'email' => $user->email,
                    'phone' => $validated['phone'],
                ],
                'callbacks' => [
                    'finish' => route('payment.return', ['order' => $order->orders_id]),
                ],
            ];

            $snapUrl = Snap::createTransaction($params)->redirect_url;

            $order->payment_url = $snapUrl;
            $order->save();

            DB::commit();

            return redirect($snapUrl);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }
    private function validateRequest(Request $request)
    {
        return $request->validate([
            'firstName' => 'required|string|max:100',
            'lastName' => 'required|string|max:100',
            'email' => 'nullable|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'zip' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'termsAgreement' => 'required|accepted'
        ]);
    }

    public function checkout(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Your cart is empty!');
        }

        DB::beginTransaction();
        try {
            $totalPrice = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->with('error', 'Checkout gagal: ' . $e->getMessage());
        }
    }

    public function terms()
    {
        return view('terms-and-conditions');
    }

}
