@extends('layouts.app')

@section('title', 'Articles - Agricom')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2><i class="fas fa-newspaper"></i> Articles</h2>
                    @if (auth()->user()->role === 'veo')
                        <a href="{{ route('articles.create') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Create Article
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('articles.index') }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Search articles..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <select name="crop" class="form-select">
                                        <option value="">All Crops</option>
                                        <option value="maize" {{ request('crop') == 'maize' ? 'selected' : '' }}>Maize
                                        </option>
                                        <option value="rice" {{ request('crop') == 'rice' ? 'selected' : '' }}>Rice
                                        </option>
                                        <option value="beans" {{ request('crop') == 'beans' ? 'selected' : '' }}>Beans
                                        </option>
                                        <option value="cassava" {{ request('crop') == 'cassava' ? 'selected' : '' }}>Cassava
                                        </option>
                                        <option value="coffee" {{ request('crop') == 'coffee' ? 'selected' : '' }}>Coffee
                                        </option>
                                        <option value="cotton" {{ request('crop') == 'cotton' ? 'selected' : '' }}>Cotton
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="category" class="form-select">
                                        <option value="">All Categories</option>
                                        <option value="disease" {{ request('category') == 'disease' ? 'selected' : '' }}>
                                            Disease Alert</option>
                                        <option value="pest" {{ request('category') == 'pest' ? 'selected' : '' }}>Pest
                                            Control</option>
                                        <option value="technology"
                                            {{ request('category') == 'technology' ? 'selected' : '' }}>New Technology
                                        </option>
                                        <option value="method" {{ request('category') == 'method' ? 'selected' : '' }}>
                                            Farming Methods</option>
                                        <option value="general" {{ request('category') == 'general' ? 'selected' : '' }}>
                                            General Knowledge</option>
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
                        @if ($article->image)
                            <img src="{{ asset('storage/' . $article->image) }}" class="card-img-top"
                                style="height: 200px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="badge bg-primary">{{ ucfirst($article->category) }}</span>
                                @if ($article->crop)
                                    <span class="badge bg-success">{{ ucfirst($article->crop) }}</span>
                                @endif
                            </div>
                            <h5 class="card-title">{{ $article->title }}</h5>
                            <p class="card-text">{{ Str::limit($article->content, 150) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    By {{ $article->author->name }}<br>
                                    {{ $article->created_at->format('M d, Y') }}
                                </small>
                                <div>
                                    <span class="badge bg-secondary">{{ $article->comments_count ?? 0 }} comments</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('articles.show', $article->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i> Read More
                            </a>
                            @if (auth()->user()->id === $article->user_id)
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
