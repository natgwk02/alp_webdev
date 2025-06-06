@extends('base.base')

@section('content')
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password - Chillé Mart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background: linear-gradient(to bottom, #f6fbff, #d9ecfa);
      font-family: 'Segoe UI', sans-serif;
      padding: 20px;
      min-height: 100vh; 
      margin: 0;
    }
    .login-wrapper {
      max-width: 960px;
      margin: auto;
      display: flex;
      background-color: white;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      border-radius: 12px;
      flex-direction: row;
    }
    .left-panel, .right-panel {
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
      top: 0; left: 0;
      width: 100%; height: 100%;
      z-index: 1;
    }
    .carousel-caption-text {
      z-index: 2;
      position: absolute;
      bottom: 20px; left: 20px;
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
    }
    .btn-blue {
      background-color: #052659;
      color: #fff;
      border-radius: 10px;
      padding: 12px;
      font-weight: 600;
      width: 100%;
      border: none;
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
    .forgot-image {
      width: 150px;
      margin-bottom: 20px;
    }
    @media (max-width: 992px) {
      .login-wrapper { flex-direction: column; }
      .left-panel { width: 100%; height: 250px; display: block; }
      .carousel-item img { object-fit: cover; height: 100%; }
      .right-panel { width: 100%; padding: 30px 20px; }
    }
  </style>
</head>
<body>
  <div class="login-wrapper">
    <!-- LEFT: Carousel -->
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
          <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="0" class="active"></button>
          <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="1"></button>
        </div>
      </div>
    </div>

    <!-- RIGHT: Forgot Password Form (tanpa card) -->
<div class="right-panel">
  <div class="w-100" style="max-width: 400px;">
    <div class="text-center mb-4">
      <img src="/assets/forget-imagee.png" alt="Forgot Illustration" class="forgot-image" style="width: 180px; margin-bottom: 20px;">
      <h4 class="fw-bold" style="color: #052659;">Forgot Your Password?</h4>
      <p class="text-muted small mb-4">We'll send a 6-digit OTP to your email address</p>
    </div>

    @if (session('status'))
      <div class="alert alert-success text-sm">{{ session('status') }}</div>
    @endif

    @if (!session('otp_sent'))
    <!-- Send OTP form -->
    <form method="POST" action="{{ route('password.email') }}">
      @csrf
      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fa fa-envelope text-secondary"></i></span>
          <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter your email" required value="{{ old('email') }}">
        </div>
        @error('email')
        <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
      </div>
      <button type="submit" class="btn-blue w-100">Send OTP Code</button>
    </form>
    @else
    <!-- OTP Verification -->
    <form method="POST" action="{{ route('password.otp.step') }}">
      @csrf
      <input type="hidden" name="email" value="{{ session('email') }}">
      <div class="mb-3">
        <label for="otp" class="form-label">Enter OTP Code</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fa fa-key text-secondary"></i></span>
          <input type="text" name="otp" class="form-control @error('otp') is-invalid @enderror" placeholder="6-digit code" required>
        </div>
        @error('otp')
        <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
      </div>
      <button type="submit" class="btn-blue w-100">Verify OTP</button>
    </form>
    @endif

    <div class="text-center mt-4">
      <a href="{{ route('login') }}" class="text-link small">&larr; Back to Login</a>
    </div>
  </div>
</div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
@endsection
