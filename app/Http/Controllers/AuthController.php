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
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Hardcoded admin login
    if (
        $request->email === 'admin@chillemart.com' &&
        $request->password === 'admin123'
    ) {
        session([
            'is_admin' => true,
            'email' => $request->email,
        ]);

        return redirect()->route('admin.dashboard');
    }

    // Login via database
    if (Auth::attempt([
        'users_email' => $request->email,
        'password' => $request->password,
    ])) {
        $request->session()->regenerate();

        return redirect()->route('home');
    }

    // ❗ Login gagal, tampilkan pesan error
    return back()->with('error', 'Incorrect email or password.');
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); // atau ke route lain
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
            'alice@mail.com',
            'admin@chillemart.com',
        ];

        if (in_array($request->email, $allowedEmails)) {
            return back()->with('status', 'Password has been successfully reset.');
        } else {
            // Email tidak ditemukan
            return back()->with('error', 'Email address not found.');
        }
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login'); 
    }

}
