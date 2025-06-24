@extends('layouts.app')

@section('title', $article->title . ' - Agricom')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card border-0 rounded-4 shadow-sm">
                    @if ($article->featured_image)
                        <img src="{{ asset('storage/' . $article->featured_image) }}" class="card-img-top"
                            style="height: 300px; object-fit: cover;">
                    @endif
                    <div class="card-body pt-4 pb-5 px-5">
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <span
                                    class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $article->category)) }}</span>
                                @if ($article->target_crops)
                                    @foreach ($article->target_crops as $cropId)
                                        @php
                                            $crop = \App\Models\Crop::find($cropId);
                                        @endphp
                                        @if ($crop)
                                            <span class="badge bg-success">{{ ucfirst($crop->name) }}</span>
                                        @endif
                                    @endforeach
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
                                @if (
                                    $article->author->role === 'farmer' &&
                                        $article->author->farmerProfile &&
                                        is_object($article->author->farmerProfile->village))
                                    from {{ $article->author->farmerProfile->village->name }},
                                    {{ $article->author->farmerProfile->region->name }}
                                @elseif (
                                    $article->author->role === 'farmer' &&
                                        $article->author->farmerProfile &&
                                        is_string($article->author->farmerProfile->village))
                                    from {{ $article->author->farmerProfile->village }},
                                    {{ $article->author->farmerProfile->region->name }}
                                @elseif (
                                    $article->author->role === 'veo' &&
                                        $article->author->region_id &&
                                        $article->author->region &&
                                        is_object($article->author->region))
                                    from {{ $article->author->region->name }}
                                @elseif ($article->author->role === 'veo' && $article->author->region_id)
                                    @php
                                        \Log::info('Region debug', [
                                            'region_id' => $article->author->region_id,
                                            'region' => $article->author->region,
                                            'region_type' => gettype($article->author->region),
                                            'is_object' => is_object($article->author->region),
                                        ]);
                                    @endphp
                                    @if (is_string($article->author->region))
                                        from {{ $article->author->region }}
                                    @else
                                        from Unknown Region
                                    @endif
                                @endif
                            </small>
                        </div>

                        <div class="article-content">
                            {!! nl2br(e($article->content)) !!}
                        </div>

                        @if ($article->attachments)
                            <div class="mt-4">
                                <h6><i class="fas fa-paperclip"></i> Attachments</h6>
                                <div class="list-group">
                                    @foreach ($article->attachments as $attachment)
                                        @php
                                            $extension = strtolower(pathinfo($attachment, PATHINFO_EXTENSION));
                                            $filename = basename($attachment);

                                            $iconMap = [
                                                'pdf' => 'fas fa-file-pdf text-danger',
                                                'doc' => 'fas fa-file-word text-primary',
                                                'docx' => 'fas fa-file-word text-primary',
                                                'xls' => 'fas fa-file-excel text-success',
                                                'xlsx' => 'fas fa-file-excel text-success',
                                            ];
                                            $icon = $iconMap[$extension] ?? 'fas fa-file text-secondary';
                                        @endphp
                                        <a href="{{ asset('storage/' . $attachment) }}"
                                            class="list-group-item list-group-item-action d-flex align-items-center"
                                            target="_blank">
                                            <i class="{{ $icon }} me-2"></i>
                                            <span>{{ $filename }}</span>
                                            <small class="text-muted ms-auto">{{ strtoupper($extension) }}</small>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="mt-4 pt-3 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted">
                                        <i class="fas fa-eye"></i> {{ $article->views_count ?? 0 }} views
                                        <i class="fas fa-comments ms-3"></i> {{ $article->comments->count() }} comments
                                    </span>
                                </div>
                                <div>
                                    @if (auth()->user()->id === $article->author_id)
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
                <div class="card mt-4 border-0 rounded-4 shadow-sm">
                    <div class="card-header border-0 rounded-top-4 px-5 pt-3 pb-2">
                        <h5><i class="fas fa-comments"></i> Comments ({{ $article->comments->count() }})</h5>
                    </div>
                    <div class="card-body pt-4 pb-4 px-5">
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
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-header border-0 rounded-top-4 px-4 pt-3 pb-2">
                        <h6><i class="fas fa-newspaper"></i> Related Articles</h6>
                    </div>
                    <div class="card-body pt-4 pb-4 px-4">
                        @php
                            $relatedArticles = \App\Models\Article::published()
                                ->where('id', '!=', $article->id)
                                ->where('category', $article->category)
                                ->latest()
                                ->take(5)
                                ->get();
                        @endphp
                        @forelse($relatedArticles as $related)
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
                <div class="card mt-3 border-0 rounded-4 shadow-sm">
                    <div class="card-header border-0 rounded-top-4 px-4 pt-3 pb-2">
                        <h6><i class="fas fa-user"></i> About the Author</h6>
                    </div>
                    <div class="card-body pt-4 pb-4 px-4">
                        <h6>{{ $article->author->name }}</h6>
                        <p class="text-muted mb-1">{{ ucfirst($article->author->role) }}</p>
                        @if ($article->author->role === 'farmer' && $article->author->farmerProfile)
                            <p class="text-muted mb-2">
                                @if (is_string($article->author->farmerProfile->village))
                                    {{ $article->author->farmerProfile->village }},
                                @elseif (is_object($article->author->farmerProfile->village))
                                    {{ $article->author->farmerProfile->village->name }},
                                @endif
                                {{ $article->author->farmerProfile->region->name }}
                            </p>
                        @elseif (
                            $article->author->role === 'veo' &&
                                $article->author->region_id &&
                                $article->author->region &&
                                is_object($article->author->region))
                            <p class="text-muted mb-2">
                                {{ $article->author->region->name }}
                            </p>
                        @endif
                        @if (
                            $article->author->role === 'farmer' &&
                                $article->author->farmerProfile &&
                                $article->author->farmerProfile->farmerCrops &&
                                $article->author->farmerProfile->farmerCrops->count() > 0)
                            <div>
                                <small class="text-muted">Crops:</small>
                                @foreach ($article->author->farmerProfile->farmerCrops as $farmerCrop)
                                    @if ($farmerCrop->crop)
                                        <span
                                            class="badge bg-light text-dark">{{ ucfirst($farmerCrop->crop->name) }}</span>
                                    @endif
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

        <div class="row mt-4">
            <div class="col-12">
                <a href="{{ route('articles.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Articles
                </a>
            </div>
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
                    // Show success message
                    const successDiv = $(
                        '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                        '<i class="fas fa-check-circle me-1"></i> Article link copied to clipboard!' +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                        '</div>');
                    $('body').prepend(successDiv);

                    // Auto-hide after 5 seconds
                    setTimeout(function() {
                        successDiv.alert('close');
                    }, 5000);
                });
            }
        }

        function startChat(userId) {
            // Redirect to chat with specific user
            window.location.href = '/chat?user=' + userId;
        }
    </script>
@endsection

@section('styles')
    <style>
        body {
            background-color: #f5f7fb;
        }
    </style>
@endsection
