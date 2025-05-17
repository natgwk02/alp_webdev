<?php

namespace App\Http\Controllers;

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
                continue;
            }

            $itemCount = 0;

            // Reset item keys agar bisa di-loop di Blade
            $items = array_values($order['items']);

            foreach ($items as &$item) {
                $itemCount += $item['quantity'] ?? 0;

                $productId = $item['product_id'] ?? $item['id'] ?? null;

                if ($productId && isset($productsById[$productId])) {
                    $product = $productsById[$productId];
                    $item['image'] = $product['image'] ?? 'no-image.png';
                    $item['product_name'] = $product['name'] ?? 'Unknown Product';
                } else {
                    $item['image'] = 'no-image.png';
                    $item['product_name'] = 'Unknown Product';
                }
            }

            $order['items'] = $items; // update with cleaned array
            $order['item_count'] = $itemCount;
            $order['order_date'] = $order['created_at'] ?? now();
            $order['order_number'] = $order['order_number'] ?? (is_string($key) ? $key : null);

            $orders[] = $order;
        }

        return view('customer.orders', ['orders' => $orders]);
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
            return redirect()->route('orders')->with('error', 'Order not found.');
        }

        $order['customer_name'] = $order['customer']['first_name'] . ' ' . $order['customer']['last_name'] ?? 'Unknown Customer';
        $order['customer_email'] = $order['customer']['email'] ?? null;
        $order['payment_method'] = $order['payment_method'] ?? 'Unknown';
        $order['payment_status'] = $order['payment_status'] ?? 'Unpaid';
        $order['shipping_address'] = $order['customer']['address'] ?? '-';
        $order['billing_address'] = $order['customer']['address'] ?? '-';
        $order['subtotal'] = $order['subtotal'] ?? 0;
        $order['shipping_fee'] = $order['shipping_fee'] ?? 0;
        $order['tax'] = $order['tax'] ?? 0;
        $order['total_amount'] = $order['total'] ?? 0;
        $order['status'] = $order['status'] ?? 'Pending';
        $order['order_date'] = $order['created_at'] ?? now()->format('Y-m-d H:i:s');
        $order['items'] = $order['items'] ?? [];

        $productController = new \App\Http\Controllers\ProductController;
        $products = $productController->products();
        $productsById = collect($products)->keyBy('id');

        foreach ($order['items'] as &$item) {
            $productId = $item['product_id'] ?? $item['id'] ?? null;

            if (!$productId) {
                $item['image'] = 'no-image.png';
                $item['product_name'] = $item['name'] ?? 'Unknown Product';
            } else {
                $product = $productsById[$productId] ?? null;
                $item['image'] = $product['image'] ?? 'no-image.png';
                $item['product_name'] = $product['name'] ?? $item['name'] ?? 'Unknown Product';
            }

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

        $voucherDiscount = session('voucher_discount', 0);
        $shippingFee = 5000;
        $tax = round($subtotal * 0.1);
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
            'cartItems',
            'subtotal',
            'shippingFee',
            'tax',
            'total',
            'defaultData',
            'voucherDiscount'
        ));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'zip' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'paymentMethod' => 'required|in:creditCard,paypal,bankTransfer',
            'termsAgreement' => 'accepted',
            'sellerNotes' => 'nullable|string|max:200',
        ]);

        $cartItems = session('cart', []);
        if (empty($cartItems)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Tambahkan product_id secara eksplisit ke item
        foreach ($cartItems as &$item) {
            $item['product_id'] = $item['id']; // agar bisa dikenali nanti
        }

        $subtotal = collect($cartItems)->sum(fn($item) => $item['price'] * $item['quantity']);
        $shippingFee = 20000;
        $tax = $subtotal * 0.1;
        $voucherDiscount = session('voucher_discount', 0);
        $total = $subtotal + $shippingFee + $tax - $voucherDiscount;

        $orders = session('orders', []);
        $year = now()->year;
        $orderCount = count($orders) + 1001;
        $orderNumber = "CHILE-{$year}-{$orderCount}";


        $order = [
            'id' => uniqid('order_'),
            'order_number' => $orderNumber,
            'customer' => [
                'first_name' => $request->firstName,
                'last_name' => $request->lastName,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'zip' => $request->zip,
                'country' => $request->country,
                'notes' => $request->sellerNotes,
            ],
            'customer_name' => $request->firstName . ' ' . $request->lastName,
            'payment_method' => $request->paymentMethod,
            'items' => $cartItems,
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'tax' => $tax,
            'voucher_discount' => $voucherDiscount,
            'total' => $total,
            'total_amount' => $total,
            'status' => 'Pending',
            'created_at' => now()->toDateTimeString(),
        ];
        // Simpan ke session
        $orders[] = $order;
        session(['orders' => $orders]);
        session()->put('latest_order', $order);
        session()->forget('cart');
        $voucherDiscount = 0; // Default tidak ada diskon

        // Cek hanya jika user klik tombol "apply voucher"
        if ($request->has('apply_voucher')) {
            $voucherDiscount = session('voucher_discount', 0);
        }

        return redirect()->route('products')->with('success', 'Checkout completed! Order placed.');
    }
}