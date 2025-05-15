<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    private $products = [
        ['id' => 1, 'name' => 'Chilean Sea Bass Fillet', 'price' => 200000, 'image' => 'sea-bass.jpg', 'stock' => 10, 'category' => 'Fish', 'description' => 'Premium Chilean sea bass fillets, wild-caught from the cold waters of Chile.'],
        ['id' => 2, 'name' => 'Argentinian Red Shrimp', 'price' => 220000, 'image' => 'red-shrimp.jpg', 'stock' => 15, 'category' => 'Shellfish', 'description' => 'Large, sweet Argentinian red shrimp, perfect for grilling or sautéing.'],
        ['id' => 3, 'name' => 'Kanzler Nugget Crispy', 'price' => 50000, 'image' => 'kanzler-nugget.jpg', 'stock' => 20, 'category' => 'Chicken Nugget', 'description' => 'Crispy and flavorful chicken nuggets, perfect for quick meals or party snacks.'],
        ['id' => 4, 'name' => 'Ready Meal Fiesta Beef Bulgogi With Rice', 'price' => 26999, 'image' => 'rm-fiesta-bulgogi.jpg', 'stock' => 25, 'category' => 'Ready Meals', 'description' => 'Tender beef in a savory bulgogi marinade, perfectly paired with fluffy rice, ideal for a quick and delicious meal.'],
        ['id' => 5, 'name' => 'Gorton\'s Classic Grilled Salmon', 'price' => 56000, 'image' => 'fish-grilled-salmon.jpg', 'stock' => 30, 'category' => 'Fish', 'description' => 'Grilled salmon fillets seasoned and ready to enjoy.'],
        ['id' => 6, 'name' => 'Fiesta Chicken Karaage 500gr', 'price' => 48000, 'image' => 'chicken-fiesta-karage.jpg', 'stock' => 15, 'category' => 'Chicken', 'description' => 'Crispy Japanese-style chicken karaage, made from tender chicken thigh meat. Ready to fry.'],
        ['id' => 7, 'name' => 'Good Value Mixed Fruit', 'price' => 32000, 'image' => 'gv-mixed-fruit.jpg', 'stock' => 40, 'category' => 'Frozen Fruit', 'description' => 'A convenient mix of frozen strawberries, blueberries, mango, and pineapple. Perfect for smoothies or desserts.'],
        ['id' => 8, 'name' => 'Golden Farm Mixed Vegetable', 'price' => 25000, 'image' => 'gf-mixedvegetables.jpg', 'stock' => 35, 'category' => 'Frozen Vegetables', 'description' => 'A healthy blend of frozen carrots, corn, green beans, and peas. Great for stir-fries or soups.'],
        ['id' => 9, 'name' => 'Fiesta Siomay', 'price' => 34000, 'image' => 'fiesta-siomay.jpg', 'stock' => 50, 'category' => 'Frozen Dim Sum', 'description' => 'Delicious and ready-to-steam chicken siomay, perfect for snacks or side dishes.'],
    ];

    public function index(Request $request)
    {
        // Ambil data cart dari session
        $cartItems = session('cart', []);

        // Hitung subtotal
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $shippingFee = 5000; // biaya shipping fix contoh
        $tax = round($subtotal * 0.1); // pajak 10%
        $total = $subtotal + $shippingFee + $tax;

        return view('customer.cart', compact('cartItems', 'subtotal', 'shippingFee', 'tax', 'total'));
    }

    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        // cari produk dari array berdasarkan id (index dimulai 0, id mulai 1)
        $product = collect($this->products)->firstWhere('id', (int)$productId);

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found');
        }

        $cart = session('cart', []);

        // Jika produk sudah ada di cart, tambah quantity
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            // Baru tambah produk dengan quantity 1
            $cart[$productId] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => 1,
            ];
        }

        session(['cart' => $cart]);

        return redirect()->route('cart')->with('success', 'Product added to cart');
    }

    public function removeFromCart(Request $request, $productId)
    {
        $cart = session('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']--;

            if ($cart[$productId]['quantity'] <= 0) {
                unset($cart[$productId]);
            }

            session(['cart' => $cart]);
        }

        return redirect()->route('cart')->with('success', 'Product quantity updated');
    }
}