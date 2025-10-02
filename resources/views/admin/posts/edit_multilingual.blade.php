@extends('layouts.admin')

@section('title', 'Edit Post')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Edit Post: {{ $post->title }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}">Posts</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data" id="postForm">
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
                                    @if($post->translation('en'))
                                        <span class="badge bg-success ms-1">✓</span>
                                    @else
                                        <span class="badge bg-secondary ms-1">Empty</span>
                                    @endif
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="vi-tab" data-bs-toggle="tab" data-bs-target="#vi-content" type="button" role="tab">
                                    <i class="bi bi-flag"></i> Tiếng Việt
                                    @if($post->translation('vi'))
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
                                    <label for="vi_title" class="form-label">Tiêu đề (Tiếng Việt) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('translations.1.title') is-invalid @enderror" id="vi_title"
                                           name="translations[1][title]"
                                           value="{{ old('translations.1.title', $post->translation('vi')->title ?? '') }}"
                                           placeholder="Nhập tiêu đề tiếng Việt">
                                    <input type="hidden" name="translations[1][language]" value="vi">
                                    @error('translations.1.title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="vi_description" class="form-label">Mô tả ngắn (Tiếng Việt)</label>
                                    <textarea class="form-control @error('translations.1.description') is-invalid @enderror" id="vi_description"
                                              name="translations[1][description]" rows="3"
                                              placeholder="Tóm tắt ngắn gọn tiếng Việt">{{ old('translations.1.description', $post->translation('vi')->description ?? '') }}</textarea>
                                    @error('translations.1.description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="vi_content" class="form-label">Nội dung (Tiếng Việt) <span class="text-danger">*</span></label>
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
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-image text-primary"></i> Featured Image
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
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-gear text-primary"></i> Publishing Options
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="pending" {{ old('status', $post->status) == 'pending' ? 'selected' : '' }}>Pending Review</option>
                                @if(auth()->user()->role === 'admin')
                                    <option value="approved" {{ old('status', $post->status) == 'approved' ? 'selected' : '' }}>{{ __('admin.approve') }}d</option>
                                @endif
                            </select>
                        </div>

                        @if($post->published_at)
                            <div class="mb-3">
                                <label class="form-label">Published Date</label>
                                <p class="form-control-plaintext">{{ $post->published_at->format('M d, Y H:i') }}</p>
                            </div>
                        @endif

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Update Post
                            </button>
                            <a href="{{ route('admin.posts.show', $post) }}" class="btn btn-info">
                                <i class="bi bi-eye"></i> View Post
                            </a>
                            <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Posts
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Post Information -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-info-circle text-info"></i> Post Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <strong>Created by:</strong> {{ $post->creator->name }}
                        </div>
                        <div class="mb-2">
                            <strong>Created:</strong> {{ $post->created_at->format('M d, Y H:i') }}
                        </div>
                        <div class="mb-2">
                            <strong>Last updated:</strong> {{ $post->updated_at->format('M d, Y H:i') }}
                        </div>
                        <div class="mb-0">
                            <strong>Current status:</strong> 
                            <span class="badge 
                                @if($post->status === 'approved') bg-success
                                @elseif($post->status === 'pending') bg-warning
                                @else bg-secondary @endif">
                                {{ ucfirst($post->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Translation Status -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-translate text-success"></i> Translation Status
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="bi bi-flag"></i> English</span>
                            @if($post->translation('en'))
                                <span class="badge bg-success">Complete</span>
                            @else
                                <span class="badge bg-secondary">Empty</span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-flag"></i> Tiếng Việt</span>
                            @if($post->translation('vi'))
                                <span class="badge bg-success">Complete</span>
                            @else
                                <span class="badge bg-secondary">Empty</span>
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
