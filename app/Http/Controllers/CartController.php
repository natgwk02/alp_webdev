<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
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

        // Menghitung jumlah total item dalam keranjang
        $totalItems = array_reduce($cartItems, function($carry, $item) {
            return $carry + $item['quantity'];
        }, 0);

        // Menghitung subtotal, shipping fee, tax, dan total
        $subtotal = array_reduce($cartItems, function($carry, $item) {
            return $carry + ($item['price'] * $item['quantity']);
        }, 0);

        $shippingFee = 5.00;
        $tax = $subtotal * 0.1; // 10% tax
        $total = $subtotal + $shippingFee + $tax;

        // Mengirimkan data ke view
        return view('customer.cart', compact('cartItems', 'totalItems', 'subtotal', 'shippingFee', 'tax', 'total'));
    }

    public function addToCart(Request $request, $productId)
    {
        // In a real application, this would add item to cart
        return redirect()->route('cart')
            ->with('success', 'Product added to cart successfully');
    }

    public function updateCart(Request $request)
    {
        // In a real application, this would update cart quantities
        return redirect()->route('cart')
            ->with('success', 'Cart updated successfully');
    }

    public function removeFromCart($productId)
    {
        // In a real application, this would remove item from cart
        return redirect()->route('cart')
            ->with('success', 'Product removed from cart');
    }

    public function wishlist()
    {
        // Hardcoded wishlist items
        $wishlistItems = [
            [
                'product_id' => 3,
                'product_name' => 'Alaskan King Crab Legs',
                'price' => 39.99,
                'image' => 'crab-legs.jpg',
                'in_stock' => true
            ],
            [
                'product_id' => 4,
                'product_name' => 'Patagonian Scallops',
                'price' => 29.99,
                'image' => 'scallops.jpg',
                'in_stock' => false
            ]
        ];

        return view('customer.wishlist', compact('wishlistItems'));
    }

    public function addToWishlist($productId)
    {
        // In a real application, this would add item to wishlist
        return redirect()->back()
            ->with('success', 'Product added to wishlist');
    }

    public function removeFromWishlist($productId)
    {
        // In a real application, this would remove item from wishlist
        return redirect()->route('wishlist')
            ->with('success', 'Product removed from wishlist');
    }
}
