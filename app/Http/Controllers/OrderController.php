<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
{
    $productController = new \App\Http\Controllers\ProductController;
    $products = $productController->products();

    $productsById = [];
    foreach ($products as $product) {
        $productsById[$product['id']] = $product;
    }

    $sessionOrders = session('orders', []);

    // Tambahkan 'image' ke setiap item dan hitung item_count
    foreach ($sessionOrders as &$order) {
        $itemCount = 0;
        foreach ($order['items'] as &$item) {
            $itemCount += $item['quantity']; // jumlah total item
            $pid = $item['product_id'];
            $item['image'] = $productsById[$pid]['image'] ?? 'no-image.png';
        }
        $order['item_count'] = $itemCount; // tambahkan ke array order
    }

    return view('customer.orders', ['orders' => $sessionOrders]);
}

    public function markAsReceived($id)
    {
        $orders = session('orders', []);

        $orderIndex = null;
        foreach ($orders as $index => $order) {
            if ($order['id'] == $id) {
                $orderIndex = $index;
                break;
            }
        }

        if ($orderIndex === null) {
            return redirect()->back()->with('error', 'Order not found.');
        }

        if ($orders[$orderIndex]['status'] === 'Delivered') {
            $orders[$orderIndex]['status'] = 'Completed';
            session(['orders' => $orders]);

            return redirect()->back()->with('success', 'Order marked as received.');
        }

        return redirect()->back()->with('error', 'This order cannot be marked as received.');
    }

    public function show($id)
    {
        // Hardcoded order details based on $id
        $order = [
            'id' => $id,
            'order_number' => 'CHILE-2025-' . $id,
            'order_date' => '2025-05-10 14:30:00',
            'status' => 'Processing',
            'payment_method' => 'Credit Card',
            'payment_status' => 'Paid',
            'shipping_address' => '123 Main St, Santiago, Chile',
            'subtotal' => 89.97,
            'shipping_fee' => 5.00,
            'tax' => 8.99,
            'total_amount' => 103.96,
            'items' => [
                [
                    'product_id' => 1,
                    'product_name' => 'Chilean Sea Bass Fillet',
                    'quantity' => 2,
                    'price' => 24.99,
                    'total' => 49.98
                ],
                [
                    'product_id' => 2,
                    'product_name' => 'Argentinian Red Shrimp',
                    'quantity' => 1,
                    'price' => 18.99,
                    'total' => 18.99
                ]
            ],
            'tracking_info' => [
                'carrier' => 'ChilePost',
                'tracking_number' => 'CP123456789CL',
                'estimated_delivery' => '2025-05-15'
            ]
        ];

        return view('customer.order_details', compact('order'));
    }
    public function showCheckoutForm()
{
    $cartItems = session('cart', []);
    if (empty($cartItems)) {
        return redirect()->route('cart.index')->with('error', 'Cart is empty.');
    }

    $subtotal = array_sum(array_map(function ($item) {
        return $item['price'] * $item['quantity'];
    }, $cartItems));

    $shippingFee = 5000;
    $tax = round($subtotal * 0.1);
    $total = $subtotal + $shippingFee + $tax;

    return view('customer.checkout', compact('cartItems', 'subtotal', 'shippingFee', 'tax', 'total'));
}

  public function checkout(Request $request)
{
    $cartItems = session('cart', []);
    if (empty($cartItems)) {
        return redirect()->route('cart.index')->with('error', 'Cart is empty');
    }

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'address' => 'required|string',
        'payment_method' => 'required|string',
    ]);

    $subtotal = array_sum(array_map(function ($item) {
        return $item['price'] * $item['quantity'];
    }, $cartItems));

    $shippingFee = 5000;
    $tax = round($subtotal * 0.1);
    $total = $subtotal + $shippingFee + $tax;

    $orders = session('orders', []);
    $orderId = count($orders) + 1001;
    $orderNumber = 'CHILE-2025-' . $orderId;

    $order = [
        'id' => $orderId,
        'order_number' => $orderNumber,
        'customer_name' => $validated['name'],
        'customer_email' => $validated['email'],
        'order_date' => now()->format('Y-m-d'),
        'status' => 'Processing',
        'payment_method' => $validated['payment_method'],
        'payment_status' => 'Paid', // Simulasi
        'shipping_address' => $validated['address'],
        'billing_address' => $validated['address'],
        'subtotal' => $subtotal,
        'shipping_fee' => $shippingFee,
        'tax' => $tax,
        'total_amount' => $total,
        'items' => array_map(function ($item) {
            return [
                'product_id' => $item['id'],
                'product_name' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['price'] * $item['quantity'],
            ];
        }, $cartItems),
    ];

    $orders[] = $order;
    session(['orders' => $orders]);
    session()->forget('cart');

    return redirect()->route('products')->with('success', 'Checkout completed! Order placed.');
}
}