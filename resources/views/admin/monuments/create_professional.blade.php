@extends('layouts.admin')

@section('title', 'Create Professional Monument Article')

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h3 mb-1">{{ __('admin.create_new_monument') }}</h1>
        <p class="text-muted mb-0">{{ __('Create comprehensive educational content about heritage monuments') }}</p>
    </div>
    <a href="{{ route('admin.monuments.index') }}" class="btn-minimal">
        {{ __('admin.back_to_list') }}
    </a>
</div>
<form action="{{ route('admin.monuments.store') }}" method="POST" enctype="multipart/form-data" id="monumentForm">
    @csrf
    
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
                            <option value="en" {{ old('language', 'en') == 'en' ? 'selected' : '' }}>{{ __('admin.english') }}</option>
                            <option value="vi" {{ old('language') == 'vi' ? 'selected' : '' }}>{{ __('admin.vietnamese') }}</option>
                        </select>
                        @error('language')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Monument Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">{{ __('admin.monument_title') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               id="title" name="title" value="{{ old('title') }}"
                               placeholder="e.g., Angkor Wat – Kỳ quan bất tử của nền văn minh Khmer">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Zone Selection -->
                    <div class="mb-3">
                        <label for="zone" class="form-label">{{ __('admin.geographic_zone') }} <span class="text-danger">*</span></label>
                        <select class="form-select @error('zone') is-invalid @enderror" id="zone" name="zone">
                            <option value="">{{ __('admin.select_zone') }}</option>
                            <option value="North" {{ old('zone') == 'North' ? 'selected' : '' }}>{{ __('admin.zones.north') }}</option>
                            <option value="South" {{ old('zone') == 'South' ? 'selected' : '' }}>{{ __('admin.zones.south') }}</option>
                            <option value="East" {{ old('zone') == 'East' ? 'selected' : '' }}>{{ __('admin.zones.east') }}</option>
                            <option value="West" {{ old('zone') == 'West' ? 'selected' : '' }}>{{ __('admin.zones.west') }}</option>
                            <option value="Central" {{ old('zone') == 'Central' ? 'selected' : '' }}>{{ __('admin.zones.central') }}</option>
                        </select>
                        @error('zone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div class="mb-3">
                        <label for="location" class="form-label">{{ __('admin.specific_location') }}</label>
                        <input type="text" class="form-control @error('location') is-invalid @enderror"
                               id="location" name="location" value="{{ old('location') }}"
                               placeholder="e.g., Siem Reap Province, Cambodia">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Coordinates -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="latitude" class="form-label">Latitude <small class="text-muted">(Optional)</small></label>
                            <input type="number" step="0.00000001" class="form-control @error('latitude') is-invalid @enderror"
                                   id="latitude" name="latitude" value="{{ old('latitude') }}"
                                   placeholder="e.g., 13.412469">
                            @error('latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="longitude" class="form-label">Longitude <small class="text-muted">(Optional)</small></label>
                            <input type="number" step="0.00000001" class="form-control @error('longitude') is-invalid @enderror"
                                   id="longitude" name="longitude" value="{{ old('longitude') }}"
                                   placeholder="e.g., 103.866986">
                            @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- World Wonder Checkbox -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_world_wonder" name="is_world_wonder" value="1" {{ old('is_world_wonder') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_world_wonder">
                                🌟 Mark as World Wonder
                                <small class="text-muted">(Will be displayed in special World Wonders section)</small>
                            </label>
                        </div>
                    </div>

                    <!-- Map Embed -->
                    <div class="mb-3">
                        <label for="map_embed" class="form-label">
                            Google Maps Embed
                            <small class="text-muted">(Optional)</small>
                        </label>
                        <textarea class="form-control @error('map_embed') is-invalid @enderror"
                                  id="map_embed" name="map_embed" rows="3"
                                  placeholder='<iframe src="https://www.google.com/maps/embed?pb=..." width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'>{{ old('map_embed') }}</textarea>
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
                    Short Description
                    <small class="text-muted ms-2">Brief summary for previews and search results</small>
                </div>
                <div class="editor-section-body">
                    <div class="mb-3">
                        <label for="description" class="form-label">Brief Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="3"
                                  placeholder="Write a brief, engaging description of this monument (2-3 sentences)...">{{ old('description') }}</textarea>
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
                    Article Content
                    <small class="text-muted ms-2">Use headings (H2, H3, H4) to create automatic table of contents</small>
                </div>
                <div class="editor-section-body">
                    <!-- Content Editor -->
                    <div class="mb-3">
                        <label for="content" class="form-label">Full Article Content <span class="text-danger">*</span></label>
                        <div class="editor-toolbar">
                            <small class="text-muted">
                                💡 <strong>Tips:</strong> Use <strong>Heading 2</strong> for main sections (Giới thiệu, Lịch sử, Kiến trúc...), 
                                <strong>Heading 3</strong> for subsections. This will automatically generate a table of contents.
                            </small>
                        </div>
                        <textarea class="form-control ckeditor @error('content') is-invalid @enderror" 
                                  id="content" name="content" rows="20" 
                                  placeholder="Start writing your comprehensive article about this monument...">{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Write a comprehensive article with sections like: Introduction, History, Architecture, Cultural Significance, Visiting Tips, References
                        </div>
                    </div>
                </div>
            </div>

            <!-- Image Gallery -->
            <div class="editor-section">
                <div class="editor-section-header">
                    Image Gallery
                    <small class="text-muted ms-2">Add multiple images to illustrate your article</small>
                </div>
                <div class="editor-section-body">
                    <!-- Featured Image -->
                    <div class="mb-4">
                        <label for="featured_image" class="form-label">Featured Image <span class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('featured_image') is-invalid @enderror" 
                               id="featured_image" name="featured_image" accept="image/*">
                        @error('featured_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Main image that represents this monument (will be used as thumbnail)</div>
                    </div>

                    <!-- Additional Images -->
                    <div class="mb-3">
                        <label class="form-label">Additional Images <small class="text-muted">(Max 50MB each, drag & drop supported)</small></label>
                        <div id="image-gallery">
                            <div class="image-gallery-item drag-drop-zone" data-slot="1" style="border: 2px dashed #ccc; padding: 2rem; text-align: center; cursor: pointer; margin-bottom: 1rem;">
                                <div class="text-muted">
                                    <div class="mb-2">📸</div>
                                    <div>Drag & drop or click to add image</div>
                                    <small>Architecture details, interior views, etc.</small>
                                    <small class="d-block mt-1">Max size: 5MB each</small>
                                    <small class="d-block mt-1 text-info">DEBUG: Click me to test!</small>
                                </div>
                                <input type="file" id="gallery-input-1" name="gallery_images[]" accept="image/*" style="display: none;">
                            </div>
                        </div>
                        <button type="button" class="btn-minimal btn-primary mt-2" onclick="window.addImageSlot()">Add More Images</button>
                        <button type="button" class="btn-minimal btn-secondary mt-2 ms-2" onclick="window.testFunction()">Test JS</button>
                    </div>
                </div>
            </div>

        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            
            <!-- Table of Contents Preview -->
            <div class="editor-section">
                <div class="editor-section-header">
                    Table of Contents Preview
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
                    Publishing Options
                </div>
                <div class="editor-section-body">
                    <!-- Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                            <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending Review</option>
                            <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tags -->
                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags</label>
                        <input type="text" class="form-control @error('tags') is-invalid @enderror" 
                               id="tags" name="tags" value="{{ old('tags') }}" 
                               placeholder="heritage, temple, ancient, architecture">
                        @error('tags')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">{{ __('admin.separate_tags_commas') }}</div>
                    </div>

                    <!-- Save Buttons -->
                    <div class="d-grid gap-2">
                        <button type="submit" name="action" value="save" class="btn-minimal btn-primary">
                            Save Monument Article
                        </button>
                        <button type="submit" name="action" value="preview" class="btn-minimal">
                            Save & Preview
                        </button>
                        <a href="{{ route('admin.monuments.index') }}" class="btn-minimal">
                            Cancel
                        </a>
                    </div>
                </div>
            </div>

            <!-- Writing Guidelines -->
            <div class="editor-section">
                <div class="editor-section-header">
                    📝 Writing Guidelines
                </div>
                <div class="editor-section-body">
                    <div class="small text-muted">
                        <p><strong>Suggested Structure:</strong></p>
                        <ul class="list-unstyled">
                            <li>• <strong>Giới thiệu</strong> - Overview & significance</li>
                            <li>• <strong>Lịch sử hình thành</strong> - Historical background</li>
                            <li>• <strong>Kiến trúc & Nghệ thuật</strong> - Architecture details</li>
                            <li>• <strong>Giá trị văn hóa</strong> - Cultural importance</li>
                            <li>• <strong>Kinh nghiệm tham quan</strong> - Visiting tips</li>
                            <li>• <strong>Nguồn tham khảo</strong> - References</li>
                        </ul>
                        <p><strong>Best Practices:</strong></p>
                        <ul class="list-unstyled">
                            <li>• Use clear, descriptive headings</li>
                            <li>• {{ __('admin.include_dates_facts') }}</li>
                            <li>• Add multiple high-quality images</li>
                            <li>• {{ __('admin.provide_visitor_info') }}</li>
                            <li>• {{ __('admin.cite_reliable_sources') }}</li>
                        </ul>
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
// Simple Image Gallery Functions
var imageSlotCount = 1;

// Test function first
window.testFunction = function() {
    alert('JavaScript is working!');
    console.log('Test function called!');

    var zones = document.querySelectorAll('.drag-drop-zone');
    console.log('Found zones:', zones.length);

    var input1 = document.getElementById('gallery-input-1');
    console.log('Input 1:', input1);

    if (input1) {
        console.log('Triggering input click...');
        input1.click();
    }
};

// Add image slot function
window.addImageSlot = function() {
    imageSlotCount++;
    console.log('Adding new image slot:', imageSlotCount);

    var gallery = document.getElementById('image-gallery');
    var newSlot = document.createElement('div');
    newSlot.className = 'image-gallery-item drag-drop-zone';
    newSlot.setAttribute('data-slot', imageSlotCount);
    newSlot.style.cssText = 'border: 2px dashed #ccc; padding: 2rem; text-align: center; cursor: pointer; margin-bottom: 1rem;';
    newSlot.innerHTML = '<div class="text-muted">' +
        '<div class="mb-2">📸</div>' +
        '<div>Drag & drop or click to add image</div>' +
        '<small>{{ __('admin.additional_monument_photos') }}</small>' +
        '<small class="d-block mt-1">Max size: 50MB</small>' +
        '</div>' +
        '<input type="file" id="gallery-input-' + imageSlotCount + '" name="gallery_images[]" accept="image/*" style="display: none;">';

    gallery.appendChild(newSlot);
    setupImageZone(newSlot, imageSlotCount);
};

// Setup individual zone
function setupImageZone(zone, slotNumber) {
    var input = document.getElementById('gallery-input-' + slotNumber);

    if (!input) {
        console.error('Input not found for slot:', slotNumber);
        return;
    }

    console.log('Setting up zone:', slotNumber);

    // Click to upload
    zone.onclick = function() {
        console.log('Zone clicked, opening file dialog...');
        input.click();
    };

    // File input change
    input.onchange = function(e) {
        console.log('File selected:', e.target.files);
        if (e.target.files.length > 0) {
            handleFileUpload(e.target.files[0], zone, slotNumber);
        }
    };
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing image gallery...');

    // Setup existing zones
    var zones = document.querySelectorAll('.drag-drop-zone');
    console.log('Found zones:', zones.length);

    for (var i = 0; i < zones.length; i++) {
        var zone = zones[i];
        var slotNumber = zone.getAttribute('data-slot');
        setupImageZone(zone, slotNumber);
    }
});

function handleFileUpload(file, zone, slotNumber) {
    console.log('Handling file upload:', file.name, file.size, file.type);

    // Validate file type
    if (!file.type.startsWith('image/')) {
        alert('Please select an image file.');
        return;
    }

    // Validate file size (5MB to match PHP config)
    var maxSize = 5 * 1024 * 1024; // 5MB to match PHP upload_max_filesize
    if (file.size > maxSize) {
        alert('File too large! Max size: 5MB. Your file: ' + (file.size / 1024 / 1024).toFixed(2) + 'MB');
        return;
    }

    // Additional validation
    if (file.size === 0) {
        alert('File is empty or corrupted. Please select a valid image.');
        return;
    }

    // Store file reference globally for later use
    if (!window.galleryFiles) {
        window.galleryFiles = {};
    }
    window.galleryFiles[slotNumber] = file;
    console.log('✅ File stored globally for slot', slotNumber, ':', file.name);

    // Show loading
    zone.innerHTML = '<div class="text-muted">' +
        '<div class="mb-2">⏳</div>' +
        '<div>Loading image...</div>' +
        '<small>Please wait</small>' +
        '</div>';

    // Read and preview image
    var reader = new FileReader();
    reader.onload = function(e) {
        console.log('Image loaded successfully');
        zone.classList.add('has-image');
        zone.innerHTML = '<img src="' + e.target.result + '" alt="Preview" style="width: 100%; height: 200px; object-fit: cover; border-radius: 6px;">' +
            '<div class="image-caption" style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.7); color: white; padding: 0.5rem;">' +
            '<input type="text" name="image_captions[]" placeholder="Add image caption..." class="form-control form-control-sm mb-1" style="background: rgba(255,255,255,0.9); color: #333;">' +
            '<button type="button" class="btn btn-sm btn-danger" onclick="removeImageSimple(this, ' + slotNumber + ')">Remove</button>' +
            '</div>' +
            '<input type="file" id="gallery-input-' + slotNumber + '" name="gallery_images[]" accept="image/*" style="display: none;">';

        // Set file to the new input after innerHTML change
        try {
            var newInput = document.getElementById('gallery-input-' + slotNumber);
            if (newInput && window.galleryFiles && window.galleryFiles[slotNumber]) {
                var storedFile = window.galleryFiles[slotNumber];
                var dt = new DataTransfer();
                dt.items.add(storedFile);
                newInput.files = dt.files;
                console.log('✅ File set to new input:', newInput.files.length, 'files');
                console.log('✅ File details:', storedFile.name, (storedFile.size / 1024 / 1024).toFixed(2) + 'MB');
            } else {
                console.error('❌ New input or stored file not found');
            }
        } catch (error) {
            console.error('❌ Error setting file to new input:', error);
        }

        alert('Image uploaded successfully!');
    };

    reader.onerror = function() {
        console.error('Error reading file');
        alert('Error reading file. Please try again.');
    };

    reader.readAsDataURL(file);
}

function removeImageSimple(button, slotNumber) {
    var zone = button.closest('.image-gallery-item');
    zone.classList.remove('has-image');
    zone.style.cssText = 'border: 2px dashed #ccc; padding: 2rem; text-align: center; cursor: pointer; margin-bottom: 1rem;';
    zone.innerHTML = '<div class="text-muted">' +
        '<div class="mb-2">📸</div>' +
        '<div>Drag & drop or click to add image</div>' +
        '<small>{{ __('admin.additional_monument_photos') }}</small>' +
        '<small class="d-block mt-1">Max size: 5MB</small>' +
        '</div>' +
        '<input type="file" id="gallery-input-' + slotNumber + '" name="gallery_images[]" accept="image/*" style="display: none;">';

    // Clear from global storage
    if (window.galleryFiles && window.galleryFiles[slotNumber]) {
        delete window.galleryFiles[slotNumber];
        console.log('✅ File removed from global storage for slot:', slotNumber);
    }

    setupImageZone(zone, slotNumber);
    console.log('Image removed from slot:', slotNumber);
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing image gallery...');

    // Setup existing zones
    var zones = document.querySelectorAll('.drag-drop-zone');
    console.log('Found zones:', zones.length);

    for (var i = 0; i < zones.length; i++) {
        var zone = zones[i];
        var slotNumber = zone.getAttribute('data-slot');
        setupImageZone(zone, slotNumber);
    }

    // Debug form submission
    var form = document.querySelector('form');
    if (form) {
        console.log('Form found, adding submit listener');

        form.addEventListener('submit', function(e) {
            console.log('🚀 Form submit event triggered!');

            try {
                // Sync CKEditor data first
                if (editorInstance) {
                    try {
                        var editorData = editorInstance.getData();
                        document.getElementById('content').value = editorData;
                        console.log('✅ CKEditor data synced:', editorData.length, 'characters');
                    } catch (error) {
                        console.error('❌ Error syncing CKEditor data:', error);
                    }
                } else {
                    console.warn('⚠️ CKEditor instance not found');
                }

                // Re-set all gallery files before submit
                console.log('🔄 Re-setting gallery files before submit...');
                if (window.galleryFiles) {
                    for (var slotNum in window.galleryFiles) {
                        var input = document.getElementById('gallery-input-' + slotNum);
                        var file = window.galleryFiles[slotNum];
                        if (input && file) {
                            try {
                                var dt = new DataTransfer();
                                dt.items.add(file);
                                input.files = dt.files;
                                console.log('✅ Re-set file for slot', slotNum, ':', file.name);
                            } catch (error) {
                                console.error('❌ Error re-setting file for slot', slotNum, ':', error);
                            }
                        }
                    }
                }

                // Check all gallery inputs
                var galleryInputs = document.querySelectorAll('input[name="gallery_images[]"]');
                console.log('Gallery inputs found:', galleryInputs.length);

                var filesCount = 0;
                var totalSize = 0;

                for (var i = 0; i < galleryInputs.length; i++) {
                    var input = galleryInputs[i];
                    console.log('Input', i, 'ID:', input.id, 'Files:', input.files.length);
                    console.log('Input element:', input);
                    console.log('Input name:', input.name);
                    console.log('Input type:', input.type);

                    if (input.files.length > 0) {
                        console.log('📁 Files in input', i, ':');
                        for (var j = 0; j < input.files.length; j++) {
                            var file = input.files[j];
                            totalSize += file.size;
                            console.log('  📄 File', j, ':', file.name, (file.size / 1024 / 1024).toFixed(2) + 'MB', 'Type:', file.type);

                            // Check individual file size
                            if (file.size > 5 * 1024 * 1024) {
                                console.error('❌ File too large:', file.name);
                                alert('File "' + file.name + '" is too large. Max size: 5MB');
                                e.preventDefault();
                                return false;
                            }
                        }
                    } else {
                        console.log('📭 No files in input', i);
                    }

                    filesCount += input.files.length;
                }

                console.log('Total gallery files:', filesCount, 'Total size:', (totalSize / 1024 / 1024).toFixed(2) + 'MB');

                // Check total upload size (50MB limit)
                if (totalSize > 50 * 1024 * 1024) {
                    console.error('❌ Total upload size too large');
                    alert('Total file size too large. Please reduce the number or size of images.');
                    e.preventDefault();
                    return false;
                }

                // Check required fields
                var title = document.getElementById('title').value;
                var location = document.getElementById('location').value;
                var zone = document.getElementById('zone').value;
                var content = document.getElementById('content').value;

                console.log('Form data:', {
                    title: title,
                    location: location,
                    zone: zone,
                    content: content ? 'has content' : 'empty'
                });

                // Validate required fields
                if (!title || !location || !zone || !content) {
                    console.error('❌ Missing required fields!');
                    alert('Please fill in all required fields');
                    e.preventDefault();
                    return false;
                }

                // Debug FormData
                console.log('🔍 Debugging FormData...');
                var formData = new FormData(form);

                console.log('📋 FormData entries:');
                for (var pair of formData.entries()) {
                    if (pair[1] instanceof File) {
                        console.log('  📄', pair[0], ':', pair[1].name, (pair[1].size / 1024 / 1024).toFixed(2) + 'MB');
                    } else {
                        console.log('  📝', pair[0], ':', pair[1]);
                    }
                }

                console.log('✅ All validations passed, allowing form to submit...');

            } catch (error) {
                console.error('❌ Error in form submit handler:', error);
                alert('An error occurred while processing the form. Please try again.');
                e.preventDefault();
                return false;
            }
        });

        // Also check for button clicks
        var submitBtns = document.querySelectorAll('button[type="submit"]');
        console.log('Submit buttons found:', submitBtns.length);

        for (var i = 0; i < submitBtns.length; i++) {
            var btn = submitBtns[i];
            console.log('Button', i, ':', btn.textContent.trim());

            btn.addEventListener('click', function(e) {
                console.log('🔘 Submit button clicked:', e.target.textContent.trim());
                console.log('Button value:', e.target.value);

                // Validate form size before submit
                if (!validateFormSize()) {
                    e.preventDefault();
                    return false;
                }
            });
        }
    } else {
        console.error('❌ Form not found!');
    }
});

// Validate form size to prevent PostTooLargeException
function validateFormSize() {
    const form = document.getElementById('monumentForm');
    if (!form) return true;

    // Calculate approximate form data size
    let totalSize = 0;

    // Check text fields
    const textInputs = form.querySelectorAll('input[type="text"], textarea, select');
    textInputs.forEach(input => {
        if (input.value) {
            totalSize += new Blob([input.value]).size;
        }
    });

    // Check file inputs
    const fileInputs = form.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        if (input.files) {
            for (let i = 0; i < input.files.length; i++) {
                totalSize += input.files[i].size;
            }
        }
    });

    // Check CKEditor content
    if (typeof CKEDITOR !== 'undefined') {
        for (let instance in CKEDITOR.instances) {
            const content = CKEDITOR.instances[instance].getData();
            totalSize += new Blob([content]).size;
        }
    }

    const maxSize = 150 * 1024 * 1024; // 150MB limit

    console.log('📊 Form size check:', {
        totalSize: (totalSize / 1024 / 1024).toFixed(2) + ' MB',
        maxSize: (maxSize / 1024 / 1024).toFixed(2) + ' MB',
        isValid: totalSize < maxSize
    });

    if (totalSize > maxSize) {
        alert('⚠️ Form data is too large!\n\n' +
              'Current size: ' + (totalSize / 1024 / 1024).toFixed(2) + ' MB\n' +
              'Maximum allowed: ' + (maxSize / 1024 / 1024).toFixed(2) + ' MB\n\n' +
              'Please reduce:\n' +
              '• Image file sizes\n' +
              '• Content length\n' +
              '• Number of images');
        return false;
    }

    return true;
}

</script>
@endpush
@endsection
