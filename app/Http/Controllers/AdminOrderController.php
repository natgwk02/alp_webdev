<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{

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
    
