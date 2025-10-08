@extends('layouts.admin')

@section('title', 'Add Gallery Image')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">{{ __('admin.add_new') }} Gallery {{ __('admin.image') }}</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> {{ __('admin.back') }} to Gallery
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="monument_id" class="form-label">Monument <span class="text-danger">*</span></label>
                        <select class="form-select @error('monument_id') is-invalid @enderror" 
                                id="monument_id" name="monument_id">
                            <option value="">Select Monument</option>
                            @foreach($monuments as $monument)
                                <option value="{{ $monument->id }}" 
                                        {{ old('monument_id', request('monument_id')) == $monument->id ? 'selected' : '' }}>
                                    {{ $monument->title }} ({{ $monument->zone }})
                                </option>
                            @endforeach
                        </select>
                        @error('monument_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">{{ __('admin.image') }} {{ __('admin.title') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">{{ __('admin.description') }}</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">{{ __('admin.optional_description') }}</div>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">{{ __('admin.image') }} <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB</div>
                        <div id="imagePreview" class="mt-2"></div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">Add {{ __('admin.image') }}</button>
                        <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary">{{ __('admin.cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5>{{ __('admin.image') }} Guidelines</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li><i class="bi bi-check-circle text-success"></i> Use high-quality images</li>
                    <li><i class="bi bi-check-circle text-success"></i> {{ __('admin.choose_descriptive_titles') }}</li>
                    <li><i class="bi bi-check-circle text-success"></i> {{ __('admin.add_relevant_descriptions') }}</li>
                    <li><i class="bi bi-check-circle text-success"></i> Ensure images are relevant to the monument</li>
                </ul>
                
                <hr>
                
                <h6>{{ __('admin.image') }} Requirements:</h6>
                <ul class="list-unstyled small">
                    <li><strong>Formats:</strong> JPEG, PNG, JPG, GIF</li>
                    <li><strong>Max Size:</strong> 2MB</li>
                    <li><strong>Recommended:</strong> High resolution</li>
                    <li><strong>Aspect Ratio:</strong> Any (will be cropped for display)</li>
                </ul>
            </div>
        </div>

        @if($monuments->count() > 0)
            <div class="card mt-3">
                <div class="card-header">
                    <h5>Available Monuments</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($monuments->take(5) as $monument)
                            <div class="list-group-item px-0 py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ Str::limit($monument->title, 25) }}</h6>
                                        <small class="text-muted">{{ $monument->zone }}</small>
                                    </div>
                                    <span class="badge bg-info">{{ $monument->gallery->count() }} images</span>
                                </div>
                            </div>
                        @endforeach
                        @if($monuments->count() > 5)
                            <div class="list-group-item px-0 py-2 text-center">
                                <small class="text-muted">And {{ $monuments->count() - 5 }} more monuments...</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const previewContainer = document.getElementById('imagePreview');

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Check file size
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
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
                                <strong>File:</strong> ${file.name}<br>
                                <strong>Size:</strong> ${(file.size / 1024 / 1024).toFixed(2)} MB<br>
                                <strong>Type:</strong> ${file.type}
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

    // Auto-generate title from filename
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const titleInput = document.getElementById('title');
        
        if (file && !titleInput.value) {
            // Remove extension and format filename
            let filename = file.name.replace(/\.[^/.]+$/, "");
            filename = filename.replace(/[-_]/g, ' ');
            filename = filename.replace(/\b\w/g, l => l.toUpperCase());
            titleInput.value = filename;
        }
    });
});
</script>
@endpush
@endsection
