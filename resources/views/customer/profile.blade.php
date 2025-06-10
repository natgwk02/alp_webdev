@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row">
            {{-- Sidebar --}}
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <img src="/assets/profile.png" class="rounded-circle mb-3" width="120" height="120"
                            alt="Profile Photo">
                        <h5 class="card-title">
                            @if (Auth::check())
                                <span>{{ Auth::user()->users_name }}</span>
                            @else
                                <span>Guest</span>
                            @endif
                        </h5>
                        <p class="text-muted small">Member since 2023</p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item active" style="background-color: #052659; color: white;">
                            <i class="fas fa-user me-2"></i> My Profile
                        </li>
                    </ul>
                </div>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
                <a class="w-100 btn text-white mt-3 fw-bold" style="background-color: #052659" href="#"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out me-2" style="color: white"></i> Sign Out
                </a>
            </div>

            {{-- Profile Card --}}
            <div class="col-md-9">
                <div class="card shadow-sm">
                    <div class="card-header text-white" style="background-color: #052659">
                        <h4 class="mb-0">Profile Information</h4>
                    </div>
                    <div class="card-body p-4">
                        {{-- Profile Summary --}}
                        <div class="text-center mb-4">
                            <img src="/assets/profile.png" class="rounded-circle shadow" width="120" height="120"
                                alt="Profile Photo">
                            <h5 class="mt-3 mb-1">{{ Auth::user()->users_name }}</h5>
                            <p class="text-muted">{{ Auth::user()->users_email }}</p>
                        </div>

                        {{-- Success Notification --}}
                        @if (session('success'))
                            <div id="success-alert" class="alert alert-success py-2 px-3 small" style="font-size: 14px;">
                                {{ session('success') }}
                            </div>
                        @endif

                        {{-- Error Notification --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0 small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Profile Update Form --}}
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="users_name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control @error('users_name') is-invalid @enderror"
                                        id="users_name" name="users_name"
                                        value="{{ old('users_name', Auth::user()->users_name) }}" required>
                                    @error('users_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="users_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="users_email" name="users_email"
                                        value="{{ Auth::user()->users_email }}" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label for="users_phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control @error('users_phone') is-invalid @enderror"
                                        id="users_phone" name="users_phone"
                                        value="{{ old('users_phone', Auth::user()->users_phone ?? '') }}"
                                        placeholder="+62 812-3456-7890">
                                    @error('users_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="users_address" class="form-label">Address</label>
                                    <input type="text" class="form-control @error('users_address') is-invalid @enderror"
                                        id="users_address" name="users_address"
                                        value="{{ old('users_address', Auth::user()->users_address ?? '') }}">
                                    @error('users_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn px-4 text-white fw-semibold"
                                    style="background-color: #052659">
                                    <i class="fas fa-save me-2"></i> Save Changes
                                </button>
                            </div>
                        </form>

                        <hr class="my-5">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Auto-hide success alert --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alert = document.getElementById('success-alert');
            if (alert) {
                setTimeout(() => {
                    alert.classList.add('fade');
                    alert.classList.remove('show');
                    setTimeout(() => alert.remove(), 500);
                }, 5000); // Hide after 5 seconds
            }
        });
    </script>
@endsection
