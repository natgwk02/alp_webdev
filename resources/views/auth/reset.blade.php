@extends('base.base')
@section('content')
    <style>
        body {
            background: url('/images/background.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
        }

        .forgot-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .forgot-card {
            background-color: rgba(240, 240, 240, 0.85);
            padding: 40px;
            border-radius: 18px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            text-align: center;
        }

        .forgot-card img {
            width: 50%;
            max-width: 150px;
            margin-bottom: 0px;
        }

        .forgot-card h2 {
            font-size: 1.5rem;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 10px;
            color: #224488;
        }

        .forgot-card p {
            font-size: 0.9rem;
            color: #444;
            margin-bottom: 25px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border-radius: 10px;
            border: 1px solid #ccddee;
            background-color: #f8fbff;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }

        .btn-blue {
            background-color: #224488;
            color: white;
            padding: 12px;
            width: 100%;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-blue:hover {
            background-color: #C1E8FF;
            color: #224488;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            color: #224488;
            font-size: 0.9rem;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>

    <div class="forgot-wrapper">
        <div class="forgot-card">
            <h2>Reset your password</h2>
            <p>Enter your new password below.</p>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @elseif (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <div class="mb-3 text-start">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" id="password" name="password"
                        class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 text-start">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                        required>
                </div>

                <button type="submit" class="btn btn-blue">Reset Password</button>
            </form>

            <a href="{{ route('login.show') }}" class="back-link">← Back to Login</a>
        </div>
    </div>
@endsection
