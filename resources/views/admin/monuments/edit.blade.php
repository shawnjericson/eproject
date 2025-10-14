@extends('layouts.admin')

@section('title', __('admin.edit_monument'))

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h3 mb-1">{{ __('admin.edit_monument') }}: {{ $monument->title }}</h1>
        <p class="text-muted mb-0">{{ __('admin.update_monument_content') }}</p>
    </div>
    <a href="{{ route('admin.monuments.index') }}" class="btn-minimal">
        {{ __('admin.back_to_list') }}
    </a>
</div>

<form action="{{ route('admin.monuments.update', $monument) }}" method="POST" enctype="multipart/form-data" id="monumentForm">
    @csrf
    @method('PUT')

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">

            <!-- Basic Information -->
            <div class="editor-section">
                <div class="editor-section-header">
                    {{ __('admin.basic_information') }}
                </div>
                <div class="editor-section-body">
                    <!-- Language Selection -->
                    <div class="mb-3">
                        <label for="language" class="form-label">{{ __('admin.language') }} <span class="text-danger">*</span></label>
                        <select class="form-select @error('language') is-invalid @enderror" id="language" name="language">
                            <option value="en" {{ old('language', $monument->language ?? 'en') == 'en' ? 'selected' : '' }}>{{ __('admin.english') }}</option>
                            <option value="vi" {{ old('language', $monument->language) == 'vi' ? 'selected' : '' }}>{{ __('admin.vietnamese') }}</option>
                        </select>
                        @error('language')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Monument Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">{{ __('admin.monument_title') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               id="title" name="title" value="{{ old('title', $monument->title) }}"
                               placeholder="{{ __('admin.monument_title_placeholder') }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Zone Selection -->
                    <div class="mb-3">
                        <label for="zone" class="form-label">{{ __('admin.geographic_zone') }} <span class="text-danger">*</span></label>
                        <select class="form-select @error('zone') is-invalid @enderror" id="zone" name="zone">
                            <option value="">{{ __('admin.select_zone') }}</option>
                            <option value="North" {{ old('zone', $monument->zone) == 'North' ? 'selected' : '' }}>{{ __('admin.north') }}</option>
                            <option value="South" {{ old('zone', $monument->zone) == 'South' ? 'selected' : '' }}>{{ __('admin.south') }}</option>
                            <option value="East" {{ old('zone', $monument->zone) == 'East' ? 'selected' : '' }}>{{ __('admin.east') }}</option>
                            <option value="West" {{ old('zone', $monument->zone) == 'West' ? 'selected' : '' }}>{{ __('admin.west') }}</option>
                            <option value="Central" {{ old('zone', $monument->zone) == 'Central' ? 'selected' : '' }}>{{ __('admin.central') }}</option>
                        </select>
                        @error('zone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div class="mb-3">
                        <label for="location" class="form-label">{{ __('admin.specific_location') }}</label>
                        <input type="text" class="form-control @error('location') is-invalid @enderror"
                               id="location" name="location" value="{{ old('location', $monument->location) }}"
                               placeholder="{{ __('admin.location_placeholder') }}">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Coordinates -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="latitude" class="form-label">{{ __('admin.latitude') }} <small class="text-muted">({{ __('admin.optional') }})</small></label>
                            <input type="number" step="0.00000001" class="form-control @error('latitude') is-invalid @enderror"
                                   id="latitude" name="latitude" value="{{ old('latitude', $monument->latitude) }}"
                                   placeholder="e.g., 13.412469">
                            @error('latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="longitude" class="form-label">{{ __('admin.longitude') }} <small class="text-muted">({{ __('admin.optional') }})</small></label>
                            <input type="number" step="0.00000001" class="form-control @error('longitude') is-invalid @enderror"
                                   id="longitude" name="longitude" value="{{ old('longitude', $monument->longitude) }}"
                                   placeholder="e.g., 103.866986">
                            @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- World Wonder Checkbox -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_world_wonder" name="is_world_wonder" value="1" {{ old('is_world_wonder', $monument->is_world_wonder) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_world_wonder">
                                🌟 Mark as World Wonder
                                <small class="text-muted">(Will be displayed in special World Wonders section)</small>
                            </label>
                        </div>
                    </div>

                    <!-- Map Embed -->
                    <div class="mb-3">
                        <label for="map_embed" class="form-label">
                            {{ __('admin.google_maps_embed') }}
                            <small class="text-muted">({{ __('admin.optional') }})</small>
                        </label>
                        <textarea class="form-control @error('map_embed') is-invalid @enderror"
                                  id="map_embed" name="map_embed" rows="3"
                                  placeholder='<iframe src="https://www.google.com/maps/embed?pb=..." width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'>{{ old('map_embed', $monument->map_embed) }}</textarea>
                        @error('map_embed')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <strong>📍 How to get Google Maps embed code:</strong><br>
                            1. Go to <a href="https://maps.google.com" target="_blank">Google Maps</a><br>
                            2. {{ __('admin.search_monument_location') }}<br>
                            3. Click "Share" → "Embed a map"<br>
                            4. Copy the entire &lt;iframe&gt; code and paste it here<br>
                            <small class="text-success">✅ This will show an interactive map on the monument page</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Short Description -->
            <div class="editor-section">
                <div class="editor-section-header">
                    {{ __('admin.short_description') }}
                    <small class="text-muted ms-2">{{ __('admin.brief_summary_preview') }}</small>
                </div>
                <div class="editor-section-body">
                    <div class="mb-3">
                        <label for="description" class="form-label">{{ __('admin.brief_description') }} <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="3"
                                  placeholder="Write a brief, engaging description of this monument (2-3 sentences)...">{{ old('description', $monument->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            {{ __('admin.description_usage_hint') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Article Content -->
            <div class="editor-section">
                <div class="editor-section-header">
                    {{ __('admin.article_content') }}
                    <small class="text-muted ms-2">Use headings (H2, H3, H4) to create automatic table of contents</small>
                </div>
                <div class="editor-section-body">
                    <!-- Content Editor -->
                    <div class="mb-3">
                        <label for="content" class="form-label">{{ __('admin.full_article_content') }} <span class="text-danger">*</span></label>
                        <div class="editor-toolbar">
                            <small class="text-muted">
                                💡 <strong>Tips:</strong> Use <strong>Heading 2</strong> for main sections (Giới thiệu, Lịch sử, Kiến trúc...),
                                <strong>Heading 3</strong> for subsections. This will automatically generate a table of contents.
                            </small>
                        </div>
                        <textarea class="form-control ckeditor @error('content') is-invalid @enderror"
                                  id="content" name="content" rows="20"
                                  placeholder="Start writing your comprehensive article about this monument...">{{ old('content', $monument->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Write a comprehensive article with sections like: Introduction, History, Architecture, Cultural Significance, Visiting Tips, References
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Featured Image -->
            <div class="editor-section">
                <div class="editor-section-header">
                    {{ __('admin.featured_image') }}
                </div>
                <div class="editor-section-body">
                    @if($monument->image)
                        <div class="mb-3">
                            <img src="{{ $monument->image_url }}"
                                 alt="{{ $monument->title }}"
                                 class="img-fluid rounded" style="max-height: 200px; width: 100%; object-fit: cover;">
                            <div class="form-text mt-2">{{ __('admin.current_featured_image') }}</div>
                        </div>
                    @endif
                    <div class="mb-3">
                        <label for="image" class="form-label">
                            @if($monument->image) {{ __('admin.replace_featured_image') }} @else {{ __('admin.upload_featured_image') }} @endif
                        </label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                               id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Supported formats: JPEG, PNG, JPG, GIF. Max size: 10MB
                            @if($monument->image)
                                <br>{{ __('admin.leave_empty_keep_current') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gallery Images -->
            <div class="editor-section">
                <div class="editor-section-header">
                    {{ __('admin.gallery_images') }}
                </div>
                <div class="editor-section-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('admin.add_gallery_images') }}</label>
                        <input type="file" class="form-control @error('gallery_images.*') is-invalid @enderror"
                               name="gallery_images[]" multiple accept="image/*" id="gallery_images">
                        @error('gallery_images.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Select multiple images to add to the gallery. Max 10MB each.
                        </div>

                        <!-- Preview area -->
                        <div id="gallery_preview" class="mt-3 row g-2" style="display: none;">
                            <!-- Previews will be inserted here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table of Contents Preview -->
            <div class="editor-section">
                <div class="editor-section-header">
                    {{ __('admin.table_of_contents_preview') }}
                </div>
                <div class="editor-section-body">
                    <div class="toc-preview">
                        <h6>📋 Auto-generated TOC</h6>
                        <ul id="toc-preview-list">
                            <li class="text-muted">Start writing with headings to see TOC...</li>
                        </ul>
                    </div>
                    <small class="text-muted">
                        The table of contents will be automatically generated from your headings (H2, H3, H4) in the article content.
                    </small>
                </div>
            </div>

            <!-- Publishing Options -->
            <div class="editor-section">
                <div class="editor-section-header">
                    {{ __('admin.publishing_options') }}
                </div>
                <div class="editor-section-body">
                    <!-- Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">{{ __('admin.status') }}</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                            <option value="draft" {{ old('status', $monument->status) == 'draft' ? 'selected' : '' }}>{{ __('admin.draft') }}</option>
                            <option value="pending" {{ old('status', $monument->status) == 'pending' ? 'selected' : '' }}>{{ __('admin.pending_review') }}</option>
                            @if(auth()->user()->isAdmin())
                                <option value="approved" {{ old('status', $monument->status) == 'approved' ? 'selected' : '' }}>{{ __('admin.published') }}</option>
                            @endif
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tags -->
                    <div class="mb-3">
                        <label for="tags" class="form-label">{{ __('admin.tags') }}</label>
                        <input type="text" class="form-control @error('tags') is-invalid @enderror"
                               id="tags" name="tags" value="{{ old('tags', $monument->tags) }}"
                               placeholder="heritage, temple, ancient, architecture">
                        @error('tags')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">{{ __('admin.separate_tags_commas') }}</div>
                    </div>

                    <!-- Save Buttons -->
                    <div class="d-grid gap-2">
                        <button type="submit" name="action" value="save" class="btn-minimal btn-primary">
                            {{ __('admin.update_monument_article') }}
                        </button>
                        <button type="submit" name="action" value="preview" class="btn-minimal">
                            {{ __('admin.update_preview') }}
                        </button>
                        <a href="{{ route('admin.monuments.index') }}" class="btn-minimal">
                            {{ __('admin.cancel') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@push('scripts')
<script>
// Initialize CKEditor
var editorInstance = null;

ClassicEditor
    .create(document.querySelector('#content'), {
        toolbar: [
            'heading', '|',
            'bold', 'italic', 'link', '|',
            'bulletedList', 'numberedList', '|',
            'outdent', 'indent', '|',
            'imageUpload', 'blockQuote', 'insertTable', '|',
            'undo', 'redo'
        ],
        heading: {
            options: [
                { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' }
            ]
        }
    })
    .then(function(editor) {
        console.log('✅ CKEditor initialized successfully');
        editorInstance = editor;

        // Update TOC when content changes
        editor.model.document.on('change:data', function() {
            updateTOC(editor.getData());
        });
    })
    .catch(function(error) {
        console.error('❌ CKEditor initialization failed:', error);
    });

// Update Table of Contents
function updateTOC(content) {
    var tocList = document.getElementById('toc-preview-list');
    var parser = new DOMParser();
    var doc = parser.parseFromString(content, 'text/html');
    var headings = doc.querySelectorAll('h2, h3, h4');

    if (headings.length === 0) {
        tocList.innerHTML = '<li class="text-muted">Start writing with headings to see TOC...</li>';
        return;
    }

    var tocHTML = '';
    for (var i = 0; i < headings.length; i++) {
        var heading = headings[i];
        var level = heading.tagName.toLowerCase();
        var text = heading.textContent;
        tocHTML += '<li class="' + level + '">' + (i + 1) + '. ' + text + '</li>';
    }

    tocList.innerHTML = tocHTML;
}

// Gallery preview functionality
document.addEventListener('DOMContentLoaded', function() {
    const galleryInput = document.getElementById('gallery_images');
    const galleryPreview = document.getElementById('gallery_preview');

    if (galleryInput) {
        galleryInput.addEventListener('change', function(e) {
            const files = e.target.files;
            galleryPreview.innerHTML = '';

            if (files.length > 0) {
                galleryPreview.style.display = 'block';

                Array.from(files).forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const col = document.createElement('div');
                            col.className = 'col-6 col-md-4';
                            col.innerHTML = `
                                <div class="card">
                                    <img src="${e.target.result}" class="card-img-top" style="height: 100px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <small class="text-muted">${file.name}</small>
                                    </div>
                                </div>
                            `;
                            galleryPreview.appendChild(col);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            } else {
                galleryPreview.style.display = 'none';
            }
        });
    }
});

</script>
@endpush
@endsection
