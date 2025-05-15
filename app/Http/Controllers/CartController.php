<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    private $products = [
        ['id'=>1,'name'=>'Chilean Sea Bass Fillet','price'=>200000,'image'=>'sea-bass.jpg','stock'=>10],
        ['id'=>2,'name'=>'Argentinian Red Shrimp','price'=>220000,'image'=>'red-shrimp.jpg','stock'=>15],
        ['id'=>3,'name'=>'Kanzler Nugget Crispy','price'=>50000,'image'=>'kanzler-nugget.jpg','stock'=>20],
        ['id'=>4,'name'=>'Ready Meal Fiesta Beef Bulgogi With Rice','price'=>26999,'image'=>'rm-fiesta-bulgogi.jpg','stock'=>25],
        ['id'=>5,'name'=>'Gorton\'s Classic Grilled Salmon','price'=>56000,'image'=>'fish-grilled-salmon.jpg','stock'=>30],
        ['id'=>6,'name'=>'Fiesta Chicken Karaage 500gr','price'=>48000,'image'=>'chicken-fiesta-karage.jpg','stock'=>15],
        ['id'=>7,'name'=>'Good Value Mixed Fruit','price'=>32000,'image'=>'gv-mixed-fruit.jpg','stock'=>40],
        ['id'=>8,'name'=>'Golden Farm Mixed Vegetable','price'=>25000,'image'=>'gf-mixedvegetables.jpg','stock'=>35],
        ['id'=>9,'name'=>'Fiesta Siomay','price'=>34000,'image'=>'fiesta-siomay.jpg','stock'=>50],
    ];

    public function index()
    {
        $cartItems = session('cart', []);
        $totalItems = array_sum(array_column($cartItems, 'quantity'));
        $subtotal = 0;
        foreach($cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $shippingFee = 5000;
        $tax = $subtotal * 0.1;
        $total = $subtotal + $shippingFee + $tax;

        return view('customer.cart', compact('cartItems', 'totalItems', 'subtotal', 'shippingFee', 'tax', 'total'));
    }

    public function addToCart(Request $request, $productId)
{
    $products = collect($this->products)->keyBy('id');
    if (!$products->has($productId)) 
        return redirect()->route('cart')->with('error', 'Product not found');

    $cart = session('cart', []);
    $product = $products->get($productId);

    // Cek apakah product punya 'stock'
    if(!isset($product['stock'])) {
        return redirect()->route('cart')->with('error', 'Product stock information missing');
    }

    if(isset($cart[$productId])) {
        if($cart[$productId]['quantity'] < $product['stock']) {
            $cart[$productId]['quantity']++;
        } else {
            return redirect()->route('cart')->with('error', 'Cannot add more than stock available');
        }
    } else {
        $product['quantity'] = 1;
        $cart[$productId] = $product;
    }

    session(['cart' => $cart]);

    return redirect()->route('cart')->with('success', 'Product added to cart');
}


    public function removeFromCart(Request $request, $productId)
    {
        $cart = session('cart', []);
        if(isset($cart[$productId])) {
            $cart[$productId]['quantity']--;
            if($cart[$productId]['quantity'] <= 0) unset($cart[$productId]);
            session(['cart' => $cart]);
        }
        return redirect()->route('cart')->with('success', 'Product quantity updated');
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
