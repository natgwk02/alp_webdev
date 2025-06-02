<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders_query_result = Order::with('orderDetails.product')
            ->where('users_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
            return [
                'orders_id' => $order->orders_id,
                'orders_number' => $order->orders_id, // or any other order number logic
                'orders_status' => $order->orders_status,
                'created_at' => $order->created_at,
                'total' => $order->total,
                'items' => $order->orderDetails->map(function ($detail) {
                    return [
                        'product_id' => $detail->product->products_id ?? null,
                        'product_name' => $detail->product->product_name ?? 'Unknown Product',
                        'price' => $detail->price,
                        'quantity' => $detail->order_details_quantity,
                        'product_image' => $detail->product->product_image ?? 'no-image.png',
                    ];
                })->toArray(),
                'customer' => [
                    'first_name' => $order->first_name,
                    'last_name' => $order->last_name,
                    'email' => $order->email,
                    'phone' => $order->phone,
                    'address' => $order->address,
                    'city' => $order->city,
                    'zip' => $order->zip,
                    'country' => $order->country,
                ],
                'payment_method' => $order->payment_method,
                'payment_status' => $order->payment_status,
                'subtotal' => $order->subtotal,
                'shipping_fee' => $order->shipping_fee,
                'tax' => $order->tax,
                'voucher_discount' => $order->voucher_discount,
            ];
        });

        $orders = $orders_query_result->all();
        return view('customer.orders', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('orderDetails.product')
            ->where('id', $id)
            ->where('users_id', Auth::id())
            ->first();

        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Order not found.');
        }

         $formattedOrder = [
        'id' => $order->orders_id,
        'order_number' => $order->orders_id,
        'created_at' => $order->created_at,
        'status' => $order->orders_status,
        'items' => $order->orderDetails->map(function ($detail) {
            return [
                'products_id' => $detail->product->product_id ?? null,
                'product_name' => $detail->product->product_name ?? 'Unknown Product',
                'price' => $detail->price,
                'quantity' => $detail->order_details_quantity,
                'product_image' => $detail->product->product_image ?? 'no-image.png',
            ];
        })->toArray(),
        'customer' => [
            'first_name' => $order->first_name,
            'last_name' => $order->last_name,
            'email' => $order->email,
            'phone' => $order->phone,
            'address' => $order->address,
            'city' => $order->city,
            'zip' => $order->zip,
            'country' => $order->country,
        ],
        'payment_method' => $order->payment_method,
        'payment_status' => $order->payment_status,
        'subtotal' => $order->subtotal,
        'shipping_fee' => $order->shipping_fee,
        'tax' => $order->tax,
        'voucher_discount' => $order->voucher_discount,
        'total' => $order->total,
    ];


        return view('customer.order_details',[ 'order' =>$formattedOrder]);
    }

    public function showCheckoutForm(Request $request)
    {
        // 1. Get selected product IDs from the request (sent from the cart page)
        // The input name should be 'selected_items[]' from cart page for PHP to get it as array,
        // or if it's a comma-separated string, explode it.
        // Your existing code already handles string to array conversion.
        $selectedProductIds = $request->input('selected_items', []);
        if (is_string($selectedProductIds) && !empty($selectedProductIds)) {
            $selectedProductIds = explode(',', $selectedProductIds);
        }

        // If empty from request, try to get from session (e.g., if there was a validation error on checkout page)
        if (empty($selectedProductIds) && session()->has('checkout_selected_ids_temp')) {
            $selectedProductIds = session('checkout_selected_ids_temp');
        }

        if (empty($selectedProductIds)) {
            return redirect()->route('cart.index')->with('error', 'No items selected for checkout. Please select items from your cart.');
        }

        // Store these IDs in session temporarily in case of validation errors on checkout form
        session(['checkout_selected_ids_temp' => $selectedProductIds]);

        // 2. Get the actual item details from the main cart session
        //    based on the selected product IDs.
        //    session('cart') should be an array like: [['id' => products_id, 'name' => ..., 'quantity' => ..., 'price' => ...], ...]
        $cartItemsFromSession = session('cart', []);
        $filteredItems = collect($cartItemsFromSession)->filter(function ($item) use ($selectedProductIds) {
            // Assuming $item['id'] in your session('cart') is the products_id
            return in_array($item['id'], $selectedProductIds);
        })->values()->all(); // Convert back to a simple array

        if (empty($filteredItems)) {
            session()->forget('checkout_selected_ids_temp'); // Clear temp session
            return redirect()->route('cart.index')->with('error', 'Selected items not found in cart. Please try again.');
        }

        // 3. Calculate totals based on these filtered items
        $subtotal = array_sum(array_map(function ($item) {
            return ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
        }, $filteredItems));

        $shippingFee = 5000; // Or your dynamic shipping logic
        $tax = round($subtotal * 0.1);
        $voucherDiscount = session('voucher_discount', 0); // Assumes voucher is applied to these selected items
        $total = $subtotal + $shippingFee + $tax - $voucherDiscount;

        // 4. Prepare default data for shipping form (use Auth user if logged in)
        $defaultData = [
            'firstName' => '',
            'lastName' => '',
            'email' => '',
            'phone' => '',
            'address' => '',
            'city' => '',
            'zip' => '',
            'country' => 'Chile', // Default country
        ];

        if (Auth::check()) {
            $user = Auth::user();
            // Assuming your User model has these actual attributes or accessors:
            // users_name, users_email, users_phone, users_address
            $nameParts = explode(' ', $user->users_name ?? '', 2);
            $defaultData['firstName'] = old('firstName', $nameParts[0] ?? '');
            $defaultData['lastName'] = old('lastName', $nameParts[1] ?? '');
            $defaultData['email'] = old('email', $user->users_email ?? '');
            $defaultData['phone'] = old('phone', $user->users_phone ?? '');
            $defaultData['address'] = old('address', $user->users_address ?? '');
            // City, zip, country might not be on your User model directly.
            // If you have them, add: e.g., $user->users_city, $user->users_zip, etc.
            $defaultData['city'] = old('city', $user->users_city ?? ''); // Example
            $defaultData['zip'] = old('zip', $user->users_zip ?? '');   // Example
            $defaultData['country'] = old('country', $user->users_country ?? 'Chile'); // Example
        } else {
            // For guest users, keep using old input or basic defaults
            foreach ($defaultData as $key => $value) {
                $defaultData[$key] = old($key, $value);
            }
        }

        return view('customer.checkout', compact(
            'filteredItems', // These are the items for the order summary
            'subtotal',
            'shippingFee',
            'tax',
            'total',
            'voucherDiscount',
            'defaultData'
        ));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|max:255', // Ensure 'email' is in your 'orders' fillable if you save it
            'phone' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'zip' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'paymentMethod' => 'required|string',
            'selected_items' => 'required|array', // From your checkout form
            'sellerNotes' => 'nullable|string|max:65535', // Max length for TEXT
            // Add 'termsAgreement' => 'accepted', if your form requires it
        ]);

        $selectedProductIds = $request->input('selected_items');
        // $cartItemsFromSession should contain items with at least:
        // 'id' (as products_id), 'quantity', 'price' (unit price), 'name' (product name)
        $cartItemsFromSession = session('cart', []);

        $checkoutItems = collect($cartItemsFromSession)->filter(function ($item) use ($selectedProductIds) {
            return in_array($item['id'], $selectedProductIds); // Assuming $item['id'] is products_id
        })->values();

        if ($checkoutItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'No items selected for checkout or cart is empty.');
        }

        // Recalculate totals server-side for security
        $subtotal = $checkoutItems->sum(fn($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 0));
        $shippingFee = 5000; // Get this dynamically if it varies
        $tax = round($subtotal * 0.1);
        $voucherDiscount = session('voucher_discount', 0);
        $finalTotal = $subtotal + $shippingFee + $tax - $voucherDiscount;

        $order = Order::create([
            'users_id' => Auth::id(), // Make sure users_id column exists and is fillable
            'orders_date' => now()->toDateString(),
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'email' => $request->email, // Add if you have an 'email' column in 'orders'
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'zip' => $request->zip,
            'country' => $request->country,
            'notes' => $request->sellerNotes, // Ensure your form submits 'sellerNotes'
            'payment_method' => ucfirst($request->paymentMethod),
            'payment_status' => 'Pending', // Or 'Paid' based on actual payment confirmation
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'tax' => $tax,
            'voucher_discount' => $voucherDiscount,
            'total' => $finalTotal, // Corresponds to 'total' column in your 'orders' table
            'orders_total_price' => $finalTotal, // Corresponds to 'orders_total_price' column
            'orders_status' => 'Pending', // Corresponds to 'orders_status' column
            // 'status_del' typically defaults to 0 or is handled by SoftDeletes trait
        ]);

        foreach ($checkoutItems as $item) {
            OrderDetail::create([
                'orders_id' => $order->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // Clear the cart and related session data
        session()->forget(['cart', 'voucher_code', 'voucher_discount', 'selected_items', 'checkout_data']);
        // You might also want to forget 'selected_items_on_load' if you used that in CartController

        // Redirect to an order confirmation/detail page
        // Ensure 'order.show' route exists and OrderController@show is configured correctly
        return redirect()->route('order.show', ['id' => $order->orders_id])
            ->with('success', 'Checkout completed! Your order has been placed.');
    }

}
