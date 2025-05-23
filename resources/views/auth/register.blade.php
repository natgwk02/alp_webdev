@extends('base.base')

@section('content')
<style>
    body {
        background: url('/images/background.jpg') no-repeat center center fixed;
        background-size: cover;
    }

    .register-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding-top: 100px;
        padding-bottom: 60px;
    }

    .register-card {
        background-color: rgba(240, 240, 240, 0.85);
        padding: 40px;
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 500px;
    }

    .register-card h4 {
        text-align: center;
        margin-bottom: 10px;
        color: #224488;
        font-weight: bold;
    }

    .register-card p {
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

    .text-muted {
        font-size: 0.9rem;
    }

    .toggle-password {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #666;
    }

    .position-relative {
        position: relative;
    }
</style>

<div class="register-wrapper">
    <div class="register-card">

        <h4>Create an Account</h4>
        <p>Start shopping your frozen favorites with Chillé Mart!</p>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li class="small">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.submit') }}" method="POST">
            @csrf

            <input type="text" name="name" class="form-control" placeholder="Full Name" required>
            <input type="email" name="email" class="form-control" placeholder="Email Address" required>
            <input type="text" name="phone" class="form-control" placeholder="Phone Number"
                   maxlength="12" pattern="[0-9]+"
                   oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                   required>
            <input type="text" name="address" class="form-control" placeholder="Address" required>

            <div class="position-relative">
                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                <span class="toggle-password" onclick="togglePassword('password', this)">
                    <i class="fa fa-eye"></i>
                </span>
            </div>

            <div class="position-relative">
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm Password" required>
                <span class="toggle-password" onclick="togglePassword('password_confirmation', this)">
                    <i class="fa fa-eye"></i>
                </span>
            </div>

            <button type="submit" class="btn btn-blue w-100 mt-3">Register</button>
        </form>

        <div class="text-center mt-3">
            <span class="text-muted">Already have an account?
                <a href="{{ route('login.show') }}" class="text-link">Sign In</a>
            </span>
        </div>

    </div>
</div>

<script>
function togglePassword(fieldId, iconElement) {
    const input = document.getElementById(fieldId);
    const icon = iconElement.querySelector('i');
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
@endsection
