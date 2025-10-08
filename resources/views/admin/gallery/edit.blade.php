@extends('layouts.admin')

@section('title', 'Edit Gallery Image')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h3 mb-1">Edit Gallery Image</h1>
        <p class="text-muted mb-0">{{ Str::limit($gallery->title, 60) }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.gallery.show', $gallery) }}" class="btn-minimal btn-primary">
            <i class="bi bi-eye"></i> View
        </a>
        <a href="{{ route('admin.gallery.index') }}" class="btn-minimal">
            <i class="bi bi-arrow-left"></i> {{ __('admin.back') }}
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card-minimal">
            <div class="card-body">
                <form action="{{ route('admin.gallery.update', $gallery) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="monument_id" class="form-label">Monument</label>
                        <div class="input-group">
                            <input type="text" class="form-control"
                                   value="{{ $gallery->monument->title }} ({{ $gallery->monument->zone }})"
                                   readonly>
                            <span class="input-group-text">
                                <i class="bi bi-lock"></i>
                            </span>
                        </div>
                        <input type="hidden" name="monument_id" value="{{ $gallery->monument_id }}">
                        <div class="form-text">
                            <i class="bi bi-info-circle"></i> {{ __('admin.monument_cannot_be_changed') }}
                            To move this image to another monument, delete and recreate it.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">{{ __('admin.image') }} {{ __('admin.title') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $gallery->title) }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">{{ __('admin.description') }}</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description', $gallery->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">{{ __('admin.image') }}</label>
                        @if($gallery->image_path)
                            <div class="mb-3">
                                <img src="{{ $gallery->image_url }}"
                                     alt="{{ $gallery->title }}"
                                     class="img-thumbnail" style="max-width: 300px; max-height: 200px;">
                                <div class="form-text">{{ __('admin.current_image') }}</div>
                            </div>
                        @endif
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="bi bi-info-circle"></i> Supported formats: JPEG, PNG, JPG, GIF. Max size: 10MB
                            <br><i class="bi bi-arrow-clockwise"></i> {{ __('admin.leave_empty_keep_current') }}
                        </div>
                        <div id="imagePreview" class="mt-2"></div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <button type="submit" class="btn-minimal btn-primary">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            Save Changes
                        </button>
                        <a href="{{ route('admin.gallery.index') }}" class="btn-minimal">
                            <i class="bi bi-arrow-left me-2"></i>
                            Back to Gallery
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card-minimal">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle text-primary"></i> Image Information
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="d-flex align-items-center p-2 bg-light rounded">
                            <i class="bi bi-building text-primary me-2"></i>
                            <div>
                                <small class="text-muted d-block">Monument</small>
                                <span class="fw-semibold">{{ $gallery->monument->title }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center p-2 bg-light rounded">
                            <i class="bi bi-geo-alt text-success me-2"></i>
                            <div>
                                <small class="text-muted d-block">Zone</small>
                                <span class="badge bg-success">{{ $gallery->monument->zone }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center p-2 bg-light rounded">
                            <i class="bi bi-calendar-plus text-info me-2"></i>
                            <div>
                                <small class="text-muted d-block">Created</small>
                                <span class="fw-semibold">{{ $gallery->created_at->format('M d, Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center p-2 bg-light rounded">
                            <i class="bi bi-arrow-clockwise text-warning me-2"></i>
                            <div>
                                <small class="text-muted d-block">Last Updated</small>
                                <span class="fw-semibold">{{ $gallery->updated_at->format('M d, Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-minimal">
            <div class="card-header bg-white border-0 pb-2">
                <h6 class="mb-0 text-muted">
                    <i class="bi bi-gear"></i> Actions
                </h6>
            </div>
            <div class="card-body pt-2">
                <div class="d-grid gap-3">
                    <!-- View Image -->
                    <a href="{{ route('admin.gallery.show', $gallery) }}"
                       class="btn btn-outline-primary btn-lg text-start border-2 hover-lift">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2">
                                    <i class="bi bi-eye text-primary"></i>
                                </div>
                            </div>
                            <div>
                                <div class="fw-bold">View Image</div>
                                <small class="text-muted">{{ __('admin.see_full_details') }}</small>
                            </div>
                        </div>
                    </a>

                    <!-- View Monument -->
                    <a href="{{ route('admin.monuments.show', $gallery->monument) }}"
                       class="btn btn-outline-success btn-lg text-start border-2 hover-lift">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <div class="bg-success bg-opacity-10 rounded-circle p-2">
                                    <i class="bi bi-building text-success"></i>
                                </div>
                            </div>
                            <div>
                                <div class="fw-bold">View Monument</div>
                                <small class="text-muted">{{ Str::limit($gallery->monument->title, 25) }}</small>
                            </div>
                        </div>
                    </a>

                    <!-- Delete -->
                    <form action="{{ route('admin.gallery.destroy', $gallery) }}"
                          method="POST"
                          onsubmit="return confirm('⚠️ Are you sure you want to delete this image?\n\nThis action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-lg text-start border-2 w-100 hover-lift">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="bg-danger bg-opacity-10 rounded-circle p-2">
                                        <i class="bi bi-trash text-danger"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="fw-bold">Delete Image</div>
                                    <small class="text-muted">{{ __('admin.permanently_remove') }}</small>
                                </div>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.hover-lift {
    transition: all 0.3s ease;
}
.hover-lift:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
}
.btn-outline-primary:hover .bg-primary {
    background-color: var(--bs-primary) !important;
}
.btn-outline-primary:hover .text-primary {
    color: white !important;
}
.btn-outline-success:hover .bg-success {
    background-color: var(--bs-success) !important;
}
.btn-outline-success:hover .text-success {
    color: white !important;
}
.btn-outline-danger:hover .bg-danger {
    background-color: var(--bs-danger) !important;
}
.btn-outline-danger:hover .text-danger {
    color: white !important;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const previewContainer = document.getElementById('imagePreview');

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Check file size
            if (file.size > 10 * 1024 * 1024) {
                alert('File size must be less than 10MB');
                this.value = '';
                previewContainer.innerHTML = '';
                return;
            }

            // Check file type
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                alert('Please select a valid image file (JPEG, PNG, JPG, GIF)');
                this.value = '';
                previewContainer.innerHTML = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                previewContainer.innerHTML = `
                    <div class="border rounded p-2">
                        <img src="${e.target.result}" class="img-fluid rounded" style="max-height: 200px;">
                        <div class="mt-2">
                            <small class="text-muted">
                                <strong>{{ __('admin.new_image_preview') }}</strong><br>
                                Size: ${(file.size / 1024 / 1024).toFixed(2)} MB
                            </small>
                        </div>
                    </div>
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
