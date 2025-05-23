<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function showHome()
    {
        $products = Product::whereBetween('products_id', [2, 5])->get();
        return view('customer.home', compact('products'));
    }
}
