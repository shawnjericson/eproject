# Hướng dẫn Hệ thống Search và Filter

## Tổng quan

Hệ thống search và filter đã được cải thiện để hoạt động linh hoạt và độc lập:

### Nguyên tắc hoạt động:

1. **Search hoạt động độc lập**: Bạn có thể tìm kiếm mà không cần chọn bất kỳ filter nào
2. **Filter hoạt động độc lập**: Mỗi filter chỉ áp dụng khi bạn chọn giá trị, các filter không chọn sẽ bị bỏ qua
3. **Kết hợp Search + Filter**: Có thể sử dụng cả search và filter cùng lúc

## Thay đổi kỹ thuật

### Trước đây (Có vấn đề):
```php
if ($request->has('status') && $request->status !== '') {
    $query->where('status', $request->status);
}

// Search chỉ trong bảng chính, không search trong translations
if ($request->has('search') && $request->search !== '') {
    $query->where('title', 'like', '%' . $request->search . '%');
}
```

**Vấn đề**:
- `has()` chỉ kiểm tra key có tồn tại, không kiểm tra giá trị
- `!== ''` không xử lý được `null` hoặc các giá trị falsy khác
- Khi submit form với select rỗng, vẫn gửi `status=` (empty string)
- Search không tìm trong bảng translations (không tìm được nội dung tiếng Việt)

### Bây giờ (Đã sửa):
```php
// Filter - chỉ áp dụng khi có giá trị
if ($request->filled('status')) {
    $query->where('status', $request->status);
}

// Search - tìm trong cả bảng chính và bảng translations
if ($request->filled('search')) {
    $query->where(function($q) use ($request) {
        $searchTerm = '%' . $request->search . '%';
        $q->where('title', 'like', $searchTerm)
          ->orWhere('description', 'like', $searchTerm)
          ->orWhereHas('translations', function($tq) use ($searchTerm) {
              $tq->where('title', 'like', $searchTerm)
                 ->orWhere('description', 'like', $searchTerm)
                 ->orWhere('content', 'like', $searchTerm);
          });
    });
}
```

**Cải thiện**:
- `filled()` kiểm tra cả key tồn tại VÀ giá trị không rỗng
- Xử lý đúng cả `null`, `''`, `0`, `false`
- Chỉ áp dụng filter khi thực sự có giá trị
- Search tìm trong cả bảng chính VÀ bảng translations (hỗ trợ đa ngôn ngữ)

## Các module đã được cập nhật

### 1. Monuments (Di tích)

**Filters có sẵn:**
- Status (draft, pending, approved)
- Zone (East, North, West, South, Central)
- Search (title, description, history, content, location - tìm trong cả bảng chính và translations)

**Cách sử dụng:**
```
# Chỉ search
?search=temple

# Chỉ filter zone
?zone=East

# Chỉ filter status
?status=approved

# Kết hợp search + zone
?search=temple&zone=East

# Kết hợp cả 3
?search=temple&zone=East&status=approved
```

### 2. Posts (Bài viết)

**Filters có sẵn:**
- Status (draft, pending, approved)
- Search (title, content - tìm trong cả bảng chính và translations)

**Cách sử dụng:**
```
# Chỉ search
?search=travel

# Chỉ filter status
?status=approved

# Kết hợp
?search=travel&status=approved
```

### 3. Users (Người dùng)

**Filters có sẵn:**
- Role (admin, moderator, user)
- Status (active, inactive)
- Search (name, email)

**Cách sử dụng:**
```
# Chỉ search
?search=john

# Chỉ filter role
?role=admin

# Chỉ filter status
?status=active

# Kết hợp
?search=john&role=admin&status=active
```

### 4. Feedbacks (Phản hồi)

**Filters có sẵn:**
- Monument ID (filter theo di tích cụ thể)
- Days (1, 7, 30 - filter theo thời gian)
- Search (name, email, message)

**Cách sử dụng:**
```
# Chỉ search
?search=great

# Chỉ filter monument
?monument_id=5

# Chỉ filter days
?days=7

# Kết hợp
?search=great&monument_id=5&days=7
```

### 5. Gallery (Thư viện ảnh)

**Filters có sẵn:**
- Monument ID (filter theo di tích cụ thể)
- Search (title, description)

**Cách sử dụng:**
```
# Chỉ search
?search=sunset

# Chỉ filter monument
?monument_id=5

# Kết hợp
?search=sunset&monument_id=5
```

## API Endpoints

Tất cả các API endpoint đều hỗ trợ search và filter:

### Monuments
```
GET /api/monuments?search=temple&zone=East&status=approved
```

### Posts
```
GET /api/posts?search=travel&status=approved
```

### Users
```
GET /api/users?search=john&role=admin&status=active
```

### Feedbacks
```
GET /api/feedbacks?search=great&monument_id=5&days=7
```

### Gallery
```
GET /api/gallery?search=sunset&monument_id=5
```

## Ví dụ sử dụng trong Frontend

### React/JavaScript
```javascript
const fetchData = async () => {
  const params = new URLSearchParams();
  
  // Chỉ thêm param khi có giá trị
  if (search) params.append('search', search);
  if (zone) params.append('zone', zone);
  if (status) params.append('status', status);
  
  const response = await fetch(`/api/monuments?${params}`);
  const data = await response.json();
};
```

### Blade Template
```blade
<form method="GET" action="{{ route('admin.monuments.index') }}">
    <select name="zone">
        <option value="">All Zones</option>
        <option value="East">East</option>
        <option value="North">North</option>
    </select>
    
    <input type="text" name="search" placeholder="Search...">
    
    <button type="submit">Filter</button>
    <a href="{{ route('admin.monuments.index') }}">Clear</a>
</form>
```

## Testing

### Test Case 1: Chỉ Search
```
URL: /admin/monuments?search=temple
Kết quả: Tìm tất cả monuments có "temple" trong title/description/history
```

### Test Case 2: Chỉ Filter Zone
```
URL: /admin/monuments?zone=East
Kết quả: Hiển thị tất cả monuments ở East zone
```

### Test Case 3: Chỉ Filter Status
```
URL: /admin/monuments?status=approved
Kết quả: Hiển thị tất cả monuments có status approved
```

### Test Case 4: Search + 1 Filter
```
URL: /admin/monuments?search=temple&zone=East
Kết quả: Tìm monuments có "temple" VÀ ở East zone
```

### Test Case 5: Search + Nhiều Filters
```
URL: /admin/monuments?search=temple&zone=East&status=approved
Kết quả: Tìm monuments có "temple" VÀ ở East zone VÀ status approved
```

### Test Case 6: Không có gì (Clear All)
```
URL: /admin/monuments
Kết quả: Hiển thị tất cả monuments
```

## Lưu ý quan trọng

1. **Empty Select**: Khi không chọn gì trong dropdown, giá trị là `""` (empty string), hệ thống sẽ bỏ qua filter đó

2. **Pagination**: Khi filter/search, pagination vẫn hoạt động bình thường và giữ nguyên các tham số filter

3. **Clear Filters**: Button "Clear" sẽ redirect về URL gốc không có query parameters

4. **Case Insensitive**: Search không phân biệt chữ hoa/thường (sử dụng LIKE trong SQL)

5. **Multilingual**: Search hỗ trợ cả tiếng Anh và tiếng Việt (tìm trong cả bảng chính và bảng translations)

## Troubleshooting

### Vấn đề: Filter không hoạt động
**Giải pháp**: Kiểm tra xem form có đang submit đúng parameters không bằng cách xem URL

### Vấn đề: Search không tìm thấy kết quả
**Giải pháp**: 
- Kiểm tra xem từ khóa có đúng không
- Thử search với từ khóa ngắn hơn
- Kiểm tra xem có filter nào đang active không

### Vấn đề: Pagination mất filter
**Giải pháp**: Đảm bảo sử dụng `appends(request()->query())` trong Blade:
```blade
{{ $monuments->appends(request()->query())->links() }}
```

## Code Reference

Các file đã được cập nhật:
- `app/Http/Controllers/Api/MonumentController.php`
- `app/Http/Controllers/Admin/MonumentController.php`
- `app/Http/Controllers/Api/PostController.php`
- `app/Http/Controllers/Admin/PostController.php`
- `app/Http/Controllers/Api/UserController.php`
- `app/Http/Controllers/Admin/UserController.php`
- `app/Http/Controllers/Api/FeedbackController.php`
- `app/Http/Controllers/Admin/FeedbackController.php`
- `app/Http/Controllers/Api/GalleryController.php`
- `app/Http/Controllers/Admin/GalleryController.php`
- `resources/views/admin/gallery/index.blade.php`

