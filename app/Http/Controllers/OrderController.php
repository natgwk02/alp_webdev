<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
   public function index()
{
    // Ambil data produk dari ProductController
    $productController = new \App\Http\Controllers\ProductController;
    $products = $productController->products();

    // Buat array keyed by product_id agar gampang cari
    $productsById = [];
    foreach ($products as $product) {
        $productsById[$product['id']] = $product;
    }

    // Hardcoded list of orders, tambah item dengan image dari produk
    $orders = [
        [
            'id' => 1001,
            'order_number' => 'CHILE-2025-1001',
            'order_date' => '2025-05-10',
            'status' => 'Processing',
            'total_amount' => 103960,
            'item_count' => 3,
            'items' => [
                [
                    'product_id' => 1,
                    'product_name' => 'Chilean Sea Bass Fillet',
                    'quantity' => 2,
                    'price' => 24990,
                    'total' => 49980,
                ],
                [
                    'product_id' => 2,
                    'product_name' => 'Argentinian Red Shrimp',
                    'quantity' => 1,
                    'price' => 18990,
                    'total' => 18990,
                ]
            ]
        ],
        [
            'id' => 1002,
            'order_number' => 'CHILE-2025-1002',
            'order_date' => '2025-05-05',
            'status' => 'Delivered',
            'total_amount' => 64980,
            'item_count' => 2,
            'items' => [
                [
                    'product_id' => 3,
                    'product_name' => 'Kanzler Nugget Crispy',
                    'quantity' => 2,
                    'price' => 25000,
                    'total' => 50000,
                ]
            ]
        ]
    ];

    // Tambahkan 'image' ke setiap item dari data produk
    foreach ($orders as &$order) {
        foreach ($order['items'] as &$item) {
            $pid = $item['product_id'];
            if (isset($productsById[$pid])) {
                $item['image'] = $productsById[$pid]['image'];
            } else {
                $item['image'] = 'no-image.png'; // default image kalau produk gak ketemu
            }
        }
    }

    return view('customer.orders', compact('orders'));
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
}
