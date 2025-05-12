<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function checkout()
    {
        // Hardcoded cart data
        $cartItems = [
            [
                'product_id' => 1,
                'product_name' => 'Chilean Sea Bass Fillet',
                'price' => 24.99,
                'quantity' => 2
            ],
            [
                'product_id' => 2,
                'product_name' => 'Argentinian Red Shrimp',
                'price' => 18.99,
                'quantity' => 1
            ]
        ];

        // Calculate totals
        $subtotal = array_reduce($cartItems, function($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        $shippingFee = 5.00;
        $tax = $subtotal * 0.1; // 10% tax
        $total = $subtotal + $shippingFee + $tax;

        // Hardcoded user addresses
        $userAddresses = [
            [
                'id' => 1,
                'address_type' => 'Home',
                'street' => '123 Main St',
                'city' => 'Santiago',
                'postal_code' => '8320000',
                'country' => 'Chile',
                'is_default' => true
            ],
            [
                'id' => 2,
                'address_type' => 'Office',
                'street' => '456 Business Ave',
                'city' => 'Santiago',
                'postal_code' => '8320001',
                'country' => 'Chile',
                'is_default' => false
            ]
        ];

        return view('customer.checkout', compact(
            'cartItems',
            'subtotal',
            'shippingFee',
            'tax',
            'total',
            'userAddresses'
        ));
    }

    public function placeOrder(Request $request)
    {
        // Hardcoded order number (in a real app, generate this dynamically)
        $orderNumber = 'CHILE-' . date('Y') . '-' . rand(1000, 9999);

        // Hardcoded success message and redirect (without actual database saving)
        return redirect()->route('orders.show', ['id' => 1001])
            ->with('success', 'Order placed successfully! Your order number is: ' . $orderNumber);
    }

    public function index()
    {
        // Hardcoded list of orders
        $orders = [
            [
                'id' => 1001,
                'order_number' => 'CHILE-2025-1001',
                'order_date' => '2025-05-10',
                'status' => 'Processing',
                'total_amount' => 103.96,
                'item_count' => 3
            ],
            [
                'id' => 1002,
                'order_number' => 'CHILE-2025-1002',
                'order_date' => '2025-05-05',
                'status' => 'Delivered',
                'total_amount' => 64.98,
                'item_count' => 2
            ]
        ];

        return view('customer.orders', compact('orders'));
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
