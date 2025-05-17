@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <img src="{{'/assets/profile.png' }}" 
                         class="rounded-circle mb-3" 
                         width="120" 
                         height="120"
                         alt="Profile Photo">
                    <h5 class="card-title">Hi! Alice</h5>
                    <p class="text-muted small">Member since 2025</p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <i class="fas fa-user me-2"></i> My Profile
                    </li>                   
                    <li class="list-group-item">
                        <a href="" class="text-decoration-none text-dark">
                            <i class="fas fa-calendar me-2"></i> Birth Date
                        </a>
                    </li>                   
                </ul>
                
            </div>
            <a class="w-100 btn text-white mt-3 fw-bold" style="background-color: #052659" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out me-2" style="color: white"></i> Sign Out</a>
        </div>

        <!-- Main Profile Content -->
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #052659">
                    <h4 class="mb-0">Profile Information</h4>
                </div>
                <div class="card-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Profile Photo Upload -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <label class="form-label">Profile Photo</label>
                                <div class="profile-photo-container">
                                    {{-- <img id="profile-photo-preview" 
                                          
                                         class="rounded-circle" 
                                         width="120" 
                                         height="120"
                                         alt="Profile Preview"> --}}
                                </div>
                            </div>
                            <div class="col-md-9 d-flex align-items-end">
                                <div class="w-100">
                                    <input type="file" 
                                           class="form-control" 
                                           id="profile_photo" 
                                           name="profile_photo"
                                           accept="image/*">
                                    <div class="form-text">Max 2MB (JPG, PNG)</div>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="name" 
                                       name="name"
                                       value=""
                                       required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email"
                                       value=""
                                       required>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" 
                                       class="form-control" 
                                       id="phone" 
                                       name="phone"
                                       value=""
                                       placeholder="+62 812-3456-7890">
                            </div>
                            <div class="col-md-6">
                                <label for="birthdate" class="form-label">Address</label>
                                <input type="input" 
                                       class="form-control" 
                                       id="birthdate" 
                                       name="birthdate"
                                       value="">
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn px-4 text-white fw-semibold" style="background-color: #052659">
                                <i class="fas fa-save me-2"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

           
        </div>
    </div>
</div>

<!-- JavaScript for Profile Photo Preview -->
<script>
    document.getElementById('profile_photo').addEventListener('change', function(e) {
        const preview = document.getElementById('profile-photo-preview');
        const file = e.target.files[0];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
        }
        
        reader.readAsDataURL(file);
    });
</script>
@endsection