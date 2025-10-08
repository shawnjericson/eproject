@extends('layouts.admin')

@section('title', 'Create New Post')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-2">
                <i class="bi bi-file-text text-primary me-2"></i>Create New Post
            </h1>
            <p class="text-muted mb-0">Create a new blog post or article</p>
        </div>
        <div>
            <a href="{{ route('admin.posts.index') }}" class="btn btn-modern-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Posts
            </a>
        </div>
    </div>
</div>

    <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" id="postForm">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <!-- Language Information -->
                <div class="modern-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-translate text-primary me-2"></i>Language Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-0">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle me-3 fs-4"></i>
                                <div>
                                    <h6 class="mb-1">Post will be created in: <strong>{{ app()->getLocale() == 'en' ? 'English' : 'Tiếng Việt' }}</strong></h6>
                                    <p class="mb-0 small">The language is automatically set based on your current interface language. You can add translations later by editing this post.</p>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="language" value="{{ app()->getLocale() }}">
                    </div>
                </div>

                <!-- Post Content -->
                <div class="modern-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-file-text text-primary me-2"></i>Post Content
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" placeholder="Enter post title" >
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Short Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Brief summary of the post (optional)">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="post_content" class="form-label">Content <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror"
                                      id="post_content" name="content" rows="15">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                        <div class="mb-3">
                            <label for="image" class="form-label">Upload Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                   id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maximum file size: 5MB. Supported formats: JPEG, PNG, GIF, WebP</div>
                            <div class="form-text">
                                <i class="bi bi-info-circle"></i> 
                                Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB. 
                                Images will be automatically uploaded to Cloudinary for better performance.
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
                            <i class="bi bi-gear text-primary me-2"></i>Publishing Options
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="save_as_draft" name="save_as_draft" value="1" {{ old('save_as_draft') ? 'checked' : '' }}>
                                <label class="form-check-label" for="save_as_draft">
                                    <strong>Save as Draft</strong>
                                    <br><small class="text-muted">If unchecked, the post will be saved as "Pending Review" for admin approval</small>
                                </label>
                            </div>
                            <input type="hidden" name="status" id="status" value="{{ old('save_as_draft') ? 'draft' : 'pending' }}">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-modern-primary" id="submitBtn">
                                <span class="btn-text">
                                    <i class="bi bi-check-circle me-2"></i>Create Post
                                </span>
                                <span class="btn-loading" style="display: none;">
                                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                    Creating... Please wait
                                </span>
                            </button>
                            <a href="{{ route('admin.posts.index') }}" class="btn btn-modern-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Back to Posts
                            </a>
                            <small class="text-muted mt-2" id="uploadHint" style="display: none;">
                                <i class="bi bi-info-circle"></i> Uploading image to Cloudinary... Please wait.
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Multilingual Info -->
                <!-- <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-globe text-info"></i> Multilingual Support
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h6><i class="bi bi-lightbulb"></i> How it works:</h6>
                            <ul class="mb-0">
                                <li><strong>Step 1:</strong> Create post in your primary language</li>
                                <li><strong>Step 2:</strong> After saving, use "Edit" to add translations</li>
                                <li><strong>Step 3:</strong> Each language has separate title, description, and content</li>
                                <li><strong>Benefit:</strong> Clean database structure and easy management</li>
                            </ul>
                        </div>
                    </div>
                </div> -->

                <!-- Help & Instructions -->
                <!-- <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-question-circle text-info"></i> Instructions
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-success">
                            <h6><i class="bi bi-check-circle"></i> Tips for creating great posts:</h6>
                            <ul class="mb-0">
                                <li><strong>Title:</strong> Keep it clear and engaging</li>
                                <li><strong>Description:</strong> Brief summary for previews</li>
                                <li><strong>Content:</strong> Use rich text editor for formatting</li>
                                <li><strong>Images:</strong> High-quality images improve engagement</li>
                                <li><strong>Status:</strong> Draft to save, Pending for review</li>
                            </ul>
                        </div>
                    </div>
                </div> -->
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
    let contentEditor;

    // Initialize CKEditor with error handling
    const contentTextarea = document.querySelector('#post_content');
    if (contentTextarea) {
        ClassicEditor.create(contentTextarea, {
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
                contentEditor = editor;
                console.log('CKEditor initialized successfully');

                // Remove required attribute from textarea since CKEditor handles it
                contentTextarea.removeAttribute('required');

                // Sync data on editor change
                editor.model.document.on('change:data', () => {
                    contentTextarea.value = editor.getData();
                });
            })
            .catch(error => {
                console.error('CKEditor initialization error:', error);
                // Fallback: keep textarea visible if CKEditor fails
                contentTextarea.style.display = 'block';
            });
    } else {
        console.error('Content textarea not found');
    }

    // Image preview
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    if (imageInput && imagePreview && previewImg) {
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            // chặn không cho up file ngoài images
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
    // const form = document.getElementById('postForm');
    // const submitBtn = document.getElementById('submitBtn');
    // let isSubmitting = false;

    // if (form) {
    //     form.addEventListener('submit', function(e) {
    //         // Prevent double submission
    //         if (isSubmitting) {
    //             e.preventDefault();
    //             return false;
    //         }

    //         // Sync CKEditor data back to textarea before validation
    //         if (contentEditor) {
    //             const editorData = contentEditor.getData();
    //             document.getElementById('post_content').value = editorData;
    //         }

    //         const language = document.getElementById('language').value;
    //         const title = document.getElementById('title').value;
    //         const content = document.getElementById('post_content').value;
    //         const status = document.getElementById('status').value;

    //         if (!language || !title || !content.trim() || !status) {
    //             e.preventDefault();
    //             alert('Please fill in all required fields');
    //             return false;
    //         }

    //         // Check if there's an image
    //         const imageInput = document.getElementById('image');
    //         const hasImage = imageInput && imageInput.files.length > 0;

    //         // Mark as submitting
    //         isSubmitting = true;

    //         // Disable button and show loading state
    //         submitBtn.disabled = true;
    //         submitBtn.querySelector('.btn-text').style.display = 'none';
    //         submitBtn.querySelector('.btn-loading').style.display = 'inline-block';

    //         // Show upload hint if there's an image
    //         if (hasImage) {
    //             document.getElementById('uploadHint').style.display = 'block';
    //         }

    //         console.log('Form submission data:', {
    //             language: language,
    //             title: title,
    //             content: content.substring(0, 50) + '...',
    //             status: status
    //         });
    //     });
    // }

    // Handle draft checkbox change
    document.getElementById('save_as_draft').addEventListener('change', function() {
        const statusField = document.getElementById('status');
        statusField.value = this.checked ? 'draft' : 'pending';
    });
});
</script>
@endpush
