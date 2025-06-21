@extends('layouts.app')

@section('title', 'Articles - Agricom')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-newspaper"></i> Articles</h2>
                @if (auth()->user()->role === 'veo')
                    <a href="{{ route('articles.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Create Article
                    </a>
                @endif
            </div>
        </div>

        <!-- Filter Section -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('articles.index') }}">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Search articles..." value="{{ request('search') }}">
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
                                        <option value="disease_management" {{ request('category') == 'disease_management' ? 'selected' : '' }}>Disease Management</option>
                                        <option value="pest_control" {{ request('category') == 'pest_control' ? 'selected' : '' }}>Pest Control</option>
                                        <option value="farming_techniques" {{ request('category') == 'farming_techniques' ? 'selected' : '' }}>Farming Techniques</option>
                                        <option value="weather" {{ request('category') == 'weather' ? 'selected' : '' }}>Weather</option>
                                        <option value="market_prices" {{ request('category') == 'market_prices' ? 'selected' : '' }}>Market Prices</option>
                                        <option value="general" {{ request('category') == 'general' ? 'selected' : '' }}>General</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search"></i> Filter
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Articles List -->
        <div class="row mt-4">
            @forelse($articles ?? [] as $article)
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        @if ($article->featured_image)
                            <img src="{{ asset('storage/' . $article->featured_image) }}" class="card-img-top"
                                style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge bg-primary">{{ ucfirst($article->category) }}</span>

                                {{-- Show crop badges --}}
                                @foreach ($article->target_crops ?? [] as $cropId)
                                    @php
                                        $crop = $crops->firstWhere('id', $cropId);
                                    @endphp
                                    @if ($crop)
                                        <span class="badge bg-success">{{ ucfirst($crop->name) }}</span>
                                    @endif
                                @endforeach
                            </div>

                            <h5 class="card-title">{{ $article->title }}</h5>
                            <p class="card-text">{{ Str::limit($article->content, 150) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    By {{ $article->author->name }}<br>
                                    {{ $article->created_at->format('M d, Y') }}
                                </small>
                                <div>
                                    <span class="badge bg-secondary">{{ $article->comments->count() }} comments</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <a href="{{ route('articles.show', $article->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i> Read More
                            </a>
                            @if (auth()->id() === $article->author_id)
                                <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                        <h4>No Articles Found</h4>
                        <p class="text-muted">There are no articles matching your criteria.</p>
                        @if (auth()->user()->role === 'veo')
                            <a href="{{ route('articles.create') }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> Create First Article
                            </a>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        @if (isset($articles) && $articles->hasPages())
            <div class="row mt-4">
                <div class="col-12">
                    {{ $articles->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection
