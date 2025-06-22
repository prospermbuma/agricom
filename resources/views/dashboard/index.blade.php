@extends('layouts.app')

@section('title', 'Dashboard - Agricom')

@section('content')
    <div class="container-fluid">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="welcome-card bg-white p-4 rounded-3 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="fw-bold mb-2">Welcome back, {{ ucfirst(auth()->user()->name) }}!</h2>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-primary bg-opacity-10 text-primary me-3">
                                    <i class="fas fa-user-tag me-1"></i> {{ ucfirst(auth()->user()->role) }}
                                </span>
                                <span class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i> {{ auth()->user()->village }},
                                    {{ auth()->user()->region }}
                                </span>
                            </div>
                        </div>
                        <div class="avatar avatar-lg">
                            <img src="{{ auth()->user()->avatar ?? asset('images/default-avatar.png') }}"
                                class="rounded-circle border-2" alt="User Avatar">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @php
            $user = auth()->user();
        @endphp

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            @if (in_array($user->role, ['admin', 'veo']))
                <!-- Admin/VEO Stats -->
                <div class="col-md-6 col-lg-3">
                    <div class="card stat-card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h3 class="fw-bold mb-2 text-success">{{ $articlesCount ?? 0 }}</h3>
                                    <p class="text-muted mb-0">Total Articles</p>
                                </div>
                                <div class="bg-success bg-opacity-10 p-3 rounded-4">
                                    <i class="fas fa-newspaper fa-lg text-success"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('articles.index') }}" class="btn btn-sm btn-outline-success w-100">
                                    View Articles <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($user->role === 'admin')
                    <div class="col-md-6 col-lg-3">
                        <div class="card stat-card border-0 shadow-sm h-100 hover-lift">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h3 class="fw-bold mb-2 text-info">{{ $usersCount ?? 0 }}</h3>
                                        <p class="text-muted mb-0">Active Users</p>
                                    </div>
                                    <div class="bg-info bg-opacity-10 p-3 rounded-4">
                                        <i class="fas fa-users fa-lg text-info"></i>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-info w-100">
                                        Manage Users <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="col-md-6 col-lg-3">
                    <div class="card stat-card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h3 class="fw-bold mb-2 text-warning">{{ $commentsCount ?? 0 }}</h3>
                                    <p class="text-muted mb-0">Comments</p>
                                </div>
                                <div class="bg-warning bg-opacity-10 p-3 rounded-4">
                                    <i class="fas fa-comments fa-lg text-warning"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('articles.index') }}" class="btn btn-sm btn-outline-warning w-100">
                                    View Comments <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="card stat-card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h3 class="fw-bold mb-2 text-danger">{{ $activitiesCount ?? 0 }}</h3>
                                    <p class="text-muted mb-0">Activities Today</p>
                                </div>
                                <div class="bg-danger bg-opacity-10 p-3 rounded-4">
                                    <i class="fas fa-chart-line fa-lg text-danger"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('activity-logs.index') }}" class="btn btn-sm btn-outline-danger w-100">
                                    View Activity <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($user->role === 'farmer')
                <!-- Farmer Stats -->
                <div class="col-md-4">
                    <div class="card stat-card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h3 class="fw-bold mb-2 text-success">{{ $relevantArticlesCount ?? 0 }}</h3>
                                    <p class="text-muted mb-0">Relevant Articles</p>
                                </div>
                                <div class="bg-success bg-opacity-10 p-3 rounded-4">
                                    <i class="fas fa-newspaper fa-lg text-success"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('articles.index') }}" class="btn btn-sm btn-outline-success w-100">
                                    Browse Articles <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card stat-card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h3 class="fw-bold mb-2 text-info">{{ $myCommentsCount ?? 0 }}</h3>
                                    <p class="text-muted mb-0">My Comments</p>
                                </div>
                                <div class="bg-info bg-opacity-10 p-3 rounded-4">
                                    <i class="fas fa-comments fa-lg text-info"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('profile.show') }}" class="btn btn-sm btn-outline-info w-100">
                                    View My Comments <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card stat-card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h3 class="fw-bold mb-2 text-warning">{{ $urgentArticlesCount ?? 0 }}</h3>
                                    <p class="text-muted mb-0">Urgent Articles</p>
                                </div>
                                <div class="bg-warning bg-opacity-10 p-3 rounded-4">
                                    <i class="fas fa-exclamation-circle fa-lg text-warning"></i>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('articles.index', ['urgent' => 1]) }}"
                                    class="btn btn-sm btn-outline-warning w-100">
                                    View Urgent <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Content Section -->
        <div class="row g-4">
            <!-- Recent Articles -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm recent-articles">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fas fa-newspaper text-primary me-2"></i>Recent Articles
                            </h5>
                            <a href="{{ route('articles.index') }}" class="btn btn-sm btn-outline-primary">
                                View All
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($recentArticles ?? [] as $article)
                                <a href="{{ route('articles.show', $article->id) }}"
                                    class="list-group-item list-group-item-action border-0 py-3 hover-bg-light">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1 me-3">
                                            <div class="d-flex align-items-center mb-1">
                                                <h6 class="mb-0">{{ $article->title }}</h6>
                                                @if ($article->is_urgent)
                                                    <span class="badge bg-danger bg-opacity-10 text-danger ms-2">
                                                        <i class="fas fa-exclamation-circle me-1"></i>Urgent
                                                    </span>
                                                @endif
                                            </div>
                                            <small class="text-muted d-block mb-2">
                                                <i class="fas fa-user me-1"></i> {{ $article->author->name }} •
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $article->created_at->diffForHumans() }}
                                            </small>
                                            <p class="mb-0 text-muted">{{ Str::limit($article->content, 120) }}</p>
                                        </div>
                                        <div class="badge bg-primary bg-opacity-10 text-primary">
                                            {{ ucfirst($article->category) }}
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="text-center py-4">
                                    <div class="bg-light rounded-circle p-4 d-inline-block mb-3">
                                        <i class="fas fa-newspaper fa-2x text-muted"></i>
                                    </div>
                                    <h5 class="fw-semibold">No recent articles</h5>
                                    <p class="text-muted">There are no articles to display</p>
                                    @if (auth()->user()->role === 'veo')
                                        <a href="{{ route('articles.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus me-1"></i> Create Article
                                        </a>
                                    @endif
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity or Quick Actions -->
            <div class="col-lg-4">
                @if (in_array($user->role, ['veo', 'admin']))
                    <!-- Recent Activity for VEO/Admin -->
                    <div class="card border-0 shadow-sm h-100 recent-activity">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fas fa-history text-primary me-2"></i>Recent Activity
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                @forelse($recentActivities ?? [] as $activity)
                                    <div class="list-group-item border-0 py-3 hover-bg-light">
                                        <div class="d-flex">
                                            <div class="avatar avatar-sm me-3">
                                                <img src="{{ $activity->user->avatar ?? asset('images/default-avatar.png') }}"
                                                    class="rounded-circle" alt="{{ $activity->user->name }}">
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between">
                                                    <strong>{{ $activity->user->name }}</strong>
                                                    <small
                                                        class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                                </div>
                                                <p class="mb-0 small text-muted">{{ $activity->action }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4">
                                        <div class="bg-light rounded-circle p-4 d-inline-block mb-3">
                                            <i class="fas fa-history fa-2x text-muted"></i>
                                        </div>
                                        <h5 class="fw-semibold">No recent activity</h5>
                                        <p class="text-muted">There are no activities to display</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 py-3">
                            <a href="{{ route('activity-logs.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                                View All Activity
                            </a>
                        </div>
                    </div>
                @else
                    <!-- Quick Actions for Farmers -->
                    <div class="card border-0 shadow-sm quick-action">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fas fa-bolt text-primary me-2"></i>Quick Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-3">
                                <a href="{{ route('chat.index') }}" class="btn btn-outline-primary text-start py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                                            <i class="fas fa-plus-circle text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">Ask a Question</div>
                                            <small class="text-muted">Get help from experts</small>
                                        </div>
                                    </div>
                                </a>
                                <a href="{{ route('articles.index', ['urgent' => 1]) }}"
                                    class="btn btn-outline-danger text-start py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-danger bg-opacity-10 p-2 rounded-3 me-3">
                                            <i class="fas fa-exclamation-triangle text-danger"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">Urgent Alerts</div>
                                            <small class="text-muted">View critical updates</small>
                                        </div>
                                    </div>
                                </a>
                                <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary text-start py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-secondary bg-opacity-10 p-2 rounded-3 me-3">
                                            <i class="fas fa-user-edit text-secondary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">Update Profile</div>
                                            <small class="text-muted">Keep your info current</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        body {
           background-color: #fff;
        }

        .welcome-card {
            border-radius: 12px;
            background-color: #fff;
            border-left: 4px solid var(--primary-color);
        }

        .avatar {
            width: 50px;
            height: 50px;
        }

        .avatar-sm {
            width: 36px;
            height: 36px;
        }

        .avatar-lg {
            width: 60px;
            height: 60px;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-color: var(--primary-color);
        }

        .stat-card {
            border-radius: 30px;
            padding: 20px 10px;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .stat-card a.btn {
            border-radius: 20px;
            padding: 10px 20px;
        }

        .recent-articles,
        .recent-activity,
        .quick-action {
            border-radius: 30px;
            padding: 20px;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .hover-bg-light:hover {
            background-color: rgba(76, 175, 80, 0.05) !important;
        }

        .badge {
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 6px;
        }

        .list-group-item {
            transition: all 0.2s ease;
        }

        .btn-outline-primary {
            border-radius: 8px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
        }

        .btn-primary:hover {
            background-color: #3d8b40;
        }

        /* Stat card border colors */
        .stat-card:nth-child(1) {
            border-left-color: var(--primary-color);
        }

        .stat-card:nth-child(2) {
            border-left-color: #17a2b8;
        }

        .stat-card:nth-child(3) {
            border-left-color: #ffc107;
        }

        .stat-card:nth-child(4) {
            border-left-color: #dc3545;
        }

        /* Text colors for stats */
        .text-success {
            color: var(--primary-color) !important;
        }

        .text-info {
            color: #17a2b8 !important;
        }

        .text-warning {
            color: #ffc107 !important;
        }

        .text-danger {
            color: #dc3545 !important;
        }
    </style>
@endsection
