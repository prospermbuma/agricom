@extends('layouts.app')

@section('title', 'My Profile - Agricom')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-lg rounded-4 border-0 overflow-hidden">
                    <div class="row g-0">
                        <!-- Sidebar Avatar -->
                        <div
                            class="col-md-12 sidebar-avatar text-white d-flex flex-column align-items-center justify-content-center p-4 text-center">
                            <img src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('images/default-avatar.png') }}"
                                alt="User Avatar" class="img-fluid rounded-circle shadow mb-3"
                                style="width: 120px; height: 120px; object-fit: cover;">
                            <h4 class="fw-bold">{{ auth()->user()->name }}</h4>
                            <span class="badge bg-light text-success mt-1">{{ ucfirst(auth()->user()->role) }}</span>
                            <p class="small mt-2"><i
                                    class="fas fa-map-marker-alt me-1"></i>{{ auth()->user()->village ?? '-' }},
                                {{ auth()->user()->region ?? '-' }}</p>
                        </div>

                        <!-- Profile Info -->
                        <div class="col-md-12 p-5">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="text-success"><i class="fas fa-user-circle me-2"></i>Profile Details</h5>
                                <a href="{{ route('profile.edit') }}" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                            </div>

                            <div class="mb-3">
                                <strong>Email:</strong>
                                <p class="text-muted mb-0">{{ auth()->user()->email }}</p>
                            </div>

                            <div class="mb-3">
                                <strong>Phone:</strong>
                                <p class="text-muted mb-0">{{ auth()->user()->phone ?? 'Not provided' }}</p>
                            </div>

                            <div class="mb-3">
                                <strong>Bio:</strong>
                                <p class="text-muted mb-0">{{ auth()->user()->bio ?? 'No bio added yet.' }}</p>
                            </div>

                            @if (auth()->user()->role === 'farmer')
                                <hr class="my-4">
                                <h6 class="text-success mb-3">Farming Info</h6>

                                <div class="mb-3">
                                    <strong>Farm Size:</strong>
                                    <p class="text-muted mb-0">{{ auth()->user()->farm_size ?? 'Not specified' }} hectares
                                    </p>
                                </div>

                                <div class="mb-3">
                                    <strong>Crops:</strong>
                                    @php $userCrops = auth()->user()->crops ?? []; @endphp
                                    @if (!empty($userCrops))
                                        @foreach ($userCrops as $crop)
                                            <span class="badge bg-light text-dark border me-1 mb-1">
                                                <i class="fas fa-seedling me-1"></i>{{ ucfirst($crop) }}
                                            </span>
                                        @endforeach
                                    @else
                                        <p class="text-muted">No crops listed.</p>
                                    @endif
                                </div>
                            @endif

                            <hr class="my-4">

                            <div class="row text-muted small">
                                <div class="col-md-6 mb-2">
                                    <i class="fas fa-calendar me-1"></i>
                                    <strong>Member Since:</strong> {{ auth()->user()->created_at->format('M d, Y') }}
                                </div>
                                <div class="col-md-6 text-md-end mb-2">
                                    <i class="fas fa-clock me-1"></i>
                                    <strong>Last Updated:</strong> {{ auth()->user()->updated_at->format('M d, Y h:i A') }}
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                                </a>
                                @if (auth()->user()->role === 'farmer')
                                    <a href="#" class="btn btn-success">
                                        <i class="fas fa-leaf me-1"></i> My Crops
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('styles')

    <style>
        .sidebar-avatar {
            background-color: #d4edda;
            color: #155724;
        }
        .sidebar-avatar * {
            color: #155724;
        }
    </style>

@endsection
