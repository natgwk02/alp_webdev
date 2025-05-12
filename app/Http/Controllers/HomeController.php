<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Hardcoded featured products for Chile Mart
        $featuredProducts = [
            [
                'id' => 1,
                'name' => 'Chilean Sea Bass Fillet',
                'price' => 24.99,
                'image' => 'sea-bass.jpg',
                'discount' => 5.00
            ],
            [
                'id' => 2,
                'name' => 'Argentinian Red Shrimp',
                'price' => 18.99,
                'image' => 'red-shrimp.jpg',
                'discount' => 0
            ],
            [
                'id' => 3,
                'name' => 'Alaskan King Crab Legs',
                'price' => 39.99,
                'image' => 'crab-legs.jpg',
                'discount' => 10.00
            ]
        ];

        return view('customer.home', compact('featuredProducts'));
    }
}