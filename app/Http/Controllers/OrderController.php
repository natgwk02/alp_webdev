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
    $orders = session('orders', []);
    $order = collect($orders)->firstWhere('id', $id);

    if (!$order) {
        return redirect()->route('customer.orders')->with('error', 'Order not found.');
    }

    // Set default values supaya aman di view
    $order['customer_name'] = $order['customer_name'] ?? 'Unknown Customer';
    $order['customer_email'] = $order['customer_email'] ?? null;
    $order['payment_method'] = $order['payment_method'] ?? 'Unknown';
    $order['payment_status'] = $order['payment_status'] ?? 'Unpaid';
    $order['shipping_address'] = $order['shipping_address'] ?? '-';
    $order['billing_address'] = $order['billing_address'] ?? '-';
    $order['subtotal'] = $order['subtotal'] ?? 0;
    $order['shipping_fee'] = $order['shipping_fee'] ?? 0;
    $order['tax'] = $order['tax'] ?? 0;
    $order['total_amount'] = $order['total_amount'] ?? 0;
    $order['status'] = $order['status'] ?? 'Pending';
    $order['order_date'] = $order['order_date'] ?? now()->format('Y-m-d H:i:s');
    $order['items'] = $order['items'] ?? [];

    // Ambil data produk supaya bisa tambah image di setiap item
    $productController = new \App\Http\Controllers\ProductController;
    $products = $productController->products();
    $productsById = collect($products)->keyBy('id');

    foreach ($order['items'] as &$item) {
        $item['image'] = $productsById[$item['product_id']]['image'] ?? 'no-image.png';
        $item['product_name'] = $productsById[$item['product_id']]['name'] ?? ($item['product_name'] ?? 'Unknown Product');
        $item['price'] = $item['price'] ?? 0;
        $item['quantity'] = $item['quantity'] ?? 0;
        $item['total'] = $item['price'] * $item['quantity'];
    }

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