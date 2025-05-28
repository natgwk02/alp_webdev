<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
        /** @var \App\Models\User $user An instance of your User model */
        $user = Auth::user();

        // It's also good practice to ensure $user is not null,
        // though Auth::check() should generally guarantee it.
        if ($user) {
            return redirect($user->getRedirectRoute());
        }
        // Fallback if somehow user is null after Auth::check()
        return redirect('/home'); // Or your default fallback
    }

        return view('auth.login');
    }


    public function login_auth(Request $request)
{
    $credentials = $request->validate([
        'users_email' => 'required|email',
        'users_password' => 'required',
    ]);

    $user = \App\Models\User::where('users_email', $request->users_email)->first();

    if ($user && Hash::check($request->users_password, $user->users_password)) {
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended($user->getRedirectRoute());
    } else {
        return back()->withErrors([
            'users_email' => 'The provided credentials do not match our records.',
        ]);
    }
}


    public function logout(Request $request)
    {
        Auth::logout();  // Log the user out
        $request->session()->invalidate();  // Invalidate the session
        $request->session()->regenerateToken();  // Regenerate CSRF token

        return redirect('/login');  // Redirect to login page
    }
}
