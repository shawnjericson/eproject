@extends('layouts.admin')

@section('title', 'Create Monument')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-building text-primary"></i> Create New Monument
        </h1>
        <a href="{{ route('admin.monuments.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Monuments
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.monuments.store') }}" method="POST" enctype="multipart/form-data" id="monumentForm">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <!-- Language Selection -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-translate text-primary"></i> Language Selection
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="language" class="form-label">Primary Language <span class="text-danger">*</span></label>
                            <select class="form-select @error('language') is-invalid @enderror" id="language" name="language" required>
                                <option value="">Select Language</option>
                                <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>English</option>
                                <option value="vi" {{ old('language') == 'vi' ? 'selected' : '' }}>Tiếng Việt</option>
                            </select>
                            @error('language')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-info-circle text-primary"></i> Basic Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                   id="location" name="location" value="{{ old('location') }}">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="zone" class="form-label">Zone <span class="text-danger">*</span></label>
                            <select class="form-select @error('zone') is-invalid @enderror" id="zone" name="zone" required>
                                <option value="">Select Zone</option>
                                <option value="East" {{ old('zone') == 'East' ? 'selected' : '' }}>East</option>
                                <option value="North" {{ old('zone') == 'North' ? 'selected' : '' }}>North</option>
                                <option value="West" {{ old('zone') == 'West' ? 'selected' : '' }}>West</option>
                                <option value="South" {{ old('zone') == 'South' ? 'selected' : '' }}>South</option>
                                <option value="Central" {{ old('zone') == 'Central' ? 'selected' : '' }}>Central</option>
                            </select>
                            @error('zone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Coordinates -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="latitude" class="form-label">Latitude <small class="text-muted">(Optional)</small></label>
                                <input type="number" step="0.00000001" class="form-control @error('latitude') is-invalid @enderror"
                                       id="latitude" name="latitude" value="{{ old('latitude') }}"
                                       placeholder="e.g., 13.412469">
                                <small class="text-muted">Range: -90 to 90</small>
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="longitude" class="form-label">Longitude <small class="text-muted">(Optional)</small></label>
                                <input type="number" step="0.00000001" class="form-control @error('longitude') is-invalid @enderror"
                                       id="longitude" name="longitude" value="{{ old('longitude') }}"
                                       placeholder="e.g., 103.866986">
                                <small class="text-muted">Range: -180 to 180</small>
                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- World Wonder Checkbox -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_world_wonder" name="is_world_wonder" value="1" {{ old('is_world_wonder') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_world_wonder">
                                     <strong>Mark as World Wonder</strong>
                                    <br><small class="text-muted">This monument will be displayed in the special World Wonders section on the frontend</small>
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-file-text text-primary"></i> Content
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="history" class="form-label">History</label>
                            <textarea class="form-control ckeditor @error('history') is-invalid @enderror" 
                                      id="history" name="history" rows="8">{{ old('history') }}</textarea>
                            @error('history')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="monument_content" class="form-label">Main Content</label>
                            <textarea class="form-control ckeditor @error('content') is-invalid @enderror" 
                                      id="monument_content" name="content" rows="15">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Map Integration -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-geo-alt text-primary"></i> Map Integration
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="map_embed" class="form-label">Google Maps Embed Code</label>
                            <textarea class="form-control @error('map_embed') is-invalid @enderror" 
                                      id="map_embed" name="map_embed" rows="3" 
                                      placeholder="{{ __('admin.paste_google_maps_embed') }}">{{ old('map_embed') }}</textarea>
                            @error('map_embed')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <strong>How to get embed code:</strong><br>
                                1. Go to <a href="https://maps.google.com" target="_blank">Google Maps</a><br>
                                2. {{ __('admin.search_for_location') }}<br>
                                3. Click "Share" → "Embed a map"<br>
                                4. Copy the iframe code and paste here
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Featured Image -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-image text-primary"></i> Featured Image
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="featured_image" class="form-label">Upload Image</label>
                            <input type="file" class="form-control @error('featured_image') is-invalid @enderror"
                                   id="featured_image" name="featured_image" accept="image/*">
                            @error('featured_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum file size: 5MB. Supported formats: JPEG, PNG, GIF, WebP</div>
                        </div>
                        
                        <div id="imagePreview" style="display: none;">
                            <img id="previewImg" src="" alt="Preview" class="img-fluid rounded">
                        </div>
                    </div>
                </div>

                <!-- Gallery Images -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-images text-primary"></i> Gallery Images
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="gallery_images" class="form-label">Upload Multiple Images</label>
                            <input type="file" class="form-control @error('gallery_images.*') is-invalid @enderror"
                                   id="gallery_images" name="gallery_images[]" accept="image/*" multiple>
                            @error('gallery_images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">You can select multiple images at once. Max 5MB per image.</div>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-gear text-primary"></i> Settings
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending Review</option>
                                <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>{{ __('admin.approve') }}d</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Create Monument
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Multilingual Info -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-globe text-info"></i> Multilingual Support
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h6><i class="bi bi-lightbulb"></i> How it works:</h6>
                            <ul class="mb-0">
                                <li><strong>Step 1:</strong> Create monument in your primary language</li>
                                <li><strong>Step 2:</strong> After saving, use "Edit" to add translations</li>
                                <li><strong>Step 3:</strong> Each language has separate content</li>
                                <li><strong>Benefit:</strong> Clean database structure and easy management</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<link href="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.css" rel="stylesheet">
<style>
.ck-editor__editable {
    min-height: 300px;
}

/* Hide original textarea when CKEditor is active */
.ck-editor + textarea {
    display: none !important;
}

/* Ensure CKEditor is visible */
.ck.ck-editor {
    display: block !important;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let editors = {};

    // Initialize CKEditor for all textareas with ckeditor class
    document.querySelectorAll('.ckeditor').forEach(function(textarea) {
        ClassicEditor.create(textarea, {
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'link', '|',
                'bulletedList', 'numberedList', '|',
                'outdent', 'indent', '|',
                'blockQuote', 'insertTable', '|',
                'imageUpload', '|',
                'undo', 'redo'
            ],
            simpleUpload: {
                uploadUrl: '{{ route("admin.posts.upload-image") }}',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }
        })
            .then(editor => {
                editors[textarea.id] = editor;
                console.log('CKEditor initialized for:', textarea.id);

                // Remove required attribute from textarea since CKEditor handles it
                textarea.removeAttribute('required');

                // Sync data on editor change
                editor.model.document.on('change:data', () => {
                    textarea.value = editor.getData();
                });
            })
            .catch(error => {
                console.error('CKEditor initialization error:', error);
                // Fallback: keep textarea visible if CKEditor fails
                textarea.style.display = 'block';
            });
    });

    // Featured image preview
    const featuredImageInput = document.getElementById('featured_image');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    if (featuredImageInput && imagePreview && previewImg) {
        featuredImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                imagePreview.style.display = 'none';
            }
        });
    }

    // Form validation and CKEditor sync
    const form = document.getElementById('monumentForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Sync CKEditor data back to textareas before validation
            Object.keys(editors).forEach(function(editorId) {
                const editor = editors[editorId];
                const textarea = document.getElementById(editorId);
                if (editor && textarea) {
                    textarea.value = editor.getData();
                }
            });

            const language = document.getElementById('language').value;
            const title = document.getElementById('title').value;
            const zone = document.getElementById('zone').value;
            const status = document.getElementById('status').value;

            if (!language || !title || !zone || !status) {
                e.preventDefault();
                alert('Please fill in all required fields');
                return false;
            }

            console.log('Form submission data:', {
                language: language,
                title: title,
                zone: zone,
                status: status
            });
        });
    }
});
</script>
@endpush
