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
        $order['customer_name'] = $order['customer_name'] ?? 
            (($order['customer']['first_name'] ?? 'Unknown') . ' ' . ($order['customer']['last_name'] ?? 'Customer'));
        $order['payment_method'] = ucfirst($order['payment_method'] ?? 'Unknown');
        $order['status'] = ucfirst($order['status'] ?? 'Pending');
        $order['order_date'] = $order['order_date'] ?? now();
        $order['total'] = $order['total'] ?? 0;
        $order['order_number'] = $order['order_number'] ?? 'ORD-' . str_pad($order['id'] ?? '0000', 6, '0', STR_PAD_LEFT);
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

    // Perbarui data customer dengan lebih detail
    $order['customer_name'] = $order['customer_name'] 
        ?? (($order['customer']['first_name'] ?? 'Unknown') . ' ' . ($order['customer']['last_name'] ?? 'Customer'));
    $order['customer_email'] = $order['customer']['email'] ?? 'Unknown';
    $order['payment_method'] = ucfirst($order['payment_method'] ?? 'Unknown');
    $order['payment_status'] = ucfirst($order['payment_status'] ?? 'Unpaid');
    $order['shipping_address'] = $order['customer']['address'] ?? 'N/A';
    $order['billing_address'] = $order['billing_address'] ?? $order['shipping_address'];
    $order['subtotal'] = $order['subtotal'] ?? 0;
    $order['shipping_fee'] = $order['shipping_fee'] ?? 0;
    $order['tax'] = $order['tax'] ?? 0;
    $order['voucher_discount'] = $order['voucher_discount'] ?? 0;
    $order['total'] = $order['total'] ?? ($order['subtotal'] + $order['shipping_fee'] + $order['tax'] - $order['voucher_discount']);
    $order['status'] = ucfirst($order['status'] ?? 'Pending');
    $order['order_date'] = $order['order_date'] ?? now()->format('Y-m-d H:i:s');
    $order['items'] = $order['items'] ?? [];

    // Cek dan atur item agar tidak muncul "Unknown"
    foreach ($order['items'] as &$item) {
        $item['product_id'] = $item['product_id'] ?? 'Unknown';
        $item['product_name'] = $item['product_name'] ?? 'Unknown Product';
        $item['price'] = $item['price'] ?? 0;
        $item['quantity'] = $item['quantity'] ?? 1;
        $item['image'] = $item['image'] ?? 'no-image.png';
        $item['total'] = $item['price'] * $item['quantity'];
    }

    return view('admin.orders.show', compact('order'));
}


}
