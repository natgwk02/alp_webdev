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
            'email' => 'required|email:dns',
            'password' => 'required',
        ]);

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();

            return redirect()->intended('/home');
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
}