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
            return redirect()->intended('/home');
        }
        return view('auth.login');
    }

    public function login_auth(Request $request)
    {
        // Validate the input fields
        $credentials = $request->validate([
            'users_email' => 'required|email',
            'users_password' => 'required',
        ]);

        // Find the user by email
        $user = User::where('users_email', $request->users_email)->first();

        // Check if the user exists and the password is correct
        if ($user && Hash::check($request->users_password, $user->users_password)) {
            // Attempt to log the user in
            Auth::login($user);

            // Regenerate the session
            $request->session()->regenerate();
            // dd(Auth::user());
            // Redirect to intended page or home
            return redirect()->intended('/home');
        } else {
            // If credentials are incorrect, return error
            return back()->withErrors(['users_email' => 'The provided credentials do not match our records.']); // Improve error message
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
