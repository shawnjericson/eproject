<!DOCTYPE html>
<html>
<head>
    <title>Cloudinary Test Upload</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>🌤️ Cloudinary Upload Test</h1>
        <p class="text-muted">Upload hình lên Cloudinary - không giới hạn kích thước!</p>
        
        <div id="result"></div>
        
        <form id="cloudinaryForm" enctype="multipart/form-data" style="border: 2px solid #007bff; padding: 20px; border-radius: 10px;">
            <h3>Test Upload to Cloudinary</h3>
            
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="Cloudinary Test">
            </div>
            
            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location" value="Test Location">
            </div>
            
            <div class="mb-3">
                <label for="zone" class="form-label">Zone</label>
                <select class="form-select" id="zone" name="zone">
                    <option value="North">North</option>
                    <option value="South">South</option>
                    <option value="East">East</option>
                    <option value="West">West</option>
                    <option value="Central">Central</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control" id="content" name="content" rows="3">Test content with Cloudinary</textarea>
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
                <small class="text-success">✅ Cloudinary hỗ trợ file lớn (lên đến 10MB+)</small>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-cloud-upload-alt"></i> Upload to Cloudinary
            </button>
        </form>
        
        <div class="mt-4">
            <h4>🔧 Setup Instructions:</h4>
            <div class="alert alert-info">
                <p><strong>Để sử dụng Cloudinary, bạn cần:</strong></p>
                <ol>
                    <li>Đăng ký tài khoản miễn phí tại: <a href="https://cloudinary.com" target="_blank">cloudinary.com</a></li>
                    <li>Lấy thông tin từ Dashboard:</li>
                    <ul>
                        <li><code>Cloud Name</code></li>
                        <li><code>API Key</code></li>
                        <li><code>API Secret</code></li>
                    </ul>
                    <li>Cập nhật file <code>.env</code>:</li>
                    <pre>CLOUDINARY_CLOUD_NAME=your_cloud_name
CLOUDINARY_API_KEY=your_api_key
CLOUDINARY_API_SECRET=your_api_secret</pre>
                </ol>
            </div>
            
            <h4>✅ Ưu điểm Cloudinary:</h4>
            <ul class="list-group">
                <li class="list-group-item">🚀 Upload nhanh, không giới hạn kích thước</li>
                <li class="list-group-item">🌍 CDN toàn cầu - load hình siêu nhanh</li>
                <li class="list-group-item">🔧 Tự động optimize (WebP, AVIF, resize)</li>
                <li class="list-group-item">💾 Không tốn dung lượng server</li>
                <li class="list-group-item">🔒 Secure URLs</li>
                <li class="list-group-item">📱 Responsive images tự động</li>
            </ul>
        </div>
    </div>

    <script>
        document.getElementById('cloudinaryForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const resultDiv = document.getElementById('result');
            
            // Add CSRF token
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            resultDiv.innerHTML = '<div class="alert alert-info">🌤️ Uploading to Cloudinary...</div>';
            
            fetch('/cloudinary-test', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    resultDiv.innerHTML = `
                        <div class="alert alert-success">
                            <h5>✅ SUCCESS!</h5>
                            <p><strong>Monument ID:</strong> ${data.monument_id}</p>
                            <p><strong>Image URL:</strong> <a href="${data.image_url}" target="_blank">${data.image_url}</a></p>
                            <img src="${data.image_url}" style="max-width: 300px; height: auto;" class="img-thumbnail mt-2">
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = '<div class="alert alert-danger">❌ ERROR: ' + (data.error || 'Unknown error') + '</div>';
                }
            })
            .catch(error => {
                resultDiv.innerHTML = '<div class="alert alert-danger">❌ FETCH ERROR: ' + error.message + '</div>';
            });
        });
    </script>
</body>
</html>
