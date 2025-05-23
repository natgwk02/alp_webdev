<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UpdateOldProductDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $updates = [
            'Chicken Nugget' => [
                'products_description' => 'Frozen chicken nuggets, easy to prepare and perfect for any meal.',
                'hover_image' => 'nugget_hover.jpg',
                'rating' => 4.2,
                'calories' => 250,
                'protein' => '15g',
                'fat' => '17g',
            ],
            'Frozen Shrimp' => [
                'products_description' => 'Frozen shrimp ready to cook, great for stir-fry or grilling.',
                'hover_image' => 'shrimp_hover.jpg',
                'rating' => 4.5,
                'calories' => 180,
                'protein' => '23g',
                'fat' => '9g',
            ],
            'Ice Cream Mochi' => [
                'products_description' => 'Soft mochi filled with delicious ice cream inside.',
                'hover_image' => 'mochi_hover.jpg',
                'rating' => 4.8,
                'calories' => 220,
                'protein' => '3g',
                'fat' => '7g',
            ],
            'Banana Cake' => [
                'products_description' => 'Soft and moist banana cake made with real bananas.',
                'hover_image' => 'banana_hover.jpg',
                'rating' => 4.0,
                'calories' => 300,
                'protein' => '5g',
                'fat' => '12g',
            ],
            'Cold Brew Coffee' => [
                'products_description' => 'Bottled cold brew coffee with smooth flavor.',
                'hover_image' => 'coldbrew_hover.jpg',
                'rating' => 4.5,
                'calories' => 100,
                'protein' => '0g',
                'fat' => '0g',
            ],
            'Frozen Pizza' => [
                'products_description' => 'Ready-to-bake frozen pizza with rich toppings.',
                'hover_image' => 'pizza_hover.jpg',
                'rating' => 4.3,
                'calories' => 400,
                'protein' => '18g',
                'fat' => '20g',
            ],
            'Beef Patty' => [
                'products_description' => 'Frozen beef patty, ideal for burgers or steaks.',
                'hover_image' => 'beefpatty_hover.jpg',
                'rating' => 4.6,
                'calories' => 350,
                'protein' => '21g',
                'fat' => '25g',
            ],
            'Vegan Sausage' => [
                'products_description' => 'Healthy vegan sausage, plant-based and flavorful.',
                'hover_image' => 'vegan_hover.jpg',
                'rating' => 4.1,
                'calories' => 280,
                'protein' => '12g',
                'fat' => '18g',
            ],
            'Potato Wedges' => [
                'products_description' => 'Crispy frozen potato wedges, easy to fry or bake.',
                'hover_image' => 'wedges_hover.jpg',
                'rating' => 4.4,
                'calories' => 200,
                'protein' => '3g',
                'fat' => '9g',
            ],
            'Bolognese Sauce' => [
                'products_description' => 'Rich and savory Bolognese sauce for your favorite pasta.',
                'hover_image' => 'sauce_hover.jpg',
                'rating' => 4.7,
                'calories' => 150,
                'protein' => '6g',
                'fat' => '10g',
            ],
        ];
        foreach ($updates as $name => $data) {
            DB::table('products')->where('products_name', $name)->update($data);
        }
    }
}
