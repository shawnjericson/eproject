<!DOCTYPE html>
<html>
<head>
    <title>Debug Featured Image Upload</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Debug Featured Image Upload</h1>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <h5>Errors:</h5>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="row">
            <div class="col-md-6">
                <h3>Test 1: Chỉ text (không có file)</h3>
                <form action="{{ route('admin.monuments.store') }}" method="POST" style="border: 2px solid blue; padding: 20px; margin-bottom: 20px;">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Title *</label>
                        <input type="text" class="form-control" id="title" name="title" value="Test Text Only" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="location" class="form-label">Location *</label>
                        <input type="text" class="form-control" id="location" name="location" value="Test Location" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="zone" class="form-label">Zone *</label>
                        <select class="form-select" id="zone" name="zone" required>
                            <option value="North">North</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="content" class="form-label">Content *</label>
                        <textarea class="form-control" id="content" name="content" rows="3" required>Test content</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Save Text Only</button>
                </form>
            </div>
            
            <div class="col-md-6">
                <h3>Test 2: Với featured image</h3>
                <form action="{{ route('admin.monuments.store') }}" method="POST" enctype="multipart/form-data" style="border: 2px solid red; padding: 20px;">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="title2" class="form-label">Title *</label>
                        <input type="text" class="form-control" id="title2" name="title" value="Test With Image" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="location2" class="form-label">Location *</label>
                        <input type="text" class="form-control" id="location2" name="location" value="Test Location" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="zone2" class="form-label">Zone *</label>
                        <select class="form-select" id="zone2" name="zone" required>
                            <option value="North">North</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="content2" class="form-label">Content *</label>
                        <textarea class="form-control" id="content2" name="content" rows="3" required>Test content</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status2" class="form-label">Status</label>
                        <select class="form-select" id="status2" name="status">
                            <option value="draft">Draft</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="featured_image" class="form-label">Featured Image *</label>
                        <input type="file" class="form-control" id="featured_image" name="featured_image" accept="image/*" required>
                        <small class="text-muted">Chọn file nhỏ < 2MB</small>
                    </div>
                    
                    <button type="submit" class="btn btn-danger">Save With Image</button>
                </form>
            </div>
        </div>
        
        <div class="mt-4">
            <h4>Debug Info:</h4>
            <ul>
                <li><strong>PHP upload_max_filesize:</strong> {{ ini_get('upload_max_filesize') }}</li>
                <li><strong>PHP post_max_size:</strong> {{ ini_get('post_max_size') }}</li>
                <li><strong>Storage path exists:</strong> {{ Storage::disk('public')->exists('monuments') ? 'YES' : 'NO' }}</li>
                <li><strong>Storage writable:</strong> {{ is_writable(storage_path('app/public')) ? 'YES' : 'NO' }}</li>
            </ul>
        </div>
    </div>
</body>
</html>
