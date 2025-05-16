{{-- @extends('base.base')

@section('content') --}}

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chile Mart - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
    <!-- Bootstrap CSS -->

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

</head>

@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false
        });
    </script>
@endif

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
        color: #052659;
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
        background-color: #052659;
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
        color: #052659;
        text-decoration: none;
    }

    .text-link:hover {
        text-decoration: underline;
    }
</style>


<body>

    <div class="login-wrapper">
        <div class="login-card">

            <h4>Welcome Back!</h4>
            <p>Sign in to your Account</p>

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif


            <form method="POST" action="{{ route('login.auth') }}">
                @csrf

                <input type="text" name="email" class="form-control" placeholder="Email Address" required>

                <div class="position-relative">
                    <input type="password" name="password" id="password" class="form-control pe-5"
                        placeholder="Password" required>

                    <span onclick="togglePassword('password', this)"
                        class="position-absolute end-0 top-50 translate-middle-y me-3"
                        style="cursor: pointer; z-index: 2;">
                        <i class="fa fa-eye" id="toggleIcon"></i>
                    </span>
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
                <span class="text-muted">Don't have an account? <a href="{{ route('register') }}" class="text-link">Sign
                        Up</a></span>
            </div>

        </div>
    </div>
</body>
{{-- @endsection --}}


<script>
    function togglePassword(fieldId, el) {
        const input = document.getElementById(fieldId);
        const icon = el.querySelector('i');

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
</script>
