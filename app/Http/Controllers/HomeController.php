<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function showHome()
    {
        //dd(Auth::user());
        
        $products = (new \App\Http\Controllers\ProductController)->products(); // ambil dari ProductController
        return view('customer.home', compact('products'));
    }
}
