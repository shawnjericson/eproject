@extends('layouts.admin')

@section('title', 'Edit Monument')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-building text-primary"></i> Edit Monument: {{ $monument->title }}
        </h1>
        <div>
            <a href="{{ route('admin.monuments.show', $monument) }}" class="btn btn-info">
                <i class="bi bi-eye"></i> View
            </a>
            <a href="{{ route('admin.monuments.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Monuments
            </a>
        </div>
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

    <form action="{{ route('admin.monuments.update', $monument) }}" method="POST" enctype="multipart/form-data" id="monumentForm">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-8">
                <!-- Language Tabs -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-translate text-primary"></i> {{ __('admin.multilingual_content') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Language Tabs -->
                        <ul class="nav nav-tabs" id="languageTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="en-tab" data-bs-toggle="tab" data-bs-target="#en-content" type="button" role="tab">
                                    <i class="bi bi-flag"></i> English
                                    @if($monument->translation('en'))
                                        <span class="badge bg-success ms-1">✓</span>
                                    @else
                                        <span class="badge bg-secondary ms-1">Empty</span>
                                    @endif
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="vi-tab" data-bs-toggle="tab" data-bs-target="#vi-content" type="button" role="tab">
                                    <i class="bi bi-flag"></i> Tiếng Việt
                                    @if($monument->translation('vi'))
                                        <span class="badge bg-success ms-1">✓</span>
                                    @else
                                        <span class="badge bg-secondary ms-1">Empty</span>
                                    @endif
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content mt-3" id="languageTabContent">
                            <!-- English Content -->
                            <div class="tab-pane fade show active" id="en-content" role="tabpanel">
                                <input type="hidden" name="translations[en][language]" value="en">
                                
                                <div class="mb-3">
                                    <label for="title_en" class="form-label">{{ __('admin.title_english') }}</label>
                                    <input type="text" class="form-control" id="title_en" name="translations[en][title]" 
                                           value="{{ $monument->translation('en') ? $monument->translation('en')->title : '' }}">
                                </div>

                                <div class="mb-3">
                                    <label for="location_en" class="form-label">Location (English)</label>
                                    <input type="text" class="form-control" id="location_en" name="translations[en][location]" 
                                           value="{{ $monument->translation('en') ? $monument->translation('en')->location : '' }}">
                                </div>

                                <div class="mb-3">
                                    <label for="description_en" class="form-label">Description (English)</label>
                                    <textarea class="form-control" id="description_en" name="translations[en][description]" rows="3">{{ $monument->translation('en') ? $monument->translation('en')->description : '' }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="history_en" class="form-label">History (English)</label>
                                    <textarea class="form-control ckeditor" id="history_en" name="translations[en][history]" rows="8">{{ $monument->translation('en') ? $monument->translation('en')->history : '' }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="content_en" class="form-label">{{ __('admin.content_english') }}</label>
                                    <textarea class="form-control ckeditor" id="content_en" name="translations[en][content]" rows="15">{{ $monument->translation('en') ? $monument->translation('en')->content : '' }}</textarea>
                                </div>
                            </div>

                            <!-- Vietnamese Content (Default Language - from base monument data) -->
                            <div class="tab-pane fade" id="vi-content" role="tabpanel">
                                <input type="hidden" name="translations[vi][language]" value="vi">

                                <div class="mb-3">
                                    <label for="title_vi" class="form-label">Tiêu đề (Tiếng Việt) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('translations.vi.title') is-invalid @enderror" id="title_vi" name="translations[vi][title]"
                                           value="{{ old('translations.vi.title', $monument->title) }}">
                                    <small class="text-muted">This is the default language. Required field.</small>
                                    @error('translations.vi.title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="location_vi" class="form-label">Địa điểm (Tiếng Việt)</label>
                                    <input type="text" class="form-control @error('translations.vi.location') is-invalid @enderror" id="location_vi" name="translations[vi][location]"
                                           value="{{ old('translations.vi.location', $monument->location) }}">
                                    @error('translations.vi.location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description_vi" class="form-label">Mô tả (Tiếng Việt)</label>
                                    <textarea class="form-control @error('translations.vi.description') is-invalid @enderror" id="description_vi" name="translations[vi][description]" rows="3">{{ old('translations.vi.description', $monument->description) }}</textarea>
                                    @error('translations.vi.description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="history_vi" class="form-label">Lịch sử (Tiếng Việt)</label>
                                    <textarea class="form-control ckeditor @error('translations.vi.history') is-invalid @enderror" id="history_vi" name="translations[vi][history]" rows="8">{{ old('translations.vi.history', $monument->history) }}</textarea>
                                    @error('translations.vi.history')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="content_vi" class="form-label">Nội dung (Tiếng Việt)</label>
                                    <textarea class="form-control ckeditor @error('translations.vi.content') is-invalid @enderror" id="content_vi" name="translations[vi][content]" rows="15">{{ old('translations.vi.content', $monument->content) }}</textarea>
                                    @error('translations.vi.content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Basic Settings -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-gear text-primary"></i> Basic Settings
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="zone" class="form-label">Zone <span class="text-danger">*</span></label>
                                    <select class="form-select" id="zone" name="zone" required>
                                        <option value="East" {{ $monument->zone == 'East' ? 'selected' : '' }}>East</option>
                                        <option value="North" {{ $monument->zone == 'North' ? 'selected' : '' }}>North</option>
                                        <option value="West" {{ $monument->zone == 'West' ? 'selected' : '' }}>West</option>
                                        <option value="South" {{ $monument->zone == 'South' ? 'selected' : '' }}>South</option>
                                        <option value="Central" {{ $monument->zone == 'Central' ? 'selected' : '' }}>Central</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="draft" {{ $monument->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="pending" {{ $monument->status == 'pending' ? 'selected' : '' }}>Pending Review</option>
                                        <option value="approved" {{ $monument->status == 'approved' ? 'selected' : '' }}>{{ __('admin.approve') }}d</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Coordinates -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="latitude" class="form-label">Latitude <small class="text-muted">(Optional)</small></label>
                                <input type="number" step="0.00000001" class="form-control @error('latitude') is-invalid @enderror"
                                       id="latitude" name="latitude" value="{{ old('latitude', $monument->latitude) }}"
                                       placeholder="e.g., 13.412469">
                                <small class="text-muted">Range: -90 to 90</small>
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="longitude" class="form-label">Longitude <small class="text-muted">(Optional)</small></label>
                                <input type="number" step="0.00000001" class="form-control @error('longitude') is-invalid @enderror"
                                       id="longitude" name="longitude" value="{{ old('longitude', $monument->longitude) }}"
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
                                <input class="form-check-input" type="checkbox" id="is_world_wonder" name="is_world_wonder" value="1" {{ old('is_world_wonder', $monument->is_world_wonder) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_world_wonder">
                                    🌟 <strong>Mark as World Wonder</strong>
                                    <br><small class="text-muted">This monument will be displayed in the special World Wonders section on the frontend</small>
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="map_embed" class="form-label">Google Maps Embed Code</label>
                            <textarea class="form-control" id="map_embed" name="map_embed" rows="3" 
                                      placeholder="{{ __('admin.paste_google_maps_embed') }}">{{ $monument->map_embed }}</textarea>
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
                        @if($monument->image)
                            <div class="mb-3">
                                <img src="{{ $monument->image_url }}" alt="Current Image" class="img-fluid rounded">
                                <p class="text-muted mt-2">Current Image</p>
                            </div>
                        @endif
                        
                        <div class="mb-3">
                            <label for="featured_image" class="form-label">Upload New Image</label>
                            <input type="file" class="form-control" id="featured_image" name="featured_image" accept="image/*">
                            <div class="form-text">Maximum file size: 5MB. Supported formats: JPEG, PNG, GIF, WebP</div>
                        </div>
                        
                        <div id="imagePreview" style="display: none;">
                            <img id="previewImg" src="" alt="Preview" class="img-fluid rounded">
                        </div>
                    </div>
                </div>

                <!-- Translation Status -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-translate text-info"></i> Translation Status
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>English:</span>
                            @if($monument->translation('en'))
                                <span class="badge bg-success">Complete</span>
                            @else
                                <span class="badge bg-secondary">Empty</span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Vietnamese:</span>
                            @if($monument->translation('vi'))
                                <span class="badge bg-success">Complete</span>
                            @else
                                <span class="badge bg-secondary">Empty</span>
                            @endif
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update Monument
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Gallery Images -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-images text-primary"></i> Add Gallery Images
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="gallery_images" class="form-label">Upload Multiple Images</label>
                            <input type="file" class="form-control" id="gallery_images" name="gallery_images[]" accept="image/*" multiple>
                            <div class="form-text">You can select multiple images at once. Max 5MB per image.</div>
                        </div>
                        
                        @if($monument->gallery && $monument->gallery->count() > 0)
                            <div class="mt-3">
                                <h6>Current Gallery ({{ $monument->gallery->count() }} images)</h6>
                                <div class="row">
                                    @foreach($monument->gallery->take(4) as $image)
                                        <div class="col-6 mb-2">
                                            <img src="{{ $image->image_url }}" alt="Gallery" class="img-fluid rounded">
                                        </div>
                                    @endforeach
                                </div>
                                @if($monument->gallery->count() > 4)
                                    <p class="text-muted">... and {{ $monument->gallery->count() - 4 }} more</p>
                                @endif
                            </div>
                        @endif
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
.nav-tabs .nav-link.active {
    color: #007bff;
    font-weight: 600;
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
            })
            .catch(error => {
                console.error(error);
            });
    });

    // Image preview
    const imageInput = document.getElementById('featured_image');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    if (imageInput && imagePreview && previewImg) {
        imageInput.addEventListener('change', function(e) {
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
});
</script>
@endpush
