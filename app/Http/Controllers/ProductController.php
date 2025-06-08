<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    public function index(Request $request)
    {
    $query = Product::query();

    // Filter: Search by name
    if ($request->filled('search')) {
        $query->where('products_name', 'like', '%' . $request->search . '%');
    }

    // Filter: Category
    if ($request->filled('category')) {
        $query->where('categories_id', $request->category);
    }

    // Filter: Price range
    if ($request->filled('min_price')) {
        $query->where('orders_price', '>=', $request->min_price);
    }

    if ($request->filled('max_price')) {
        $query->where('orders_price', '<=', $request->max_price);
    }

    $products = $query->with(['category', 'ratings'])->paginate(12);

    // Wishlist
    $wishlistProductIds = Auth::check()
        ? Wishlist::where('users_id', Auth::id())->pluck('products_id')->toArray()
        : [];

    $wishlistCount = Auth::check()
        ? Wishlist::where('users_id', Auth::id())->count()
        : 0;

    // Cart Count
    $cartCount = 0;
    if (Auth::check()) {
        $cart = \App\Models\Cart::where('users_id', Auth::id())->first();
        $cartCount = $cart ? $cart->items()->count() : 0;
    }

    // Get distinct product categories
    $categories = DB::table('categories')->pluck('categories_name', 'categories_id')->toArray();

    return view('customer.products', compact(
        'products',
        'wishlistProductIds',
        'wishlistCount',
        'cartCount',
        'categories'
    ));
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        $averageRating = $product->rating; // dari kolom 'rating' di tabel products
        $reviewCount = \App\Models\Rating::where('product_id', $product->products_id)->count();

        $userId = Auth::id();

        // Cek apakah user sudah menyelesaikan pesanan produk ini
        $hasCompletedOrder = false;
        $hasRated = false;
        
        if (Auth::check()) {
            $hasCompletedOrder = \App\Models\OrderDetail::where('products_id', $product->products_id)
                ->whereHas('order', function ($query) use ($userId) {
                    $query->where('users_id', $userId)
                        ->where('orders_status', 'Delivered');
                })
                ->exists();

            $hasRated = \App\Models\Rating::where('user_id', $userId)
                ->where('product_id', $product->products_id)
                ->exists();
        }

        return view('customer.product_details', compact(
            'product',
            'averageRating',
            'reviewCount',
            'hasCompletedOrder',
            'hasRated'
        ));
    }


    public function wishlist()
    {
         $wishlistItems = Wishlist::with('product')
        ->where('users_id', Auth::id())
        ->get()
        ->map(function ($item) {
            return [
                'products_id' => $item->product->products_id,
                'products_name' => $item->product->product_name,
                'price' => $item->product->price,
                'products_image' => $item->product->products_image,
                'products_stock' => $item->product->products_stock > 0,
                'orders_price' => $item->product->orders_price, 
                'categories_name'=> optional($item->product->category)->categories_name
            ];
        });
        

    return view('customer.wishlist', compact('wishlistItems'));
    }


    public function addToWishlist(Request $request, $productId)
    {
        Wishlist::firstOrCreate([
        'users_id' => Auth::id(),
        'products_id' => $productId,
    ]);

    return redirect()->back()->with('success', 'Product added to wishlist');
    }

    public function removeFromWishlist(Request $request, $productId)
    {
         Wishlist::where('users_id', Auth::id())
            ->where('products_id', $productId)
            ->delete();

    return redirect()->back()->with('success', 'Product removed from wishlist');
    }

    public function showHome()
    {
        $products = Product::limit(8)->get();
        return view('customer.home', compact('products'));
    }


    public function toggleWishlist($productId)
    {
        if (!Auth::check()) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $product = \App\Models\Product::find($productId);
    if (!$product) {
        return response()->json(['message' => 'Product not found'], 404);
    }

    $wishlist = \App\Models\Wishlist::where('users_id', Auth::id())
                ->where('products_id', $productId)
                ->first();

    if ($wishlist) {
        $wishlist->delete();
        $message = 'Product removed from wishlist';
    } else {
        \App\Models\Wishlist::create([
            'users_id' => Auth::id(),
            'products_id' => $productId,
        ]);
        $message = 'Product added to wishlist';
    }

    return response()->json(['message' => $message]);

    }
}

