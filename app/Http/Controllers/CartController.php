<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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

    // 🛒 Show Cart Page
public function index(Request $request)
{
    $cartItems = session('cart', []);
    $subtotal = 0;

    // Hapus item yang tidak memiliki data lengkap
    foreach ($cartItems as $key => $item) {
        if (!isset($item['id'], $item['name'], $item['price'], $item['image'], $item['quantity'])) {
            unset($cartItems[$key]);
            continue;
        }
        $subtotal += $item['price'] * $item['quantity'];
    }

    session(['cart' => $cartItems]);

    $shippingFee = 5000;
    $tax = round($subtotal * 0.1);
    $voucherDiscount = session('voucher_discount', 0);
    $total = $subtotal + $shippingFee + $tax - $voucherDiscount;

    return view('customer.cart', compact('cartItems', 'subtotal', 'shippingFee', 'tax', 'total', 'voucherDiscount'));
}


// app/Http/Controllers/CartController.php

public function applyVoucher(Request $request)
{
    $validVouchers = [
        'CHILLBRO' => ['min' => 200000, 'discount' => 50000],
        'COOLMAN' => ['discount' => 20000],
        'GOODDAY' => ['discount' => 10000]
    ];

    $code = strtoupper($request->input('voucher_code'));
    $subtotal = $this->calculateSubtotal();

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
    Session::forget(['voucher_code', 'voucher_discount']);
    return back()->with('voucher_success', 'Voucher removed successfully');
}

    // ➕ Add Item to Cart
    public function addToCart(Request $request)
{
    $productId = $request->input('product_id');
    $quantity = max(1, (int) $request->input('quantity', 1)); // Ambil dari form, minimal 1
    $cart = session('cart', []);

    $product = collect($this->products)->firstWhere('id', (int)$productId);

    if (!$product) {
        return redirect()->back()->with('error', 'Product not found.');
    }

    if (isset($cart[$productId])) {
        // Tambahkan quantity jika produk sudah ada di cart
        $cart[$productId]['quantity'] += $quantity;
    } else {
        // Masukkan produk baru
        $cart[$productId] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image'],
            'quantity' => $quantity,
        ];
    }

    session(['cart' => $cart]);

    return redirect()->route('cart.index')->with('success', 'Item added to cart!');
}


    // ➖ Remove or Decrease Item from Cart
    public function removeFromCart(Request $request, $productId)
    {
        $cart = session('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = ($cart[$productId]['quantity'] ?? 1) - 1;

            if ($cart[$productId]['quantity'] <= 0) {
                unset($cart[$productId]);
            }

            session(['cart' => $cart]);
        }

        return redirect()->route('cart.index')->with('success', 'Product quantity updated.');
    }





    private function calculateSubtotal()
{
    $cartItems = session('cart', []);
    return array_reduce($cartItems, function($carry, $item) {
        return $carry + ($item['price'] * $item['quantity']);
    }, 0);
}

// CartController.php

public function proceedToCheckout(Request $request)
{
    $cartItems = session('cart', []);
    
    if (empty($cartItems)) {
        return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
    }

    // Simpan semua data yang diperlukan untuk checkout di session
    $checkoutData = [
        'items' => $cartItems,
        'subtotal' => $this->calculateSubtotal(),
        'shipping' => 5000,
        'tax' => round($this->calculateSubtotal() * 0.1),
        'voucher_discount' => session('voucher_discount', 0),
        'created_at' => now()->toDateTimeString()
    ];
    
    session(['checkout_data' => $checkoutData]);
    
    return redirect()->route('checkout');
}
}