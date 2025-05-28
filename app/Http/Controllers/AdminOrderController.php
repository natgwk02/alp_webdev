<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{

    public function dashboard()
    {
        $stats = [
            'total_orders' => \App\Models\Order::count(),
            // kamu bisa tambah statistik lainnya juga
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function index(Request $request)
    {
        $query = Order::with('user')
                       ->orderBy('orders_date', 'desc');

        if ($request->filled('order_id')) {
            $query->where('orders_id', 'like', '%' . $request->order_id . '%');
        }

        if ($request->filled('status')) {
            $query->where('orders_status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('orders_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('orders_date', ' <=', $request->date_to);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }


        if ($request->filled('amount_min') && !$request->filled('amount_more_than')) {
            $query->where('orders_total_price', '>=', $request->amount_min);
        }
        if ($request->filled('amount_max') && !$request->filled('amount_more_than')) {
            $query->where('orders_total_price', '<=', $request->amount_max);
        }
        if ($request->filled('amount_more_than') && $request->filled('amount_min')) {
             $query->where('orders_total_price', '>', $request->amount_min);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            // The request helper in the view can still get current filter values
        ]);
    }

    public function show($id) // Assuming $id is orders_id
    {
        // Eager load user and order items
        // **ASSUMPTION**: You have an 'items' relationship in your Order model
        // for order details/products
        $order = Order::with(['user', 'items', 'items.product']) // Example: items.product to get product name
                      ->where('orders_id', $id) // Use your actual order ID column
                      ->firstOrFail(); // Fails with 404 if not found

        // You might not need to manually set these if your models/accessors handle them
        // $order->customer_name = $order->user ? $order->user->users_name : 'Unknown Customer';
        // $order->payment_status = ucfirst($order->payment_status ?? 'Unpaid');

        return view('admin.orders.show', compact('order'));
    }
}
    // public function index(Request $request)
    // {
    //     $orders = session('orders', []);

    //     foreach ($orders as &$order) {
    //         $order['customer_name'] = $order['customer_name'] ??
    //             (($order['customer']['first_name'] ?? 'Unknown') . ' ' . ($order['customer']['last_name'] ?? 'Customer'));
    //         $order['payment_method'] = ucfirst($order['payment_method'] ?? 'Unknown');
    //         $order['status'] = ucfirst($order['status'] ?? 'Pending');
    //         $order['order_date'] = $order['order_date'] ?? now();
    //         $order['total'] = $order['total'] ?? 0;
    //         $order['order_number'] = $order['order_number'] ?? 'ORD-' . str_pad($order['id'] ?? '0000', 6, '0', STR_PAD_LEFT);
    //     }

    //     session(['orders' => $orders]); // nyimpen ulang untuk memastikan

    //     $perPage = 10;
    //     $currentPage = $request->get('page', 1);
    //     $totalProducts = count($orders);
    //     $totalPages = ceil($totalProducts / $perPage);
    //     $pagedOrders = array_slice($orders, ($currentPage - 1) * $perPage, $perPage);

    //     return view('admin.orders.index', [
    //         'orders' => $pagedOrders,
    //         'currentPage' => $currentPage,
    //         'totalPages' => $totalPages,
    //         'totalProducts' => $totalProducts,
    //         'perPage' => $perPage,
    //     ]);
    // }


    // public function show($id)
    // {
    //     $orders = session('orders', []);
    //     $order = collect($orders)->firstWhere('id', $id);

    //     if (!$order) {
    //         return redirect()->route('admin.orders')->with('error', 'Order not found.');
    //     }

    //     $order['customer_name'] = $order['customer_name']
    //         ?? (($order['customer']['first_name'] ?? 'Unknown') . ' ' . ($order['customer']['last_name'] ?? 'Customer'));
    //     $order['customer_email'] = $order['customer']['email'] ?? 'Unknown';
    //     $order['payment_method'] = ucfirst($order['payment_method'] ?? 'Unknown');
    //     $order['payment_status'] = ucfirst($order['payment_status'] ?? 'Unpaid');
    //     $order['shipping_address'] = $order['customer']['address'] ?? 'N/A';
    //     $order['billing_address'] = $order['billing_address'] ?? $order['shipping_address'];
    //     $order['subtotal'] = $order['subtotal'] ?? 0;
    //     $order['shipping_fee'] = $order['shipping_fee'] ?? 0;
    //     $order['tax'] = $order['tax'] ?? 0;
    //     $order['voucher_discount'] = $order['voucher_discount'] ?? 0;
    //     $order['total'] = $order['total'] ?? ($order['subtotal'] + $order['shipping_fee'] + $order['tax'] - $order['voucher_discount']);
    //     $order['status'] = ucfirst($order['status'] ?? 'Pending');
    //     $order['order_date'] = $order['order_date'] ?? now()->format('Y-m-d H:i:s');
    //     $order['items'] = $order['items'] ?? [];

    //     foreach ($order['items'] as &$item) {
    //         $item['product_id'] = $item['product_id'] ?? 'Unknown';
    //         $item['product_name'] = $item['product_name'] ?? 'Unknown Product';
    //         $item['price'] = $item['price'] ?? 0;
    //         $item['quantity'] = $item['quantity'] ?? 1;
    //         $item['image'] = $item['image'] ?? 'no-image.png';
    //         $item['total'] = $item['price'] * $item['quantity'];
    //     }

    //     return view('admin.orders.show', compact('order'));
    // }
// }
