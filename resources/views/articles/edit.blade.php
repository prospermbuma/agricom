@extends('layouts.app')

@section('title', 'Edit Article - Agricom')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2><i class="fas fa-edit"></i> Edit Article</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('articles.index') }}">Articles</a></li>
                        <li class="breadcrumb-item"><a
                                href="{{ route('articles.show', $article->id) }}">{{ Str::limit($article->title, 30) }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-edit"></i> Edit Article
                            @if ($article->is_urgent)
                                <span class="badge badge-danger ms-2">Urgent</span>
                            @endif
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('articles.update', $article->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Title *</label>
                                <input type="text" name="title"
                                    class="form-control @error('title') is-invalid @enderror"
                                    value="{{ old('title', $article->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Category *</label>
                                        <select name="category" class="form-select @error('category') is-invalid @enderror"
                                            required>
                                            <option value="">Select Category</option>
                                            <option value="disease_management"
                                                {{ old('category', $article->category) == 'disease_management' ? 'selected' : '' }}>
                                                Disease Management</option>
                                            <option value="pest_control"
                                                {{ old('category', $article->category) == 'pest_control' ? 'selected' : '' }}>
                                                Pest Control</option>
                                            <option value="farming_techniques"
                                                {{ old('category', $article->category) == 'farming_techniques' ? 'selected' : '' }}>
                                                Farming Techniques</option>
                                            <option value="weather"
                                                {{ old('category', $article->category) == 'weather' ? 'selected' : '' }}>
                                                Weather</option>
                                            <option value="market_prices"
                                                {{ old('category', $article->category) == 'market_prices    ' ? 'selected' : '' }}>
                                                Market Price</option>
                                            <option value="general"
                                                {{ old('category', $article->category) == 'general' ? 'selected' : '' }}>
                                                General Knowledge</option>
                                        </select>
                                        @error('category')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Related Crops (Optional)</label>
                                        <select name="target_crops[]"
                                            class="form-select @error('target_crops') is-invalid @enderror" multiple>
                                            @foreach ($crops as $crop)
                                                <option value="{{ $crop->id }}"
                                                    {{ collect(old('target_crops', $article->target_crops))->contains($crop->id) ? 'selected' : '' }}>
                                                    {{ ucfirst($crop->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('target_crops')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Featured Image</label>
                                @if ($article->image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $article->image) }}" alt="Current featured image"
                                            class="img-thumbnail" style="max-height: 200px;">
                                        <div class="mt-1">
                                            <small class="text-muted">Current image</small>
                                            <button type="button" class="btn btn-sm btn-outline-danger ms-2"
                                                onclick="removeCurrentImage()">
                                                <i class="fas fa-trash"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                @endif
                                <input type="file" name="image"
                                    class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                <input type="hidden" name="remove_image" id="remove_image" value="0">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    {{ $article->image ? 'Upload a new image to replace the current one.' : 'Supported formats: JPG, PNG, GIF. Max size: 2MB' }}
                                </small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Content *</label>
                                <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="12" required>{{ old('content', $article->content) }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_urgent" id="is_urgent"
                                        {{ old('is_urgent', $article->is_urgent) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_urgent">
                                        Mark as Urgent (Disease/Pest Alert)
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="{{ route('articles.show', $article->id) }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Back to Article
                                    </a>
                                    <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-list"></i> All Articles
                                    </a>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Article
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6><i class="fas fa-info-circle"></i> Article Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <strong>Created:</strong> {{ $article->created_at->format('M d, Y H:i') }}<br>
                                <strong>Updated:</strong> {{ $article->updated_at->format('M d, Y H:i') }}<br>
                                <strong>Author:</strong> {{ $article->user->name ?? 'Unknown' }}<br>
                                <strong>Status:</strong>
                                <span class="badge badge-{{ $article->is_published ? 'success' : 'warning' }}">
                                    {{ $article->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h6><i class="fas fa-lightbulb"></i> Editing Tips</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-check text-success"></i> Review content for accuracy before updating
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success"></i> Update images if information has changed
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success"></i> Check category and crop relevance
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success"></i> Mark as urgent only for critical alerts
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success"></i> Ensure content remains farmer-friendly
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6><i class="fas fa-bolt"></i> Quick Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('articles.show', $article->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i> Preview Article
                            </a>
                            @if ($article->is_published)
                                <form method="POST" action="{{ route('articles.unpublish', $article->id) }}"
                                    class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-warning w-100"
                                        onclick="return confirm('Are you sure you want to unpublish this article?')">
                                        <i class="fas fa-eye-slash"></i> Unpublish
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('articles.publish', $article->id) }}"
                                    class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-success w-100">
                                        <i class="fas fa-globe"></i> Publish Now
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function removeCurrentImage() {
                if (confirm('Are you sure you want to remove the current image?')) {
                    document.getElementById('remove_image').value = '1';
                    // Hide the current image preview
                    document.querySelector('.img-thumbnail').style.display = 'none';
                    document.querySelector('.img-thumbnail').nextElementSibling.style.display = 'none';

                    // Show confirmation message
                    const imageContainer = document.querySelector('.img-thumbnail').parentElement;
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-warning alert-sm';
                    alertDiv.innerHTML =
                        '<i class="fas fa-exclamation-triangle"></i> Image will be removed when you save the article.';
                    imageContainer.appendChild(alertDiv);
                }
            }

            // Auto-save draft functionality (optional)
            let autoSaveInterval;
            const form = document.querySelector('form');
            const titleInput = document.querySelector('input[name="title"]');
            const contentTextarea = document.querySelector('textarea[name="content"]');

            function enableAutoSave() {
                autoSaveInterval = setInterval(() => {
                    // You can implement auto-save functionality here
                    console.log('Auto-save could be implemented here');
                }, 30000); // Every 30 seconds
            }

            // Uncomment to enable auto-save
            // enableAutoSave();

            // Warning for unsaved changes
            let formChanged = false;
            const formElements = form.querySelectorAll('input, select, textarea');

            formElements.forEach(element => {
                element.addEventListener('change', () => {
                    formChanged = true;
                });
            });

            window.addEventListener('beforeunload', (e) => {
                if (formChanged) {
                    e.preventDefault();
                    e.returnValue = '';
                }
            });

            form.addEventListener('submit', () => {
                formChanged = false;
            });
        </script>
    @endpush
@endsection
