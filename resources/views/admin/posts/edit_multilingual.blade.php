@extends('layouts.admin')

@section('title', 'Edit Post')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-2">
                <i class="bi bi-file-text text-primary me-2"></i>Edit Post: {{ Str::limit($post->title, 50) }}
            </h1>
            <p class="text-muted mb-0">Update post content and translations</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.posts.show', $post) }}" class="btn btn-modern-secondary">
                <i class="bi bi-eye me-2"></i>View
            </a>
            <a href="{{ route('admin.posts.index') }}" class="btn btn-modern-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Posts
            </a>
        </div>
    </div>
</div>

    <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data" id="postForm">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-lg-8">
                <!-- Language Tabs -->
                <div class="modern-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-translate text-primary me-2"></i>{{ __('admin.multilingual_content') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Language Tabs -->
                        <ul class="nav nav-tabs" id="languageTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="en-tab" data-bs-toggle="tab" data-bs-target="#en-content" type="button" role="tab">
                                    <i class="bi bi-flag"></i> {{ __('admin.english') }}
                                    @if($post->translation('en'))
                                        <span class="badge bg-success ms-1">✓</span>
                                    @else
                                        <span class="badge bg-secondary ms-1">{{ __('admin.empty') }}</span>
                                    @endif
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="vi-tab" data-bs-toggle="tab" data-bs-target="#vi-content" type="button" role="tab">
                                    <i class="bi bi-flag"></i> {{ __('admin.vietnamese') }}
                                    @if($post->translation('vi'))
                                        <span class="badge bg-success ms-1">✓</span>
                                    @else
                                        <span class="badge bg-secondary ms-1">{{ __('admin.empty') }}</span>
                                    @endif
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content mt-3" id="languageTabContent">
                            <!-- English Content -->
                            <div class="tab-pane fade show active" id="en-content" role="tabpanel">
                                <div class="mb-3">
                                    <label for="en_title" class="form-label">{{ __('admin.title_english') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('translations.0.title') is-invalid @enderror" id="en_title"
                                           name="translations[0][title]"
                                           value="{{ old('translations.0.title', $post->translation('en')->title ?? '') }}"
                                           placeholder="{{ __('admin.placeholder_title_en') }}">
                                    <input type="hidden" name="translations[0][language]" value="en">
                                    @error('translations.0.title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="en_description" class="form-label">{{ __('admin.description_english') }}</label>
                                    <textarea class="form-control @error('translations.0.description') is-invalid @enderror" id="en_description"
                                              name="translations[0][description]" rows="3"
                                              placeholder="{{ __('admin.placeholder_description_en') }}">{{ old('translations.0.description', $post->translation('en')->description ?? '') }}</textarea>
                                    @error('translations.0.description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="en_content" class="form-label">{{ __('admin.content_english') }} <span class="text-danger">*</span></label>
                                    <textarea class="form-control ckeditor @error('translations.0.content') is-invalid @enderror" id="en_content"
                                              name="translations[0][content]" rows="15">{{ old('translations.0.content', $post->translation('en')->content ?? '') }}</textarea>
                                    @error('translations.0.content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Vietnamese Content -->
                            <div class="tab-pane fade" id="vi-content" role="tabpanel">
                                <div class="mb-3">
                                    <label for="vi_title" class="form-label">{{ __('admin.title_vietnamese') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('translations.1.title') is-invalid @enderror" id="vi_title"
                                           name="translations[1][title]"
                                           value="{{ old('translations.1.title', $post->translation('vi')->title ?? '') }}"
                                           placeholder="{{ __('admin.placeholder_title_vi') }}">
                                    <input type="hidden" name="translations[1][language]" value="vi">
                                    @error('translations.1.title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="vi_description" class="form-label">{{ __('admin.description_vietnamese') }}</label>
                                    <textarea class="form-control @error('translations.1.description') is-invalid @enderror" id="vi_description"
                                              name="translations[1][description]" rows="3"
                                              placeholder="{{ __('admin.placeholder_description_vi') }}">{{ old('translations.1.description', $post->translation('vi')->description ?? '') }}</textarea>
                                    @error('translations.1.description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="vi_content" class="form-label">{{ __('admin.content_vietnamese') }} <span class="text-danger">*</span></label>
                                    <textarea class="form-control ckeditor @error('translations.1.content') is-invalid @enderror" id="vi_content"
                                              name="translations[1][content]" rows="15">{{ old('translations.1.content', $post->translation('vi')->content ?? '') }}</textarea>
                                    @error('translations.1.content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Featured Image -->
                <div class="modern-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-image text-primary me-2"></i>Featured Image
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($post->image)
                            <div class="mb-3">
                                <label class="form-label">Current Image</label>
                                <div>
                                    <img src="{{ $post->image_url }}" alt="{{ __('admin.current_image') }}" class="img-thumbnail" style="max-width: 300px; max-height: 200px;">
                                </div>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="image" class="form-label">Upload New Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <div class="form-text">
                                <i class="bi bi-info-circle"></i> 
                                Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB. 
                                {{ __('admin.leave_empty_keep_current') }}
                            </div>
                        </div>

                        <!-- Image Preview -->
                        <div id="imagePreview" style="display: none;">
                            <img id="previewImg" src="" alt="Preview" class="img-thumbnail" style="max-width: 300px; max-height: 200px;">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Publishing Options -->
                <div class="modern-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-gear text-primary me-2"></i>{{ __('admin.publishing_options') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="status" class="form-label">{{ __('admin.status') }} <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status">
                                <option value="">{{ __('admin.select_status') }}</option>
                                <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>{{ __('admin.draft') }}</option>
                                <option value="pending" {{ old('status', $post->status) == 'pending' ? 'selected' : '' }}>{{ __('admin.pending_review') }}</option>
                                @if(auth()->user()->role === 'admin')
                                    <option value="approved" {{ old('status', $post->status) == 'approved' ? 'selected' : '' }}>{{ __('admin.approve') }}</option>
                                @endif
                            </select>
                        </div>

                        @if($post->published_at)
                            <div class="mb-3">
                                <label class="form-label">{{ __('admin.published_date') }}</label>
                                <p class="form-control-plaintext">{{ $post->published_at->format('M d, Y H:i') }}</p>
                            </div>
                        @endif

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-modern-primary">
                                <i class="bi bi-check-circle me-2"></i>{{ __('admin.update_post') }}
                            </button>
                            <a href="{{ route('admin.posts.show', $post) }}" class="btn btn-modern-secondary">
                                <i class="bi bi-eye me-2"></i>{{ __('admin.view_post') }}
                            </a>
                            <a href="{{ route('admin.posts.index') }}" class="btn btn-modern-secondary">
                                <i class="bi bi-arrow-left me-2"></i>{{ __('admin.back_to_posts') }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Post Information -->
                <div class="modern-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-info-circle text-info me-2"></i>{{ __('admin.post_information') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <strong>{{ __('admin.created_by') }}:</strong> {{ $post->creator->name }}
                        </div>
                        <div class="mb-2">
                            <strong>{{ __('admin.created') }}:</strong> {{ $post->created_at->format('M d, Y H:i') }}
                        </div>
                        <div class="mb-2">
                            <strong>{{ __('admin.last_updated') }}:</strong> {{ $post->updated_at->format('M d, Y H:i') }}
                        </div>
                        <div class="mb-0">
                            <strong>{{ __('admin.current_status') }}:</strong> 
                            <span class="badge 
                                @if($post->status === 'approved') bg-success
                                @elseif($post->status === 'pending') bg-warning
                                @else bg-secondary @endif">
                                {{ __('admin.' . $post->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Translation Status -->
                <div class="modern-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-translate text-success me-2"></i>{{ __('admin.translation_status') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="bi bi-flag"></i> {{ __('admin.english') }}</span>
                            @if($post->translation('en'))
                                <span class="badge bg-success">{{ __('admin.complete') }}</span>
                            @else
                                <span class="badge bg-secondary">{{ __('admin.empty') }}</span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-flag"></i> {{ __('admin.vietnamese') }}</span>
                            @if($post->translation('vi'))
                                <span class="badge bg-success">{{ __('admin.complete') }}</span>
                            @else
                                <span class="badge bg-secondary">{{ __('admin.empty') }}</span>
                            @endif
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
.nav-tabs .nav-link {
    color: #495057;
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
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
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
});
</script>
@endpush
