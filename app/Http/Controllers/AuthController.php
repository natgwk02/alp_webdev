<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
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

        if (
            $request->email === 'admin@chillemart.com' &&
            $request->password === session('admin_password', 'admin123') // default admin123
            ) {
            session([
                'is_admin' => true,
                'email' => $request->email,
                'admin_password' => $request->password, // nyimpen password yg dipakai
            ]);

    return redirect()->route('admin.dashboard');
        }

        if (Auth::attempt([
            'users_email' => $request->email,
            'password' => $request->password,
        ])) {
            $request->session()->regenerate();

            return redirect()->route('home');
        }

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

}
