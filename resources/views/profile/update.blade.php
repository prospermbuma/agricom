@extends('layouts.app')

@section('title', 'Profile Updated - Agricom')

@section('content')
    <div class="container">
        <div class="row mt-4">
            <div class="col-md-8 mx-auto">
                <!-- Profile Summary Card -->
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-user-check"></i> Profile Updated
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <div class="profile-avatar mb-3">
                                    <div
                                        class="avatar-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto">
                                        <i class="fas fa-user fs-1"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <h5 class="text-success mb-3">
                                    <i class="fas fa-check me-2"></i>Your profile has been updated successfully!
                                </h5>

                                <!-- Updated Information Summary -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <strong>Name:</strong> {{ auth()->user()->name }}
                                        </div>
                                        <div class="mb-2">
                                            <strong>Email:</strong> {{ auth()->user()->email }}
                                        </div>
                                        <div class="mb-2">
                                            <strong>Phone:</strong> {{ auth()->user()->phone ?? 'Not provided' }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <strong>Role:</strong>
                                            <span
                                                class="badge bg-{{ auth()->user()->role === 'farmer' ? 'success' : 'info' }}">
                                                {{ ucfirst(auth()->user()->role) }}
                                            </span>
                                        </div>
                                        <div class="mb-2">
                                            <strong>Region:</strong> {{ auth()->user()->region ?? 'Not specified' }}
                                        </div>
                                        <div class="mb-2">
                                            <strong>Village:</strong> {{ auth()->user()->village ?? 'Not specified' }}
                                        </div>
                                    </div>
                                </div>

                                @if (auth()->user()->role === 'farmer')
                                    <div class="mt-3">
                                        <div class="mb-2">
                                            <strong>Crops:</strong>
                                            @php
                                                $userCrops = auth()->user()->crops
                                                    ? json_decode(auth()->user()->crops)
                                                    : [];
                                            @endphp
                                            @if (!empty($userCrops))
                                                @foreach ($userCrops as $crop)
                                                    <span class="badge bg-light text-dark border me-1">
                                                        {{ ucfirst($crop) }}
                                                    </span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">No crops specified</span>
                                            @endif
                                        </div>
                                        <div class="mb-2">
                                            <strong>Farm Size:</strong>
                                            {{ auth()->user()->farm_size ? auth()->user()->farm_size . ' hectares' : 'Not specified' }}
                                        </div>
                                    </div>
                                @endif

                                <div class="mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-clock"></i> Last updated:
                                        {{ auth()->user()->updated_at->format('F j, Y g:i A') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="card mt-3">
                    <div class="card-body">
                        <h6 class="card-title">What would you like to do next?</h6>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <a href="{{ route('profile.show') }}" class="btn btn-outline-info w-100">
                                    <i class="fas fa-eye"></i> View Profile
                                </a>
                            </div>
                            <div class="col-md-4 mb-2">
                                <a href="{{ route('profile.edit') }}" class="btn btn-outline-warning w-100">
                                    <i class="fas fa-edit"></i> Edit Again
                                </a>
                            </div>
                            <div class="col-md-4 mb-2">
                                <a href="{{ route('dashboard') }}" class="btn btn-primary w-100">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard
                                </a>
                            </div>
                        </div>

                        @if (auth()->user()->role === 'farmer')
                            <div class="row mt-2">
                                <div class="col-md-6 mb-2">
                                    <a href="#" class="btn btn-outline-success w-100">
                                        <i class="fas fa-plus"></i> Add Crop Listing
                                    </a>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <a href="#" class="btn btn-outline-secondary w-100">
                                        <i class="fas fa-leaf"></i> Manage Crops
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tips Card -->
                <div class="card mt-3">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="fas fa-lightbulb text-warning"></i> Profile Tips
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled mb-0">
                                    <li><i class="fas fa-check text-success me-2"></i>Keep your contact information updated
                                    </li>
                                    <li><i class="fas fa-check text-success me-2"></i>Verify your phone number for better
                                        reach</li>
                                    @if (auth()->user()->role === 'farmer')
                                        <li><i class="fas fa-check text-success me-2"></i>Add all crops you grow</li>
                                    @endif
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled mb-0">
                                    <li><i class="fas fa-check text-success me-2"></i>Specify your exact location</li>
                                    @if (auth()->user()->role === 'farmer')
                                        <li><i class="fas fa-check text-success me-2"></i>Update farm size for better
                                            matching</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Complete profile attracts more
                                            buyers</li>
                                    @else
                                        <li><i class="fas fa-check text-success me-2"></i>Complete profile builds trust</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Active profiles get better
                                            responses</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('styles')
            <style>
                .avatar-circle {
                    width: 80px;
                    height: 80px;
                    border-radius: 50%;
                    margin: 0 auto;
                }

                .profile-avatar {
                    position: relative;
                }

                .alert-heading {
                    color: inherit !important;
                }

                .card-header.bg-success {
                    border-bottom: none;
                }
            </style>
        @endpush

        @push('scripts')
            <script>
                // Auto-hide success alert after 5 seconds
                setTimeout(function() {
                    $('.alert-success').fadeOut('slow');
                }, 5000);

                // Auto-redirect to dashboard after 10 seconds if no user interaction
                let redirectTimer = setTimeout(function() {
                    window.location.href = "{{ route('dashboard') }}";
                }, 10000);

                // Cancel auto-redirect if user interacts with the page
                $(document).on('click keypress scroll', function() {
                    clearTimeout(redirectTimer);
                });
            </script>
        @endpush
    </div>
@endsection
