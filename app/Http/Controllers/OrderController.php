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
        $orders_query_result = Order::with('orderDetails.product')
            ->where('users_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
            return [
                'orders_id' => $order->orders_id,
                'orders_number' => $order->orders_id, // or any other order number logic
                'orders_status' => $order->orders_status,
                'created_at' => $order->created_at,
                'total' => $order->total,
                'items' => $order->orderDetails->map(function ($detail) {
                    return [
                        'product_id' => $detail->product->products_id ?? null,
                        'product_name' => $detail->product->product_name ?? 'Unknown Product',
                        'price' => $detail->price,
                        'quantity' => $detail->order_details_quantity,
                        'product_image' => $detail->product->product_image ?? 'no-image.png',
                    ];
                })->toArray(),
                'customer' => [
                    'first_name' => $order->first_name,
                    'last_name' => $order->last_name,
                    'email' => $order->email,
                    'phone' => $order->phone,
                    'address' => $order->address,
                    'city' => $order->city,
                    'zip' => $order->zip,
                    'country' => $order->country,
                ],
                'payment_method' => $order->payment_method,
                'payment_status' => $order->payment_status,
                'subtotal' => $order->subtotal,
                'shipping_fee' => $order->shipping_fee,
                'tax' => $order->tax,
                'voucher_discount' => $order->voucher_discount,
            ];
        });

        $orders = $orders_query_result->all();
        return view('customer.orders', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('orderDetails.product')
            ->where('id', $id)
            ->where('users_id', Auth::id())
            ->first();

        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Order not found.');
        }

         $formattedOrder = [
        'id' => $order->orders_id,
        'order_number' => $order->orders_id,
        'created_at' => $order->created_at,
        'status' => $order->orders_status,
        'items' => $order->orderDetails->map(function ($detail) {
            return [
                'products_id' => $detail->product->product_id ?? null,
                'product_name' => $detail->product->product_name ?? 'Unknown Product',
                'price' => $detail->price,
                'quantity' => $detail->order_details_quantity,
                'product_image' => $detail->product->product_image ?? 'no-image.png',
            ];
        })->toArray(),
        'customer' => [
            'first_name' => $order->first_name,
            'last_name' => $order->last_name,
            'email' => $order->email,
            'phone' => $order->phone,
            'address' => $order->address,
            'city' => $order->city,
            'zip' => $order->zip,
            'country' => $order->country,
        ],
        'payment_method' => $order->payment_method,
        'payment_status' => $order->payment_status,
        'subtotal' => $order->subtotal,
        'shipping_fee' => $order->shipping_fee,
        'tax' => $order->tax,
        'voucher_discount' => $order->voucher_discount,
        'total' => $order->total,
    ];


        return view('customer.order_details',[ 'order' =>$formattedOrder]);
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
                'orders_id' => $order->id,
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
