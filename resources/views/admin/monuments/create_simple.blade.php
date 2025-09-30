@extends('layouts.admin')

@section('title', 'Create Professional Monument Article')

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h3 mb-1">{{ __('admin.create_new_monument') }}</h1>
        <p class="text-muted mb-0">Create comprehensive educational content about heritage monuments</p>
    </div>
    <a href="{{ route('admin.monuments.index') }}" class="btn-minimal">
        {{ __('admin.back_to_list') }}
    </a>
</div>

<form action="{{ route('admin.monuments.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">

            <!-- Basic Information -->
            <div class="editor-section">
                <div class="editor-section-header">
                    Basic Information
                </div>
                <div class="editor-section-body">
                    <!-- Language Selection -->
                    <div class="mb-3">
                        <label for="language" class="form-label">Language <span class="text-danger">*</span></label>
                        <select class="form-select @error('language') is-invalid @enderror" id="language" name="language">
                            <option value="en" {{ old('language', 'en') == 'en' ? 'selected' : '' }}>English</option>
                            <option value="vi" {{ old('language') == 'vi' ? 'selected' : '' }}>Tiếng Việt</option>
                        </select>
                        @error('language')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Monument Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Monument Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               id="title" name="title" value="{{ old('title') }}"
                               placeholder="e.g., Angkor Wat – Kỳ quan bất tử của nền văn minh Khmer">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Zone Selection -->
                    <div class="mb-3">
                        <label for="zone" class="form-label">Geographic Zone <span class="text-danger">*</span></label>
                        <select class="form-select @error('zone') is-invalid @enderror" id="zone" name="zone">
                            <option value="">Select Zone</option>
                            <option value="North" {{ old('zone') == 'North' ? 'selected' : '' }}>North</option>
                            <option value="South" {{ old('zone') == 'South' ? 'selected' : '' }}>South</option>
                            <option value="East" {{ old('zone') == 'East' ? 'selected' : '' }}>East</option>
                            <option value="West" {{ old('zone') == 'West' ? 'selected' : '' }}>{{ __('admin.west') }}</option>
                        </select>
                        @error('zone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- English Fields -->
                    <div id="english-fields">
                        <h6 class="text-primary mb-3">
                            <i class="bi bi-flag"></i> English Content
                        </h6>
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" 
                                   placeholder="Enter monument title in English">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control ckeditor @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="12"
                                      placeholder="Brief description of the monument">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-info-circle"></i> Use the rich text editor to format your description with headings, bold, italic, images, etc.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="history" class="form-label">History <span class="text-danger">*</span></label>
                            <textarea class="form-control ckeditor @error('history') is-invalid @enderror"
                                      id="history" name="history" rows="10"
                                      placeholder="Historical background and significance">{{ old('history') }}</textarea>
                            @error('history')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-info-circle"></i> Add historical context, dates, and important events.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="location" name="location" 
                                   value="{{ old('location') }}" placeholder="Address or location details">
                        </div>
                    </div>

                    <!-- Vietnamese Fields -->
                    <div id="vietnamese-fields" style="display: none;">
                        <h6 class="text-success mb-3">
                            <i class="bi bi-flag"></i> Vietnamese Content
                        </h6>
                        
                        <div class="mb-3">
                            <label for="title_vi" class="form-label">Tên di tích <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title_vi') is-invalid @enderror" 
                                   id="title_vi" name="title_vi" value="{{ old('title_vi') }}" 
                                   placeholder="Nhập tên di tích bằng tiếng Việt">
                            @error('title_vi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description_vi" class="form-label">Mô tả <span class="text-danger">*</span></label>
                            <textarea class="form-control ckeditor @error('description_vi') is-invalid @enderror"
                                      id="description_vi" name="description_vi" rows="12"
                                      placeholder="Mô tả ngắn gọn về di tích">{{ old('description_vi') }}</textarea>
                            @error('description_vi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-info-circle"></i> Sử dụng trình soạn thảo để định dạng mô tả với tiêu đề, in đậm, in nghiêng, hình ảnh, v.v.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="history_vi" class="form-label">Lịch sử <span class="text-danger">*</span></label>
                            <textarea class="form-control ckeditor @error('history_vi') is-invalid @enderror"
                                      id="history_vi" name="history_vi" rows="10"
                                      placeholder="Lịch sử hình thành và ý nghĩa của di tích">{{ old('history_vi') }}</textarea>
                            @error('history_vi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-info-circle"></i> Thêm bối cảnh lịch sử, ngày tháng và các sự kiện quan trọng.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="location_vi" class="form-label">Vị trí</label>
                            <input type="text" class="form-control" id="location_vi" name="location_vi" 
                                   value="{{ old('location_vi') }}" placeholder="Địa chỉ hoặc thông tin vị trí">
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div class="mb-3">
                        <label for="image" class="form-label">
                            <i class="bi bi-image"></i> {{ __('admin.featured_image') }}
                        </label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">{{ __('admin.supported_formats') }}: JPG, PNG, GIF (Max: 2MB)</div>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">{{ __('admin.status') }}</label>
                        <select class="form-select" id="status" name="status">
                            <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>
                                {{ __('admin.draft') }}
                            </option>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>
                                {{ __('admin.pending_review') }}
                            </option>
                            <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>
                                {{ __('admin.approved') }}
                            </option>
                        </select>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> {{ __('admin.create_monument') }}
                        </button>
                        <a href="{{ route('admin.monuments.index') }}" class="btn btn-secondary">
                            {{ __('admin.cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-info-circle"></i> Hướng dẫn</h5>
            </div>
            <div class="card-body">
                <h6>🏛️ Cách tạo di tích:</h6>
                <ol class="small">
                    <li><strong>Chọn ngôn ngữ chính:</strong> English hoặc Tiếng Việt</li>
                    <li><strong>Chọn khu vực:</strong> Bắc/Nam/Đông/Tây</li>
                    <li><strong>Điền thông tin:</strong> Tên, mô tả, lịch sử</li>
                    <li><strong>Thêm vị trí:</strong> Địa chỉ cụ thể (tùy chọn)</li>
                    <li><strong>Upload ảnh:</strong> Ảnh đại diện di tích</li>
                </ol>

                <hr>

                <h6>📝 Gợi ý viết nội dung:</h6>
                <ul class="small text-muted">
                    <li><strong>Mô tả:</strong> Tóm tắt ngắn gọn về di tích</li>
                    <li><strong>Lịch sử:</strong> Năm xây dựng, triều đại, sự kiện quan trọng</li>
                    <li><strong>Vị trí:</strong> Địa chỉ đầy đủ hoặc landmark gần đó</li>
                </ul>

                <hr>

                <h6>🌍 Thêm bản dịch:</h6>
                <p class="small text-muted">
                    Sau khi tạo di tích, bạn có thể vào trang "Sửa" để thêm bản dịch sang ngôn ngữ khác.
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleLanguageFields() {
    const language = document.getElementById('language').value;
    const englishFields = document.getElementById('english-fields');
    const vietnameseFields = document.getElementById('vietnamese-fields');
    
    if (language === 'vi') {
        englishFields.style.display = 'none';
        vietnameseFields.style.display = 'block';
        
        // Make Vietnamese fields required
        document.getElementById('title_vi').required = true;
        document.getElementById('description_vi').required = true;
        document.getElementById('history_vi').required = true;
        
        // Make English fields optional
        document.getElementById('title').required = false;
        document.getElementById('description').required = false;
        document.getElementById('history').required = false;
    } else {
        englishFields.style.display = 'block';
        vietnameseFields.style.display = 'none';
        
        // Make English fields required
        document.getElementById('title').required = true;
        document.getElementById('description').required = true;
        document.getElementById('history').required = true;
        
        // Make Vietnamese fields optional
        document.getElementById('title_vi').required = false;
        document.getElementById('description_vi').required = false;
        document.getElementById('history_vi').required = false;
    }
}

// Initialize on page load
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

    toggleLanguageFields();
});
</script>
@endpush
@endsection
