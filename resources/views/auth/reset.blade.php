@extends('base.base')
@section('title', 'Reset Password')

@section('content')
<style>
    body {
        background: url('/images/background.jpg') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Segoe UI', sans-serif;
        margin: 0;
    }

    .forgot-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    .forgot-card {
        background-color: rgba(240, 240, 240, 0.95);
        padding: 40px;
        border-radius: 18px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 420px;
        text-align: center;
    }

    .forgot-card h2 {
        font-size: 1.5rem;
        font-weight: bold;
        color: #224488;
        margin-bottom: 20px;
    }

    .form-control {
        border-radius: 10px;
        padding: 12px 15px;
        font-size: 0.95rem;
        margin-bottom: 20px;
        border: 1px solid #ccddee;
        background-color: #f8fbff;
    }

    .btn-blue {
        background-color: #224488;
        color: white;
        padding: 12px;
        width: 100%;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-blue:hover {
        background-color: #C1E8FF;
        color: #224488;
    }

    .back-link {
        display: block;
        margin-top: 20px;
        color: #224488;
        font-size: 0.9rem;
        text-decoration: none;
    }

    .back-link:hover {
        text-decoration: underline;
    }

    .password-wrapper {
        position: relative;
    }

    .toggle-password {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1rem;
        color: #888;
        cursor: pointer;
        z-index: 2;
        height: 20px;
        width: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
    }

    .password-wrapper .form-control {
        padding-right: 45px;
    }
</style>

<div class="forgot-wrapper">
    <div class="forgot-card">
        <h2>Reset Your Password</h2>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('password.update.step') }}">
            @csrf
            <input type="hidden" name="email" value="{{ session('email_verified') }}">

            <div class="mb-3 text-start">
                <label for="password" class="form-label">New Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="New password" required>
                    <i class="fa fa-eye toggle-password" onclick="togglePassword('password', this)"></i>
                </div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 text-start">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        class="form-control" placeholder="Confirm password" required>
                    <i class="fa fa-eye toggle-password" onclick="togglePassword('password_confirmation', this)"></i>
                </div>
            </div>

            <button type="submit" class="btn-blue">Reset Password</button>
        </form>

        <a href="{{ route('login') }}" class="back-link">← Back to Login</a>
    </div>
</div>

<script>
    function togglePassword(fieldId, el) {
        const input = document.getElementById(fieldId);
        if (input.type === "password") {
            input.type = "text";
            el.classList.replace("fa-eye", "fa-eye-slash");
        } else {
            input.type = "password";
            el.classList.replace("fa-eye-slash", "fa-eye");
        }
    }
</script>
@endsection
