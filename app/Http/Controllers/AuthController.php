<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    //
    public function show(){
        return view('auth.login');
    }

    public function login_auth(Request $request){
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

         if (Auth::attempt([
            'users_email' => $request['email'],
            'password' => $request['password'],
        ])) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard'); // ubah sesuai halaman tujuanmu
        }

        return back()->with([
            'error' => 'The provided credentials do not match our records.'
        ]);
    }
        public function logout(Request $request){
            Auth::logout();
        
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        
            return redirect()->route('login.show');
    }
    public function showForgotPassword(){
        return view('auth.forgot_password');
    }

    public function registerForm()
    {
        return view('auth.register'); // arahkan ke Blade view
    }

    // Memproses data register
    public function register(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed'], // butuh input name="password_confirmation"
        ]);

        // Simpan user baru
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Login otomatis setelah register (opsional)
        Auth::login($user);

        return redirect('/dashboard'); // arahkan ke halaman utama
    }
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

    public function login(Request $request)
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