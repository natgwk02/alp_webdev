@extends('base.base')

@section('content')

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reset Password - Chillé Mart</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            body {
                background: linear-gradient(to bottom, #f6fbff, #d9ecfa);
                font-family: 'Segoe UI', sans-serif;
                margin: 0;
                padding: 0;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .login-wrapper {
                width: 960px;
                height: 750px;
                display: flex;
                background-color: white;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                border-radius: 12px;
                flex-direction: row;
            }

            .left-panel,
            .right-panel {
                width: 50%;
                height: 100%;
            }

            .carousel-item img {
                height: 100%;
                width: 100%;
                object-fit: cover;
            }

            .carousel-caption-overlay {
                background-color: rgba(0, 0, 0, 0.3);
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 1;
            }

            .carousel-caption-text {
                z-index: 2;
                position: absolute;
                bottom: 20px;
                left: 20px;
                color: white;
            }

            .right-panel {
                padding: 50px 40px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }

            .form-control {
                border-radius: 10px;
                padding: 12px 15px;
                background-color: #f8fbff;
                border: 1px solid #ccddee;
                transition: border-color 0.2s ease, box-shadow 0.2s ease;
            }

            .form-control:focus {
                border-color: #84b8ff;
                box-shadow: 0 0 0 3px rgba(118, 174, 241, 0.2);
            }

            .btn-blue {
                background-color: #052659;
                color: #fff;
                border-radius: 10px;
                padding: 12px;
                font-weight: 600;
                width: 100%;
                border: none;
                transition: background-color 0.3s ease;
            }

            .btn-blue:hover {
                background-color: #084c8b;
            }

            .text-link {
                color: #052659;
                text-decoration: underline;
            }

            .text-link:hover {
                text-decoration: none;
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
            }

            .toggle-password:hover {
                color: #052659;
            }

            .password-wrapper {
                position: relative;
            }

            .password-wrapper .form-control {
                padding-right: 45px;
            }

            @media (max-width: 992px) {
                body {
                    display: block;
                }

                .login-wrapper {
                    flex-direction: column;
                    width: 100%;
                    height: auto;
                    border-radius: 0;
                    box-shadow: none;
                }

                .left-panel {
                    width: 100%;
                    height: 250px;
                }

                .right-panel {
                    width: 100%;
                    padding: 30px 20px;
                }
            }
        </style>
    </head>

    <body>
        <div class="login-wrapper">
            <div class="left-panel">
                <div id="carouselExample" class="carousel slide h-100" data-bs-ride="carousel" data-bs-interval="4000">
                    <div class="carousel-inner h-100">
                        <div class="carousel-item active position-relative h-100">
                            <img src="{{ asset('images/chille5.png') }}" alt="Slide 1">
                            <div class="carousel-caption-overlay"></div>
                            <div class="carousel-caption-text">
                                <h5 class="fw-bold">Delivered fresh, right to your freezer</h5>
                                <p class="mb-0 small">Schedule your visit in just a few clicks</p>
                            </div>
                        </div>
                        <div class="carousel-item position-relative h-100">
                            <img src="{{ asset('images/chille6.jpg') }}" alt="Slide 2">
                            <div class="carousel-caption-overlay"></div>
                            <div class="carousel-caption-text">
                                <h5 class="fw-bold">Shop. Chill. Repeat</h5>
                                <p class="mb-0 small">Your daily chill starts here</p>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-indicators position-absolute bottom-0 start-0 ms-4 mb-4 z-2">
                        <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="0"
                            class="active"></button>
                        <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="1"></button>
                    </div>
                </div>
            </div>

            <div class="right-panel">
                <div class="w-100 d-flex flex-column align-items-center justify-content-center" style="max-width: 400px;">
                    <img src="{{ asset('images/securitylock.png') }}" alt="Reset Password"
                        style="width: 80px; margin-bottom: 20px;">
                    <h3 class="fw-bold text-center mb-2" style="color: #052659;">Reset Your Password</h3>
                    <p class="text-muted text-center mb-4" style="font-size: 0.95rem;">
                        Set a new secure password for your account below.
                    </p>

                    @if (session('error'))
                        <div class="alert alert-danger w-100">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('password.update.step') }}" class="w-100">
                        @csrf
                        <input type="hidden" name="email" value="{{ session('email_verified') }}">

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <div class="password-wrapper">
                                <input type="password" id="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" placeholder="New password"
                                    required>
                                <i class="fa fa-eye toggle-password" id="togglePasswordIcon"
                                    onclick="togglePassword('password', this)"></i>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <div class="password-wrapper">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control" placeholder="Confirm password" required>
                                <i class="fa fa-eye toggle-password"
                                    onclick="togglePassword('password_confirmation', this)"></i>
                            </div>
                        </div>

                        <button type="submit" class="btn-blue w-100 mt-2">Reset Password</button>
                    </form>

                    <div class="mt-3">
                        <a href="{{ route('login') }}" class="text-link">&larr; Back to Login</a>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function togglePassword(fieldId, el) {
                const input = document.getElementById(fieldId);
                const icon = el.querySelector('i') || el;
                if (input.type === "password") {
                    input.type = "text";
                    icon.classList.replace("fa-eye", "fa-eye-slash");
                } else {
                    input.type = "password";
                    icon.classList.replace("fa-eye-slash", "fa-eye");
                }
            }

            function handlePasswordInput() {
                const field = document.getElementById("password");
                const icon = document.getElementById("togglePasswordIcon");

                if (field.classList.contains("is-invalid")) {
                    icon.style.display = "none";
                } else {
                    icon.style.display = field.value.length > 0 ? "block" : "none";
                }
            }

            document.addEventListener("DOMContentLoaded", function() {
                const passwordField = document.getElementById("password");
                passwordField.addEventListener("input", handlePasswordInput);
                handlePasswordInput(); 
            });
        </script>
    </body>
@endsection
