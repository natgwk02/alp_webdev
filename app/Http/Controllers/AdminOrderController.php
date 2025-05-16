<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {
        // Hardcoded orders data
        $orders = [
            [
                'id' => 1001,
                'order_number' => 'CHILE-2025-1001',
                'customer_name' => 'John Doe',
                'order_date' => '2025-05-10',
                'total_amount' => 89.97,
                'status' => 'Processing',
                'payment_method' => 'Credit Card'
            ],
            [
                'id' => 1002,
                'order_number' => 'CHILE-2025-1002',
                'customer_name' => 'Jane Smith',
                'order_date' => '2025-05-11',
                'total_amount' => 124.95,
                'status' => 'Shipped',
                'payment_method' => 'PayPal'
            ],
            [
                'id' => 1003,
                'order_number' => 'CHILE-2025-1003',
                'customer_name' => 'Robert Johnson',
                'order_date' => '2025-05-12',
                'total_amount' => 64.98,
                'status' => 'Delivered',
                'payment_method' => 'Bank Transfer'
            ]
        ];

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        // Hardcoded order details
        $order = [
            'id' => $id,
            'order_number' => 'CHILE-2025-' . $id,
            'customer_name' => 'John Doe',
            'customer_email' => 'john.doe@example.com',
            'order_date' => '2025-05-10 14:30:00',
            'status' => 'Processing',
            'payment_method' => 'Credit Card',
            'payment_status' => 'Paid',
            'shipping_address' => '123 Main St, Santiago, Chile',
            'billing_address' => '123 Main St, Santiago, Chile',
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
                    'total' => 49.98, 
                    'image'=> 'seabass.jpg'
                ],
                [
                    'product_id' => 2,
                    'product_name' => 'Argentinian Red Shrimp',
                    'quantity' => 1,
                    'price' => 18.99,
                    'total' => 18.99, 
                    'image'=> 'redshrimp.jpg'
                ],
                [
                    'product_id' => 3,
                    'product_name' => 'Alaskan King Crab Legs',
                    'quantity' => 1,
                    'price' => 39.99,
                    'total' => 39.99, 
                    'image'=>'redshrimp.jpg'
                ]
            ]
        ];

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        // In a real application, this would update the order status
        return redirect()->route('admin.orders.show', $id)
            ->with('success', 'Order status updated successfully');
    }
}