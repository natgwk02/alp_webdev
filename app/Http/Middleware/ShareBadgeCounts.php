<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Cart;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ShareBadgeCounts
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $cart = Cart::where('users_id', Auth::id())->first();
            $cartCount = $cart ? $cart->items()->count() : 0;
            $wishlistCount = Wishlist::where('users_id', Auth::id())->count();
            
            View::share('cartCount', $cartCount);
            View::share('wishlistCount', $wishlistCount);
        } else {
            View::share('cartCount', 0);
            View::share('wishlistCount', 0);
        }

        return $next($request);
    }
}
