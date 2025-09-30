# Tóm tắt triển khai phân quyền CMS PHP

## Yêu cầu đã thực hiện

✅ **Moderator**: Có thể đăng/edit bài post, monument, gallery  
✅ **Moderator**: Không được xóa content đã approve  
✅ **Admin**: Chỉ admin mới được approve content  
✅ **Admin**: Có thể xóa mọi content (kể cả đã approve)  

## Các thành phần đã triển khai

### 1. Middleware mới
- **`AdminApprovalMiddleware`**: Đảm bảo chỉ admin mới có thể approve content
- **`PreventApprovedDeletionMiddleware`**: Ngăn moderator xóa content đã approve

### 2. Routes được bảo vệ
**Web Routes:**
- `POST /admin/posts/{post}/approve` → middleware: `admin.approval`
- `POST /admin/posts/{post}/reject` → middleware: `admin.approval`
- `DELETE /admin/posts/{post}` → middleware: `prevent.approved.deletion`
- `POST /admin/monuments/{monument}/approve` → middleware: `admin.approval`
- `DELETE /admin/monuments/{monument}` → middleware: `prevent.approved.deletion`

**API Routes:**
- `POST /api/posts/{post}/approve` → middleware: `admin.approval`
- `POST /api/posts/{post}/reject` → middleware: `admin.approval`
- `DELETE /api/posts/{post}` → middleware: `prevent.approved.deletion`
- `POST /api/monuments/{monument}/approve` → middleware: `admin.approval`
- `DELETE /api/monuments/{monument}` → middleware: `prevent.approved.deletion`

### 3. Controllers được cập nhật
**Admin Controllers:**
- `PostController::destroy()` - kiểm tra quyền xóa
- `PostController::approve()` - kiểm tra quyền approve
- `MonumentController::destroy()` - kiểm tra quyền xóa
- `MonumentController::approve()` - kiểm tra quyền approve

**API Controllers:**
- `Api\PostController::destroy()` - kiểm tra quyền xóa
- `Api\PostController::approve()` - kiểm tra quyền approve
- `Api\MonumentController::destroy()` - kiểm tra quyền xóa
- `Api\MonumentController::approve()` - kiểm tra quyền approve

### 4. Views được cập nhật
**Posts:**
- `admin/posts/show_professional.blade.php` - ẩn/hiện nút approve/delete
- `admin/posts/index.blade.php` - ẩn/hiện nút delete cho approved content
- `admin/posts/edit.blade.php` - nút approve chỉ hiện cho admin

**Monuments:**
- `admin/monuments/show.blade.php` - ẩn/hiện nút approve/delete
- `admin/monuments/index.blade.php` - ẩn/hiện nút approve/delete

**Gallery:**
- Không thay đổi (gallery không có status field)

## Logic phân quyền

### Approve Content
- **Admin**: ✅ Có thể approve posts và monuments
- **Moderator**: ❌ Không thể approve (middleware + controller check)

### Delete Content
- **Admin**: ✅ Có thể xóa mọi content (approved/pending)
- **Moderator**: 
  - ✅ Có thể xóa content pending/draft
  - ❌ Không thể xóa content approved (middleware + controller check)

### Create/Edit Content
- **Admin**: ✅ Có thể tạo/edit mọi content
- **Moderator**: ✅ Có thể tạo/edit posts, monuments, gallery

### Gallery
- **Admin**: ✅ Có thể tạo/edit/xóa gallery
- **Moderator**: ✅ Có thể tạo/edit/xóa gallery (gallery không có approval system)

## Test Data
Đã tạo test data với seeder `PermissionTestSeeder`:
- Test admin user: `test-admin@example.com` / `password123`
- Test moderator user: `test-moderator@example.com` / `password123`
- Test posts: 1 pending, 1 approved
- Test monuments: 1 pending, 1 approved

## Cách kiểm tra

### 1. Test với Moderator
```
Login: test-moderator@example.com / password123
- Thử approve post/monument → Should fail (403)
- Thử xóa approved post/monument → Should fail (403)
- Thử xóa pending post/monument → Should success
- Tạo/edit content → Should success
```

### 2. Test với Admin
```
Login: test-admin@example.com / password123
- Approve post/monument → Should success
- Xóa approved post/monument → Should success
- Xóa pending post/monument → Should success
- Tạo/edit content → Should success
```

### 3. Test API
```bash
# Get moderator token
POST /api/login
{
  "email": "test-moderator@example.com",
  "password": "password123"
}

# Try approve with moderator token (should fail)
POST /api/posts/{approved_post_id}/approve
Authorization: Bearer {moderator_token}
# Expected: 403 Forbidden

# Try delete approved content with moderator token (should fail)
DELETE /api/posts/{approved_post_id}
Authorization: Bearer {moderator_token}
# Expected: 403 Forbidden
```

## Files đã thay đổi
1. `app/Http/Middleware/AdminApprovalMiddleware.php` (new)
2. `app/Http/Middleware/PreventApprovedDeletionMiddleware.php` (new)
3. `app/Http/Kernel.php` (updated)
4. `routes/web.php` (updated)
5. `routes/api.php` (updated)
6. `app/Http/Controllers/Admin/PostController.php` (updated)
7. `app/Http/Controllers/Api/PostController.php` (updated)
8. `app/Http/Controllers/Admin/MonumentController.php` (updated)
9. `app/Http/Controllers/Api/MonumentController.php` (updated)
10. `resources/views/admin/posts/show_professional.blade.php` (updated)
11. `resources/views/admin/posts/index.blade.php` (updated)
12. `resources/views/admin/monuments/show.blade.php` (updated)
13. `resources/views/admin/monuments/index.blade.php` (updated)
14. `database/seeders/PermissionTestSeeder.php` (new)
15. `test_permissions.php` (new - test script)

## Kết luận
Hệ thống phân quyền đã được triển khai hoàn chỉnh theo yêu cầu:
- Moderator có thể tạo/edit content nhưng không thể approve
- Moderator không thể xóa content đã approve
- Admin có full quyền approve và delete
- Bảo vệ ở nhiều tầng: middleware, controller, view
