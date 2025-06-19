@extends('layouts.app')

@section('title', 'Profile - Agricom')

@section('content')
    <div class="container mt-5">
        <div class="row mt-4">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4><i class="fas fa-user"></i> My Profile</h4>
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Edit Profile
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Full Name</label>
                                    <p class="form-control-plaintext">{{ auth()->user()->name }}</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Email Address</label>
                                    <p class="form-control-plaintext">{{ auth()->user()->email }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Phone Number</label>
                                    <p class="form-control-plaintext">{{ auth()->user()->phone ?? 'Not provided' }}</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Role</label>
                                    <p class="form-control-plaintext">
                                        <span
                                            class="badge bg-{{ auth()->user()->role === 'farmer' ? 'success' : (auth()->user()->role === 'buyer' ? 'info' : 'primary') }}">
                                            {{ ucfirst(auth()->user()->role) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Region</label>
                                    <p class="form-control-plaintext">{{ auth()->user()->region ?? 'Not specified' }}</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Village</label>
                                    <p class="form-control-plaintext">{{ auth()->user()->village ?? 'Not specified' }}</p>
                                </div>
                            </div>
                        </div>

                        @if (auth()->user()->role === 'farmer')
                            <div class="mb-3">
                                <label class="form-label fw-bold">Types of Crops</label>
                                @php
                                    $userCrops = auth()->user()->crops ? json_decode(auth()->user()->crops) : [];
                                @endphp
                                @if (!empty($userCrops))
                                    <div class="row">
                                        @foreach ($userCrops as $crop)
                                            <div class="col-md-4 col-sm-6">
                                                <span class="badge bg-light text-dark border me-2 mb-2">
                                                    <i class="fas fa-seedling"></i> {{ ucfirst($crop) }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="form-control-plaintext text-muted">No crops specified</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Farm Size</label>
                                <p class="form-control-plaintext">
                                    @if (auth()->user()->farm_size)
                                        {{ auth()->user()->farm_size }} hectares
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </p>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Member Since</label>
                                    <p class="form-control-plaintext">
                                        <i class="fas fa-calendar"></i>
                                        {{ auth()->user()->created_at->format('F j, Y') }}
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Last Updated</label>
                                    <p class="form-control-plaintext">
                                        <i class="fas fa-clock"></i>
                                        {{ auth()->user()->updated_at->format('F j, Y g:i A') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if (auth()->user()->role === 'farmer')
                            <div class="card bg-light mt-4">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-chart-line text-success"></i> Quick Stats
                                    </h6>
                                    <div class="row text-center">
                                        <div class="col-md-4">
                                            <div class="border-end">
                                                <h5 class="text-primary mb-0">{{ count($userCrops) }}</h5>
                                                <small class="text-muted">Crop Types</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="border-end">
                                                <h5 class="text-success mb-0">
                                                    {{ auth()->user()->farm_size ?? '0' }}
                                                </h5>
                                                <small class="text-muted">Hectares</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <h5 class="text-info mb-0">
                                                {{ auth()->user()->region ?? 'N/A' }}
                                            </h5>
                                            <small class="text-muted">Region</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Dashboard
                            </a>
                            <div>
                                <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Edit Profile
                                </a>
                                @if (auth()->user()->role === 'farmer')
                                    <a href="#" class="btn btn-success ms-2" data-bs-toggle="tooltip"
                                        title="View your crop listings">
                                        <i class="fas fa-leaf"></i> My Crops
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>
        </div>

        @push('scripts')
            <script>
                // Initialize Bootstrap tooltips
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                })
            </script>
        @endpush
    </div>
@endsection
