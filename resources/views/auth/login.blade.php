@extends('base.base')

@section('content')

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login - Chillé Mart</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <style>
            body {
                background: linear-gradient(to bottom, #f6fbff, #d9ecfa);
                font-family: 'Segoe UI', sans-serif;
                margin: 0;
                padding: 20px;
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
            }

            .form-control {
                border-radius: 10px;
                padding: 12px 15px;
            }

            .input-group-text {
                background-color: #edf4ff;
                border: none;
                border-radius: 0 10px 10px 0;
            }

            .form-control:focus {
                box-shadow: none;
            }

            .btn-login {
                background-color: #052659;
                color: #fff;
                border-radius: 10px;
                padding: 12px;
                font-weight: 600;
                width: 100%;
                border: none;
            }

            .btn-login:hover {
                background-color: #084c8b;
            }

            .text-link {
                color: #052659;
                text-decoration: underline;
            }

            .text-link:hover {
                text-decoration: none;
            }

            @media (max-width: 992px) {
                .login-wrapper {
                    flex-direction: column;
                }

                .left-panel {
                    width: 100%;
                    height: 250px;
                    display: block;
                }

                .carousel-item img {
                    object-fit: cover;
                    height: 100%;
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
                <div class="w-100" style="max-width: 360px; margin: auto;">
                    <h4 class="fw-bold mb-2" style="color: #052659;">Welcome Back to Chillé Mart!</h4>
                    <p class="text-muted mb-3">Sign in to explore fresh picks just for you</p>

                    @if ($errors->has('users_email'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ $errors->first('users_email') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('login.auth') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Your Email</label>
                            <input type="email" name="users_email" class="form-control" value="{{ old('users_email') }}"
                                required placeholder="Enter your email">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input type="password" id="password" name="users_password" class="form-control" required
                                    placeholder="Enter your password">
                                <span class="input-group-text" onclick="togglePassword('password', this)"
                                    style="cursor:pointer;">
                                    <i class="fa fa-eye text-secondary"></i>
                                </span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember">
                                <label for="remember" class="form-check-label">Remember Me</label>
                            </div>
                            <a href="{{ route('password.request') }}" class="text-link">Forgot Password?</a>
                        </div>

                        <button type="submit" class="btn-login mb-3">Sign In</button>
                    </form>

                    <div class="text-center mt-3">
                        <span class="text-muted">Don't have an account? <a href="{{ route('register') }}"
                                class="text-link">Register</a></span>
                    </div>

                    <div class="text-center mt-2">
                        <span class="text-muted">or </span><a href="{{ route('guest.login') }}" class="text-link">Sign in as
                            Guest</a>
                    </div>


                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function togglePassword(fieldId, el) {
                const input = document.getElementById(fieldId);
                const icon = el.querySelector('i');
                if (input.type === "password") {
                    input.type = "text";
                    icon.classList.replace("fa-eye", "fa-eye-slash");
                } else {
                    input.type = "password";
                    icon.classList.replace("fa-eye-slash", "fa-eye");
                }
            }
        </script>
    </body>
@endsection
