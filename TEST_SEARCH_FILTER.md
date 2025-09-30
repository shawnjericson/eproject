# Test Cases cho Hệ thống Search và Filter

## Cách test

### 1. Test qua Browser (Admin Panel)

#### Monuments
1. Truy cập: `http://localhost:8000/admin/monuments`
2. Test các trường hợp:

**Test 1: Chỉ Search**
- Nhập "temple" vào ô Search
- Không chọn Status, không chọn Zone
- Click Filter
- ✅ Kết quả: Hiển thị tất cả monuments có "temple" trong title/description/history

**Test 2: Chỉ Filter Status**
- Không nhập Search
- Chọn Status = "Approved"
- Không chọn Zone
- Click Filter
- ✅ Kết quả: Hiển thị tất cả monuments có status = approved

**Test 3: Chỉ Filter Zone**
- Không nhập Search
- Không chọn Status
- Chọn Zone = "East"
- Click Filter
- ✅ Kết quả: Hiển thị tất cả monuments ở East zone

**Test 4: Search + 1 Filter**
- Nhập "temple" vào Search
- Chọn Zone = "East"
- Không chọn Status
- Click Filter
- ✅ Kết quả: Hiển thị monuments có "temple" VÀ ở East zone

**Test 5: Search + 2 Filters**
- Nhập "temple" vào Search
- Chọn Status = "Approved"
- Chọn Zone = "East"
- Click Filter
- ✅ Kết quả: Hiển thị monuments có "temple" VÀ status approved VÀ ở East zone

**Test 6: Clear All**
- Click button "Clear"
- ✅ Kết quả: Hiển thị tất cả monuments, URL không có query parameters

#### Posts
1. Truy cập: `http://localhost:8000/admin/posts`
2. Test các trường hợp:

**Test 1: Chỉ Search**
- Nhập "travel" vào ô Search
- Không chọn Status
- Click Filter
- ✅ Kết quả: Hiển thị tất cả posts có "travel"

**Test 2: Chỉ Filter Status**
- Không nhập Search
- Chọn Status = "Approved"
- Click Filter
- ✅ Kết quả: Hiển thị tất cả posts có status = approved

**Test 3: Search + Filter**
- Nhập "travel" vào Search
- Chọn Status = "Approved"
- Click Filter
- ✅ Kết quả: Hiển thị posts có "travel" VÀ status approved

#### Users
1. Truy cập: `http://localhost:8000/admin/users`
2. Test các trường hợp:

**Test 1: Chỉ Search**
- Nhập "john" vào ô Search
- Không chọn Role, không chọn Status
- Click Filter
- ✅ Kết quả: Hiển thị users có "john" trong name hoặc email

**Test 2: Chỉ Filter Role**
- Không nhập Search
- Chọn Role = "Admin"
- Không chọn Status
- Click Filter
- ✅ Kết quả: Hiển thị tất cả users có role = admin

**Test 3: Chỉ Filter Status**
- Không nhập Search
- Không chọn Role
- Chọn Status = "Active"
- Click Filter
- ✅ Kết quả: Hiển thị tất cả users có status = active

**Test 4: Search + 2 Filters**
- Nhập "john" vào Search
- Chọn Role = "Admin"
- Chọn Status = "Active"
- Click Filter
- ✅ Kết quả: Hiển thị users có "john" VÀ role admin VÀ status active

#### Feedbacks
1. Truy cập: `http://localhost:8000/admin/feedbacks`
2. Test các trường hợp:

**Test 1: Chỉ Search**
- Nhập "great" vào ô Search
- Không chọn Monument, không chọn Days
- Click Filter
- ✅ Kết quả: Hiển thị feedbacks có "great" trong name/email/message

**Test 2: Chỉ Filter Monument**
- Không nhập Search
- Chọn Monument (chọn 1 monument bất kỳ)
- Không chọn Days
- Click Filter
- ✅ Kết quả: Hiển thị feedbacks của monument đó

**Test 3: Chỉ Filter Days**
- Không nhập Search
- Không chọn Monument
- Chọn Days = "Last 7 days"
- Click Filter
- ✅ Kết quả: Hiển thị feedbacks trong 7 ngày gần đây

**Test 4: Search + 2 Filters**
- Nhập "great" vào Search
- Chọn Monument
- Chọn Days = "Last 7 days"
- Click Filter
- ✅ Kết quả: Hiển thị feedbacks có "great" VÀ của monument đó VÀ trong 7 ngày

#### Gallery
1. Truy cập: `http://localhost:8000/admin/gallery`
2. Test các trường hợp:

**Test 1: Chỉ Search**
- Nhập "sunset" vào ô Search
- Không chọn Monument
- Click Filter
- ✅ Kết quả: Hiển thị gallery có "sunset" trong title/description

**Test 2: Chỉ Filter Monument**
- Không nhập Search
- Chọn Monument
- Click Filter
- ✅ Kết quả: Hiển thị gallery của monument đó

**Test 3: Search + Filter**
- Nhập "sunset" vào Search
- Chọn Monument
- Click Filter
- ✅ Kết quả: Hiển thị gallery có "sunset" VÀ của monument đó

### 2. Test qua API (Postman/cURL)

#### Test Monuments API

**Test 1: Chỉ Search**
```bash
curl "http://localhost:8000/api/monuments?search=temple"
```

**Test 2: Chỉ Filter Zone**
```bash
curl "http://localhost:8000/api/monuments?zone=East"
```

**Test 3: Chỉ Filter Status (cần auth)**
```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
     "http://localhost:8000/api/monuments?status=approved"
```

**Test 4: Search + Zone**
```bash
curl "http://localhost:8000/api/monuments?search=temple&zone=East"
```

**Test 5: Search + Zone + Status (cần auth)**
```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
     "http://localhost:8000/api/monuments?search=temple&zone=East&status=approved"
```

**Test 6: Không có parameters**
```bash
curl "http://localhost:8000/api/monuments"
```

#### Test Posts API

**Test 1: Chỉ Search**
```bash
curl "http://localhost:8000/api/posts?search=travel"
```

**Test 2: Chỉ Filter Status (cần auth)**
```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
     "http://localhost:8000/api/posts?status=approved"
```

**Test 3: Search + Status (cần auth)**
```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
     "http://localhost:8000/api/posts?search=travel&status=approved"
```

#### Test Users API (cần auth)

**Test 1: Chỉ Search**
```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
     "http://localhost:8000/api/users?search=john"
```

**Test 2: Chỉ Filter Role**
```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
     "http://localhost:8000/api/users?role=admin"
```

**Test 3: Chỉ Filter Status**
```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
     "http://localhost:8000/api/users?status=active"
```

**Test 4: Search + Role + Status**
```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
     "http://localhost:8000/api/users?search=john&role=admin&status=active"
```

#### Test Feedbacks API

**Test 1: Chỉ Search**
```bash
curl "http://localhost:8000/api/feedbacks?search=great"
```

**Test 2: Chỉ Filter Monument**
```bash
curl "http://localhost:8000/api/feedbacks?monument_id=5"
```

**Test 3: Search + Monument**
```bash
curl "http://localhost:8000/api/feedbacks?search=great&monument_id=5"
```

#### Test Gallery API

**Test 1: Chỉ Search**
```bash
curl "http://localhost:8000/api/gallery?search=sunset"
```

**Test 2: Chỉ Filter Monument**
```bash
curl "http://localhost:8000/api/gallery?monument_id=5"
```

**Test 3: Search + Monument**
```bash
curl "http://localhost:8000/api/gallery?search=sunset&monument_id=5"
```

### 3. Test với Frontend React

Nếu bạn có frontend React, test như sau:

1. Mở trang Posts: `http://localhost:3000/posts`
2. Nhập từ khóa vào ô search
3. Click Search
4. Kiểm tra URL có chứa `?search=...`
5. Kiểm tra kết quả hiển thị đúng

## Expected Results

### Khi không có filter/search
- Hiển thị tất cả records (có phân trang)
- URL: `/admin/monuments` (không có query params)

### Khi chỉ có search
- Hiển thị records match với search term
- URL: `/admin/monuments?search=temple`

### Khi chỉ có 1 filter
- Hiển thị records match với filter đó
- URL: `/admin/monuments?zone=East`

### Khi có search + filters
- Hiển thị records match với TẤT CẢ điều kiện (AND logic)
- URL: `/admin/monuments?search=temple&zone=East&status=approved`

### Khi clear filters
- Quay về hiển thị tất cả
- URL: `/admin/monuments` (không có query params)

## Common Issues và Solutions

### Issue 1: Filter không hoạt động
**Triệu chứng**: Chọn filter nhưng vẫn hiển thị tất cả records

**Kiểm tra**:
1. Xem URL có chứa query parameter không?
2. Xem giá trị của parameter có đúng không?
3. Check console log xem có error không?

**Solution**: Đảm bảo form đang submit đúng và controller đang sử dụng `filled()` thay vì `has()`

### Issue 2: Search không tìm thấy
**Triệu chứng**: Search nhưng không có kết quả dù biết chắc có data

**Kiểm tra**:
1. Từ khóa search có đúng không?
2. Có filter nào đang active không?
3. Check database xem data có tồn tại không?

**Solution**: 
- Thử search với từ khóa ngắn hơn
- Clear tất cả filters trước khi search
- Kiểm tra xem search có case-sensitive không

### Issue 3: Pagination mất filter
**Triệu chứng**: Click sang trang khác thì mất filter/search

**Kiểm tra**: Xem pagination links có chứa query params không?

**Solution**: Đảm bảo sử dụng `appends(request()->query())`:
```blade
{{ $monuments->appends(request()->query())->links() }}
```

### Issue 4: Clear không hoạt động
**Triệu chứng**: Click Clear nhưng vẫn còn filter

**Kiểm tra**: Xem button Clear có link đúng không?

**Solution**: Đảm bảo Clear button link về URL gốc:
```blade
<a href="{{ route('admin.monuments.index') }}">Clear</a>
```

## Checklist

Sau khi test, đảm bảo:

- [ ] Search hoạt động độc lập (không cần chọn filter)
- [ ] Mỗi filter hoạt động độc lập (không cần chọn filter khác)
- [ ] Search + 1 filter hoạt động
- [ ] Search + nhiều filters hoạt động
- [ ] Clear filters hoạt động
- [ ] Pagination giữ nguyên filters
- [ ] URL parameters đúng
- [ ] Không có error trong console
- [ ] Kết quả hiển thị đúng
- [ ] Performance tốt (không quá chậm)

## Performance Notes

- Search sử dụng `LIKE` nên có thể chậm với database lớn
- Nên thêm index cho các cột thường xuyên search:
  ```sql
  ALTER TABLE monuments ADD INDEX idx_title (title);
  ALTER TABLE monuments ADD INDEX idx_zone (zone);
  ALTER TABLE monuments ADD INDEX idx_status (status);
  ```

- Với database rất lớn, nên cân nhắc sử dụng Full-Text Search hoặc Elasticsearch

