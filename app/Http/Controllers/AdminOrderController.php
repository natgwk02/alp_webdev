<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Container\Attributes\Log;

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
        $order = Order::with(['user', 'orderDetails.product']) // 'user' for customer, 'details' for order items, 'details.product' to get product info for each item
            ->findOrFail($id); // $id is the orders_id

        return view('admin.orders.show', compact('order'));
    }
}
