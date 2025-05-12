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
                'price' => 24.99,
                'image' => 'sea-bass.jpg',
                'category' => 'Fish',
                'description' => 'Premium Chilean sea bass fillets, wild-caught from the cold waters of Chile.'
            ],
            [
                'id' => 2,
                'name' => 'Argentinian Red Shrimp',
                'price' => 18.99,
                'image' => 'red-shrimp.jpg',
                'category' => 'Shellfish',
                'description' => 'Large, sweet Argentinian red shrimp, perfect for grilling or sautéing.'
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

        return view('customer.product-detail', compact('product'));
    }
}