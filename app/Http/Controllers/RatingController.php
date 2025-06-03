<?php

namespace App\Http\Controllers;

use id;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    //
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,products_id',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Simpan atau update rating dari user untuk produk yang sama
        Rating::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $request->product_id
            ],
            ['rating' => $request->rating]
        );

        return back()->with('success', 'Thank you for rating!');
    }
}
