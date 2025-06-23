@extends('layouts.app')

@section('title', 'User Details - Agricom')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 p-3 rounded-4 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0 fw-bold">
                                <i class="fas fa-user-circle me-2"></i>User Details
                            </h4>
                            <div class="d-flex gap-2">
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                                <form action="{{ route('users.toggle-status', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="btn btn-sm btn-outline-{{ $user->is_active ? 'danger' : 'success' }}">
                                        <i class="fas fa-{{ $user->is_active ? 'times' : 'check' }} me-1"></i>
                                        {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-4 mb-md-0">
                                <div class="avatar avatar-xxl mb-3">
                                    <img src="{{ $user->avatar_url }}"
                                        class="rounded-circle border-3" alt="{{ $user->name }}">
                                </div>
                                <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                                <div class="d-flex gap-2 justify-content-start align-items-center mt-2">
                                    <span
                                        class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'veo' ? 'warning' : 'success') }} bg-opacity-10 text-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'veo' ? 'warning' : 'success') }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                    <span
                                        class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }} bg-opacity-10 text-{{ $user->is_active ? 'success' : 'secondary' }}">
                                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="bg-light p-4 rounded-4 h-100">
                                            <h6 class="fw-semibold mb-2">Contact Information</h6>
                                            <p class="mb-1"><i class="fas fa-envelope me-2 text-muted"></i>
                                                {{ $user->email }}</p>
                                            <p class="mb-1"><i class="fas fa-phone me-2 text-muted"></i>
                                                {{ $user->phone }}</p>
                                            <p class="mb-0"><i class="fas fa-map-marker-alt me-2 text-muted"></i>
                                                {{ $user->village }}, {{ $user->region }}</p>
                                        </div>
                                    </div>

                                    @if ($user->isFarmerRole())
                                        <div class="col-md-6">
                                            <div class="bg-light p-4 rounded-4 h-100">
                                                <h6 class="fw-semibold mb-2">Farming Information</h6>
                                                @if ($user->farmerProfile && $user->farmerProfile->crops->count() > 0)
                                                    <p class="mb-2"><i class="fas fa-seedling me-2 text-muted"></i>
                                                        <strong>Crops:</strong>
                                                        {{ $user->farmerProfile->crops->pluck('name')->implode(', ') }}
                                                    </p>
                                                @endif
                                                @if ($user->farmerProfile && $user->farmerProfile->farm_size_acres)
                                                    <p class="mb-0"><i class="fas fa-ruler-combined me-2 text-muted"></i>
                                                        <strong>Farm Size:</strong> {{ $user->farmerProfile->farm_size_acres }} hectares
                                                    </p>
                                                @endif
                                                <p class="mb-0 mt-2"><i class="fas fa-user-graduate me-2 text-muted"></i>
                                                    <strong>Farming Experience:</strong>
                                                    @php
                                                        $exp = optional($user->farmerProfile)->farming_experience ?? null;
                                                        $expLabels = ['beginner' => 'Beginner', 'intermediate' => 'Intermediate', 'expert' => 'Expert'];
                                                    @endphp
                                                    {{ $expLabels[$exp] ?? '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-12">
                                        <div class="bg-light p-4 rounded-4 h-100">
                                            <h6 class="fw-semibold mb-3">Account Information</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p class="mb-2"><i class="fas fa-calendar-alt me-2 text-muted"></i>
                                                        <strong>Created:</strong> {{ $user->created_at->format('M d, Y') }}
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="mb-2"><i class="fas fa-clock me-2 text-muted"></i>
                                                        <strong>Last Updated:</strong>
                                                        {{ $user->updated_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Recent Activity -->
                        <div class="mt-5">
                            <hr class="py-2 border-3">
                            <h5 class="fw-semibold mb-3">
                                <i class="fas fa-history me-2"></i>Recent Activity
                            </h5>

                            @if ($activities->count() > 0)
                                <div class="list-group">
                                    @foreach ($activities as $activity)
                                        <div class="list-group-item border-0 py-3">
                                            <div class="d-flex justify-content-between">
                                                <div class="bg-light w-100 p-3 rounded-4">
                                                    <p class="mb-1">{{ $activity->description }}</p>
                                                    <small
                                                        class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                                </div>
                                                @if ($activity->properties && count($activity->properties) > 0)
                                                    <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal"
                                                        data-bs-target="#activityModal{{ $activity->id }}">
                                                        <i class="fas fa-info-circle"></i> Details
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-history fa-2x text-muted mb-3"></i>
                                    <p class="text-muted">No recent activity found</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Detail Modals -->
    @foreach ($activities as $activity)
        @if ($activity->properties && count($activity->properties) > 0)
            <div class="modal fade" id="activityModal{{ $activity->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Activity Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <pre class="bg-light p-3 rounded">{{ json_encode($activity->properties, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection

@section('styles')
    <style>
        body {
            background-color: #f5f7fb;
        }

        .avatar-xxl {
            width: 150px;
            height: 150px;
        }

        .avatar-xxl img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-color: var(--primary-color);
        }

        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
@endsection
