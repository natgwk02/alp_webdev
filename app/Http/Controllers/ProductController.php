<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    
    public function index()
    {
    $products = Product::all();
    $wishlistProductIds = Auth::check()
    ? Wishlist::where('user_id', Auth::id())->pluck('product_id')->toArray()
    : [];
    $categories = DB::table('categories')->pluck('categories_name', 'categories_id')->toArray();

    return view('customer.products', compact('products', 'wishlistProductIds', 'categories'));
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        return view('customer.product_details', compact('product'));
    }
    public function wishlist()
    {
         $wishlistItems = Wishlist::with('product')
        ->where('user_id', Auth::id())
        ->get()
        ->map(function ($item) {
            return $item->product;
        });

    return view('customer.wishlist', compact('wishlistItems'));
    }


    public function addToWishlist(Request $request, $productId)
    {
        Wishlist::firstOrCreate([
        'user_id' => Auth::id(),
        'product_id' => $productId,
    ]);

    return redirect()->back()->with('success', 'Product added to wishlist');
    }

    public function removeFromWishlist(Request $request, $productId)
    {
         Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
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

    $product = Product::find($productId);
    if (!$product) {
        return response()->json(['message' => 'Product not found'], 404);
    }

    $wishlist = Wishlist::where('user_id', Auth::id())->where('product_id', $productId)->first();

    if ($wishlist) {
        $wishlist->delete();
        $message = 'Product removed from wishlist';
    } else {
        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $productId,
        ]);
        $message = 'Product added to wishlist';
    }

    return response()->json(['message' => $message]);

    }
}
