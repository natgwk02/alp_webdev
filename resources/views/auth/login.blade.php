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
    }

    .login-card {
        background-color: rgba(240, 240, 240, 0.85);
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
        color: #224488;
    }

    .text-link {
        color: #224488;
        text-decoration: none;
        font-size: 0.9rem;
    }

    .text-link:hover {
        text-decoration: underline;
    }
</style>

<div class="login-wrapper">
    <div class="login-card">

        <h4>Welcome Back!</h4>
        <p>Sign in to your Account</p>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login.auth') }}">
            @csrf

            <input type="text" name="email" class="form-control" placeholder="Email Address" required>

            <div class="position-relative">
                <input 
                    type="password" 
                    class="form-control" 
                    id="password" 
                    name="password" 
                    placeholder="Password"
                    required
                >
                <button type="button" 
                    class="btn position-absolute top-50 end-0 translate-middle-y me-2 p-1 bg-transparent border-0" 
                    onclick="togglePassword()" 
                    style="z-index: 2;"
                >
                    <i id="toggleIcon" class="fa fa-eye"></i>
                </button>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3 mt-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember Me</label>
                </div>
                <a href="{{ route('forgot-password') }}" class="text-link">Forgot Password?</a>
            </div>

            <button type="submit" class="btn btn-blue">Sign In</button>
        </form>

        <div class="text-center mt-3">
            <span class="text-muted">Don't have an account? 
                <a href="{{ route('register') }}" class="text-link">Sign Up</a>
            </span>
        </div>

    </div>
</div>

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
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow rounded-4 border-0">
                <div class="card-header bg-gradient bg-primary text-white text-center rounded-top-4">
                    <h4 class="mb-0">Welcome Back to <strong>Chillé Mart</strong></h4>
                    <small>Please login to continue</small>
                </div>
                <div class="card-body p-4">

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('login.auth') }}" method="POST" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   class="form-control @error('email') is-invalid @enderror"
                                   required
                                   autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>
                            <a href="" class="text-decoration-none small">Forgot password?</a>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>

                        <div class="text-center mt-3">
                            <span class="text-muted">Don't have an account?</span>
                            <a href="{{ route('register') }}" class="text-primary text-decoration-none">Register</a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
