<?php

namespace App\Http\Controllers;

use id;
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
            // 'email' => 'john.doe@example.com',
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
        $country = $request->input('country');

        // Override shipping fee berdasarkan negara
        $shippingFee = ($country === 'Indonesia') ? 50000 : 150000;

        // Hitung ulang total
        $subtotal = $checkoutData['subtotal'];
        $tax = $checkoutData['tax'];
        $voucherDiscount = $checkoutData['voucher_discount'];
        $total = $subtotal + $shippingFee + $tax - $voucherDiscount;

        $userId = Auth::id();

        // Generate Order ID secara otomatis oleh DB
        $total = $checkoutData['subtotal'] + $checkoutData['shipping'] + $checkoutData['tax'] - $checkoutData['voucher_discount'];
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(uniqid());

        $order = Order::create([
            'users_id' => $userId,
            'orders_date' => now(),
            'orders_status' => 'pending',
            'first_name' => $validated['firstName'],
            'last_name' => $validated['lastName'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'zip' => $validated['zip'],
            'country' => $validated['country'],
            'payment_method' => $validated['paymentMethod'],
            'payment_status' => 'pending',
            'subtotal' => $checkoutData['subtotal'],
            'shipping_fee' => $checkoutData['shipping'],
            'tax' => $checkoutData['tax'],
            'voucher_discount' => $checkoutData['voucher_discount'],
            'total' => $total,
            'orders_total_price' => $total,
            'notes' => $request->input('sellerNotes'),
                'invoice_number' => $invoiceNumber,

            'status_del' => false,
        ]);

        // Simpan semua item ke order_details
        foreach ($checkoutData['items'] as $item) {
            OrderDetail::create([
                'orders_id' => $order->orders_id,
                'products_id' => $item['id'],
                'order_details_quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['quantity'] * $item['price'],
                'status_del' => false,
            ]);
        }

        // MIDTRANS CONFIG
        // Config::$serverKey = config('midtrans.server_key');
        // Config::$isProduction = config('midtrans.is_production');
        // Config::$isSanitized = true;
        // Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => 'ORDER-' . $order->orders_id,
                'gross_amount' => $order->total,
            ],
            'customer_details' => [
                'first_name' => $validated['firstName'],
                'last_name' => $validated['lastName'],
                //'email' => $validated['email'],
                'phone' => $validated['phone'],
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            // Redirect ke view Midtrans dengan Snap Token
            return view('customer.midtrans-payment', [
                'snapToken' => $snapToken,
                'orderId' => $order->orders_id,
                'paymentStatusUrl' => route('payment.status', ['order' => $order->orders_id]),
            ]);
        } catch (\Exception $e) {
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
            'paymentMethod' => 'required|string|in:creditCard,paypal,bankTransfer',
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


            // // Midtrans Config
            // Config::$serverKey = config('midtrans.server_key');
            // Config::$isProduction = config('midtrans.is_production');
            // Config::$isSanitized = true;
            // Config::$is3ds = true;

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

    public function terms()
    {
        return view('terms-and-conditions');
    }

    public function handleCallback(Request $request)
    {
        // Ambil notifikasi dari Midtrans
        $serverKey = config('midtrans.server_key');
        $json = file_get_contents("php://input");
        $notification = json_decode($json);

        // Verifikasi tanda tangan
        $signatureKey = $notification->signature_key ?? '';
        $orderId = $notification->order_id ?? '';
        $statusCode = $notification->status_code ?? '';
        $grossAmount = $notification->gross_amount ?? '';
        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $expectedSignature) {
            Log::warning('Invalid Midtrans signature for order: ' . $orderId);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Hapus prefix jika perlu (misalnya "ORDER-123" → ambil 123)
        $orderId = str_replace('ORDER-', '', $orderId);

        $order = Order::where('orders_id', $orderId)->first();

        if (!$order) {
            Log::error('Order not found: ' . $orderId);
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Update status berdasarkan notifikasi
        switch ($notification->transaction_status) {
            case 'capture':
            case 'settlement':
                $order->orders_status = 'completed';
                $order->payment_status = 'paid';
                break;
            case 'pending':
                $order->orders_status = 'pending';
                $order->payment_status = 'pending';
                break;
            case 'deny':
            case 'cancel':
            case 'expire':
                $order->orders_status = 'failed';
                $order->payment_status = 'failed';
                break;
        }

        $order->save();

        Log::info('Payment status updated from Midtrans callback for order: ' . $order->orders_id);

        return response()->json(['message' => 'Callback processed successfully']);
    }
}
