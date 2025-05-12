@extends('base.base')

@section('content')
<style>
    body {
        background: url('/images/background.jpg') no-repeat center center fixed;
        background-size: cover;
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', sans-serif;
    }

    .login-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding-top: 100px;
        padding-bottom: 60px;
    }

    .login-card {
        background-color: rgba(240, 240, 240, 0.8);
        padding: 40px;
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        width: 100%;
        max-width: 420px;
    }

    .login-card h4 {
        text-align: center;
        margin-bottom: 10px;
        color: #224488;
        font-weight: bold;
    }

    .login-card p {
        text-align: center;
        color: #555;
        margin-bottom: 25px;
        font-size: 0.95rem;
    }

    .form-control {
        border-radius: 10px;
        background-color: #f8fbff;
        padding: 12px 15px;
        border: 1px solid #ccddee;
        margin-bottom: 15px;
    }

    .btn-blue {
        background-color: #224488;
        color: white;
        border: none;
        padding: 12px;
        border-radius: 10px;
        width: 100%;
        font-weight: 600;
    }

    .btn-blue:hover {
        background-color: #C1E8FF;
    }

    .text-link {
        color: #224488;
        text-decoration: none;
    }

    .text-link:hover {
        text-decoration: underline;
    }
</style>

<div class="login-wrapper">
    <div class="login-card">

        <h4>Reset Password</h4>
        <p>Enter your email and new password</p>

        @if (session('status'))
            <div class="alert alert-success text-center">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="email" id="email" name="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}" placeholder="Email Address" required>
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror

            <input type="password" id="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="New Password" required>
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror

            <input type="password" id="password_confirmation" name="password_confirmation"
                   class="form-control" placeholder="Confirm New Password" required>

            <button type="submit" class="btn btn-blue mt-2">Reset Password</button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('login.show') }}" class="text-link">← Back to Login</a>
        </div>
    </div>
</div>
@endsection
