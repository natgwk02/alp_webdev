<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,products_id',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Simpan atau update rating dari user untuk produk
        Rating::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $request->product_id
            ],
            ['rating' => $request->rating]
        );

        // Hitung rata-rata dan update ke kolom 'rating' di tabel products
        $avg = Rating::where('product_id', $request->product_id)->avg('rating');
        Product::where('products_id', $request->product_id)->update(['rating' => $avg]);

        return back()->with('success', 'Thank you for rating!');
    }
}
