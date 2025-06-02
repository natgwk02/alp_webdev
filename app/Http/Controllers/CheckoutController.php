<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use Midtrans\Config;
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
            'email' => 'john.doe@example.com',
            'phone' => '+56912345678',
            'address' => '123 Main St, Santiago, Chile',
            'city' => 'Santiago',
            'zip' => '8320000',
            'country' => 'Chile'
        ];

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

        $orderId = 'ORD-' . now()->format('Ymd') . '-' . rand(1000, 9999);

        $order = [
            'id' => $orderId,
            'customer' => [
                'name' => $validated['firstName'] . ' ' . $validated['lastName'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'zip' => $validated['zip'],
                'country' => $validated['country']
            ],
            'items' => $checkoutData['items'],
            'payment_method' => $validated['paymentMethod'],
            'subtotal' => $checkoutData['subtotal'],
            'shipping' => $checkoutData['shipping'],
            'tax' => $checkoutData['tax'],
            'discount' => $checkoutData['voucher_discount'],
            'total' => $checkoutData['subtotal'] + $checkoutData['shipping'] + $checkoutData['tax'] - $checkoutData['voucher_discount'],
            'status' => 'pending',
            'created_at' => $checkoutData['created_at']
        ];

        $orders = session('orders', []);
        $orders[$orderId] = $order;
        session(['orders' => $orders]);

        Log::info(session('orders'));

        session()->forget(['checkout_data', 'cart', 'voucher_code', 'voucher_discount']);

        return redirect()->route('order.show', ['id' => $orderId])->with('success', 'Checkout completed! Order placed.');
    }


    private function validateRequest(Request $request)
    {
        return $request->validate([
            'firstName' => 'required|string|max:100',
            'lastName' => 'required|string|max:100',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'zip' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'paymentMethod' => 'required|string|in:creditCard,paypal,bankTransfer',
            'termsAgreement' => 'required|accepted'
        ]);
    }

    public function checkout(Request $request){
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Your cart is empty!');
        }

        DB::beginTransaction();
        try {
            $totalPrice = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

            // $order = Order::create([
            //     'invoice_number' => 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
            //     'user_id' => Auth::id(),
            //     'customer_name' => Auth::user()->name,
            //     'total_price' => $totalPrice,
            //     'status' => 'pending',
            //     'payment_url' => null,
            // ]);

            // foreach ($cart as $product_id => $item) {
            //     OrderDetail::create([
            //         'order_id' => $order->id,
            //         'product_id' => $product_id,
            //         'product_name' => $item['name'],
            //         'quantity' => $item['quantity'],
            //         'price' => $item['price'],
            //         'subtotal' => $item['price'] * $item['quantity'],
            //     ]);
            // }


            // Midtrans Config
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            // Create Midtrans Transaction
            // $params = [
            //     'transaction_details' => [
            //         'order_id' => $order->invoice_number,
            //         'gross_amount' => $totalPrice,
            //     ],
            //     'customer_details' => [
            //         'first_name' => Auth::user()->name,
            //         'email' => Auth::user()->email,
            //     ],
			// 	'callbacks' => [
            //         'finish' => route('store'),
            //     ]
            // ];

            // $snapUrl = Snap::createTransaction($params)->redirect_url;
            // // Save Payment URL
            // $order->payment_url = $snapUrl;
            // $order->save();
            
            // DB::commit();

            // session()->forget('cart'); // Clear cart
            // return redirect($snapUrl);

            //return redirect()->route('store')->with('success', 'Checkout berhasil!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Checkout gagal: ' . $e->getMessage());
        }
    }
}
