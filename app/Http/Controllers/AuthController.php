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
    // --- Langkah 1: Validasi input hanya dilakukan SATU KALI di awal ---
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // ✨ Langkah 2: Cek khusus admin
        // Catatan: Cara yang lebih baik untuk admin adalah membuat akun admin di database
        // dan mengautentikasinya melalui sistem Auth Laravel standar,
        // daripada hardcode email dan password di controller.
        // Ini akan lebih aman dan konsisten.
        if (
            $validatedData['email'] === 'admin@chillemart.com' &&
            $validatedData['password'] === 'admin123'
        ) {
            // Jika Anda ingin admin memiliki sesi Laravel yang sama,
            // Anda bisa mencari user admin dari database dan login mereka.
            // Contoh:
            // $adminUser = User::where('users_email', 'admin@chillemart.com')->first();
            // if ($adminUser) {
            //     Auth::login($adminUser);
            //     $request->session()->regenerate();
            //     return redirect()->route('admin.dashboard');
            // }

            // Untuk saat ini, as per code Anda, tetap mengarahkan tanpa sesi Auth Laravel
            return redirect()->route('admin.dashboard')->with([
                'admin_email' => $validatedData['email']
            ]);
        }

        // ✋ Langkah 3: Jika email adalah admin@chillemart.com tapi password salah
        // (Ini hanya akan terpanggil jika password admin123 salah)
        if ($validatedData['email'] === 'admin@chillemart.com') {
            return back()->with('error', 'Incorrect email or password.');
        }

        // 👤 Langkah 4: Otentikasi User biasa
        // Gunakan kredensial yang divalidasi.
        // `Auth::attempt()` akan secara otomatis menggunakan `getAuthPassword()`
        // dari model User Anda yang mengacu ke `users_password`.
        if (Auth::attempt([
            'users_email' => $validatedData['email'], // Memastikan Anda menggunakan kolom DB yang benar
            'password' => $validatedData['password'], // Laravel akan menggunakan getAuthPassword()
        ])) {
            $request->session()->regenerate();
            return redirect()->route('home'); // Ganti 'home' dengan rute yang sesuai (misal 'profile')
        }

        // Langkah 5: Jika otentikasi gagal untuk user biasa
        return back()->with('error', 'Incorrect email or password.');
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
