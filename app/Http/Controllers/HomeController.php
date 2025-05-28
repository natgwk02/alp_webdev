<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function showHome()
    {
        $products = Product::whereBetween('products_id', [2, 5])->get();
        //dd(Auth::user());
        return view('customer.home', compact('products'));
    }
}
