@extends('layouts.app')

@section('title', $article->title . ' - Agricom')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    @if ($article->image)
                        <img src="{{ asset('storage/' . $article->image) }}" class="card-img-top"
                            style="height: 300px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <span class="badge bg-primary">{{ ucfirst($article->category) }}</span>
                                @if ($article->crop)
                                    <span class="badge bg-success">{{ ucfirst($article->crop) }}</span>
                                @endif
                                @if ($article->is_urgent)
                                    <span class="badge bg-danger">URGENT</span>
                                @endif
                            </div>
                            <small class="text-muted">{{ $article->created_at->format('M d, Y \a\t g:i A') }}</small>
                        </div>

                        <h1 class="card-title">{{ $article->title }}</h1>

                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-user"></i> By {{ $article->author->name }}
                                ({{ ucfirst($article->author->role) }})
                                @if ($article->author->village)
                                    from {{ $article->author->village }}, {{ $article->author->region }}
                                @endif
                            </small>
                        </div>

                        <div class="article-content">
                            {!! nl2br(e($article->content)) !!}
                        </div>

                        <div class="mt-4 pt-3 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted">
                                        <i class="fas fa-eye"></i> {{ $article->views_count ?? 0 }} views
                                        <i class="fas fa-comments ms-3"></i> {{ $article->comments->count() }} comments
                                    </span>
                                </div>
                                <div>
                                    @if (auth()->user()->id === $article->user_id)
                                        <a href="{{ route('articles.edit', $article->id) }}"
                                            class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    @endif
                                    <button class="btn btn-outline-primary btn-sm" onclick="shareArticle()">
                                        <i class="fas fa-share"></i> Share
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5><i class="fas fa-comments"></i> Comments ({{ $article->comments->count() }})</h5>
                    </div>
                    <div class="card-body">
                        <!-- Comment Form -->
                        <form method="POST" action="{{ route('articles.comments.store', $article->id) }}" class="mb-4">
                            @csrf
                            <div class="mb-3">
                                <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="3"
                                    placeholder="Share your thoughts or ask questions..." required>{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-comment"></i> Post Comment
                            </button>
                        </form>

                        <!-- Comments List -->
                        @forelse($article->comments as $comment)
                            <div class="comment mb-3 p-3 bg-light rounded">
                                <div class="d-flex justify-content-between mb-2">
                                    <strong>{{ $comment->user->name }}</strong>
                                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-0">{{ $comment->content }}</p>
                                @if ($comment->user->role === 'veo')
                                    <small class="badge bg-success">VEO Response</small>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-3 text-muted">
                                <i class="fas fa-comment fa-2x mb-2"></i>
                                <p>No comments yet. Be the first to comment!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-md-4">
                <!-- Related Articles -->
                <div class="card">
                    <div class="card-header">
                        <h6><i class="fas fa-newspaper"></i> Related Articles</h6>
                    </div>
                    <div class="card-body">
                        @forelse($relatedArticles ?? [] as $related)
                            <div class="mb-3 border-bottom pb-2">
                                <h6><a
                                        href="{{ route('articles.show', $related->id) }}">{{ Str::limit($related->title, 50) }}</a>
                                </h6>
                                <small class="text-muted">{{ $related->created_at->diffForHumans() }}</small>
                            </div>
                        @empty
                            <p class="text-muted">No related articles found.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Author Info -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6><i class="fas fa-user"></i> About the Author</h6>
                    </div>
                    <div class="card-body">
                        <h6>{{ $article->author->name }}</h6>
                        <p class="text-muted mb-1">{{ ucfirst($article->author->role) }}</p>
                        <p class="text-muted mb-2">{{ $article->author->village }}, {{ $article->author->region }}</p>
                        @if ($article->author->role === 'farmer' && $article->author->crops)
                            <div>
                                <small class="text-muted">Crops:</small>
                                @foreach (json_decode($article->author->crops) as $crop)
                                    <span class="badge bg-light text-dark">{{ ucfirst($crop) }}</span>
                                @endforeach
                            </div>
                        @endif
                        <button class="btn btn-outline-primary btn-sm mt-2"
                            onclick="startChat('{{ $article->author->id }}')">
                            <i class="fas fa-message"></i> Message Author
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <a href="{{ route('articles.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Articles
                </a>
            </div>
        </div>
    @endsection

    @section('scripts')
        <script>
            function shareArticle() {
                if (navigator.share) {
                    navigator.share({
                        title: '{{ $article->title }}',
                        text: '{{ Str::limit($article->content, 100) }}',
                        url: window.location.href
                    });
                } else {
                    // Fallback - copy to clipboard
                    navigator.clipboard.writeText(window.location.href).then(function() {
                        alert('Article link copied to clipboard!');
                    });
                }
            }

            function startChat(userId) {
                // Redirect to chat with specific user
                window.location.href = '/chat?user=' + userId;
            }
        </script>
    </div>
@endsection
