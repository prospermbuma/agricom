@extends('layouts.app')

@section('title', 'Edit Profile - Agricom')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm p-4">
                    <!-- Card Header -->
                    <div class="card-header bg-white border-0 py-4">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-lg me-4">
                                <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('images/default-avatar.png') }}"
                                    class="rounded-circle border-3" alt="Profile Picture">
                            </div>
                            <div>
                                <h2 class="fw-bold mb-1">Edit Your Profile</h2>
                                <p class="text-muted mb-0">
                                    <span class="badge bg-primary bg-opacity-10 text-primary">
                                        {{ ucfirst(auth()->user()->role) }}
                                    </span>
                                    <i class="fas fa-map-marker-alt ml-1 me-1"></i> {{ auth()->user()->village }},
                                    {{ auth()->user()->region }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.update') }}" class="needs-validation" novalidate enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Personal Information Section -->
                            <div class="mb-5">
                                <h5 class="fw-semibold mb-4 text-secondary">
                                    <i class="fas fa-user-circle me-2"></i>Personal Information
                                </h5>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" name="name" id="name"
                                                class="form-control @error('name') is-invalid @enderror"
                                                value="{{ old('name', auth()->user()->name) }}" placeholder="Full Name"
                                                required>
                                            <label for="name">Full Name</label>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="email" name="email" id="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email', auth()->user()->email) }}"
                                                placeholder="Email Address" required>
                                            <label for="email">Email Address</label>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="tel" name="phone" id="phone"
                                                class="form-control @error('phone') is-invalid @enderror"
                                                value="{{ old('phone', auth()->user()->phone) }}" placeholder="Phone Number"
                                                required>
                                            <label for="phone">Phone Number</label>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="role"
                                                value="{{ ucfirst(auth()->user()->role) }}" disabled>
                                            <input type="hidden" name="role" value="{{ auth()->user()->role }}">
                                            <label for="role">Account Role</label>
                                            <small class="text-muted ms-2">Role cannot be changed</small>
                                        </div>
                                    </div>

                                    <!-- Avatar Upload -->
                                    <div class="col-md-6">
                                        <label for="avatar" class="form-label">Profile Image</label>
                                        <input class="form-control @error('avatar') is-invalid @enderror" type="file" id="avatar" name="avatar" accept="image/*">
                                        @error('avatar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Bio -->
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <textarea name="bio" id="bio" class="form-control @error('bio') is-invalid @enderror" placeholder="Short Bio" style="height: 100px;">{{ old('bio', auth()->user()->bio) }}</textarea>
                                            <label for="bio">Short Bio</label>
                                            @error('bio')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Location Information -->
                            <div class="mb-5">
                                <h5 class="fw-semibold mb-4 text-secondary">
                                    <i class="fas fa-map-marker-alt me-2"></i>Location Information
                                </h5>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select name="region" id="region"
                                                class="form-select @error('region') is-invalid @enderror" required>
                                                <option value="">Select Region</option>
                                                @foreach (['Arusha', 'Dar es Salaam', 'Dodoma', 'Geita', 'Iringa', 'Kagera', 'Katavi', 'Kigoma', 'Kilimanjaro', 'Lindi', 'Manyara', 'Mara', 'Mbeya', 'Morogoro', 'Mtwara', 'Mwanza', 'Njombe', 'Pemba North', 'Pemba South', 'Pwani', 'Rukwa', 'Ruvuma', 'Shinyanga', 'Simiyu', 'Singida', 'Songwe', 'Tabora', 'Tanga', 'Unguja North', 'Unguja South', 'Zanzibar West'] as $region)
                                                    <option value="{{ $region }}"
                                                        {{ old('region', auth()->user()->region) == $region ? 'selected' : '' }}>
                                                        {{ $region }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label for="region">Region</label>
                                            @error('region')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" name="village" id="village"
                                                class="form-control @error('village') is-invalid @enderror"
                                                value="{{ old('village', auth()->user()->village) }}" placeholder="Village"
                                                required>
                                            <label for="village">Village</label>
                                            @error('village')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Farmer-Specific Fields -->
                            @if (auth()->user()->role === 'farmer')
                                <div class="mb-5">
                                    <h5 class="fw-semibold mb-4 text-secondary">
                                        <i class="fas fa-tractor me-2"></i>Farming Information
                                    </h5>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold mb-3">Types of Crops</label>
                                        <div class="row g-3">
                                            @foreach (['maize', 'rice', 'beans', 'cassava', 'coffee', 'cotton', 'sunflower', 'other'] as $crop)
                                                <div class="col-md-6">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="checkbox" name="crops[]"
                                                            value="{{ $crop }}" id="{{ $crop }}"
                                                            {{ is_array(old('crops', auth()->user()->crops ?? [])) && in_array($crop, old('crops', auth()->user()->crops ?? [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="{{ $crop }}">
                                                            {{ ucfirst($crop) }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        @error('crops')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="number" name="farm_size" id="farm_size" step="0.1"
                                                    min="0"
                                                    class="form-control @error('farm_size') is-invalid @enderror"
                                                    value="{{ old('farm_size', auth()->user()->farm_size) }}"
                                                    placeholder="Farm Size">
                                                <label for="farm_size">Farm Size (Hectares)</label>
                                                @error('farm_size')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-floating mb-4">
                                                <select name="farming_experience" id="farming_experience" class="form-select @error('farming_experience') is-invalid @enderror" required>
                                                    <option value="">Select Experience</option>
                                                    <option value="beginner" {{ old('farming_experience', auth()->user()->farming_experience) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                                    <option value="intermediate" {{ old('farming_experience', auth()->user()->farming_experience) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                                    <option value="expert" {{ old('farming_experience', auth()->user()->farming_experience) == 'expert' ? 'selected' : '' }}>Expert</option>
                                                </select>
                                                <label for="farming_experience">Farming Experience</label>
                                                @error('farming_experience')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Password Change Section -->
                            <div class="mb-5">
                                <h5 class="fw-semibold mb-4 text-secondary">
                                    <i class="fas fa-lock me-2"></i>Password Change
                                </h5>
                                <p class="text-muted mb-4">Leave blank if you don't want to change your password</p>

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="password" name="password" id="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="New Password">
                                            <label for="password">New Password</label>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="password" name="password_confirmation"
                                                id="password_confirmation" class="form-control"
                                                placeholder="Confirm Password">
                                            <label for="password_confirmation">Confirm Password</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex justify-content-between border-top pt-4">
                                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary px-4">
                                    <i class="fas fa-arrow-left me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-2"></i>Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        body {
            background-color: #f5f7fb;
        }

        .card {
            border-radius: 12px;
            overflow: hidden;
        }

        .card-header {
            background-color: #f8fafc;
        }

        .form-floating label {
            color: #6c757d;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            padding: 12px 16px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(76, 175, 80, 0.25);
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(76, 175, 80, 0.25);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            border-radius: 8px;
            padding: 10px 24px;
        }

        .btn-primary:hover {
            background-color: #3d8b40;
        }

        .btn-outline-secondary {
            border-radius: 8px;
        }

        .needs-validation .was-validated .form-control:invalid,
        .needs-validation .form-control.is-invalid {
            border-color: #dc3545;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .invalid-feedback {
            display: none;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875em;
            color: #dc3545;
        }

        .was-validated .form-control:invalid~.invalid-feedback,
        .form-control.is-invalid~.invalid-feedback {
            display: block;
        }
    </style>
@endsection

@section('scripts')
    <script>
        // Client-side form validation
        (function() {
            'use strict';

            // Fetch all forms we want to apply custom validation styles to
            const forms = document.querySelectorAll('.needs-validation');

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }

                        form.classList.add('was-validated');
                    }, false);
                });
        })();

        // Show password toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Can add password toggle functionality here if desired
        });
    </script>
@endsection
