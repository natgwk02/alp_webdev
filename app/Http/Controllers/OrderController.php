<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('orderDetails.product')
            ->where('users_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.orders', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('orderDetails.product')
            ->where('id', $id)
            ->where('users_id', Auth::id())
            ->first();

        if (!$order) {
            return redirect()->route('order.index')->with('error', 'Order not found.');
        }

        return view('customer.order_details', compact('order'));
    }

    public function showCheckoutForm(Request $request)
    {
        $selectedItems = $request->input('selected_items', []);

        if (is_string($selectedItems)) {
            $selectedItems = explode(',', $selectedItems);
        }

        if (empty($selectedItems)) {
            $selectedItems = session('selected_items', []);
        }

        if (!empty($selectedItems)) {
            session(['selected_items' => $selectedItems]);
        }

        if (empty($selectedItems) || !is_array($selectedItems)) {
            return redirect()->route('cart.index')->with('error', 'No items selected for checkout.');
        }

        $cartItems = session('cart', []);
        $filteredItems = array_filter($cartItems, function ($item) use ($selectedItems) {
            return in_array($item['id'], (array) $selectedItems);
        });

        $subtotal = array_sum(array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $filteredItems));

        $shippingFee = 5000;
        $tax = round($subtotal * 0.1);
        $voucherDiscount = session('voucher_discount', 0);
        $total = $subtotal + $shippingFee + $tax - $voucherDiscount;

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

        return view('customer.checkout', compact(
            'filteredItems',
            'subtotal',
            'shippingFee',
            'tax',
            'total',
            'voucherDiscount',
            'defaultData'
        ));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'zip' => 'required|string',
            'country' => 'required|string',
            'paymentMethod' => 'required|string',
            'selected_items' => 'required|array',
        ]);

        $selectedItems = $request->input('selected_items');
        $cartItems = session('cart', []);

        $checkoutItems = collect($cartItems)->filter(fn($item) => in_array($item['id'], $selectedItems))->values();

        $subtotal = $checkoutItems->sum(fn($item) => $item['price'] * $item['quantity']);
        $shippingFee = 20000;
        $tax = round($subtotal * 0.1);
        $voucherDiscount = session('voucher_discount', 0);
        $total = $subtotal + $shippingFee + $tax - $voucherDiscount;

        $order = Order::create([
            'users_id' => Auth::id(),
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'zip' => $request->zip,
            'country' => $request->country,
            'notes' => $request->sellerNotes,
            'payment_method' => ucfirst($request->paymentMethod),
            'payment_status' => 'Paid',
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'tax' => $tax,
            'voucher_discount' => $voucherDiscount,
            'total' => $total,
            'status' => 'Pending',
        ]);

        foreach ($checkoutItems as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        session()->forget(['voucher_code', 'voucher_discount', 'selected_items']);
        session()->put('cart', collect($cartItems)->reject(fn($item) => in_array($item['id'], $selectedItems))->toArray());

        return redirect()->route('products')->with('success', 'Checkout completed! Your order has been placed.');
    }
}
