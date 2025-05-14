<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    //
    public function show()
    {
        return view('auth.login');
    }

    public function login_auth(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/home');
        }

        return back()->with([
            'error' => 'The provided credentials do not match our records.'
        ]);
    }
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.show');
    }
    public function showForgotPassword()
    {
        return view('auth.forgot_password');
    }

    public function registerForm()
    {
        return view('auth.register'); // arahkan ke Blade view
    }

    // Memproses data register
    public function register(Request $request)
    {
        $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'unique:users,users_email'],
        'password' => ['required', 'min:6', 'confirmed'],
        'phone' => ['required', 'regex:/^[0-9]{10,12}$/'],
        'address' => ['required'],
    ]);


    $user = \App\Models\User::create([
        'users_name' => $validated['name'],
        'users_email' => $validated['email'],
        'users_password' => Hash::make($validated['password']),
        'users_phone' => $validated['phone'],
        'users_address' => $validated['address'],
        'status_del' => false,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

        // Login otomatis setelah register (opsional)
        Auth::login($user);

    return redirect()->route('login.show')->with('success', 'Registration successful! Please login.');
}


    // Dummy Reset Password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // Simulasi list email "terdaftar"
        $allowedEmails = [
            'user@example.com',
            'admin@chilemart.com',
            'test@domain.com'
        ];

        if (in_array($request->email, $allowedEmails)) {
            return back()->with('status', 'Password has been successfully reset.');
        } else {
            // Email tidak ditemukan
            return back()->with('error', 'Email address not found.');
        }
    }
}
