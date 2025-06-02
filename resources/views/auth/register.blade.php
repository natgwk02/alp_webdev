@extends('base.base')

@section('content')

<!-- ✅ SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        padding-right: 45px;
        background-color: #eef4ff;
        box-sizing: border-box;
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

    .form-group {
        position: relative;
    }

    .toggle-password {
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        cursor: pointer;
        z-index: 2;
        color: #888;
        transition: opacity 0.2s ease;
    }

    input.is-invalid {
        border-color: #dc3545 !important;
    }

    .text-danger {
        font-size: 0.85rem;
    }

    .position-relative {
        position: relative;
    }
</style>

<div class="register-wrapper">
    <div class="register-card">

        <h4>Create an Account</h4>
        <p>Start shopping your frozen favorites with Chillé Mart!</p>

        <form action="{{ route('register.submit') }}" method="POST" onsubmit="return validatePasswordMatch();">
            @csrf

            <div class="mb-3">
                <input type="text" class="form-control @error('users_name') is-invalid @enderror"
                       name="users_name" value="{{ old('users_name') }}" placeholder="Full Name" required>
                @error('users_name')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <input type="email" class="form-control @error('users_email') is-invalid @enderror"
                       name="users_email" value="{{ old('users_email') }}" placeholder="Email" required>
                @error('users_email')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <input type="text" class="form-control @error('users_phone') is-invalid @enderror"
                       name="users_phone" value="{{ old('users_phone') }}" placeholder="Phone Number"
                       pattern="[0-9]+" maxlength="15"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                @error('users_phone')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <input type="text" class="form-control @error('users_address') is-invalid @enderror"
                       name="users_address" value="{{ old('users_address') }}" placeholder="Address" required>
                @error('users_address')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group position-relative mb-3">
                <input type="password"
                       id="password"
                       name="users_password"
                       class="form-control @error('users_password') is-invalid @enderror"
                       placeholder="Password (min. 8 characters)"
                       minlength="8"
                       required
                       oninput="handlePasswordFieldInput(this)">
                <span class="toggle-password" id="toggle-password" onclick="togglePassword('password', this)">
                    <i class="fa fa-eye"></i>
                </span>
                @error('users_password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group position-relative mb-3">
                <input type="password"
                       id="password_confirmation"
                       class="form-control @error('users_password_confirmation') is-invalid @enderror"
                       name="users_password_confirmation"
                       placeholder="Confirm Password"
                       required
                       oninput="handlePasswordInput(this)">
                <span class="toggle-password" id="toggle-password-confirm"
                      onclick="togglePassword('password_confirmation', this)">
                    <i class="fa fa-eye"></i>
                </span>
                <div class="text-danger small mt-1" id="confirm-password-error" style="display: none;"></div>
                @error('users_password_confirmation')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-blue w-100 mt-3">Register</button>

            @if(session('success'))
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Registration Successful!',
                        text: '{{ session('success') }}',
                        confirmButtonColor: '#224488'
                    });
                });
            </script>
            @endif

        </form>

        <div class="text-center mt-3">
            <span class="text-muted">Already have an account?
                <a href="{{ route('login') }}" class="text-link">Sign In</a>
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

    function handlePasswordFieldInput(inputElement) {
        const icon = document.getElementById('toggle-password');
        if (inputElement.value.trim() === '') {
            icon.style.display = 'none';
        } else {
            icon.style.display = 'inline';
        }
    }

    function handlePasswordInput(inputElement) {
        const icon = document.getElementById('toggle-password-confirm');
        if (inputElement.value.trim() === '') {
            icon.style.display = 'none';
        } else {
            icon.style.display = 'inline';
        }

        inputElement.classList.remove('is-invalid');
        document.getElementById('confirm-password-error').style.display = 'none';
    }

    function validatePasswordMatch() {
        const password = document.getElementById('password');
        const confirm = document.getElementById('password_confirmation');
        const errorText = document.getElementById('confirm-password-error');
        const iconPass = document.getElementById('toggle-password');
        const iconConfirm = document.getElementById('toggle-password-confirm');

        if (password.value !== confirm.value) {
            errorText.textContent = "Password and confirmation do not match.";
            errorText.style.display = "block";

            password.type = "password";
            confirm.type = "password";
            iconPass.style.display = "none";
            iconConfirm.style.display = "none";

            confirm.classList.add('is-invalid');
            return false;
        }

        errorText.style.display = "none";
        iconPass.style.display = "inline";
        iconConfirm.style.display = "inline";
        confirm.classList.remove('is-invalid');
        return true;
    }

    document.addEventListener("DOMContentLoaded", function () {
        const confirmInput = document.getElementById('password_confirmation');
        const passInput = document.getElementById('password');
        const iconConfirm = document.getElementById('toggle-password-confirm');
        const iconPass = document.getElementById('toggle-password');

        if (confirmInput.value.trim() === '') iconConfirm.style.display = 'none';
        if (passInput.value.trim() === '') iconPass.style.display = 'none';
    });
</script>
@endsection
