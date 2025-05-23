<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index(Request $request)
    {
        // Get the current user's cart
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        
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

    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        // Get the product details from the database
        $product = Product::find($productId);

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        // Get the current user's cart, or create one if it doesn't exist
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        // Check if the product is already in the cart
        $cartItem = CartItem::firstOrNew(['cart_id' => $cart->id, 'product_id' => $productId]);

        // Update the quantity
        $cartItem->quantity += (int) $request->input('quantity', 1);
        $cartItem->save();

        return redirect()->back()->with('success', 'Item added to cart.');
    }

    public function removeFromCart(Request $request, $productId)
    {
        // Get the current user's cart
        $cart = Cart::where('user_id', Auth::id())->first();

        // Remove the product from the cart
        $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $productId)->first();
        if ($cartItem) {
            $cartItem->delete();
        }

        return redirect()->route('cart.index')->with('success', 'Product removed from cart.');
    }

    public function updateQuantity(Request $request, $productId)
    {
        // Get the new quantity from the form
        $quantity = max(1, (int) $request->input('quantity', 1));

        // Get the current user's cart
        $cart = Cart::where('user_id', Auth::id())->first();

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

    private function calculateSubtotal($cart)
    {
        return $cart->items->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
    }

    public function applyVoucher(Request $request)
    {
        $validVouchers = [
            'CHILLBRO' => ['min' => 200000, 'discount' => 50000],
            'COOLMAN' => ['discount' => 20000],
            'GOODDAY' => ['discount' => 10000]
        ];

        $code = strtoupper($request->input('voucher_code'));
        $cart = Cart::where('user_id', Auth::id())->first();
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

    public function removeVoucher()
    {
        session()->forget(['voucher_code', 'voucher_discount']);
        return back()->with('voucher_success', 'Voucher removed successfully');
    }
}
