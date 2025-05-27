@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    {{-- Display user's profile photo or default --}}
                    <img src="/assets/profile.png"
                        class="rounded-circle mb-3"
                        width="120"
                        height="120"
                        alt="Profile Photo">
                    <h5 class="card-title">
                    @if(Auth::check())
                        {{ Auth::user()->users_name }}
                    @else
                        Guest
                    @endif
                
                                        </h5>
                                        <p class="text-muted small">Member since 2023</p>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item active" style="background-color: #052659; color: white;">
                                            <i class="fas fa-user me-2"></i> My Profile
                                        </li>
                                        {{-- Example: Link to another section, if any --}}
                                        {{-- <li class="list-group-item">
                                            <a href="#" class="text-decoration-none text-dark">
                                                <i class="fas fa-calendar me-2"></i> My Orders
                                            </a>
                                        </li> --}}
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

                            <div class="col-md-9">
                                <div class="card shadow-sm">
                                    <div class="card-header text-white" style="background-color: #052659">
                                        <h4 class="mb-0">Profile Information</h4>
                                    </div>
                                    <div class="card-body">
                                        {{-- Success Message --}}
                                        @if (session('success'))
                                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                {{ session('success') }}
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                        @endif

                                        {{-- Error Messages --}}
                                        @if ($errors->any())
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                        @endif

                                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            <div class="row mb-4">
                                                <div class="col-md-3">
                                                    <label class="form-label">Profile Photo</label>
                                                    <div class="profile-photo-container">
                                                        <img id="profile-photo-preview"
                                                            src= "/assets/profile.png"
                                                            class="rounded-circle"
                                                            width="120"
                                                            height="120"
                                                            alt="Profile Preview">
                                                    </div>
                                                </div>
                                                <div class="col-md-9 d-flex align-items-end">
                                                    <div class="w-100">
                                                        <input type="file"
                                                            class="form-control @error('profile_photo') is-invalid @enderror"
                                                            id="profile_photo"
                                                            name="profile_photo"
                                                            accept="image/*">
                                                        <div class="form-text">Max 2MB (JPG, PNG). Will replace existing photo.</div>
                                                        @error('profile_photo')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="name" class="form-label">Full Name</label>
                                                    <input type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        id="name"
                                                        name="name"
                                                        value="{{ old('name', optional(Auth::user())->users_name) }}"
                                                        required>
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        id="email"
                                                        name="email"
                                                        value="{{ old('email', optional(Auth::user())->users_email) }}"
                                                        required>
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="phone" class="form-label">Phone Number</label>
                                                    <input type="tel"
                                                        class="form-control @error('phone') is-invalid @enderror"
                                                        id="phone"
                                                        name="phone"
                                                        value="{{ old('phone', Auth::user()->phone ?? '') }}"
                                                        placeholder="+62 812-3456-7890">
                                                    @error('phone')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="birthdate" class="form-label">Birth Date</label>
                                                    <input type="date"
                                                        class="form-control @error('birthdate') is-invalid @enderror"
                                                        id="birthdate"
                                                        name="birthdate"
                                                        value="{{ old('birthdate', optional(Auth::user())->birthdate ? \Carbon\Carbon::parse(optional(Auth::user())->birthdate)->format('Y-m-d') : '') }}"
                                                    @error('birthdate')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-12">
                                                    <label for="address" class="form-label">Address</label>
                                                    <input type="text"
                                                        class="form-control @error('address') is-invalid @enderror"
                                                        id="address"
                                                        name="address"
                                                        value="{{ old('address', Auth::user()->address ?? '') }}">
                                                    @error('address')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-end mt-4">
                                                <button type="submit" class="btn px-4 text-white fw-semibold" style="background-color: #052659">
                                                    <i class="fas fa-save me-2"></i> Save Changes
                                                </button>
                                            </div>
                                        </form>

                                        <hr class="my-5">

                                        {{-- Password Update Section
                                        <h4 class="mb-3">Change Password</h4>
                                        <form action="{{ route('') }}" method="POST">
                                            @csrf
                                            @method('PUT') Or POST, depending on your route definition --}}

                                            {{-- <div class="mb-3">
                                                <label for="current_password" class="form-label">Current Password</label>
                                                <input type="password"
                                                    class="form-control @error('current_password') is-invalid @enderror"
                                                    id="current_password"
                                                    name="current_password"
                                                    required>
                                                @error('current_password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="new_password" class="form-label">New Password</label>
                                                <input type="password"
                                                    class="form-control @error('new_password') is-invalid @enderror"
                                                    id="new_password"
                                                    name="new_password"
                                                    required>
                                                @error('new_password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                                <input type="password"
                                                    class="form-control"
                                                    id="new_password_confirmation"
                                                    name="new_password_confirmation"
                                                    required>
                                            </div>

                                            <div class="d-flex justify-content-end mt-4">
                                                <button type="submit" class="btn btn-warning px-4 text-white fw-semibold">
                                                    <i class="fas fa-key me-2"></i> Change Password
                                                </button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profilePhotoInput = document.getElementById('profile_photo');
        const profilePhotoPreview = document.getElementById('profile-photo-preview');

        if (profilePhotoInput) {
            profilePhotoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        profilePhotoPreview.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
@endsection