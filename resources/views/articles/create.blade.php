@extends('layouts.app')

@section('title', 'Create Article - Agricom')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-header d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold text-secondary">
                        <i class="fas fa-plus-circle me-2"></i>Create New Article
                    </h4>
                    <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Articles
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Main Form Column -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-5">
                        <form method="POST" action="{{ route('articles.store') }}" enctype="multipart/form-data"
                            class="needs-validation" novalidate>
                            @csrf

                            <!-- Title Field -->
                            <div class="mb-4">
                                <label for="title" class="form-label fw-semibold">Title <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="title" name="title"
                                    class="form-control form-control-lg @error('title') is-invalid @enderror"
                                    value="{{ old('title') }}" placeholder="Enter article title...">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Category and Crops Row -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="category" class="form-label fw-semibold">Category <span
                                            class="text-danger">*</span></label>
                                    <select id="category" name="category"
                                        class="form-select @error('category') is-invalid @enderror">
                                        <option value="" disabled selected>Select Category</option>
                                        <option value="disease_management"
                                            {{ old('category') == 'disease_management' ? 'selected' : '' }}>
                                            Disease Management
                                        </option>
                                        <option value="pest_control"
                                            {{ old('category') == 'pest_control' ? 'selected' : '' }}>
                                            Pest Control
                                        </option>
                                        <option value="farming_techniques"
                                            {{ old('category') == 'farming_techniques' ? 'selected' : '' }}>
                                            Farming Techniques
                                        </option>
                                        <option value="weather" {{ old('category') == 'weather' ? 'selected' : '' }}>
                                            Weather
                                        </option>
                                        <option value="market_prices"
                                            {{ old('category') == 'market_prices' ? 'selected' : '' }}>
                                            Market Prices
                                        </option>
                                        <option value="general" {{ old('category') == 'general' ? 'selected' : '' }}>
                                            General Knowledge
                                        </option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="target_crops" class="form-label fw-semibold">Related Crops
                                        (Optional)</label>
                                    <select id="target_crops" name="target_crops[]"
                                        class="form-select select2-multiple @error('target_crops') is-invalid @enderror"
                                        multiple="multiple">
                                        @foreach ($crops as $crop)
                                            <option value="{{ $crop->id }}"
                                                {{ collect(old('target_crops'))->contains($crop->id) ? 'selected' : '' }}>
                                                {{ ucfirst($crop->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('target_crops')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Featured Image Upload -->
                            <div class="mb-4">
                                <label for="featured_image" class="form-label fw-semibold">Featured Image</label>
                                <div class="image-upload-container">
                                    <div class="image-preview mb-2" id="imagePreview">
                                        <img src="" alt="Image Preview" class="image-preview__image"
                                            style="display: none;">
                                        <span class="image-preview__default-text">No image selected</span>
                                    </div>
                                    <input type="file" id="featured_image" name="featured_image"
                                        class="form-control @error('featured_image') is-invalid @enderror" accept="image/*">
                                    @error('featured_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Supported formats: JPG, PNG, GIF. Max size:
                                        5MB</small>
                                </div>
                            </div>

                            <!-- Content Editor -->
                            <div class="mb-4">
                                <label for="content" class="form-label fw-semibold">Content <span
                                        class="text-danger">*</span></label>
                                <textarea id="content" name="content" class="form-control @error('content') is-invalid @enderror" rows="12"
                                    placeholder="Write your article content here...">{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-between align-items-center py-4">
                                <!-- Urgent Checkbox -->
                                <div class="">
                                    <div class="form-check form-switch d-flex justify-start align-items-center">
                                        <input class="form-check-input" type="checkbox" name="is_urgent" id="is_urgent"
                                            {{ old('is_urgent') ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold mt-1 ml-3" for="is_urgent">
                                            <i class="fas fa-exclamation-triangle text-warning"></i>
                                            Mark as Urgent (Disease/Pest Alert)
                                        </label>
                                    </div>
                                </div>

                                <!-- Published Checkbox -->
                                <div class="">
                                    <div class="form-check form-switch d-flex justify-start align-items-center">
                                        <input class="form-check-input" type="checkbox" name="is_published" id="is_published"
                                            {{ old('is_published', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold mt-1 ml-3" for="is_published">
                                            <i class="fas fa-globe text-success"></i>
                                            Publish Immediately
                                        </label>
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-paper-plane me-2"></i>Publish Article
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Guidelines Sidebar -->
            <div class="col-lg-4">
                <div class="card guideline shadow-sm border-0 sticky-top" style="top: 20px;">
                    <div class="card-header text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-lightbulb me-2"></i>Publishing Guidelines
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-start mb-3">
                            <div class="badge bg-success bg-opacity-10 text-success rounded-circle p-2 me-3">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Clear Titles</h6>
                                <p class="text-muted small mb-0">Use descriptive titles that clearly indicate the article
                                    content</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-3">
                            <div class="badge bg-success bg-opacity-10 text-success rounded-circle p-2 me-3">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Relevant Images</h6>
                                <p class="text-muted small mb-0">Include high-quality images to illustrate your points</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-3">
                            <div class="badge bg-success bg-opacity-10 text-success rounded-circle p-2 me-3">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Targeted Content</h6>
                                <p class="text-muted small mb-0">Specify crop types when providing targeted advice</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-3">
                            <div class="badge bg-warning bg-opacity-10 text-warning rounded-circle p-2 me-3">
                                <i class="fas fa-exclamation"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Urgent Alerts</h6>
                                <p class="text-muted small mb-0">Mark urgent for time-sensitive disease or pest alerts</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-start">
                            <div class="badge bg-info bg-opacity-10 text-info rounded-circle p-2 me-3">
                                <i class="fas fa-language"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Clear Language</h6>
                                <p class="text-muted small mb-0">Write in simple, accessible language for all farmers</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        .card {
            border-radius: 12px;
            border: none;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .guideline .card-header {
            background-color: #d4edda;
            color: #155724;
            padding: 20px 22px;
        }

        .guideline .card-header * {
            color: #155724;
        }

        .guideline .card-body {
            padding: 20px 22px;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            padding: 10px 15px;
            border: 1px solid #e0e0e0;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(76, 175, 80, 0.25);
        }

        .form-control-lg {
            font-size: 1.1rem;
        }

        .image-upload-container {
            position: relative;
        }

        .image-preview {
            width: 100%;
            height: 200px;
            border: 2px dashed #ddd;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background-color: #f8fafc;
        }

        .image-preview__image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-preview__default-text {
            color: #aaa;
            font-size: 0.9rem;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-switch .form-check-input {
            width: 3em;
            height: 1.5em;
        }

        .select2-container--default .select2-selection--multiple {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            min-height: 42px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: var(--primary-color);
            border: none;
            color: white;
            border-radius: 4px;
        }

        .page-header {
            padding: 1rem;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
        }

        .btn-primary:hover {
            background-color: #3d8b40;
        }

        .btn-outline-secondary {
            border-radius: 8px;
        }

        .badge {
            flex-shrink: 0;
        }
    </style>

@endsection

@section('scripts')
    <script>
        // Image Preview Functionality
        const imageUpload = document.getElementById('featured_image');
        const imagePreview = document.getElementById('imagePreview');
        const previewImage = imagePreview.querySelector('.image-preview__image');
        const previewDefaultText = imagePreview.querySelector('.image-preview__default-text');

        imageUpload.addEventListener('change', function() {
            const file = this.files[0];

            if (file) {
                const reader = new FileReader();

                previewDefaultText.style.display = "none";
                previewImage.style.display = "block";

                reader.addEventListener("load", function() {
                    previewImage.setAttribute("src", this.result);
                });

                reader.readAsDataURL(file);
            } else {
                previewDefaultText.style.display = null;
                previewImage.style.display = null;
                previewImage.setAttribute("src", "");
            }
        });

        // Initialize Select2 for multiple select
        $(document).ready(function() {
            $('.select2-multiple').select2({
                placeholder: "Select crops (optional)",
                allowClear: true
            });
        });

        // Form validation
        (function() {
            'use strict'

            var forms = document.querySelectorAll('.needs-validation')

            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
@endsection
