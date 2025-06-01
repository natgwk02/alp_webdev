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
            return redirect(Auth::user()->getRedirectRoute());
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

    public function showRegister()
        {
            return view('auth.register');
        }

    public function register(Request $request)
    {
        $formattedName = ucwords(strtolower(trim($request->users_name)));

        $validated = $request->validate([
            'users_name' => 'required|string|max:255',
            'users_email' => [
                        'required',
                        'email',
                        'unique:users,users_email',
                        'regex:/@mail\.com$/i' 
                    ],            
            'users_password' => 'required|string|min:8|confirmed', 
            'users_address' => 'required|string|max:255', // Pastikan address tervalidasi
        ]);

        // Format alamat
        $formattedAddress = $this->formatAddress($validated['users_address']);

        User::create([
            'users_name' => $formattedName,
            'users_email' => $validated['users_email'],
            'users_password' => bcrypt($validated['users_password']),
            'users_address' => $formattedAddress,
            'users_phone' => $request->input('users_phone'), // Optional
            'status_del' => false,
        ]);

        return redirect()->route('login')->with('success', 'Registration successful!');
    }



    public function logout(Request $request)
    {
        Auth::logout();  // Log the user out
        $request->session()->invalidate();  // Invalidate the session
        $request->session()->regenerateToken();  // Regenerate CSRF token

        return redirect('/login');  // Redirect to login page
    }

    private function formatAddress($rawAddress)
    {
        $lowered = strtolower(trim($rawAddress));

        // Ganti "jalan", "jl" di awal jadi "Jl."
        $lowered = preg_replace('/^(jalan|jl)\.?\s+/i', 'Jl. ', $lowered);

        // Capitalize tiap kata
        $formatted = ucwords($lowered);

        return $formatted;
    }

}
