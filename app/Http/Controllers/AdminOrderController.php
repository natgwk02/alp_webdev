<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = session('orders', []);

        // Pastikan setiap order memiliki field dasar agar tidak error di tampilan
        foreach ($orders as &$order) {
            $order['customer_name'] = $order['customer_name'] ?? 'Unknown Customer';
            $order['payment_method'] = $order['payment_method'] ?? 'Unknown';
            $order['status'] = $order['status'] ?? 'Pending';
            $order['order_date'] = $order['order_date'] ?? now();
            $order['total_amount'] = $order['total_amount'] ?? 0;
            $order['order_number'] = $order['order_number'] ?? 'ORD-' . str_pad($order['id'], 6, '0', STR_PAD_LEFT);
        }

        session(['orders' => $orders]); // simpan ulang untuk memastikan

        // Manual pagination
        $perPage = 10;
        $currentPage = $request->get('page', 1);
        $totalProducts = count($orders);
        $totalPages = ceil($totalProducts / $perPage);
        $pagedOrders = array_slice($orders, ($currentPage - 1) * $perPage, $perPage);

        return view('admin.orders.index', [
            'orders' => $pagedOrders,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalProducts' => $totalProducts,
            'perPage' => $perPage,
        ]);
    }

    public function show($id)
    {
        $orders = session('orders', []);
        $order = collect($orders)->firstWhere('id', $id);

        if (!$order) {
            return redirect()->route('admin.orders')->with('error', 'Order not found.');
        }

        // Set default values
        $order['customer_name'] = $order['customer_name']
            ?? ($order['customer']['first_name'] ?? '') . ' ' . ($order['customer']['last_name'] ?? '')
            ?? 'Unknown Customer';
        $order['customer_email'] = $order['customer_email'] ?? null;
        $order['payment_method'] = $order['payment_method'] ?? 'Unknown';
        $order['payment_status'] = $order['payment_status'] ?? 'Unpaid';
        $order['shipping_address'] = $order['shipping_address'] ?? '-';
        $order['billing_address'] = $order['billing_address'] ?? '-';
        $order['subtotal'] = $order['subtotal'] ?? 0;
        $order['shipping_fee'] = $order['shipping_fee'] ?? 0;
        $order['total_amount'] = $order['total_amount']
            ?? $order['total']
            ?? (($order['subtotal'] ?? 0) + ($order['shipping_fee'] ?? 0) + ($order['tax'] ?? 0) - ($order['voucher_discount'] ?? 0));
        $order['status'] = $order['status'] ?? 'Pending';
        $order['order_date'] = $order['order_date'] ?? now();
        $order['items'] = $order['items'] ?? [];

        $order['customer']['notes'] = $order['customer']['notes'] ?? null;

        // Ambil data produk dari controller ProductController
        $productController = new \App\Http\Controllers\ProductController;
        $products = $productController->products();
        $productsById = collect($products)->keyBy('id');

        foreach ($order['items'] as &$item) {
            $item['image'] = $productsById[$item['product_id']]['image'] ?? 'no-image.png';
            $item['product_name'] = $productsById[$item['product_id']]['name'] ?? 'Unknown Product';
            $item['price'] = $item['price'] ?? 0;
            $item['quantity'] = $item['quantity'] ?? 0;
            $item['total'] = $item['price'] * $item['quantity'];
        }

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $orders = session('orders', []);
        $updatedOrders = [];
        $found = false;

        foreach ($orders as $order) {
            if ($order['id'] == $id) {
                $found = true;
                if ($request->input('status') !== 'Cancelled') {
                    $order['status'] = $request->input('status');
                    $updatedOrders[] = $order;
                }
                // Jika status Cancelled, order dihapus (tidak dimasukkan ke updatedOrders)
            } else {
                $updatedOrders[] = $order;
            }
        }

        if (!$found) {
            return redirect()->back()->with('error', 'Order not found.');
        }

        session(['orders' => $updatedOrders]);

        return redirect()->route('admin.orders')->with('success', 'Order status updated successfully');
    }
}
