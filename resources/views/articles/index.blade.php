@extends('layouts.app')

@section('title', 'Articles - Agricom')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-header d-flex justify-content-between align-items-center bg-white p-4 rounded-3 shadow-sm">
                    <div>
                        <h3 class="fw-bold text-secondary mb-0">
                            <i class="fas fa-newspaper me-2"></i>Knowledge Center
                        </h3>
                        <p class="text-muted mb-0">Browse agricultural articles and resources</p>
                    </div>
                    @if (auth()->user()->role === 'veo')
                        <a href="{{ route('articles.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Create Article
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form method="GET" action="{{ route('articles.index') }}">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent border-end-0">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" name="search" class="form-control border-start-0"
                                            placeholder="Search articles..." value="{{ request('search') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <select name="crop" class="form-select">
                                        <option value="">All Crops</option>
                                        @foreach ($crops as $crop)
                                            <option value="{{ $crop->id }}" {{ request('crop') == $crop->id ? 'selected' : '' }}>
                                                {{ ucfirst($crop->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="category" class="form-select">
                                        <option value="">All Categories</option>
                                        <option value="disease_management" {{ request('category') == 'disease_management' ? 'selected' : '' }}>
                                            Disease Management
                                        </option>
                                        <option value="pest_control" {{ request('category') == 'pest_control' ? 'selected' : '' }}>
                                            Pest Control
                                        </option>
                                        <option value="farming_techniques" {{ request('category') == 'farming_techniques' ? 'selected' : '' }}>
                                            Farming Techniques
                                        </option>
                                        <option value="weather" {{ request('category') == 'weather' ? 'selected' : '' }}>
                                            Weather
                                        </option>
                                        <option value="market_prices" {{ request('category') == 'market_prices' ? 'selected' : '' }}>
                                            Market Prices
                                        </option>
                                        <option value="general" {{ request('category') == 'general' ? 'selected' : '' }}>
                                            General Knowledge
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-filter me-2"></i>Filter
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Articles Grid -->
        <div class="row g-4">
            @forelse($articles ?? [] as $article)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm article-card">
                        @if ($article->featured_image)
                            <div class="card-img-top overflow-hidden" style="height: 180px;">
                                <img src="{{ asset('storage/' . $article->featured_image) }}" 
                                    class="img-fluid w-100 h-100 object-fit-cover"
                                    alt="{{ $article->title }}">
                            </div>
                        @endif
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                    {{ ucfirst($article->category) }}
                                </span>
                                
                                @foreach ($article->target_crops ?? [] as $cropId)
                                    @php
                                        $crop = $crops->firstWhere('id', $cropId);
                                    @endphp
                                    @if ($crop)
                                        <span class="badge bg-success bg-opacity-10 text-success">
                                            {{ ucfirst($crop->name) }}
                                        </span>
                                    @endif
                                @endforeach
                                
                                @if($article->is_urgent)
                                    <span class="badge bg-danger bg-opacity-10 text-danger">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Urgent
                                    </span>
                                @endif
                            </div>
                            
                            <h5 class="card-title fw-semibold mb-3">{{ $article->title }}</h5>
                            <p class="card-text text-muted mb-4">{{ Str::limit($article->content, 120) }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <img src="{{ $article->author->avatar ?? asset('images/default-avatar.png') }}" 
                                            class="rounded-circle" alt="{{ $article->author->name }}">
                                    </div>
                                    <div>
                                        <small class="d-block fw-semibold">{{ $article->author->name }}</small>
                                        <small class="text-muted">{{ $article->created_at->format('M d, Y') }}</small>
                                    </div>
                                </div>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                    <i class="fas fa-comment me-1"></i>{{ $article->comments->count() }}
                                </span>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 pt-0 pb-3 px-4">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('articles.show', $article->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-2"></i>Read More
                                </a>
                                @if (auth()->id() === $article->author_id)
                                    <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit me-2"></i>Edit
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-3 my-0">
                        <div class="bg-light rounded-circle p-4 d-inline-block mb-3">
                            <i class="fas fa-newspaper fa-3x text-muted"></i>
                        </div>
                        <h4 class="fw-semibold">No Articles Found</h4>
                        <p class="text-muted mb-4">There are no articles matching your criteria.</p>
                        @if (auth()->user()->role === 'veo')
                            <a href="{{ route('articles.create') }}" class="btn btn-primary px-4">
                                <i class="fas fa-plus me-2"></i>Create First Article
                            </a>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if (isset($articles) && $articles->hasPages())
            <div class="row mt-5">
                <div class="col-12">
                    <nav aria-label="Page navigation">
                        {{ $articles->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </nav>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('styles')
<style>
    :root {
        --primary-color: #4CAF50;
        --secondary-color: #8BC34A;
        --light-bg: #f8f9fa;
        --dark-text: #2c3e50;
        --light-text: #7f8c8d;
    }
    
    body {
        background-color: #f5f7fb;
    }
    
    .page-header {
        border-radius: 12px;
    }
    
    .card {
        border-radius: 12px;
        transition: all 0.3s ease;
        border: none;
        overflow: hidden;
    }
    
    .article-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .avatar {
        width: 32px;
        height: 32px;
    }
    
    .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .badge {
        font-weight: 500;
        padding: 5px 10px;
        border-radius: 6px;
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        padding: 10px 15px;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(76, 175, 80, 0.25);
    }
    
    .input-group-text {
        background-color: transparent;
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
    }
    
    .btn-primary:hover {
        background-color: #3d8b40;
    }
    
    .btn-outline-primary {
        border-radius: 8px;
    }
    
    .pagination .page-item.active .page-link {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .pagination .page-link {
        color: var(--primary-color);
    }
    
    .object-fit-cover {
        object-fit: cover;
    }
</style>
@endsection