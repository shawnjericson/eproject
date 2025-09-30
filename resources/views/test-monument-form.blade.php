<!DOCTYPE html>
<html>
<head>
    <title>Test Monument Form</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>🏛️ Test Monument Creation</h1>
        
        <div id="result"></div>
        
        <form action="{{ route('admin.monuments.store') }}" method="POST" enctype="multipart/form-data" class="border p-4 rounded">
            @csrf
            
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="Test Monument {{ time() }}" required>
            </div>
            
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location" value="Test Location" required>
            </div>
            
            <div class="mb-3">
                <label for="zone" class="form-label">Zone</label>
                <select class="form-select" id="zone" name="zone" required>
                    <option value="North">North</option>
                    <option value="South">South</option>
                    <option value="East">East</option>
                    <option value="West">West</option>
                    <option value="Central">Central</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control" id="content" name="content" rows="3" required>Test content for monument</textarea>
            </div>
            
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="draft">Draft</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="featured_image" class="form-label">Featured Image</label>
                <input type="file" class="form-control" id="featured_image" name="featured_image" accept="image/*">
                <small class="text-muted">Max size: 5MB</small>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Gallery Images</label>
                <input type="file" class="form-control mb-2" name="gallery_images[]" accept="image/*" multiple>
                <small class="text-muted">Select multiple images (Max 5MB each)</small>
            </div>
            
            <button type="submit" class="btn btn-primary">Create Monument</button>
        </form>
        
        <div class="mt-4">
            <h4>Test Results:</h4>
            <div class="alert alert-info">
                <p><strong>What this test does:</strong></p>
                <ul>
                    <li>✅ Upload featured image to Cloudinary</li>
                    <li>✅ Batch upload gallery images to Cloudinary</li>
                    <li>✅ Save monument with Cloudinary URLs</li>
                    <li>✅ Create gallery records with Cloudinary URLs</li>
                    <li>✅ Test file size limits (5MB)</li>
                </ul>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger mt-3">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</body>
</html>
