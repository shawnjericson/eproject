@extends('layouts.admin')

@section('title', 'Edit Post')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Edit Post: {{ $post->title ?? $post->title_vi }}</h4>
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
                <!-- Basic Information -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-info-circle text-primary"></i> Basic Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Language Selection -->
                        <div class="mb-3">
                            <label for="language" class="form-label">Language <span class="text-danger">*</span></label>
                            <select class="form-select @error('language') is-invalid @enderror" id="language" name="language" required>
                                <option value="">Select Language</option>
                                <option value="en" {{ old('language', $post->language) == 'en' ? 'selected' : '' }}>English</option>
                                <option value="vi" {{ old('language', $post->language) == 'vi' ? 'selected' : '' }}>Tiếng Việt</option>
                            </select>
                            @error('language')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- English Fields -->
                        <div id="english-fields" style="display: none;">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title (English) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $post->title) }}" placeholder="Enter post title in English">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Short Description (English)</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3" 
                                          placeholder="Brief summary of the post (optional)">{{ old('description', $post->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Vietnamese Fields -->
                        <div id="vietnamese-fields" style="display: none;">
                            <div class="mb-3">
                                <label for="title_vi" class="form-label">Tiêu đề (Tiếng Việt) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title_vi') is-invalid @enderror" 
                                       id="title_vi" name="title_vi" value="{{ old('title_vi', $post->title_vi) }}" placeholder="Nhập tiêu đề bài viết">
                                @error('title_vi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description_vi" class="form-label">Mô tả ngắn (Tiếng Việt)</label>
                                <textarea class="form-control @error('description_vi') is-invalid @enderror" 
                                          id="description_vi" name="description_vi" rows="3" 
                                          placeholder="Tóm tắt ngắn gọn về bài viết (tùy chọn)">{{ old('description_vi', $post->description_vi) }}</textarea>
                                @error('description_vi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Article Content -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-file-text text-primary"></i> Article Content
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- English Content -->
                        <div id="english-content" style="display: none;">
                            <div class="mb-3">
                                <label for="content" class="form-label">Content (English) <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('content') is-invalid @enderror" 
                                          id="content" name="content" rows="15">{{ old('content', $post->content) }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Vietnamese Content -->
                        <div id="vietnamese-content" style="display: none;">
                            <div class="mb-3">
                                <label for="content_vi" class="form-label">Nội dung (Tiếng Việt) <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('content_vi') is-invalid @enderror" 
                                          id="content_vi" name="content_vi" rows="15">{{ old('content_vi', $post->content_vi) }}</textarea>
                                @error('content_vi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                    <img src="{{ $post->image_url }}" alt="Current image" class="img-thumbnail" style="max-width: 300px; max-height: 200px;">
                                </div>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="image" class="form-label">Upload New Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-info-circle"></i> 
                                Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB. 
                                Leave empty to keep current image.
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
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="pending" {{ old('status', $post->status) == 'pending' ? 'selected' : '' }}>Pending Review</option>
                                @if(auth()->user()->role === 'admin')
                                    <option value="approved" {{ old('status', $post->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                @endif
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
</style>
@endpush

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let englishEditor, vietnameseEditor;
    
    // Language selection handler
    const languageSelect = document.getElementById('language');
    const englishFields = document.getElementById('english-fields');
    const vietnameseFields = document.getElementById('vietnamese-fields');
    const englishContent = document.getElementById('english-content');
    const vietnameseContent = document.getElementById('vietnamese-content');
    
    languageSelect.addEventListener('change', function() {
        const selectedLanguage = this.value;
        
        if (selectedLanguage === 'en') {
            englishFields.style.display = 'block';
            vietnameseFields.style.display = 'none';
            englishContent.style.display = 'block';
            vietnameseContent.style.display = 'none';
            
            // Initialize English editor
            if (!englishEditor) {
                ClassicEditor.create(document.querySelector('#content'))
                    .then(editor => {
                        englishEditor = editor;
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
            
            // Destroy Vietnamese editor if exists
            if (vietnameseEditor) {
                vietnameseEditor.destroy();
                vietnameseEditor = null;
            }
            
        } else if (selectedLanguage === 'vi') {
            englishFields.style.display = 'none';
            vietnameseFields.style.display = 'block';
            englishContent.style.display = 'none';
            vietnameseContent.style.display = 'block';
            
            // Initialize Vietnamese editor
            if (!vietnameseEditor) {
                ClassicEditor.create(document.querySelector('#content_vi'))
                    .then(editor => {
                        vietnameseEditor = editor;
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
            
            // Destroy English editor if exists
            if (englishEditor) {
                englishEditor.destroy();
                englishEditor = null;
            }
            
        } else {
            englishFields.style.display = 'none';
            vietnameseFields.style.display = 'none';
            englishContent.style.display = 'none';
            vietnameseContent.style.display = 'none';
            
            // Destroy both editors
            if (englishEditor) {
                englishEditor.destroy();
                englishEditor = null;
            }
            if (vietnameseEditor) {
                vietnameseEditor.destroy();
                vietnameseEditor = null;
            }
        }
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
    
    // Trigger language change on page load
    const currentLanguage = languageSelect.value;
    if (currentLanguage) {
        languageSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
