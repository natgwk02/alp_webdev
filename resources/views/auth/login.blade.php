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
        /* padding-top: 20px;
        padding-bottom: 60px; */
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

    .form-check-label,
    .text-muted,
    .text-link {
        font-size: 0.9rem;
    }

    .text-link {
        color: #224488;
        text-decoration: none;
    }

    .text-link:hover {
        text-decoration: underline;
    }
</style>

<div class="login-wrapper mb-5 mt-2">
    <div class="login-card">

        <h4>Welcome Back!</h4>
        <p>Sign in to your Account</p>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login.auth') }}">
            @csrf

            <input type="text" name="email" class="form-control" placeholder="Email Address" required>

            <div class="mb-3 position-relative">
                <input 
                    type="password" 
                    class="form-control" 
                    id="password" 
                    name="password" 
                    placeholder="Password"
                >

            <button type="button" 
                class="btn position-absolute top-50 end-0 translate-middle-y me-2 p-1 bg-transparent border-0" 
                onclick="togglePassword()" 
                style="z-index: 2;"
            >
                <i id="toggleIcon" class="fa fa-eye"></i>
            </button>
        </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember Me</label>
                </div>
                <a href="forgot-password" class="text-link">Forgot Password?</a>
            </div>

            <button type="submit" class="btn btn-blue">Sign In</button>
        </form>

        <div class="text-center mt-3">
            <span class="text-muted">Don't have an account? <a href="{{ route('register') }}" class="text-link">Sign Up</a></span>
        </div>

    </div>
</div>
@endsection

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const icon = document.getElementById('toggleIcon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
