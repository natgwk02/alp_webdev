@extends('base.base')
@section('title', 'Forgot Password')

@section('content')
<style>
    body {
        background: url('/images/background.jpg') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Segoe UI', sans-serif;
        margin: 0;
    }

    .auth-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 20px;
    }

    .auth-card {
        background-color: rgba(255, 255, 255, 0.95);
        padding: 40px 30px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 420px;
        text-align: center;
    }

    .auth-card img {
        width: 100px;
        margin-bottom: 20px;
    }

    .auth-card h4 {
        font-weight: bold;
        color: #052659;
        margin-bottom: 10px;
    }

    .form-control {
        border-radius: 12px;
        padding: 12px 15px;
        font-size: 0.95rem;
        margin-bottom: 20px;
    }

    .btn-blue {
        background-color: #052659;
        color: white;
        padding: 12px;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        width: 100%;
        transition: 0.3s;
    }

    .btn-blue:hover {
        background-color: #c1e8ff;
        color: #052659;
    }

    .text-link {
        font-size: 0.9rem;
        color: #052659;
        text-decoration: none;
    }

    .text-link:hover {
        text-decoration: underline;
    }

    .truncate-link {
        word-break: break-word;
        font-size: 0.9rem;
    }
</style>

<div class="auth-wrapper">
    <div class="auth-card">
        <img src="/assets/forget-imagee.png" alt="Forgot Password Illustration">

        <h4>Forgot Password</h4>
        <p class="mb-4 text-muted">Enter your email to receive a 6-digit OTP code.</p>

        @if (session('status'))
            <div class="alert alert-success text-start">
                {{ session('status') }}
            </div>
        @endif

        @if (!session('otp_sent'))
            {{-- Step 1: Request OTP --}}
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <input type="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="Email Address" value="{{ old('email') }}" required>

                @error('email')
                    <div class="invalid-feedback text-start">{{ $message }}</div>
                @enderror

                <button type="submit" class="btn-blue">Send OTP Code</button>
            </form>
        @else
            {{-- Step 2: Enter OTP Only --}}
            <form method="POST" action="{{ route('password.otp.step') }}">
                @csrf
                <input type="hidden" name="email" value="{{ session('email') }}">

                <input type="text" name="otp"
                       class="form-control @error('otp') is-invalid @enderror"
                       placeholder="Enter OTP Code" required>

                @error('otp')
                    <div class="invalid-feedback text-start">{{ $message }}</div>
                @enderror

                <button type="submit" class="btn-blue">Verify OTP</button>
            </form>
        @endif

        <div class="mt-3">
            <a href="{{ route('login') }}" class="text-link">← Back to Login</a>
        </div>
    </div>
</div>
@endsection
