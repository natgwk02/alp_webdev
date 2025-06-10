<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {

            /** @var \App\Models\User **/
            $user = Auth::user();

            if ($user) {
                return redirect($user->getRedirectRoute());
            }

            return redirect('/home');
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
                'users_email' => 'Oops! Your email or password does not match our records.',
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
                'regex:/@(mail\.com|gmail\.com)$/i'
            ],
            'users_password' => 'required|string|min:8|confirmed',
            'users_address' => 'required|string|max:255',
        ]);

        $formattedAddress = $this->formatAddress($validated['users_address']);

        User::create([
            'users_name' => $formattedName,
            'users_email' => $validated['users_email'],
            'users_password' => bcrypt($validated['users_password']),
            'users_address' => $formattedAddress,
            'users_phone' => $request->input('users_phone'),
            'status_del' => false,
        ]);

        return redirect()->route('login')->with('success', 'Registration successful!');
    }



    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    private function formatAddress($rawAddress)
    {
        $lowered = strtolower(trim($rawAddress));

        // Ganti "jalan", "jl" di awal jadi "Jl."
        $lowered = preg_replace('/^(jalan|jl)\.?\s+/i', 'Jl. ', $lowered);

        $formatted = ucwords($lowered);

        return $formatted;
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot_password');
    }


    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,users_email',
        ]);

        $otp = rand(100000, 999999); // Generate 6 digit OTP

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $otp,
                'created_at' => Carbon::now()
            ]
        );

        Mail::raw("Your Chille Mart password reset OTP is: $otp\nThis code will expire in 10 minutes.", function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Your OTP Code for Password Reset');
        });
        return back()
            ->with('status', 'A 6-digit OTP has been sent to your email. Please check your inbox.')
            ->with('otp_sent', true)
            ->with('email', $request->email);
    }

    public function showResetForm($token)
    {
        $reset = DB::table('password_reset_tokens')->where('token', $token)->first();

        if (!$reset) {
            return redirect()->route('password.request')->with('error', 'Token tidak ditemukan atau sudah kadaluarsa.');
        }

        return view('auth.reset', ['token' => $token, 'email' => $reset->email]);
    }

    // Simpan password baru
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        if (!session('otp_verified') || !session('email_verified')) {
            return redirect()->route('password.request')->with('error', 'Unauthorized access.');
        }

        $email = session('email_verified');

        $user = User::where('users_email', $email)->first();
        if (!$user) {
            return redirect()->route('password.request')->with('error', 'User not found.');
        }

        $user->users_password = bcrypt($request->password);
        $user->save();
        DB::table('password_reset_tokens')->where('email', $email)->delete();
        session()->forget(['otp_verified', 'email_verified']);

        return redirect()->route('login')->with('success', 'Your password has been successfully reset.');
    }
    public function verifyOtpStep(Request $request)
    {
        // Gabung array otp menjadi string
        $otpCode = implode('', $request->input('otp'));

        $request->validate([
            'email' => 'required|email|exists:users,users_email',
            'otp' => ['required', 'array', 'size:6'],
            'otp.*' => ['required', 'digits:1'], // Setiap input wajib angka 1 digit
        ]);

        // Cek OTP di database
        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $otpCode)
            ->first();

        if (!$record) {
            return back()->withErrors(['otp' => 'The OTP code is invalid or has expired.']);
        }

        // OTP valid, simpan session
        session([
            'otp_verified' => true,
            'email_verified' => $request->email,
        ]);

        return redirect()->route('password.reset.form');
    }

    public function viewAdminDashboard()
    {
        if (!Gate::allows('viewAdminDashboard')) {
            abort(403, 'Unauthorized action.');
        }
    }
}
