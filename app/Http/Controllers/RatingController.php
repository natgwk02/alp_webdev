<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Product;
use App\Models\OrderDetail;
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

        $userId = Auth::id();
        $productId = $request->product_id;

        // Cek apakah user pernah menyelesaikan pesanan untuk produk
        $hasCompletedOrder = OrderDetail::where('products_id', $productId)
            ->whereHas('order', function ($query) use ($userId) {
                $query->where('users_id', $userId)
                    ->where('orders_status', 'Delivered');
            })
            ->exists();

        if (! $hasCompletedOrder) {
            return back()->with('error', 'You can only rate products you have purchased and completed.');
        }

        Rating::updateOrCreate(
            [
                'user_id' => $userId,
                'product_id' => $productId
            ],
            [
                'rating' => $request->rating
            ]
        );

        // Update rata-rata rating produk
        $avg = Rating::where('product_id', $productId)->avg('rating');
        Product::where('products_id', $productId)->update(['rating' => $avg]);

        return back()->with('success', 'Thank you for rating!');
    }
    
}
