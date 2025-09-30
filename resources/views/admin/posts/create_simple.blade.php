@extends('layouts.admin')

@section('title', 'Tạo Bài Viết Mới')

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h3 mb-1">{{ __('admin.create_new_post') }}</h1>
        <p class="text-muted mb-0">Create engaging content for your heritage site</p>
    </div>
    <a href="{{ route('admin.posts.index') }}" class="btn-minimal">
        {{ __('admin.back_to_list') }}
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card-minimal">
            <div class="card-header">
                <h5>{{ __('admin.post_information') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Language Selection -->
                    <div class="mb-3">
                        <label for="language" class="form-label">
                            Language <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('language') is-invalid @enderror" id="language" name="language" onchange="toggleLanguageFields()">
                            <option value="en" {{ old('language', 'en') == 'en' ? 'selected' : '' }}>
                                English
                            </option>
                            <option value="vi" {{ old('language') == 'vi' ? 'selected' : '' }}>
                                Tiếng Việt
                            </option>
                        </select>
                        @error('language')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Choose the primary language for this post. You can add translations later.
                        </div>
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
                                   placeholder="Enter post title in English">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                            <textarea class="form-control ckeditor @error('content') is-invalid @enderror"
                                      id="content" name="content" rows="15"
                                      placeholder="Write your post content in English">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-info-circle"></i> Use the rich text editor to format your content with headings, bold, italic, images, links, etc.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="meta_title" class="form-label">Meta Title (SEO)</label>
                            <input type="text" class="form-control" id="meta_title" name="meta_title" 
                                   value="{{ old('meta_title') }}" placeholder="SEO title (optional)">
                            <div class="form-text">Leave empty to use main title</div>
                        </div>

                        <div class="mb-3">
                            <label for="meta_description" class="form-label">Meta Description (SEO)</label>
                            <textarea class="form-control" id="meta_description" name="meta_description" 
                                      rows="2" placeholder="Brief description for search engines">{{ old('meta_description') }}</textarea>
                        </div>
                    </div>

                    <!-- Vietnamese Fields -->
                    <div id="vietnamese-fields" style="display: none;">
                        <h6 class="text-success mb-3">
                            <i class="bi bi-flag"></i> Vietnamese Content
                        </h6>
                        
                        <div class="mb-3">
                            <label for="title_vi" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title_vi') is-invalid @enderror" 
                                   id="title_vi" name="title_vi" value="{{ old('title_vi') }}" 
                                   placeholder="Nhập tiêu đề bài viết bằng tiếng Việt">
                            @error('title_vi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content_vi" class="form-label">Nội dung <span class="text-danger">*</span></label>
                            <textarea class="form-control ckeditor @error('content_vi') is-invalid @enderror"
                                      id="content_vi" name="content_vi" rows="15"
                                      placeholder="Viết nội dung bài viết bằng tiếng Việt">{{ old('content_vi') }}</textarea>
                            @error('content_vi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-info-circle"></i> Sử dụng trình soạn thảo để định dạng nội dung với tiêu đề, in đậm, in nghiêng, hình ảnh, liên kết, v.v.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="meta_title_vi" class="form-label">Meta Title (SEO)</label>
                            <input type="text" class="form-control" id="meta_title_vi" name="meta_title_vi" 
                                   value="{{ old('meta_title_vi') }}" placeholder="Tiêu đề SEO (tùy chọn)">
                            <div class="form-text">Để trống sẽ sử dụng tiêu đề chính</div>
                        </div>

                        <div class="mb-3">
                            <label for="meta_description_vi" class="form-label">Meta Description (SEO)</label>
                            <textarea class="form-control" id="meta_description_vi" name="meta_description_vi" 
                                      rows="2" placeholder="Mô tả ngắn cho công cụ tìm kiếm">{{ old('meta_description_vi') }}</textarea>
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

                    <div class="d-flex gap-3 justify-content-end">
                        <a href="{{ route('admin.posts.index') }}" class="btn-minimal">
                            {{ __('admin.cancel') }}
                        </a>
                        <button type="submit" class="btn-minimal btn-primary">
                            {{ __('admin.create_post') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="modern-card">
            <div class="card-header">
                <h5><i class="bi bi-lightbulb me-2"></i>{{ __('admin.quick_guide') }}</h5>
            </div>
            <div class="card-body">
                <h6>📝 Cách tạo bài viết:</h6>
                <ol class="small">
                    <li><strong>Chọn ngôn ngữ chính:</strong> English hoặc Tiếng Việt</li>
                    <li><strong>Điền thông tin:</strong> Chỉ cần điền ngôn ngữ đã chọn</li>
                    <li><strong>Thêm ảnh:</strong> Upload ảnh đại diện (tùy chọn)</li>
                    <li><strong>Chọn trạng thái:</strong> Draft/Pending/{{ __('admin.approve') }}d</li>
                    <li><strong>Lưu bài viết:</strong> Click "Tạo bài viết"</li>
                </ol>

                <hr>

                <h6>🌍 Thêm bản dịch:</h6>
                <p class="small text-muted">
                    Sau khi tạo bài viết, bạn có thể vào trang "Sửa" để thêm bản dịch sang ngôn ngữ khác.
                </p>

                <hr>

                <h6>💡 Lưu ý:</h6>
                <ul class="small text-muted">
                    <li>Tiêu đề và nội dung là bắt buộc</li>
                    <li>Meta title/description giúp SEO tốt hơn</li>
                    <li>Ảnh nên có kích thước phù hợp</li>
                    <li>Draft = Bản nháp, Pending = Chờ duyệt</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Initialize CKEditor Rich Text Editor
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

function toggleLanguageFields() {
    const language = document.getElementById('language').value;
    const englishFields = document.getElementById('english-fields');
    const vietnameseFields = document.getElementById('vietnamese-fields');

    if (language === 'vi') {
        englishFields.style.display = 'none';
        vietnameseFields.style.display = 'block';

        // Make Vietnamese fields required
        document.getElementById('title_vi').required = true;
        document.getElementById('content_vi').required = true;

        // Make English fields optional
        document.getElementById('title').required = false;
        document.getElementById('content').required = false;
    } else {
        englishFields.style.display = 'block';
        vietnameseFields.style.display = 'none';

        // Make English fields required
        document.getElementById('title').required = true;
        document.getElementById('content').required = true;

        // Make Vietnamese fields optional
        document.getElementById('title_vi').required = false;
        document.getElementById('content_vi').required = false;
    }
}
</script>
@endpush
@endsection
