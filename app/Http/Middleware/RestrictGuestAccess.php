<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictGuestAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (session('is_guest')) {
            if ($request->ajax()) {
                return response()->json(['message' => 'Please sign in to use this feature.'], 401);
            }

            return redirect()->route('login')->with('error', 'Please sign in to use this feature.');
        }

        return $next($request);
    }
}
