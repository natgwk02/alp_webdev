<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

public function login_auth(Request $request)
{
    $validatedData = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Coba login menggunakan data dari database
    if (Auth::attempt([
        'users_email' => $validatedData['email'],
        'password' => $validatedData['password'],
    ])) {
        $request->session()->regenerate();

        dd(Auth::user()); // ⬅️ Tambahkan ini dulu untuk cek apakah user berhasil login

        if (Auth::user()->users_email === 'admin@chillemart.com') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home');
    }

    return back()->with('error', 'Email atau password salah.');
}


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/logout');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot_password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('users_email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Email not found.');
        }

        session(['reset_email' => $user->users_email]);

        return redirect()->route('password.reset.form')->with('status', 'A reset link has been sent to your email.');
    }

    public function showResetForm()
    {
        if (!session()->has('reset_email')) {
            return redirect()->route('password.request')->with('error', 'Please enter your email first.');
        }

        return view('auth.reset');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);

        $email = session('reset_email');

        if (!$email) {
            return redirect()->route('password.request')->with('error', 'Session expired. Please try again.');
        }

        $user = User::where('users_email', $email)->first();

        if (!$user) {
            return redirect()->route('password.request')->with('error', 'Email not found.');
        }

        $user->users_password = Hash::make($request->password);
        $user->save();

        session()->forget('reset_email');

        return redirect()->route('login')->with('status', 'Password has been reset. Please login.');
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,users_email'],
            'password' => ['required', 'min:6', 'confirmed'],
            'phone' => ['required', 'regex:/^[0-9]{10,12}$/'],
            'address' => ['required'],
        ]);

        $user = User::create([
            'users_name' => $validated['name'],
            'users_email' => $validated['email'],
            'users_password' => Hash::make($validated['password']),
            'users_phone' => $validated['phone'],
            'users_address' => $validated['address'],
            'status_del' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Auth::login($user);

        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
