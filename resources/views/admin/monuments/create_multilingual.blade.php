@extends('layouts.admin')

@section('title', __('admin.create_new_monument'))

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-2">{{ __('admin.create_new_monument') }}
            </h1>
            <p class="text-muted mb-0">{{ __('admin.add_new_monument_to_heritage') }}</p>
        </div>
        <div>
            <a href="{{ route('admin.monuments.index') }}" class="btn btn-modern-secondary">
                <i class="bi bi-arrow-left me-2"></i>{{ __('admin.back_to_monuments') }}
            </a>
        </div>
    </div>
</div>

@if ($errors->any())
    <div class="modern-card mb-4">
        <div class="card-body">
            <div class="alert alert-danger mb-0">
                <h6 class="alert-heading">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ __('admin.please_fix_errors') }}:
                </h6>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

    <form action="{{ route('admin.monuments.store') }}" method="POST" enctype="multipart/form-data" id="monumentForm">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <!-- Language Information -->
                <div class="modern-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-translate text-primary me-2"></i>{{ __('admin.language_information') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-0">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle me-3 fs-4"></i>
                                <div>
                                    <h6 class="mb-1">{{ __('admin.monument_will_be_created_in') }}: <strong>{{ app()->getLocale() == 'en' ? __('admin.english') : __('admin.vietnamese') }}</strong></h6>
                                    <p class="mb-0 small">{{ __('admin.language_auto_set_hint') }}</p>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="language" value="{{ app()->getLocale() }}">
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="modern-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle text-primary me-2"></i>{{ __('admin.basic_information') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">{{ __('admin.title') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">{{ __('admin.location') }}</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                   id="location" name="location" value="{{ old('location') }}">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="zone" class="form-label">{{ __('admin.monument_zone') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('zone') is-invalid @enderror" id="zone" name="zone">
                                <option value="">{{ __('admin.select_zone') }}</option>
                                <option value="East" {{ old('zone') == 'East' ? 'selected' : '' }}>{{ __('admin.zones.east') }}</option>
                                <option value="North" {{ old('zone') == 'North' ? 'selected' : '' }}>{{ __('admin.zones.north') }}</option>
                                <option value="West" {{ old('zone') == 'West' ? 'selected' : '' }}>{{ __('admin.zones.west') }}</option>
                                <option value="South" {{ old('zone') == 'South' ? 'selected' : '' }}>{{ __('admin.zones.south') }}</option>
                                <option value="Central" {{ old('zone') == 'Central' ? 'selected' : '' }}>{{ __('admin.zones.central') }}</option>
                            </select>
                            @error('zone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Coordinates -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="latitude" class="form-label">{{ __('admin.latitude') }} <small class="text-muted">({{ __('admin.optional') }})</small></label>
                                <input type="number" step="0.00000001" class="form-control @error('latitude') is-invalid @enderror"
                                       id="latitude" name="latitude" value="{{ old('latitude') }}"
                                       placeholder="e.g., 13.412469">
                                <small class="text-muted">{{ __('admin.range') }}: -90 {{ __('admin.to') }} 90</small>
                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="longitude" class="form-label">{{ __('admin.longitude') }} <small class="text-muted">({{ __('admin.optional') }})</small></label>
                                <input type="number" step="0.00000001" class="form-control @error('longitude') is-invalid @enderror"
                                       id="longitude" name="longitude" value="{{ old('longitude') }}"
                                       placeholder="e.g., 103.866986">
                                <small class="text-muted">{{ __('admin.range') }}: -180 {{ __('admin.to') }} 180</small>
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
                                     <strong>{{ __('admin.mark_as_world_wonder') }}</strong>
                                    <br><small class="text-muted">{{ __('admin.world_wonder_hint') }}</small>
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">{{ __('admin.description') }}</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="modern-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-file-text text-primary me-2"></i>{{ __('admin.content') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        

                        <div class="mb-3">
                            <label for="monument_content" class="form-label">{{ __('admin.main_content') }}</label>
                            <textarea class="form-control ckeditor @error('content') is-invalid @enderror" 
                                      id="monument_content" name="content" rows="15">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Map Integration -->
                <!-- <div class="modern-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-geo-alt text-primary me-2"></i>{{ __('admin.map_integration') }}
                        </h5>
                    </div>
                        <div class="card-body">
                        <div class="mb-3">
                            <label for="map_embed" class="form-label">{{ __('admin.google_maps_embed_code') }}</label>
                            <textarea class="form-control @error('map_embed') is-invalid @enderror" 
                                      id="map_embed" name="map_embed" rows="3" 
                                      placeholder="{{ __('admin.paste_google_maps_embed') }}">{{ old('map_embed') }}</textarea>
                            @error('map_embed')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <strong>{{ __('admin.how_to_get_embed_code') }}:</strong><br>
                                1. {{ __('admin.go_to_google_maps') }}<br>
                                2. {{ __('admin.search_for_location') }}<br>
                                3. {{ __('admin.click_share_embed') }}<br>
                                4. {{ __('admin.copy_iframe_paste') }}
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>

            <div class="col-lg-4">
                <!-- Featured Image -->
                <div class="modern-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-image text-primary me-2"></i>{{ __('admin.featured_image') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="featured_image" class="form-label">{{ __('admin.upload_image') }}</label>
                            <input type="file" class="form-control @error('featured_image') is-invalid @enderror"
                                   id="featured_image" name="featured_image" accept="image/*">
                            @error('featured_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">{{ __('admin.max_file_size') }}: 5MB. {{ __('admin.supported_formats') }}: JPEG, PNG, GIF, WebP</div>
                        </div>
                        
                        <div id="imagePreview" style="display: none;">
                            <img id="previewImg" src="" alt="Preview" class="img-fluid rounded">
                        </div>
                    </div>
                </div>

                <!-- Gallery Images -->
                <div class="modern-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-images text-primary me-2"></i>{{ __('admin.gallery_images') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="gallery_images" class="form-label">{{ __('admin.upload_multiple_images') }}</label>
                            <input type="file" class="form-control @error('gallery_images.*') is-invalid @enderror"
                                   id="gallery_images" name="gallery_images[]" accept="image/*" multiple>
                            @error('gallery_images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">{{ __('admin.select_multiple_images_hint') }}. {{ __('admin.max_5mb_per_image') }}.</div>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="modern-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-gear text-primary me-2"></i>{{ __('admin.settings') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="save_as_draft" name="save_as_draft" value="1" {{ old('save_as_draft') ? 'checked' : '' }}>
                                <label class="form-check-label" for="save_as_draft">
                                    <strong>{{ __('admin.save_as_draft') }}</strong>
                                    <br><small class="text-muted">{{ __('admin.save_as_draft_hint') }}</small>
                                </label>
                            </div>
                            <input type="hidden" name="status" id="status" value="{{ old('save_as_draft') ? 'draft' : 'pending' }}">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-modern-primary" id="submitBtn">
                                <span class="btn-text">
                                    <i class="bi bi-save me-2"></i>{{ __('admin.create_monument') }}
                                </span>
                                <span class="btn-loading" style="display: none;">
                                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                    {{ __('admin.creating') }}... {{ __('admin.please_wait') }}
                                </span>
                            </button>
                            <small class="text-muted mt-2" id="uploadHint" style="display: none;">
                                <i class="bi bi-info-circle"></i> {{ __('admin.uploading_images_hint') }}
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Multilingual Info -->
                <div class="modern-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-globe text-info me-2"></i>{{ __('admin.multilingual_support') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h6><i class="bi bi-lightbulb"></i> {{ __('admin.how_it_works') }}:</h6>
                            <ul class="mb-0">
                                <li><strong>{{ __('admin.step_1') }}:</strong> {{ __('admin.create_monument_primary_language') }}</li>
                                <li><strong>{{ __('admin.step_2') }}:</strong> {{ __('admin.after_saving_use_edit') }}</li>
                                <li><strong>{{ __('admin.step_3') }}:</strong> {{ __('admin.each_language_separate_content') }}</li>
                                <li><strong>{{ __('admin.benefit') }}:</strong> {{ __('admin.clean_database_structure') }}</li>
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
    const submitBtn = document.getElementById('submitBtn');
    let isSubmitting = false;

    if (form) {
        form.addEventListener('submit', function(e) {
            // Prevent double submission
            if (isSubmitting) {
                e.preventDefault();
                return false;
            }

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
                alert("{{ __('admin.please_fill_required_fields') }}");
                return false;
            }

            // Check if there are gallery images
            const galleryInput = document.getElementById('gallery_images');
            const hasGalleryImages = galleryInput && galleryInput.files.length > 0;

            // Mark as submitting
            isSubmitting = true;

            // Disable button and show loading state
            submitBtn.disabled = true;
            submitBtn.querySelector('.btn-text').style.display = 'none';
            submitBtn.querySelector('.btn-loading').style.display = 'inline-block';

            // Show upload hint if there are images
            if (hasGalleryImages) {
                document.getElementById('uploadHint').style.display = 'block';
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
