<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $productController = new \App\Http\Controllers\ProductController;
        $products = $productController->products();
        $productsById = collect($products)->keyBy('id');

        $sessionOrders = session('orders', []);
        $orders = [];

        foreach ($sessionOrders as $key => $order) {
            if (!is_array($order) || !isset($order['items'])) {
                Log::warning('Invalid order format: ', ['key' => $key, 'order' => $order]);
                continue;
            }

            $itemCount = 0;
            $items = array_values($order['items']);

            foreach ($items as &$item) {
                $itemCount += $item['quantity'] ?? 0;
                $productId = $item['product_id'] ?? $item['id'] ?? null;

                if ($productId && isset($productsById[$productId])) {
                    $product = $productsById[$productId];
                    $item['image'] = $product['image'] ?? 'no-image.png';
                    $item['product_name'] = $product['name'] ?? 'Unknown Product';
                    $item['price'] = $product['price'] ?? 0;
                } else {
                    $item['image'] = 'no-image.png';
                    $item['product_name'] = 'Unknown Product';
                    $item['price'] = 0;
                }
            }

            $order = array_merge($order, [
                'id' => uniqid('order_'),
                'order_number' => $key,
                'items' => $items,
                'item_count' => $itemCount,
                'subtotal' => $order['subtotal'],
                'shipping_fee' => $order['shipping_fee'],
                'tax' => $order['tax'],
                'voucher_discount' => $order['voucher_discount'],
                'total' => $order['total'] ?? 0,
                'status' => 'Pending',
                'created_at' => now()->format('Y-m-d H:i:s'),
                'customer' => [
                    'first_name' => 'Unknown',
                    'last_name' => 'Customer',
                    'address' => 'Unknown Address',
                    'city' => 'Unknown City',
                    'zip' => '00000',
                    'country' => 'Unknown Country',
                    'phone' => 'Unknown Phone'
                ],
                'payment_method' => 'Unknown',
                'payment_status' => 'Unpaid'
            ], $order);

            $orders[] = $order;
        }

        return view('customer.orders', ['orders' => $orders]);
    }

    public function show($id)
    {
        $orders = session('orders', []);
        $order = collect($orders)->firstWhere('id', $id);

        if (!$order) {
            Log::error('Order not found', ['id' => $id]);
            return redirect()->route('orders')->with('error', 'Order not found.');
        }

        $order['payment_method'] = ucfirst($order['payment_method'] ?? 'Unknown');
        $order['payment_status'] = ucfirst($order['payment_status'] ?? 'Unpaid');

        if (!isset($order['customer'])) {
            Log::warning('Order missing customer details', ['order_id' => $id]);
            $order['customer'] = [
                'first_name' => 'Unknown',
                'last_name' => 'Customer',
                'address' => 'Unknown Address',
                'city' => 'Unknown City',
                'zip' => '00000',
                'country' => 'Unknown Country',
                'phone' => 'Unknown Phone'
            ];
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

        return view('customer.checkout', compact('filteredItems', 'subtotal', 'shippingFee', 'tax', 'total', 'voucherDiscount', 'defaultData'));
    }

    public function processCheckout(Request $request)
    {
        $selectedItems = $request->input('selected_items', '');

        if (is_string($selectedItems)) {
            $selectedItems = json_decode($selectedItems, true) ?? explode(',', $selectedItems);
        }

        if (!is_array($selectedItems) || empty($selectedItems)) {
            return redirect()->route('cart.index')->with('error', 'No items selected for checkout.');
        }

        $cartItems = session('cart', []);

        $checkoutItems = [];
        $remainingItems = [];

        foreach ($cartItems as $item) {
            if (in_array($item['id'], $selectedItems)) {
                $checkoutItems[] = $item;
            } else {
                $remainingItems[$item['id']] = $item;
            }
        }

        $subtotal = collect($checkoutItems)->sum(fn($item) => $item['price'] * $item['quantity']);
        $shippingFee = 20000;
        $tax = $subtotal * 0.1;
        $voucherDiscount = session('voucher_discount', 0);
        $total = $subtotal + $shippingFee + $tax - $voucherDiscount;

        $orders = session('orders', []);
        $orderNumber = "CHILE-" . now()->year . "-" . (count($orders) + 1001);

        $order = [
            'id' => uniqid('order_'),
            'order_number' => $orderNumber,
            'customer' => [
                'first_name' => $request->input('firstName'),
                'last_name' => $request->input('lastName'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
                'city' => $request->input('city'),
                'zip' => $request->input('zip'),
                'country' => $request->input('country'),
                'notes' => $request->input('sellerNotes'),
            ],
            'payment_method' => ucfirst($request->input('paymentMethod') ?? 'Unknown'),
            'payment_status' => 'Paid',
            'items' => array_values($checkoutItems),
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'tax' => $tax,
            'voucher_discount' => $voucherDiscount,
            'total' => $total,
            'status' => 'Pending',
            'created_at' => now()->format('Y-m-d H:i:s'),
        ];

        $orders[] = $order;
        session([
            'orders' => $orders,
            'cart' => $remainingItems
        ]);


        session()->forget(['voucher_code', 'voucher_discount', 'selected_items']);

        return redirect()->route('orders')->with('success', 'Checkout completed! Your order has been placed.');
    }
}
