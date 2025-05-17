<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
   // CheckoutController.php

public function index()
{
    // Cek apakah ada data checkout di session
    if (!session()->has('checkout_data')) {
        return redirect()->route('cart.index')->with('error', 'Silakan tambahkan produk ke keranjang terlebih dahulu.');
    }

    $checkoutData = session('checkout_data');
    
    // Hitung total
    $total = $checkoutData['subtotal'] + $checkoutData['shipping'] + $checkoutData['tax'] - $checkoutData['voucher_discount'];

    // Data dummy user jika tidak login
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
    
    // Simpan data order ke session
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
    
    // Simpan order ke session
    $orders = session('orders', []);
    $orders[$orderId] = $order;
    session(['orders' => $orders]);

    Log::info(session('orders')); 
    
    // Bersihkan session checkout dan cart
    session()->forget(['checkout_data', 'cart', 'voucher_code', 'voucher_discount']);
    
    // Redirect ke halaman pesanan (orders page)
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

}