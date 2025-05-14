<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function show()
    {
        return view('auth.login');
    }

    // Proses login
    public function login_auth(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Jika kamu menggunakan kolom users_email, sesuaikan:
        if (Auth::attempt([
            'users_email' => $request->email,
            'password' => $request->password,
        ])) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.show');
    }

    // Menampilkan halaman lupa password
    public function showForgotPassword()
    {
        return view('auth.forgot_password');
    }

    // Menampilkan halaman register
    public function registerForm()
    {
        return view('auth.register');
    }

    // Proses pendaftaran
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        return redirect('/dashboard');
    }

    // Dummy Reset Password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $allowedEmails = [
            'user@example.com',
            'admin@chilemart.com',
            'test@domain.com'
        ];

        if (in_array($request->email, $allowedEmails)) {
            return back()->with('status', 'Password has been successfully reset.');
        } else {
            return back()->with('error', 'Email address not found.');
        }
    }

    // (Opsional) Validasi Email
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $allowedEmails = [
            'user@example.com',
            'admin@chilemart.com',
            'test@domain.com'
        ];

        if (in_array($request->email, $allowedEmails)) {
            return back()->with('status', 'Email verified.');
        } else {
            return back()->with('error', 'Email address not found.');
        }
    }
}
