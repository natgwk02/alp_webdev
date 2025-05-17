<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function products()
    {
        return [
            [
                'id' => 1,
                'name' => 'Chilean Sea Bass Fillet',
                'price' => 200000,
                'image' => 'sea-bass.jpg',
                'category' => 'Fish',
                'description' => 'Premium Chilean sea bass fillets, wild-caught from the cold waters of Chile.',
                'weight' => '1 lb',
                'origin' => 'Chile',
                'rating' => 4.0,
                'hover_image' => 'chilean.jpeg',
                'nutrition' => [
                    'calories' => 200,
                    'protein' => '20g',
                    'fat' => '13g',
                ],
                'reviews' => [
                    [
                        'user' => 'John D.',
                        'rating' => 5,
                        'comment' => 'Excellent quality fish! Will order again.',
                    ]
                ]
            ],
            [
                'id' => 2,
                'name' => 'Argentinian Red Shrimp',
                'price' => 220000,
                'image' => 'red-shrimp.jpg',
                'category' => 'Shellfish',
                'description' => 'Large, sweet Argentinian red shrimp, perfect for grilling or sautéing.',
                'weight' => '500g',
                'origin' => 'Argentina',
                'rating' => 4.5,
                'hover_image' => 'redargentine.jpeg',
                'nutrition' => [
                    'calories' => 180,
                    'protein' => '23g',
                    'fat' => '9g',
                ],
                'reviews' => []
            ],
            [
                'id' => 3,
                'name' => 'Kanzler Nugget Crispy',
                'price' => 50000,
                'image' => 'kanzler-nugget.jpg',
                'category' => 'Chicken Nugget',
                'description' => 'Crispy and flavorful chicken nuggets, perfect for quick meals or party snacks.',
                'weight' => '400g',
                'rating' => 4.5,
                'origin' => 'Indonesia',
                'hover_image' => 'kanzlernugget.jpeg',
                'nutrition' => [
                    'calories' => 250,
                    'protein' => '15g',
                    'fat' => '17g',
                ],
                'reviews' => []
            ],
            [
                'id' => 4,
                'name' => 'Ready Meal Fiesta Beef Bulgogi With Rice',
                'price' => 26999,
                'image' => 'rm-fiesta-bulgogi.jpg',
                'category' => 'Ready Meals',
                'description' => 'Tender beef in a savory bulgogi marinade, perfectly paired with fluffy rice, ideal for a quick and delicious meal.',
                'weight' => '300g',
                'origin' => 'Korea',
                'rating' => 4.5,
                'hover_image' => 'ricebowl.jpeg',
                'nutrition' => [
                    'calories' => 350,
                    'protein' => '20g',
                    'fat' => '12g',
                ],
                'reviews' => []
            ],
            [
                'id' => 5,
                'name' => 'Gorton\'s Classic Grilled Salmon',
                'price' => 56000,
                'image' => 'fish-grilled-salmon.jpg',
                'category' => 'Fish',
                'description' => 'Grilled salmon fillets seasoned and ready to enjoy.',
                'weight' => '200g',
                'origin' => 'USA',
                'rating' => 5,
                'hover_image' => 'salmon.jpeg',
                'nutrition' => [
                    'calories' => 210,
                    'protein' => '22g',
                    'fat' => '12g',
                ],
                'reviews' => []
            ],
            [
                'id' => 6,
                'name' => 'Fiesta Chicken Karaage 500gr',
                'price' => 48000,
                'image' => 'chicken-fiesta-karage.jpg',
                'category' => 'Chicken',
                'description' => 'Crispy Japanese-style chicken karaage, made from tender chicken thigh meat. Ready to fry.',
                'weight' => '500g',
                'origin' => 'Indonesia',
                'rating' => 3.5,
                'hover_image' => 'karage.jpeg',
                'nutrition' => [
                    'calories' => 290,
                    'protein' => '18g',
                    'fat' => '20g',
                ],
                'reviews' => []
            ],
            [
                'id' => 7,
                'name' => 'Good Value Mixed Fruit',
                'price' => 32000,
                'image' => 'gv-mixed-fruit.jpg',
                'category' => 'Frozen Fruit',
                'description' => 'A convenient mix of frozen strawberries, blueberries, mango, and pineapple. Perfect for smoothies or desserts.',
                'weight' => '400g',
                'origin' => 'Indonesia',
                'rating' => 5,
                'hover_image' => 'fruitsalad.jpeg',
                'nutrition' => [
                    'calories' => 160,
                    'protein' => '1g',
                    'fat' => '0g',
                ],
                'reviews' => []
            ],
            [
                'id' => 8,
                'name' => 'Golden Farm Mixed Vegetable',
                'price' => 25000,
                'image' => 'gf-mixedvegetables.jpg',
                'category' => 'Frozen Vegetables',
                'description' => 'A healthy blend of frozen carrots, corn, green beans, and peas. Great for stir-fries or soups.',
                'weight' => '500g',
                'origin' => 'Indonesia',
                'rating' => 4,
                'hover_image' => 'mixedvege.jpeg',
                'nutrition' => [
                    'calories' => 90,
                    'protein' => '3g',
                    'fat' => '0.5g',
                ],
                'reviews' => []
            ],
            [
                'id' => 9,
                'name' => 'Fiesta Siomay',
                'price' => 34000,
                'image' => 'fiesta-siomay.jpeg',
                'category' => 'Frozen Dim Sum',
                'description' => 'Delicious and ready-to-steam chicken siomay, perfect for snacks or side dishes.',
                'weight' => '250g',
                'origin' => 'Indonesia',
                'rating' => 4.0,
                'hover_image' => 'fiestasomay.jpeg',
                'nutrition' => [
                    'calories' => 190,
                    'protein' => '8g',
                    'fat' => '9g',
                ],
                'reviews' => []
            ]
        ];
    }

    public function index()
    {
        $products = $this->products();
        $wishlist = session('wishlist', []);
        $categories = ['Ready Meals', 'Frozen Vegetables', 'Frozen Dimsum', 'Frozen Meat', 'Frozen Nugget', 'Frozen Fruit', 'Frozen Seafood', 'Dessert'];
        return view('customer.products', compact('products', 'wishlist', 'categories'));
    }

    public function show($id)
    {
        $product = collect($this->products())->firstWhere('id', $id);

        if (!$product) {
            abort(404, 'Product not found');
        }

        return view('customer.product_details', compact('product'));
    }
  public function wishlist()
{
    $wishlist = session('wishlist', []); // pastikan default array kosong
    $allProducts = $this->products();

    // Filter produk yang ada di wishlist
    $wishlistItems = array_filter($allProducts, function($product) use ($wishlist) {
        return isset($wishlist[$product['id']]);
    });

    // Contoh tambahkan 'in_stock' dan 'product_name' agar sesuai view (kalau belum ada)
    $wishlistItems = array_map(function($product) {
        $product['product_name'] = $product['name'];
        $product['in_stock'] = true; // bisa disesuaikan logika stoknya
        return $product;
    }, $wishlistItems);

    return view('customer.wishlist', compact('wishlistItems'));
}


    public function addToWishlist(Request $request, $productId)
    {
        $wishlist = session()->get('wishlist', []);
        $wishlist[$productId] = true;
        session(['wishlist' => $wishlist]);
        

        return redirect()->back()->with('success', 'Product added to wishlist');
    }

    public function removeFromWishlist(Request $request, $productId)
    {
        $wishlist = session()->get('wishlist', []);
        unset($wishlist[$productId]);
        session(['wishlist' => $wishlist]);

        return redirect()->back()->with('success', 'Product removed from wishlist');
    }

    public function showHome()
    {
        $products = $this->products(); // ambil dari fungsi yang kamu buat
        return view('customer.home', compact('products'));
    }
public function toggleWishlist($productId)
{
    $wishlist = session()->get('wishlist', []);
    if(isset($wishlist[$productId])){
        unset($wishlist[$productId]);
        $message = 'Product removed from wishlist';
    } else {
        $wishlist[$productId] = true;
        $message = 'Product added to wishlist';
    }
    session(['wishlist' => $wishlist]);

    return response()->json(['message' => $message]);
}

}


