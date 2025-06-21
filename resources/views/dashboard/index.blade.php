@extends('layouts.app')

@section('title', 'Dashboard - Agricom')

@section('content')
    <div class="container dashboard-wrapper">
        <div class="row">
            <div class="col-12">
                <h2>Welcome, {{ auth()->user()->name }}!</h2>
                <p class="text-muted">{{ ucfirst(auth()->user()->role) }} from {{ auth()->user()->village }},
                    {{ auth()->user()->region }}</p>
            </div>
        </div>

        @php
            $user = auth()->user();
        @endphp

        {{-- Statistics Cards --}}
        <div class="row mt-4">
            @if (in_array($user->role, ['admin', 'veo']))
                <div class="col-md-3">
                    <div class="card bg-success text-white total-articles shadow-sm border-0">
                        <div class="card-body">
                            <h4>{{ $articlesCount ?? 0 }}</h4>
                            <p>Total Articles</p>
                            <i class="fas fa-newspaper fa-2x"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-info text-white active-users shadow-sm border-0">
                        <div class="card-body">
                            <h4>{{ $usersCount ?? 0 }}</h4>
                            <p>Active Users</p>
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-warning text-white comments shadow-sm border-0">
                        <div class="card-body">
                            <h4>{{ $commentsCount ?? 0 }}</h4>
                            <p>Comments</p>
                            <i class="fas fa-comments fa-2x"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-danger text-white activities-today shadow-sm border-0">
                        <div class="card-body">
                            <h4>{{ $activitiesCount ?? 0 }}</h4>
                            <p>Activities Today</p>
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                    </div>
                </div>
            @elseif($user->role === 'farmer')
                <div class="col-md-4">
                    <div class="card bg-success text-white relevant-articles shadow-sm border-0">
                        <div class="card-body">
                            <h4>{{ $relevantArticlesCount ?? 0 }}</h4>
                            <p>Relevant Articles</p>
                            <i class="fas fa-newspaper fa-2x"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-info text-white my-comments shadow-sm border-0">
                        <div class="card-body">
                            <h4>{{ $myCommentsCount ?? 0 }}</h4>
                            <p>My Comments</p>
                            <i class="fas fa-comments fa-2x"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-warning text-white urgent-articles shadow-sm border-0">
                        <div class="card-body">
                            <h4>{{ $urgentArticlesCount ?? 0 }}</h4>
                            <p>Urgent Articles</p>
                            <i class="fas fa-exclamation-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="row mt-4">
            {{-- Recent Articles (shown to all roles) --}}
            <div class="col-md-8">
                <div class="card recent-articles">
                    <div class="card-header">
                        <h5><i class="fas fa-newspaper"></i> Recent Articles</h5>
                    </div>
                    <div class="card-body">
                        @forelse($recentArticles ?? [] as $article)
                            <div class="mb-3 border-bottom pb-2">
                                <h6><a href="{{ route('articles.show', $article->id) }}">{{ $article->title }}</a></h6>
                                <small class="text-muted">
                                    By {{ $article->author->name }} - {{ $article->created_at->diffForHumans() }}
                                </small>
                                <p class="mb-0">{{ Str::limit($article->content, 150) }}</p>
                            </div>
                        @empty
                            <p class="text-muted">No articles available.</p>
                        @endforelse

                        <div class="text-center mt-3">
                            <a href="{{ route('articles.index') }}" class="btn btn-outline-primary">View All Articles</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Activity (only veo and admin) --}}
            @if (in_array($user->role, ['veo', 'admin']))
                <div class="col-md-4">
                    <div class="card recent-activity">
                        <div class="card-header">
                            <h5><i class="fas fa-history"></i> Recent Activity</h5>
                        </div>
                        <div class="card-body">
                            @forelse($recentActivities ?? [] as $activity)
                                <div class="activity-log mb-2">
                                    <small>
                                        <strong>{{ $activity->user->name }}</strong>
                                        {{ $activity->action }}
                                        <span class="text-muted">{{ $activity->created_at->diffForHumans() }}</span>
                                    </small>
                                </div>
                            @empty
                                <p class="text-muted">No recent activity.</p>
                            @endforelse

                            <div class="text-center mt-3">
                                <a href="{{ route('activity-logs.index') }}" class="btn btn-outline-secondary btn-sm">View
                                    All</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .dashboard-wrapper .relevant-articles,
        .dashboard-wrapper .urgent-articles,
        .dashboard-wrapper .total-articles,
        .dashboard-wrapper .active-users,
        .dashboard-wrapper .activities-today,
        .dashboard-wrapper .my-comments,
        .dashboard-wrapper .comments {
            border-radius: 20px;
            padding: 20px
        }

        .dashboard-wrapper .recent-articles,
        .dashboard-wrapper .recent-activity {
            border-radius: 20px;
        }

        .dashboard-wrapper .recent-articles .card-header,
        .dashboard-wrapper .recent-activity .card-header {
            padding: 20px 30px 12px 30px;
        }

        .dashboard-wrapper .recent-articles .card-body,
        .dashboard-wrapper .recent-activity .card-body {
            padding: 20px 30px 60px 30px;
        }
    </style>
@endsection
