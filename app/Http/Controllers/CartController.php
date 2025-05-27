<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct()
    {
      //   $this->middleware('auth');
    }
    /**
     * Show the current user's cart.
     */
    public function index(Request $request)
    {
        // Check if the user is logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to view your cart.');
        }

        // Get the current user's cart, or create one if it doesn't exist
        $cart = Cart::firstOrCreate(['users_id' => Auth::id()]);

        // Fetch all items in the cart, including product details
        $cartItems = $cart->items()->with('product')->get();

        // Calculate the subtotal, tax, shipping, and total
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $shippingFee = 5000;
        $tax = round($subtotal * 0.1);
        $voucherDiscount = session('voucher_discount', 0);
        $total = $subtotal + $shippingFee + $tax - $voucherDiscount;

        return view('customer.cart', compact('cartItems', 'subtotal', 'shippingFee', 'tax', 'total', 'voucherDiscount'));
    }

    /**
     * Add a product to the cart.
     */
    public function addToCart(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        //dd(Auth::id());
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        $cartItem = CartItem::firstOrNew([
            'cart_id' => $cart->id,
            'product_id' => $productId
        ]);

        $cartItem->quantity += (int) $request->input('quantity', 1);
        $cartItem->save();

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
        $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $productId)->first();
        if ($cartItem) {
            $cartItem->delete();
        }

        return redirect()->route('cart.index')->with('success', 'Product removed from cart.');
    }

    /**
     * Update the quantity of a product in the cart.
     */
    public function updateQuantity(Request $request, $productId)
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
        $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $productId)->first();
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
        return $cart->items->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
    }

    /**
     * Apply a voucher to the cart.
     */
    public function applyVoucher(Request $request)
    {
        // Ensure the user is logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to apply a voucher.');
        }

        $validVouchers = [
            'CHILLBRO' => ['min' => 200000, 'discount' => 50000],
            'COOLMAN' => ['discount' => 20000],
            'GOODDAY' => ['discount' => 10000]
        ];

        $code = strtoupper($request->input('voucher_code'));
        $cart = Cart::where('users_id', Auth::id())->first();
        $subtotal = $this->calculateSubtotal($cart);

        if (!array_key_exists($code, $validVouchers)) {
            return back()->with('voucher_error', 'Invalid voucher code.');
        }

        $voucher = $validVouchers[$code];

        if (isset($voucher['min']) && $subtotal < $voucher['min']) {
            return back()->with('voucher_error', 'CHILLBRO voucher requires minimum purchase of Rp200,000');
        }

        session([
            'voucher_code' => $code,
            'voucher_discount' => $voucher['discount']
        ]);

        return back()->with('voucher_success', 'Voucher applied successfully!');
    }

    /**
     * Remove the applied voucher.
     */
    public function removeVoucher()
    {
        session()->forget(['voucher_code', 'voucher_discount']);
        return back()->with('voucher_success', 'Voucher removed successfully');
    }
}
