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
                        $productName = 'Unknown Product';
                        $productImage = 'no-image.png';

                        if ($detail->product) {

                            $productName = $detail->product->products_name;
                            $productImage = $detail->product->products_image;
                        } else {
                            Log::warning('Product not loaded via OrderDetail relation. OrderDetail ID: ' . $detail->getKey() . ', Product FK: ' . $detail->products_id);
                        }

                        return [

                            'product_name'  => $productName,
                            'price'         => $detail->price,
                            'quantity'      => $detail->order_details_quantity,
                            'product_image' => $productImage,
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
            ->where('orders_id', $id)
            ->where('users_id', Auth::id())
            ->first();

        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Order not found.');
        }

        $formattedOrder = [
            'id' => $order->orders_id,
            'order_number' => $order->orders_id,
            'created_at' => $order->created_at->format('Y-m-d H:i:s'),
            'status' => $order->orders_status,
            'items' => $order->orderDetails->map(function ($detail) {
                $productName = 'Unknown Product';
                $productImage = 'no-image.png';


                if ($detail->product) {
                    $productName = $detail->product->products_name;
                    $productImage = $detail->product->products_image;
                } else {

                    Log::warning('Product not loaded for OrderDetail ID: ' . $detail->getKey() . ' (OrderDetail uses products_id FK: ' . $detail->products_id . ')');
                }

                return [

                    'name'           => $productName,
                    'price'          => $detail->price,
                    'quantity'       => $detail->order_details_quantity,
                    'image_filename' => $productImage,
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

        return view('customer.order_details', ['order' => $formattedOrder]);
    }

    public function showCheckoutForm(Request $request)
    {
        // Debug: Log the incoming request data
        Log::info('Checkout form request data:', $request->all());

        // 1. Get selected product IDs from the request
        $selectedProductIds = $request->input('selected_items', []);

        // Handle different input formats
        if (is_string($selectedProductIds) && !empty($selectedProductIds)) {
            $selectedProductIds = explode(',', $selectedProductIds);
        }

        // Remove empty values and ensure we have integers
        $selectedProductIds = array_filter($selectedProductIds, function ($id) {
            return !empty($id) && is_numeric($id);
        });
        $selectedProductIds = array_map('intval', $selectedProductIds);

        Log::info('Processed selected product IDs:', $selectedProductIds);

        // If empty from request, try to get from session
        if (empty($selectedProductIds) && session()->has('checkout_selected_ids_temp')) {
            $selectedProductIds = session('checkout_selected_ids_temp');
        }

        if (empty($selectedProductIds)) {
            Log::warning('No items selected for checkout');
            return redirect()->route('cart.index')->with('error', 'No items selected for checkout. Please select items from your cart.');
        }

        // Store these IDs in session temporarily
        session(['checkout_selected_ids_temp' => $selectedProductIds]);

        // 2. Get cart items from database instead of session (since you're using database cart)
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to checkout.');
        }

        $cart = \App\Models\Cart::where('users_id', Auth::id())->first();
        if (!$cart) {
            return redirect()->route('cart.index')->with('error', 'Cart not found.');
        }

        // Get cart items that match selected product IDs
        $cartItems = $cart->items()->with('product')->whereIn('products_id', $selectedProductIds)->get();

        if ($cartItems->isEmpty()) {
            session()->forget('checkout_selected_ids_temp');
            return redirect()->route('cart.index')->with('error', 'Selected items not found in cart. Please try again.');
        }

        // Convert cart items to the format expected by the checkout view
        $filteredItems = $cartItems->map(function ($cartItem) {
            return [
                'id' => $cartItem->products_id,
                'name' => $cartItem->product->products_name ?? 'Unknown Product',
                'price' => $cartItem->product->orders_price ?? 0,
                'quantity' => $cartItem->quantity,
                'image' => $cartItem->product->products_image ?? 'no-image.png',
            ];
        })->toArray();

        // 3. Calculate totals based on these items
        $subtotal = collect($filteredItems)->sum(function ($item) {
            return ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
        });

        $shippingFee = 5000; // Or your dynamic shipping logic
        $tax = round($subtotal * 0.1);
        $voucherDiscount = session('voucher_discount', 0);
        $total = $subtotal + $shippingFee + $tax - $voucherDiscount;

        // 4. Prepare default data for shipping form
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
            $nameParts = explode(' ', $user->users_name ?? '', 2);
            $defaultData['firstName'] = old('firstName', $nameParts[0] ?? '');
            $defaultData['lastName'] = old('lastName', $nameParts[1] ?? '');
            $defaultData['email'] = old('email', $user->users_email ?? '');
            $defaultData['phone'] = old('phone', $user->users_phone ?? '');
            $defaultData['address'] = old('address', $user->users_address ?? '');
            $defaultData['city'] = old('city', $user->users_city ?? '');
            $defaultData['zip'] = old('zip', $user->users_zip ?? '');
            $defaultData['country'] = old('country', $user->users_country ?? 'Chile');
        } else {
            foreach ($defaultData as $key => $value) {
                $defaultData[$key] = old($key, $value);
            }
        }

        Log::info('Checkout form data prepared successfully');

        return view('customer.checkout', compact(
            'filteredItems',
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
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'zip' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'paymentMethod' => 'required|string',
            'selected_items' => 'required|json',
            'sellerNotes' => 'nullable|string|max:65535',
        ]);

        $selectedProductIds = json_decode($request->input('selected_items'));

        if (empty($selectedProductIds)) {
            return redirect()->route('cart.index')->with('error', 'No items were selected for checkout.');
        }

        // Get cart items from database
        $cart = \App\Models\Cart::where('users_id', Auth::id())->first();
        if (!$cart) {
            return redirect()->route('cart.index')->with('error', 'Cart not found.');
        }

        $checkoutItems = $cart->items()->with('product')->whereIn('products_id', $selectedProductIds)->get();

        if ($checkoutItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'No items selected for checkout or cart is empty.');
        }

        // Calculate totals server-side for security
        $subtotal = $checkoutItems->sum(function ($item) {
            return ($item->product->orders_price ?? 0) * $item->quantity;
        });

        $shippingFee = 5000;
        $tax = round($subtotal * 0.1);
        $voucherDiscount = session('voucher_discount', 0);
        $finalTotal = $subtotal + $shippingFee + $tax - $voucherDiscount;

        $order = Order::create([
            'users_id' => Auth::id(),
            'orders_date' => now()->toDateString(),
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'zip' => $request->zip,
            'country' => $request->country,
            'notes' => $request->sellerNotes,
            'payment_method' => ucfirst($request->paymentMethod),
            'payment_status' => 'Pending',
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'tax' => $tax,
            'voucher_discount' => $voucherDiscount,
            'total' => $finalTotal,
            'orders_total_price' => $finalTotal,
            'orders_status' => 'Pending',
        ]);

        foreach ($checkoutItems as $cartItem) {
            OrderDetail::create([
                'orders_id' => $order->getKey(),
                'products_id' => $cartItem->products_id,
                'order_details_quantity' => $cartItem->quantity,
                'price' => $cartItem->product->orders_price,
                'total' => ($cartItem->product->orders_price ?? 0) * $cartItem->quantity,
            ]);
        }

        // Remove the checked out items from cart
        $cart->items()->whereIn('products_id', $selectedProductIds)->delete();

        // Clear voucher session data
        session()->forget(['voucher_code', 'voucher_discount', 'checkout_selected_ids_temp']);

        // return redirect()->route('order.confirmation', ['id' => $order->orders_id])
        //     ->with('success', 'Checkout completed! Your order has been placed.');
        return view('customer.order_confirmation', ['id' => $order->orders_id])
            ->with('success', 'Checkout completed! Your order has been placed.');
    }
}
