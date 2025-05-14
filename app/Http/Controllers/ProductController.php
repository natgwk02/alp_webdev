<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    private function products()
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
                'origin' => 'Indonesia',
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
            'image' => 'chicken-fiesta.jpg',
            'category' => 'Chicken',
            'description' => 'Crispy Japanese-style chicken karaage, made from tender chicken thigh meat. Ready to fry.',
            'weight' => '500g',
            'origin' => 'Indonesia',
            'nutrition' => [
            'calories' => 290,
            'protein' => '18g',
            'fat' => '20g',
            ],
            'reviews' => []
            ],


            
        ];
    }

    public function index()
    {
        $products = $this->products();
        return view('customer.products', compact('products'));
    }

    public function show($id)
    {
        $product = collect($this->products())->firstWhere('id', $id);

        if (!$product) {
            abort(404, 'Product not found');
        }

        return view('customer.product_details', compact('product'));
    }
}
