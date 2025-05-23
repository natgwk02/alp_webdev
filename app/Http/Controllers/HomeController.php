<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function showHome()
    {
        $products = (new \App\Http\Controllers\ProductController)->products(); // ambil dari ProductController
        return view('customer.home', compact('products'));
    }
}
