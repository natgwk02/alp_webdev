@extends('base.base')

@section('content')
<style>
    body {
        background: #f7faff;
        font-family: 'Segoe UI', sans-serif;
    }

    .login-container {
        display: flex;
        min-height: 100vh;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    }

    .login-card {
        background: white;
        display: flex;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        overflow: hidden;
        max-width: 900px;
        width: 100%;
    }

    .login-left {
        flex: 1;
        background: url('/images/login-illustration.png') no-repeat center;
        background-size: cover;
        min-height: 400px;
    }

    .login-right {
        flex: 1;
        padding: 60px 40px;
    }

    .login-right h3 {
        font-weight: 700;
        margin-bottom: 10px;
        color: #1e3a8a;
    }

    .login-right p {
        font-size: 0.95rem;
        color: #555;
        margin-bottom: 30px;
    }

    .form-control-custom {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ccc;
        border-radius: 30px;
        margin-bottom: 20px;
        background-color: #f3f6fa;
    }

    .btn-primary-custom {
        background-color: #1e3a8a;
        color: white;
        border: none;
        padding: 12px;
        border-radius: 30px;
        width: 48%;
        font-weight: 600;
        transition: 0.3s ease;
    }

    .btn-secondary-custom {
        border: 2px solid #1e3a8a;
        background-color: white;
        color: #1e3a8a;
        padding: 12px;
        border-radius: 30px;
        width: 48%;
        font-weight: 600;
    }

    .btn-primary-custom:hover {
        background-color: #183072;
    }

    .btn-secondary-custom:hover {
        background-color: #1e3a8a;
        color: white;
    }

    @media (max-width: 768px) {
        .login-card {
            flex-direction: column;
        }

        .login-left {
            height: 250px;
        }

        .btn-primary-custom,
        .btn-secondary-custom {
            width: 100%;
            margin-bottom: 10px;
        }
    }
</style>

<div class="login-container">
    <div class="login-card">

        <!-- Left illustration -->
        <div class="login-left d-none d-md-block"></div>

        <!-- Right form -->
        <div class="login-right">
            <h3>Welcome!</h3>
            <p>Sign in to your Account</p>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('login.auth') }}">
                @csrf

                <input type="text" name="email" class="form-control-custom" placeholder="Email Address" required>
                <input type="password" name="password" class="form-control-custom" placeholder="Password" required>

                <div class="text-end mb-4">
                    <a href="" class="small text-muted">Forgot Password?</a>
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
                            <a href="{{ route('forgot-password') }}" class="text-decoration-none small">Forgot password?</a>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>

                        <div class="text-center mt-3">
                            <span class="text-muted">Don't have an account?</span>
                            <a href="{{ route('register') }}" class="text-primary text-decoration-none">Register</a>
                        </div>

                    </form>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary-custom">SIGN IN</button>
                    <a href="{{ route('register') }}" class="btn btn-secondary-custom">SIGN UP</a>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
