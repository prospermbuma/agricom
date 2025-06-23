@extends('layouts.app')

@section('title', 'Create User - Agricom')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card border-0 p-3 rounded-4 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h4 class="mb-0 fw-bold">
                            <i class="fas fa-user-plus me-2"></i>Create New User
                        </h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}" required>
                                        <label for="name">Full Name</label>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}" required>
                                        <label for="email">Email Address</label>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone') }}" required>
                                        <label for="phone">Phone Number</label>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Bio -->
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <textarea name="bio" id="bio" class="form-control @error('bio') is-invalid @enderror" placeholder="Short Bio" style="height: 100px;">{{ old('bio') }}</textarea>
                                        <label for="bio">Short Bio</label>
                                        @error('bio')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select @error('role') is-invalid @enderror" id="role"
                                            name="role" required>
                                            <option value="">Select Role</option>
                                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin
                                            </option>
                                            <option value="veo" {{ old('role') == 'veo' ? 'selected' : '' }}>VEO</option>
                                            <option value="farmer" {{ old('role') == 'farmer' ? 'selected' : '' }}>Farmer
                                            </option>
                                        </select>
                                        <label for="role">User Role</label>
                                        @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password" required>
                                        <label for="password">Password</label>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="password" class="form-control" id="password_confirmation"
                                            name="password_confirmation" required>
                                        <label for="password_confirmation">Confirm Password</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select @error('region_id') is-invalid @enderror" id="region_id"
                                            name="region_id" required>
                                            <option value="">Select Region</option>
                                            @foreach ($regions as $region)
                                                <option value="{{ $region->id }}"
                                                    {{ old('region_id') == $region->id ? 'selected' : '' }}>
                                                    {{ $region->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="region_id">Region</label>
                                        @error('region_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('village') is-invalid @enderror"
                                            id="village" name="village" value="{{ old('village') }}" required>
                                        <label for="village">Village</label>
                                        @error('village')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Farmer Specific Fields -->
                                <div class="col-12" id="farmerFields" style="display: none;">
                                    <div class="border-top pt-4 mt-2">
                                        <h6 class="fw-semibold mb-3">Farmer Information</h6>

                                        <div class="mb-3">
                                            <label class="form-label">Crops Grown</label>
                                            <div class="row g-3">
                                                @foreach ($crops as $crop)
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="crops[]"
                                                                value="{{ $crop->id }}" id="crop_{{ $crop->id }}"
                                                                {{ is_array(old('crops')) && in_array($crop->id, old('crops')) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="crop_{{ $crop->id }}">
                                                                {{ $crop->name }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @error('crops')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="farm_size_acres" class="form-label">Farm Size (Hectares)</label>
                                            <input type="number" name="farm_size_acres" id="farm_size_acres" step="0.1" min="0" class="form-control @error('farm_size_acres') is-invalid @enderror" value="{{ old('farm_size_acres') }}">
                                            @error('farm_size_acres')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="farming_experience" class="form-label">Farming Experience</label>
                                            <select name="farming_experience" id="farming_experience" class="form-select @error('farming_experience') is-invalid @enderror" required>
                                                <option value="">Select Experience</option>
                                                <option value="beginner" {{ old('farming_experience') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                                <option value="intermediate" {{ old('farming_experience') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                                <option value="expert" {{ old('farming_experience') == 'expert' ? 'selected' : '' }}>Expert</option>
                                            </select>
                                            @error('farming_experience')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                            value="1" checked>
                                        <label class="form-check-label" for="is_active">Active User</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="avatar" class="form-label">Profile Picture</label>
                                        <input class="form-control" type="file" id="avatar" name="avatar">
                                        <small class="text-muted">Max 2MB (JPEG, PNG, JPG, GIF)</small>
                                        @error('avatar')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Create User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show/hide farmer fields based on role selection
            const roleSelect = document.getElementById('role');
            const farmerFields = document.getElementById('farmerFields');

            function toggleFarmerFields() {
                farmerFields.style.display = roleSelect.value === 'farmer' ? 'block' : 'none';
            }

            roleSelect.addEventListener('change', toggleFarmerFields);
            toggleFarmerFields(); // Initial check
        });
    </script>
@endsection

@section('styles')
    <style>
        body {
            background-color: #f5f7fb;
        }
    </style>
@endsection
@endsection
