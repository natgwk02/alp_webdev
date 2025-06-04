<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Rating;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

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
                    'orders_number' => $order->orders_id,
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
                            'product_id'    => $detail->products_id,
                            'product_name'  => $productName,
                            'price'         => $detail->price,
                            'quantity'      => $detail->order_details_quantity,
                            'product_image' => $productImage,
                            'is_rated'      => Rating::where('user_id', Auth::id())->where('product_id', $detail->products_id)->exists(),
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
        $order = Order::with(['orderDetails.product'])
            ->where('orders_id', $id)
            ->where('users_id', Auth::id())
            ->firstOrFail();

        if (!$order) {
            return redirect()->route('orders.index')->with('error', 'Order not found.');
        }

        $ratedProductIds = Rating::where('user_id', Auth::id())->pluck('product_id')->toArray();

        $formattedOrder = [
            'id' => $order->orders_id,
            'order_number' => $order->orders_id,
            'created_at' => $order->created_at->format('Y-m-d H:i:s'),
            'status' => $order->orders_status,
            'items' => $order->orderDetails->map(function ($detail) use ($ratedProductIds) {
                $productName = 'Unknown Product';
                $productImage = 'no-image.png';

                if ($detail->product) {
                    $productName = $detail->product->products_name;
                    $productImage = $detail->product->products_image;
                } else {
                    Log::warning('Product not loaded for OrderDetail ID: ' . $detail->getKey() . ' (OrderDetail uses products_id FK: ' . $detail->products_id . ')');
                }

                return [
                    'product_id' => $detail->products_id,
                    'name' => $productName,
                    'price' => $detail->price,
                    'quantity' => $detail->order_details_quantity,
                    'image_filename' => $productImage,
                    'is_rated' => in_array($detail->products_id, $ratedProductIds),
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
        Log::info('--- Checkout: showCheckoutForm START ---');
        Log::info('Checkout: Request method:', [$request->getMethod()]);

        $rawSelectedItems = $request->input('selected_items');
        Log::info('Checkout: Raw selected_items from request:', [$rawSelectedItems]);

        $selectedProductIds = [];
        if (is_string($rawSelectedItems) && !empty($rawSelectedItems)) {
            $selectedProductIds = explode(',', $rawSelectedItems);
        } elseif (is_array($rawSelectedItems)) {
            $selectedProductIds = $rawSelectedItems;
        }
        Log::info('Checkout: Selected IDs after initial processing (explode/assign):', $selectedProductIds);

        // Remove empty values and ensure we have integers
        $selectedProductIds = array_filter($selectedProductIds, function ($id) {
            return !empty($id) && is_numeric(trim($id));
        });
        $selectedProductIds = array_map('intval', $selectedProductIds);
        Log::info('Checkout: Processed selected product IDs from request (after filter/map):', $selectedProductIds);

        $source_of_ids = 'request';

        // If empty from request processing, try to get from session
        if (empty($selectedProductIds) && session()->has('checkout_selected_ids_temp')) {
            Log::warning('Checkout: IDs from request were empty/invalid. Attempting session fallback.');
            Log::info('Checkout: Session checkout_selected_ids_temp (before fallback):', [session('checkout_selected_ids_temp')]);
            $selectedProductIds = session('checkout_selected_ids_temp');
            $source_of_ids = 'session_fallback';
            Log::info('Checkout: Using selected product IDs from session fallback:', $selectedProductIds);
        }

        // Critical check: If still no IDs, redirect back to cart.
        if (empty($selectedProductIds)) {
            Log::error('Checkout: No items selected for checkout (even after potential session fallback). Redirecting to cart.');
            session()->forget('checkout_selected_ids_temp');
            return redirect()->route('cart.index')->with('error', 'No items selected for checkout. Please select items from your cart.');
        }

        // Store the *actually used* IDs in session for checkout page refreshes.
        session(['checkout_selected_ids_temp' => $selectedProductIds]);
        Log::info('Checkout: Stored/Updated checkout_selected_ids_temp in session with final IDs:', $selectedProductIds);
        Log::info('Checkout: Source of final IDs for this page load:', [$source_of_ids]);

        // 2. Get cart items from database
        if (!Auth::check()) {
            Log::warning('Checkout: User not authenticated. Redirecting to login.');
            return redirect()->route('login')->with('error', 'You must be logged in to checkout.');
        }

        $cart = Cart::where('users_id', Auth::id())->first();
        if (!$cart) {
            Log::error('Checkout: Cart not found for user_id: ' . Auth::id() . '. Redirecting to cart index.');
            session()->forget('checkout_selected_ids_temp');
            return redirect()->route('cart.index')->with('error', 'Cart not found.');
        }

        // FIXED: Use consistent field name - check your CartItem model to confirm the correct field name
        // If your CartItem model uses 'product_id', use that. If it uses 'products_id', use that.
        // I'm assuming 'products_id' based on your other code, but verify this!
        $cartItems = $cart->items()->with('product')->whereIn('products_id', $selectedProductIds)->get();

        if ($cartItems->isEmpty()) {
            Log::warning('Checkout: Selected items not found in cart for user_id: ' . Auth::id() . '. IDs:', $selectedProductIds);
            session()->forget('checkout_selected_ids_temp');
            return redirect()->route('cart.index')->with('error', 'Selected items not found in cart. Please try again.');
        }

        // FIXED: Convert cart items to the format expected by the checkout view
        $filteredItems = $cartItems->map(function ($cartItem) {
            if (!$cartItem->product) {
                Log::error('Checkout: Product data missing for cart item ID: ' . $cartItem->id . ', products_id: ' . $cartItem->products_id);
                return [
                    'id' => $cartItem->products_id, // FIXED: Use consistent field name
                    'name' => 'Error: Product Not Found',
                    'price' => 0,
                    'quantity' => $cartItem->quantity,
                    'image' => 'no-image.png',
                ];
            }
            return [
                'id' => $cartItem->products_id, // FIXED: Use the product ID consistently
                'name' => $cartItem->product->products_name ?? 'Unknown Product',
                'price' => $cartItem->product->orders_price ?? 0,
                'quantity' => $cartItem->quantity,
                'image' => $cartItem->product->products_image ?? 'no-image.png',
            ];
        })->toArray();

        Log::info('Checkout: Filtered items for view:', $filteredItems);

        // 3. Calculate totals based on these items
        $subtotal = collect($filteredItems)->sum(function ($item) {
            return ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
        });

        $shippingFee = 5000;
        $tax = round($subtotal * 0.1);
        $voucherDiscount = session('voucher_discount', 0);
        $total = $subtotal + $shippingFee + $tax - $voucherDiscount;

        // 4. Prepare default data for shipping form
        $defaultData = [
            'firstName' => '',
            'lastName' => '',
            'phone' => '',
            'address' => '',
            'city' => '',
            'zip' => '',
            'country' => 'Indonesia',
        ];

        if (Auth::check()) {
            $user = Auth::user();
            $nameParts = explode(' ', $user->users_name ?? '', 2);
            $defaultData['firstName'] = old('firstName', $nameParts[0] ?? '');
            $defaultData['lastName'] = old('lastName', $nameParts[1] ?? ($nameParts[0] && count($nameParts) == 1 ? '' : ''));
            $defaultData['email'] = old('email', $user->users_email ?? '');
            $defaultData['phone'] = old('phone', $user->users_phone ?? '');
            $defaultData['address'] = old('address', $user->users_address ?? '');
            $defaultData['city'] = old('city', $user->users_city ?? '');
            $defaultData['zip'] = old('zip', $user->users_zip ?? '');
            $defaultData['country'] = old('country', $user->users_country ?? 'Indonesia');
        } else {
            foreach ($defaultData as $key => $value) {
                $defaultData[$key] = old($key, $value);
            }
        }

        Log::info('Checkout: --- showCheckoutForm END (Data prepared for view) ---');

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
        Log::info('--- processCheckout START ---');
        Log::info('Request Data for processCheckout:', $request->all());

        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'zip' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'paymentMethod' => 'required|string',
            'selected_items' => 'required|json',
            'sellerNotes' => 'nullable|string|max:65535',
            'termsAgreement' => 'required',
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
        return redirect()->route('order.detail', ['id' => $order->orders_id])
            ->with('success', 'Checkout completed! Your order has been placed.');
    }
}
