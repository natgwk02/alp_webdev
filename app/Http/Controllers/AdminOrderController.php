<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = session('orders', []); // Ambil dari session

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $orders = session('orders', []);
        $order = collect($orders)->firstWhere('id', $id);

        if (!$order) {
            return redirect()->route('admin.orders')->with('error', 'Order not found.');
        }

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