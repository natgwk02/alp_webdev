<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Hardcoded products for Chile Mart
        $products = [
            [
                'id' => 1,
                'name' => 'Chilean Sea Bass Fillet',
                'price' => 200000,
                'image' => 'sea-bass.jpg',
                'category' => 'Fish',
                'description' => 'Premium Chilean sea bass fillets, wild-caught from the cold waters of Chile.'
            ],
            [
                'id' => 2,
                'name' => 'Argentinian Red Shrimp',
                'price' => 220000,
                'image' => 'red-shrimp.jpg',
                'category' => 'Shellfish',
                'description' => 'Large, sweet Argentinian red shrimp, perfect for grilling or sautéing.'
            ],
            [
                'id' => 3,
                'name' => 'Kanzler Nugget Crispy',
                'price' => 50000,
                'image' => 'kanzler-nugget.jpg',
                'category' => 'Chicken Nugget',
                'description' => 'Crispy and flavorful chicken nuggets, perfect for quick meals or party snacks.'
            ],
            [
                'id' => 4,
                'name' => 'Ready Meal Fiesta Beef Bulgogi With Rice',
                'price' => 26999,
                'image' => 'rm-fiesta-bulgogi.jpg',
                'category' => 'Ready Meals',
                'description' => 'Tender beef in a savory bulgogi marinade, perfectly paired with fluffy rice, ideal for a quick and delicious meal.'
            ],
            [
                'id' => 5,
                'name' => 'Gorton\'s Classic Grilled Salmon',
                'price' => 56000,
                'image' => 'fish-grilled-salmon.jpg',
                'category' => 'Fish',
                'description' => ''
            ],
            // More products...
        ];

        return view('customer.products', compact('products'));
    }

    public function show($id)
    {
        // Hardcoded product details
        $product = [
            'id' => $id,
            'name' => 'Chilean Sea Bass Fillet',
            'price' => 24.99,
            'image' => 'sea-bass.jpg',
            'category' => 'Fish',
            'description' => 'Premium Chilean sea bass fillets, wild-caught from the cold waters of Chile.',
            'weight' => '1 lb',
            'origin' => 'Chile',
            'nutrition' => [
                'calories' => 200,
                'protein' => '20g',
                'fat' => '13g'
            ],
            'reviews' => [
                [
                    'user' => 'John D.',
                    'rating' => 5,
                    'comment' => 'Excellent quality fish! Will order again.'
                ]
            ]
        ];

        return view('customer.product_details', compact('product'));
    }
}