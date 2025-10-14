# **🔌 API Documentation - Global Heritage Project**

## **📋 Overview**

API documentation cho hệ thống Global Heritage, bao gồm tất cả endpoints, request/response formats, và authentication methods.

---

## **🔐 Authentication**

### **API Token Authentication**
```http
Authorization: Bearer {token}
```

### **Session Authentication (Web)**
```http
X-CSRF-TOKEN: {csrf_token}
```

---

## **🌐 Base URLs**

- **Development**: `http://127.0.0.1:8000`
- **API Base**: `http://127.0.0.1:8000/api`
- **Admin Panel**: `http://127.0.0.1:8000/admin`

---

## **📊 Response Format**

### **Success Response**
```json
{
    "success": true,
    "data": {
        // Response data
    },
    "message": "Success message"
}
```

### **Error Response**
```json
{
    "success": false,
    "error": {
        "code": "ERROR_CODE",
        "message": "Error description"
    },
    "data": null
}
```

### **Pagination Response**
```json
{
    "success": true,
    "data": {
        "items": [...],
        "pagination": {
            "current_page": 1,
            "last_page": 10,
            "per_page": 15,
            "total": 150,
            "from": 1,
            "to": 15
        }
    }
}
```

---

## **🏛️ Monuments API**

### **GET /api/monuments**
Lấy danh sách di tích

**Query Parameters:**
- `page` (int): Số trang (default: 1)
- `per_page` (int): Số item per page (default: 15)
- `search` (string): Tìm kiếm theo tên
- `zone` (string): Lọc theo vùng (north, south, central, east, west)
- `wonder` (boolean): Lọc di tích kỳ quan thế giới
- `language` (string): Ngôn ngữ (vi, en)

**Response:**
```json
{
    "success": true,
    "data": {
        "items": [
            {
                "id": 1,
                "name": "Angkor Wat",
                "slug": "angkor-wat",
                "description": "Ancient temple complex...",
                "location": "Siem Reap, Cambodia",
                "latitude": 13.4125,
                "longitude": 103.8670,
                "zone": "southeast",
                "is_wonder": true,
                "status": "approved",
                "created_at": "2024-01-01T00:00:00Z",
                "updated_at": "2024-01-01T00:00:00Z",
                "images": [
                    {
                        "id": 1,
                        "url": "https://res.cloudinary.com/...",
                        "alt": "Angkor Wat main temple",
                        "is_primary": true
                    }
                ],
                "translations": {
                    "vi": {
                        "name": "Angkor Wat",
                        "description": "Quần thể đền cổ..."
                    },
                    "en": {
                        "name": "Angkor Wat",
                        "description": "Ancient temple complex..."
                    }
                }
            }
        ],
        "pagination": {
            "current_page": 1,
            "last_page": 10,
            "per_page": 15,
            "total": 150
        }
    }
}
```

### **GET /api/monuments/{id}**
Lấy chi tiết di tích

**Path Parameters:**
- `id` (int): ID của di tích

**Query Parameters:**
- `language` (string): Ngôn ngữ (vi, en)

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Angkor Wat",
        "slug": "angkor-wat",
        "description": "Ancient temple complex...",
        "location": "Siem Reap, Cambodia",
        "latitude": 13.4125,
        "longitude": 103.8670,
        "zone": "southeast",
        "is_wonder": true,
        "status": "approved",
        "created_at": "2024-01-01T00:00:00Z",
        "updated_at": "2024-01-01T00:00:00Z",
        "images": [...],
        "feedbacks": [
            {
                "id": 1,
                "rating": 5,
                "comment": "Amazing place!",
                "user_name": "John Doe",
                "created_at": "2024-01-01T00:00:00Z"
            }
        ],
        "translations": {...}
    }
}
```

### **POST /api/monuments** (Admin/Moderator)
Tạo di tích mới

**Headers:**
```http
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
    "name": "New Monument",
    "description": "Monument description",
    "location": "City, Country",
    "latitude": 13.4125,
    "longitude": 103.8670,
    "zone": "southeast",
    "is_wonder": false,
    "images": [
        {
            "url": "https://res.cloudinary.com/...",
            "alt": "Image description",
            "is_primary": true
        }
    ],
    "translations": {
        "vi": {
            "name": "Di Tích Mới",
            "description": "Mô tả di tích..."
        },
        "en": {
            "name": "New Monument",
            "description": "Monument description..."
        }
    }
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 2,
        "name": "New Monument",
        "slug": "new-monument",
        "status": "pending",
        "created_at": "2024-01-01T00:00:00Z"
    },
    "message": "Monument created successfully"
}
```

### **PUT /api/monuments/{id}** (Admin/Moderator)
Cập nhật di tích

**Headers:**
```http
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:** (Same as POST)

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 2,
        "name": "Updated Monument",
        "updated_at": "2024-01-01T00:00:00Z"
    },
    "message": "Monument updated successfully"
}
```

### **DELETE /api/monuments/{id}** (Admin)
Xóa di tích

**Headers:**
```http
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "message": "Monument deleted successfully"
}
```

---

## **📝 Posts API**

### **GET /api/posts**
Lấy danh sách bài viết

**Query Parameters:**
- `page` (int): Số trang (default: 1)
- `per_page` (int): Số item per page (default: 15)
- `search` (string): Tìm kiếm theo tiêu đề
- `status` (string): Lọc theo trạng thái (draft, pending, approved)
- `language` (string): Ngôn ngữ (vi, en)

**Response:**
```json
{
    "success": true,
    "data": {
        "items": [
            {
                "id": 1,
                "title": "History of Angkor Wat",
                "slug": "history-of-angkor-wat",
                "excerpt": "Brief description...",
                "content": "Full article content...",
                "featured_image": "https://res.cloudinary.com/...",
                "status": "approved",
                "created_at": "2024-01-01T00:00:00Z",
                "updated_at": "2024-01-01T00:00:00Z",
                "author": {
                    "id": 1,
                    "name": "Admin User",
                    "email": "admin@example.com"
                },
                "monument": {
                    "id": 1,
                    "name": "Angkor Wat",
                    "slug": "angkor-wat"
                },
                "translations": {...}
            }
        ],
        "pagination": {...}
    }
}
```

### **GET /api/posts/{id}**
Lấy chi tiết bài viết

**Path Parameters:**
- `id` (int): ID của bài viết

**Query Parameters:**
- `language` (string): Ngôn ngữ (vi, en)

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "History of Angkor Wat",
        "slug": "history-of-angkor-wat",
        "content": "Full article content...",
        "featured_image": "https://res.cloudinary.com/...",
        "status": "approved",
        "created_at": "2024-01-01T00:00:00Z",
        "updated_at": "2024-01-01T00:00:00Z",
        "author": {...},
        "monument": {...},
        "comments": [...],
        "translations": {...}
    }
}
```

### **POST /api/posts** (Admin/Moderator)
Tạo bài viết mới

**Headers:**
```http
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
    "title": "New Article",
    "content": "Article content...",
    "excerpt": "Brief description...",
    "featured_image": "https://res.cloudinary.com/...",
    "monument_id": 1,
    "status": "draft",
    "translations": {
        "vi": {
            "title": "Bài Viết Mới",
            "content": "Nội dung bài viết...",
            "excerpt": "Mô tả ngắn..."
        },
        "en": {
            "title": "New Article",
            "content": "Article content...",
            "excerpt": "Brief description..."
        }
    }
}
```

### **PUT /api/posts/{id}** (Admin/Moderator)
Cập nhật bài viết

### **DELETE /api/posts/{id}** (Admin)
Xóa bài viết

---

## **📸 Gallery API**

### **GET /api/gallery**
Lấy danh sách hình ảnh

**Query Parameters:**
- `page` (int): Số trang
- `per_page` (int): Số item per page
- `monument_id` (int): Lọc theo di tích
- `category` (string): Lọc theo danh mục

**Response:**
```json
{
    "success": true,
    "data": {
        "items": [
            {
                "id": 1,
                "url": "https://res.cloudinary.com/...",
                "alt": "Image description",
                "title": "Image title",
                "category": "exterior",
                "monument_id": 1,
                "is_primary": true,
                "created_at": "2024-01-01T00:00:00Z"
            }
        ],
        "pagination": {...}
    }
}
```

### **POST /api/gallery** (Admin/Moderator)
Upload hình ảnh

**Headers:**
```http
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request Body:**
```json
{
    "images": [file1, file2, ...],
    "monument_id": 1,
    "category": "exterior",
    "alt": "Image description"
}
```

### **DELETE /api/gallery/{id}** (Admin/Moderator)
Xóa hình ảnh

---

## **⭐ Feedback API**

### **GET /api/feedbacks**
Lấy danh sách phản hồi

**Query Parameters:**
- `page` (int): Số trang
- `per_page` (int): Số item per page
- `monument_id` (int): Lọc theo di tích
- `post_id` (int): Lọc theo bài viết
- `rating` (int): Lọc theo rating (1-5)
- `status` (string): Lọc theo trạng thái (pending, approved, rejected)

**Response:**
```json
{
    "success": true,
    "data": {
        "items": [
            {
                "id": 1,
                "rating": 5,
                "comment": "Amazing place!",
                "user_name": "John Doe",
                "user_email": "john@example.com",
                "monument_id": 1,
                "post_id": null,
                "status": "approved",
                "created_at": "2024-01-01T00:00:00Z"
            }
        ],
        "pagination": {...}
    }
}
```

### **POST /api/feedbacks**
Tạo phản hồi mới

**Request Body:**
```json
{
    "rating": 5,
    "comment": "Great experience!",
    "user_name": "John Doe",
    "user_email": "john@example.com",
    "monument_id": 1,
    "post_id": null
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "rating": 5,
        "comment": "Great experience!",
        "status": "pending",
        "created_at": "2024-01-01T00:00:00Z"
    },
    "message": "Feedback submitted successfully"
}
```

### **PUT /api/feedbacks/{id}/approve** (Admin/Moderator)
Duyệt phản hồi

### **PUT /api/feedbacks/{id}/reject** (Admin/Moderator)
Từ chối phản hồi

### **DELETE /api/feedbacks/{id}** (Admin/Moderator)
Xóa phản hồi

---

## **📧 Contact API**

### **GET /api/contacts** (Admin/Moderator)
Lấy danh sách tin nhắn liên hệ

**Query Parameters:**
- `page` (int): Số trang
- `per_page` (int): Số item per page
- `status` (string): Lọc theo trạng thái (new, read, replied)

**Response:**
```json
{
    "success": true,
    "data": {
        "items": [
            {
                "id": 1,
                "name": "John Doe",
                "email": "john@example.com",
                "subject": "Question about monument",
                "message": "I have a question...",
                "status": "new",
                "created_at": "2024-01-01T00:00:00Z"
            }
        ],
        "pagination": {...}
    }
}
```

### **POST /api/contacts**
Gửi tin nhắn liên hệ

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "subject": "Question about monument",
    "message": "I have a question about..."
}
```

### **PUT /api/contacts/{id}/read** (Admin/Moderator)
Đánh dấu đã đọc

### **DELETE /api/contacts/{id}** (Admin/Moderator)
Xóa tin nhắn

---

## **👥 Users API**

### **GET /api/users** (Admin)
Lấy danh sách người dùng

**Query Parameters:**
- `page` (int): Số trang
- `per_page` (int): Số item per page
- `search` (string): Tìm kiếm theo tên/email
- `role` (string): Lọc theo vai trò (admin, moderator)

**Response:**
```json
{
    "success": true,
    "data": {
        "items": [
            {
                "id": 1,
                "name": "Admin User",
                "email": "admin@example.com",
                "role": "admin",
                "status": "active",
                "created_at": "2024-01-01T00:00:00Z",
                "posts_count": 10,
                "monuments_count": 5
            }
        ],
        "pagination": {...}
    }
}
```

### **POST /api/users** (Admin)
Tạo người dùng mới

**Request Body:**
```json
{
    "name": "New User",
    "email": "user@example.com",
    "password": "password123",
    "role": "moderator"
}
```

### **PUT /api/users/{id}** (Admin)
Cập nhật người dùng

### **DELETE /api/users/{id}** (Admin)
Xóa người dùng

---

## **⚙️ Settings API**

### **GET /api/settings** (Admin)
Lấy cài đặt hệ thống

**Response:**
```json
{
    "success": true,
    "data": {
        "site_name": "Global Heritage",
        "site_description": "Explore world monuments",
        "contact_email": "admin@example.com",
        "contact_phone": "+1234567890",
        "user_registration_enabled": true,
        "post_approval_required": true,
        "monument_approval_required": true,
        "moderator_can_manage_users": false
    }
}
```

### **PUT /api/settings** (Admin)
Cập nhật cài đặt hệ thống

**Request Body:**
```json
{
    "site_name": "Updated Site Name",
    "contact_email": "new@example.com",
    "user_registration_enabled": false
}
```

---

## **📊 Statistics API**

### **GET /api/statistics** (Admin)
Lấy thống kê hệ thống

**Response:**
```json
{
    "success": true,
    "data": {
        "total_monuments": 150,
        "total_posts": 75,
        "total_feedbacks": 300,
        "total_contacts": 25,
        "total_users": 10,
        "pending_monuments": 5,
        "pending_posts": 3,
        "unread_feedbacks": 12,
        "unread_contacts": 8
    }
}
```

---

## **🔔 Notifications API**

### **GET /api/notifications/feedback-count**
Lấy số lượng phản hồi chưa đọc

**Response:**
```json
{
    "success": true,
    "data": {
        "unread_count": 12
    }
}
```

### **POST /api/notifications/mark-feedbacks-viewed** (Admin/Moderator)
Đánh dấu tất cả phản hồi đã xem

**Response:**
```json
{
    "success": true,
    "message": "All feedbacks marked as viewed"
}
```

---

## **🔐 Authentication API**

### **POST /api/login**
Đăng nhập

**Request Body:**
```json
{
    "email": "admin@example.com",
    "password": "password123"
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "Admin User",
            "email": "admin@example.com",
            "role": "admin"
        },
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
    }
}
```

### **POST /api/logout**
Đăng xuất

**Headers:**
```http
Authorization: Bearer {token}
```

### **POST /api/register**
Đăng ký (nếu được bật)

**Request Body:**
```json
{
    "name": "New User",
    "email": "user@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

---

## **❌ Error Codes**

| Code | Description |
|------|-------------|
| `VALIDATION_ERROR` | Lỗi validation dữ liệu |
| `UNAUTHORIZED` | Chưa đăng nhập |
| `FORBIDDEN` | Không có quyền truy cập |
| `NOT_FOUND` | Không tìm thấy resource |
| `RATE_LIMITED` | Vượt quá giới hạn request |
| `SERVER_ERROR` | Lỗi server |

---

## **📝 Rate Limiting**

- **API Calls**: 100 requests per minute per IP
- **Login Attempts**: 5 attempts per minute per IP
- **Contact Form**: 3 submissions per hour per IP
- **Feedback**: 10 submissions per hour per IP

---

## **🔧 Testing**

### **Postman Collection**
Import collection từ: `docs/postman/Global-Heritage-API.postman_collection.json`

### **cURL Examples**
```bash
# Get monuments
curl -X GET "http://127.0.0.1:8000/api/monuments?page=1&per_page=10"

# Create feedback
curl -X POST "http://127.0.0.1:8000/api/feedbacks" \
  -H "Content-Type: application/json" \
  -d '{"rating": 5, "comment": "Great!", "user_name": "John", "user_email": "john@example.com", "monument_id": 1}'

# Login
curl -X POST "http://127.0.0.1:8000/api/login" \
  -H "Content-Type: application/json" \
  -d '{"email": "admin@example.com", "password": "password123"}'
```

---

**📋 API Documentation này cung cấp hướng dẫn đầy đủ cho việc tích hợp với hệ thống Global Heritage. Tất cả endpoints đều được test và sẵn sàng sử dụng.**


