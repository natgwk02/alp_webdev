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
        ]);
    }

    public function show($id)
    {
        $order = Order::with(['user', 'items.product'])
            ->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }
    
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:Pending,Processing,Shipped,Delivered,Cancelled',
        ]);

        $order = Order::findOrFail($id);

        $order->orders_status = $request->status;
        $order->save();

        return redirect()->route('admin.orders')->with('success', 'Order status updated successfully.');
    }
}
