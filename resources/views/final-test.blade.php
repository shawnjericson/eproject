<!DOCTYPE html>
<html>
<head>
    <title>Final Test Upload</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Final Test - Cực Kỳ Đơn Giản</h1>
        
        <div id="result"></div>
        
        <form id="testForm" enctype="multipart/form-data" style="border: 2px solid green; padding: 20px;">
            <h3>Test Upload Featured Image</h3>
            
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="Final Test">
            </div>
            
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location" value="Test Location">
            </div>
            
            <div class="mb-3">
                <label for="zone" class="form-label">Zone</label>
                <select class="form-select" id="zone" name="zone">
                    <option value="North">North</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control" id="content" name="content" rows="3">Test content</textarea>
            </div>
            
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="draft">Draft</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="featured_image" class="form-label">Featured Image</label>
                <input type="file" class="form-control" id="featured_image" name="featured_image" accept="image/*">
                <small class="text-muted">Chọn file hình < 5MB</small>
            </div>
            
            <button type="submit" class="btn btn-success">Test Upload</button>
        </form>
        
        <div class="mt-4">
            <h4>Debug Info:</h4>
            <ul>
                <li><strong>PHP upload_max_filesize:</strong> {{ ini_get('upload_max_filesize') }}</li>
                <li><strong>PHP post_max_size:</strong> {{ ini_get('post_max_size') }}</li>
                <li><strong>Storage writable:</strong> {{ is_writable(storage_path('app/public')) ? 'YES' : 'NO' }}</li>
            </ul>
        </div>
    </div>

    <script>
        document.getElementById('testForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const resultDiv = document.getElementById('result');
            
            // Add CSRF token
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            resultDiv.innerHTML = '<div class="alert alert-info">Uploading...</div>';
            
            fetch('/test-upload', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    resultDiv.innerHTML = '<div class="alert alert-success">SUCCESS! Monument ID: ' + data.monument_id + '</div>';
                } else {
                    resultDiv.innerHTML = '<div class="alert alert-danger">ERROR: ' + (data.error || 'Unknown error') + '</div>';
                }
            })
            .catch(error => {
                resultDiv.innerHTML = '<div class="alert alert-danger">FETCH ERROR: ' + error.message + '</div>';
            });
        });
    </script>
</body>
</html>
