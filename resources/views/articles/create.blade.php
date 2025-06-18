@extends('layouts.app')

@section('title', 'Create Article - Agricom')

@section('content')
<div class="row mt-4">
    <div class="col-12">
        <h2><i class="fas fa-plus"></i> Create New Article</h2>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('articles.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Category *</label>
                                <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                    <option value="">Select Category</option>
                                    <option value="disease" {{ old('category') == 'disease' ? 'selected' : '' }}>Disease Alert</option>
                                    <option value="pest" {{ old('category') == 'pest' ? 'selected' : '' }}>Pest Control</option>
                                    <option value="technology" {{ old('category') == 'technology' ? 'selected' : '' }}>New Technology</option>
                                    <option value="method" {{ old('category') == 'method' ? 'selected' : '' }}>Farming Methods</option>
                                    <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>General Knowledge</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Related Crop (Optional)</label>
                                <select name="crop" class="form-select @error('crop') is-invalid @enderror">
                                    <option value="">Select Crop (Optional)</option>
                                    <option value="maize" {{ old('crop') == 'maize' ? 'selected' : '' }}>Maize</option>
                                    <option value="rice" {{ old('crop') == 'rice' ? 'selected' : '' }}>Rice</option>
                                    <option value="beans" {{ old('crop') == 'beans' ? 'selected' : '' }}>Beans</option>
                                    <option value="cassava" {{ old('crop') == 'cassava' ? 'selected' : '' }}>Cassava</option>
                                    <option value="coffee" {{ old('crop') == 'coffee' ? 'selected' : '' }}>Coffee</option>
                                    <option value="cotton" {{ old('crop') == 'cotton' ? 'selected' : '' }}>Cotton</option>
                                    <option value="sunflower" {{ old('crop') == 'sunflower' ? 'selected' : '' }}>Sunflower</option>
                                    <option value="other" {{ old('crop') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('crop')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Featured Image</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" 
                               accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Supported formats: JPG, PNG, GIF. Max size: 2MB</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Content *</label>
                        <textarea name="content" class="form-control @error('content') is-invalid @enderror" 
                                  rows="10" required>{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_urgent" id="is_urgent" 
                                   {{ old('is_urgent') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_urgent">
                                Mark as Urgent (Disease/Pest Alert)
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('articles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Articles
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Publish Article
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6><i class="fas fa-info-circle"></i> Publishing Guidelines</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-check text-success"></i> Use clear, descriptive titles
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success"></i> Include relevant images when possible
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success"></i> Specify crop type for targeted advice
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success"></i> Mark urgent alerts for immediate attention
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success"></i> Write content in clear, simple language
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection