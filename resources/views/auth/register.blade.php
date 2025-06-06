@extends('base.base')

@section('content')
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Chillé Mart</title>
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

    .register-wrapper {
      width: 960px;
      height: 750px;
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
      background-color: #eef4ff;
      border: 1px solid #ccddee;
    }

    .form-control:focus {
      box-shadow: none;
    }

    .btn-register {
      background-color: #052659;
      color: #fff;
      border-radius: 10px;
      padding: 12px;
      font-weight: 600;
      width: 100%;
      border: none;
    }

    .btn-register:hover {
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
      cursor: pointer;
      color: #888;
      z-index: 2;
    }

    @media (max-width: 992px) {
      .register-wrapper {
        flex-direction: column;
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
  <div class="register-wrapper">
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

    <!-- RIGHT: Register Form -->
    <div class="right-panel">
      <div class="w-100" style="max-width: 360px; margin: auto;">
        <h4 class="fw-bold mb-2" style="color: #052659;">Create an Account</h4>
        <p class="text-muted mb-3">Start shopping your frozen favorites with Chillé Mart!</p>

        <form action="{{ route('register.submit') }}" method="POST" onsubmit="return validatePasswordMatch();">
          @csrf

          <input type="text" name="users_name" class="form-control mb-3 @error('users_name') is-invalid @enderror" placeholder="Full Name" required value="{{ old('users_name') }}">
          @error('users_name')<div class="text-danger small">{{ $message }}</div>@enderror

          <input type="email" name="users_email" class="form-control mb-3 @error('users_email') is-invalid @enderror" placeholder="Email" required value="{{ old('users_email') }}">
          @error('users_email')<div class="text-danger small">{{ $message }}</div>@enderror

          <input type="text" name="users_phone" class="form-control mb-3 @error('users_phone') is-invalid @enderror" placeholder="Phone Number" pattern="[0-9]+" maxlength="15" required value="{{ old('users_phone') }}">
          @error('users_phone')<div class="text-danger small">{{ $message }}</div>@enderror

          <input type="text" name="users_address" class="form-control mb-3 @error('users_address') is-invalid @enderror" placeholder="Address" required value="{{ old('users_address') }}">
          @error('users_address')<div class="text-danger small">{{ $message }}</div>@enderror

          <div class="position-relative mb-3">
            <input type="password" id="password" name="users_password" class="form-control @error('users_password') is-invalid @enderror" placeholder="Password (min. 8 characters)" minlength="8" required>
            <span class="toggle-password" onclick="togglePassword('password', this)"><i class="fa fa-eye"></i></span>
            @error('users_password')<div class="text-danger small">{{ $message }}</div>@enderror
          </div>

          <div class="position-relative mb-3">
            <input type="password" id="password_confirmation" name="users_password_confirmation" class="form-control @error('users_password_confirmation') is-invalid @enderror" placeholder="Confirm Password" required>
            <span class="toggle-password" onclick="togglePassword('password_confirmation', this)"><i class="fa fa-eye"></i></span>
            <div class="text-danger small" id="confirm-password-error" style="display:none;"></div>
            @error('users_password_confirmation')<div class="text-danger small">{{ $message }}</div>@enderror
          </div>

          <button type="submit" class="btn btn-register mb-3">Register</button>
        </form>

        <div class="text-center mt-3">
          <span class="text-muted">Already have an account?
            <a href="{{ route('login') }}" class="text-link">Sign In</a>
          </span>
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

    function validatePasswordMatch() {
      const pass = document.getElementById('password');
      const confirm = document.getElementById('password_confirmation');
      const errorText = document.getElementById('confirm-password-error');

      if (pass.value !== confirm.value) {
        errorText.textContent = "Password and confirmation do not match.";
        errorText.style.display = "block";
        return false;
      }

      errorText.style.display = "none";
      return true;
    }
  </script>
</body>
@endsection
