<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
   public function index(Request $request)
{
    $orders = session('orders', []);

    // Pastikan setiap order memiliki customer_name dan payment_method
    foreach ($orders as &$order) {
        $order['customer_name'] = $order['customer_name'] ?? 'Unknown Customer';
        $order['payment_method'] = $order['payment_method'] ?? 'Unknown';
    }

    session(['orders' => $orders]);

    // Pagination manual
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

        $order['customer_name'] = $order['customer_name'] ?? 'Unknown Customer';
        $order['payment_method'] = $order['payment_method'] ?? 'Unknown'; // Tambahkan di detail juga

        // Tambahkan gambar dari product
        $productController = new \App\Http\Controllers\ProductController;
        $products = $productController->products();
        $productsById = collect($products)->keyBy('id');

        foreach ($order['items'] as &$item) {
            $item['image'] = $productsById[$item['product_id']]['image'] ?? 'no-image.png';
        }

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $orders = session('orders', []);
        foreach ($orders as &$order) {
            if ($order['id'] == $id) {
                $order['status'] = $request->input('status');
                break;
            }
        }

        session(['orders' => $orders]);

        return redirect()->route('admin.orders.show', $id)
            ->with('success', 'Order status updated successfully');
    }
}
