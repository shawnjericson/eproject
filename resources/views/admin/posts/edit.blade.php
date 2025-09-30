@extends('layouts.admin')

@section('title', 'Edit Post')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h3 mb-1">{{ __('admin.edit') }} Post</h1>
        <p class="text-muted mb-0">{{ Str::limit($post->title, 60) }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.posts.show', $post) }}" class="btn-minimal btn-primary">
            <i class="bi bi-eye"></i> {{ __('admin.view') }}
        </a>
        <a href="{{ route('admin.posts.index') }}" class="btn-minimal">
            <i class="bi bi-arrow-left"></i> {{ __('admin.back') }}
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card-minimal">
            <div class="card-body">
                <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label">{{ __('admin.title') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $post->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">content <span class="text-danger">*</span></label>
                        <textarea class="form-control ckeditor @error('content') is-invalid @enderror"
                                  id="content" name="content" rows="15" required>{{ old('content', $post->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Featured {{ __('admin.image') }}</label>
                        @if($post->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $post->image) }}" 
                                     alt="{{ $post->title }}" 
                                     class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                <div class="form-text">Current image</div>
                            </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB
                            @if($post->image)
                                <br>Leave empty to keep current image.
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label">{{ __('admin.status') }} <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" name="status" required>
                            <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>{{ __('admin.draft') }}</option>
                            <option value="pending" {{ old('status', $post->status) == 'pending' ? 'selected' : '' }}>{{ __('admin.pending') }}</option>
                            @if(auth()->user()->isAdmin())
                                <option value="approved" {{ old('status', $post->status) == 'approved' ? 'selected' : '' }}>{{ __('admin.approved') }}</option>
                                <option value="rejected" {{ old('status', $post->status) == 'rejected' ? 'selected' : '' }}>{{ __('admin.rejected') }}</option>
                            @endif
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn-minimal btn-primary">Update Post</button>
                        <a href="{{ route('admin.posts.index') }}" class="btn-minimal">{{ __('admin.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card-minimal">
            <div class="card-header">
                <h5>Post Information</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li><strong>{{ __('admin.created_at') }} by:</strong> {{ $post->creator->name }}</li>
                    <li><strong>{{ __('admin.created_at') }} at:</strong> {{ $post->created_at->format('M d, Y H:i') }}</li>
                    <li><strong>Updated at:</strong> {{ $post->updated_at->format('M d, Y H:i') }}</li>
                    <li><strong>Current {{ __('admin.status') }}:</strong> 
                        @if($post->status == 'approved')
                            <span class="badge bg-success">{{ __('admin.approved') }}</span>
                        @elseif($post->status == 'pending')
                            <span class="badge bg-warning">{{ __('admin.pending') }}</span>
                        @elseif($post->status == 'rejected')
                            <span class="badge bg-danger">{{ __('admin.rejected') }}</span>
                        @else
                            <span class="badge bg-secondary">{{ __('admin.draft') }}</span>
                        @endif
                    </li>
                    @if($post->published_at)
                        <li><strong>{{ __('admin.published') }} at:</strong> {{ $post->published_at->format('M d, Y H:i') }}</li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="card-minimal mt-3">
            <div class="card-header">
                <h5>Quick {{ __('admin.actions') }}</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($post->status !== 'approved' && auth()->user()->isAdmin())
                        <form action="{{ route('admin.posts.approve', $post) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-minimal btn-success w-100">
                                <i class="bi bi-check"></i> Approve Post
                            </button>
                        </form>
                    @endif

                    @if($post->status !== 'rejected' && auth()->user()->isAdmin())
                        <form action="{{ route('admin.posts.reject', $post) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-minimal btn-warning w-100">
                                <i class="bi bi-x"></i> Reject Post
                            </button>
                        </form>
                    @endif

                    <hr>

                    <form action="{{ route('admin.posts.destroy', $post) }}"
                          method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this post? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-minimal btn-danger w-100">
                            <i class="bi bi-trash"></i> {{ __('admin.delete') }} Post
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5>Publishing Guidelines</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled small">
                    <li><i class="bi bi-check-circle text-success"></i> Use descriptive titles</li>
                    <li><i class="bi bi-check-circle text-success"></i> Include relevant images</li>
                    <li><i class="bi bi-check-circle text-success"></i> Write engaging content</li>
                    <li><i class="bi bi-check-circle text-success"></i> Check for spelling errors</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize CKEditor for all textarea elements with class 'tinymce-editor'
    document.querySelectorAll('.ckeditor').forEach(function(textarea) {
        ClassicEditor
            .create(textarea, {
                toolbar: {
                    items: [
                        'heading', '|',
                        'bold', 'italic', 'underline', '|',
                        'link', 'insertImage', '|',
                        'bulletedList', 'numberedList', '|',
                        'outdent', 'indent', '|',
                        'blockQuote', 'insertTable', '|',
                        'undo', 'redo'
                    ]
                },
                image: {
                    toolbar: [
                        'imageStyle:inline',
                        'imageStyle:block',
                        'imageStyle:side',
                        '|',
                        'toggleImageCaption',
                        'imageTextAlternative'
                    ]
                },
                table: {
                    contentToolbar: [
                        'tableColumn',
                        'tableRow',
                        'mergeTableCells'
                    ]
                }
            })
            .then(editor => {
                // Store editor instance for form submission
                textarea.ckeditorInstance = editor;

                // Update textarea value when editor content changes
                editor.model.document.on('change:data', () => {
                    textarea.value = editor.getData();
                });
            })
            .catch(error => {
                console.error('CKEditor initialization error:', error);
            });
    });

    // Image preview for new uploads
    const imageInput = document.getElementById('image');
    const previewContainer = document.createElement('div');
    previewContainer.className = 'mt-2';
    imageInput.parentNode.appendChild(previewContainer);

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewContainer.innerHTML = `
                    <img src="${e.target.result}" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                    <div class="form-text">New image preview</div>
                `;
            };
            reader.readAsDataURL(file);
        } else {
            previewContainer.innerHTML = '';
        }
    });
});
</script>
@endpush
@endsection
