<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Show the cart
    public function index()
    {
        // Hardcoded cart items
        $cartItems = [
            [
                'product_id' => 1,
                'product_name' => 'Chilean Sea Bass Fillet',
                'price' => 24.99,
                'quantity' => 2,
                'image' => 'sea-bass.jpg',
                'stock' => 10
            ],
            [
                'product_id' => 2,
                'product_name' => 'Argentinian Red Shrimp',
                'price' => 18.99,
                'quantity' => 1,
                'image' => 'red-shrimp.jpg',
                'stock' => 15
            ]
        ];

        // Calculate the total number of items in the cart
        $totalItems = array_reduce($cartItems, function($carry, $item) {
            return $carry + $item['quantity'];
        }, 0);

        // Calculate subtotal, shipping fee, tax, and total
        $subtotal = array_reduce($cartItems, function($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        $shippingFee = 5.00;
        $tax = $subtotal * 0.1; // 10% tax
        $total = $subtotal + $shippingFee + $tax;

        // Return data to the view
        return view('customer.cart', compact('cartItems', 'totalItems', 'subtotal', 'shippingFee', 'tax', 'total'));
    }

    // Add a product to the cart
    public function addToCart(Request $request, $productId)
    {
        // In a real application, this would add item to the cart
        return redirect()->route('cart')
            ->with('success', 'Product added to cart successfully');
    }

    // Update cart quantities
    public function updateCart(Request $request)
    {
        // In a real application, this would update cart quantities
        $productId = $request->input('product_id');
       $quantity = $request->input('quantity');
        return redirect()->route('cart')
            ->with('success', 'Cart updated successfully');
    }

    // Remove a product from the cart
    public function removeFromCart($productId)
    {
        // In a real application, this would remove item from the cart
        return redirect()->route('cart')
            ->with('success', 'Product removed from cart');
    }

    // Show the wishlist
    public function wishlist()
    {
        // Hardcoded wishlist items
        $wishlistItems = [
            [
                'product_id' => 3,
                'product_name' => 'Alaskan King Crab Legs',
                'price' => 39.99,
                'image' => 'sea-bass.jpg',
                'in_stock' => true
            ],
            [
                'product_id' => 4,
                'product_name' => 'Patagonian Scallops',
                'price' => 29.99,
                'image' => 'sea-bass.jpg',
                'in_stock' => false
            ]
        ];

        // Return wishlist view
        return view('customer.wishlist', compact('wishlistItems'));
    }

    // Add a product to the wishlist
    public function addToWishlist($productId)
    {
        // In a real application, this would add item to wishlist
        return redirect()->route('wishlist')
            ->with('success', 'Product added to wishlist');
    }

    // Remove a product from the wishlist
    public function removeFromWishlist($productId)
    {
        // In a real application, this would remove item from wishlist
        return redirect()->route('wishlist')
            ->with('success', 'Product removed from wishlist');
    }
}
