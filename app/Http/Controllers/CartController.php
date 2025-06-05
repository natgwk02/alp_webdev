<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    /**
     * Show the current user's cart.
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to view your cart.');
        }

        $cart = Cart::firstOrCreate(['users_id' => Auth::id()]);
        $cartItems = $cart->items()->with('product')->get();



        $subtotal = $this->calculateSubtotal($cart);
        $shippingFee = 5000;
        $tax = round($subtotal * 0.1);
        $voucherDiscount = session('voucher_discount', 0);
        $total = $subtotal + $shippingFee + $tax - $voucherDiscount;

        $selectedItemsOnLoad = session('selected_items_on_load', []);

        return view('customer.cart', compact(
            'cartItems',
            'subtotal',
            'shippingFee',
            'tax',
            'total',
            'voucherDiscount',
            'selectedItemsOnLoad'
        ));
    }

public function addToCart(Request $request, $productId)
{
    if (session()->has('is_guest')) {
        return response()->json(['message' => 'Guests cannot add to cart.'], 403);
    }

    if (!Auth::check()) {
        return response()->json(['success' => false, 'message' => 'Please login.'], 403);
    }

    // Ambil product_id dari route atau form input
    $productId = $productId ?? $request->input('product_id');

    // Cek apakah produk ada
    $product = Product::find($productId);
    if (!$product) {
        return response()->json(['success' => false, 'message' => 'Product not found.'], 404);
    }

    // Ambil cart user atau buat baru
    $cart = Cart::firstOrCreate(['users_id' => Auth::id()]);

    // Tambahkan item ke cart
    $cartItem = CartItem::firstOrNew([
        'cart_id' => $cart->id,
        'products_id' => $productId,
    ]);

    // Atur jumlah quantity awal jika belum ada
    if (!$cartItem->exists) {
        $cartItem->quantity = 0;
    }

    $cartItem->quantity += (int) $request->input('quantity', 1);
    $cartItem->products_id = $productId; // Pastikan field ini terisi
    $cartItem->save();

    // Respon berbeda tergantung apakah dari AJAX atau tidak
    if ($request->ajax()) {
        return response()->json(['success' => true, 'message' => 'Item added to cart.']);
    }

    return back()->with('success', 'Item added to cart.');
}

    /**
     * Remove a product from the cart.
     */
    public function removeFromCart(Request $request, $productId)
    {
        // Check if the user is logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to remove items from the cart.');
        }

        // Get the current user's cart
        $cart = Cart::where('users_id', Auth::id())->first();

        // Remove the product from the cart
        $cartItem = CartItem::where('cart_id', $cart->id)->where('products_id', $productId)->first();
        if ($cartItem) {
            $cartItem->delete();
        }

        return redirect()->route('cart.index')->with('success', 'Product removed from cart.');
    }

    /**
     * Update the quantity of a product in the cart.
     */
    public function updateCart(Request $request, $productId)
    {
        // Check if the user is logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to update the quantity.');
        }

        // Get the new quantity from the form
        $quantity = max(1, (int) $request->input('quantity', 1));

        // Get the current user's cart
        $cart = Cart::where('users_id', Auth::id())->first();

        // Update the quantity of the product in the cart
        $cartItem = CartItem::where('cart_id', $cart->id)->where('products_id', $productId)->first();
        if ($cartItem) {
            $cartItem->quantity = $quantity;
            $cartItem->save();
        }

        $subtotal = $this->calculateSubtotal($cart);
        $shippingFee = 5000;
        $tax = round($subtotal * 0.1);
        $voucherDiscount = session('voucher_discount', 0);
        $total = $subtotal + $shippingFee + $tax - $voucherDiscount;

        return redirect()->route('cart.index')->with([
            'subtotal' => $subtotal,
            'shippingFee' => $shippingFee,
            'tax' => $tax,
            'total' => $total,
            'voucherDiscount' => $voucherDiscount,
        ]);
    }

    /**
     * Calculate the subtotal of the cart.
     */
    private function calculateSubtotal($cart)
    {
        if (!$cart || !$cart->relationLoaded('items')) {
        $cart->load('items.product'); 
    }
        return $cart->items->sum(function ($item) {

            if ($item->product && isset($item->product->orders_price)) {
                return $item->product->orders_price * $item->quantity;
            }
            return 0;
        });
    }

    /**
     * Apply a voucher to the cart.
     */
    public function applyVoucher(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to apply a voucher.');
        }
        

        $validVouchers = [
            'CHILLBRO' => ['min' => 200000, 'discount' => 50000],
            'COOLMAN' => ['discount' => 20000],
            'GOODDAY' => ['discount' => 10000]
        ];

        $code = strtoupper($request->input('voucher_code'));
        $cart = Cart::with('items.product')->where('users_id', Auth::id())->first();
        $selectedItems = explode(',', $request->input('selected_items_voucher', ''));
        $subtotal = $cart->items->filter(function ($item) use ($selectedItems) {
        return in_array((string) $item->products_id, $selectedItems);
    })->sum(function ($item) {
        return $item->product->orders_price * $item->quantity;
    });

    if (!array_key_exists($code, $validVouchers)) {
        return back()->withInput()->with('voucher_error', 'Invalid voucher code.')
            ->with('selected_items_on_load', $selectedItems);
    }

    $voucher = $validVouchers[$code];

    if (isset($voucher['min']) && $subtotal < $voucher['min']) {
        return back()->withInput()->with('voucher_error', 'Voucher ' . $code . ' requires minimum purchase of Rp' . number_format($voucher['min'], 0, ',', '.'))
            ->with('selected_items_on_load', $selectedItems);
    }

    // Simpan selected items juga ke session (optional)
    session([
        'voucher_code' => $code,
        'voucher_discount' => $voucher['discount'],
        'selected_items_on_load' => $selectedItems, // supaya ke-load lagi setelah redirect
    ]);

    return back()->with('voucher_success', 'Voucher applied successfully!')
        ->with('selected_items_on_load', $selectedItems);
    }

    public function removeVoucher(Request $request)
    {
        session()->forget(['voucher_code', 'voucher_discount']);
        // To preserve selection, you might need to get current selection via JS before submitting this form
        // For now, let's keep it simple and let the default selection (all items) apply on next page load
        return back()->with('voucher_success', 'Voucher removed successfully.');
    }

    public function getCounts()
    {
        $cartCount = 0;
        $wishlistCount = 0;

        if (Auth::check()) {
            $cart = Cart::where('users_id', Auth::id())->first();
            $cartCount = $cart ? $cart->items()->count() : 0;

            $wishlistCount = \App\Models\Wishlist::where('users_id', Auth::id())->count();
        }

        return response()->json([
            'cart' => $cartCount,
            'wishlist' => $wishlistCount,
        ]);
    }
}
