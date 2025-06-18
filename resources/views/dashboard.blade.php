@extends('layouts.app')

@section('title', 'Dashboard - Agricom')

@section('content')
<div class="row mt-4">
    <div class="col-12">
        <h2>Welcome, {{ auth()->user()->name }}!</h2>
        <p class="text-muted">{{ ucfirst(auth()->user()->role) }} from {{ auth()->user()->village }}, {{ auth()->user()->region }}</p>
    </div>
</div>

<div class="row mt-4">
    <!-- Statistics Cards -->
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h4>{{ $articlesCount ?? 0 }}</h4>
                <p>Total Articles</p>
                <i class="fas fa-newspaper fa-2x"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h4>{{ $usersCount ?? 0 }}</h4>
                <p>Active Users</p>
                <i class="fas fa-users fa-2x"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h4>{{ $commentsCount ?? 0 }}</h4>
                <p>Comments</p>
                <i class="fas fa-comments fa-2x"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h4>{{ $activitiesCount ?? 0 }}</h4>
                <p>Activities Today</p>
                <i class="fas fa-chart-line fa-2x"></i>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Recent Articles -->
    <div class="col-md-8">
        <div class="card">
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
    
    <!-- Recent Activity -->
    <div class="col-md-4">
        <div class="card">
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
                    <a href="{{ route('activity.logs') }}" class="btn btn-outline-secondary btn-sm">View All</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection