<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function showHome()
    {
        $products = (new \App\Http\Controllers\ProductController)->products(); // ambil dari ProductController
        return view('customer.home', compact('products'));
    }
}
