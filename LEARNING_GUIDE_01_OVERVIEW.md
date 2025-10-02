# 📚 Hướng Dẫn Học Code - Phần 1: Tổng Quan Dự Án

## 🎯 Mục Tiêu Của Tài Liệu Này

Tài liệu này được viết để giúp bạn hiểu **từng dòng code** trong dự án Global Heritage. Chúng ta sẽ đi từ cơ bản đến nâng cao, giải thích mọi thứ một cách đơn giản nhất.

---

## 📖 Cấu Trúc Tài Liệu Học Tập

Tôi đã chia thành 10 phần để dễ học:

1. **LEARNING_GUIDE_01_OVERVIEW.md** ← Bạn đang đọc
   - Tổng quan dự án
   - Kiến trúc hệ thống
   - Công nghệ sử dụng

2. **LEARNING_GUIDE_02_BACKEND_BASICS.md**
   - Laravel là gì?
   - MVC Pattern
   - Routes, Controllers, Models

3. **LEARNING_GUIDE_03_DATABASE.md**
   - Database structure
   - Migrations
   - Relationships

4. **LEARNING_GUIDE_04_BACKEND_ADVANCED.md**
   - Middleware
   - Authentication
   - API Controllers

5. **LEARNING_GUIDE_05_FRONTEND_BASICS.md**
   - React là gì?
   - Components
   - JSX syntax

6. **LEARNING_GUIDE_06_REACT_HOOKS.md**
   - useState
   - useEffect
   - useCallback, useMemo

7. **LEARNING_GUIDE_07_ROUTING_API.md**
   - React Router
   - API calls
   - Axios vs Fetch

8. **LEARNING_GUIDE_08_STYLING.md**
   - Tailwind CSS
   - Responsive design
   - Animations

9. **LEARNING_GUIDE_09_ADVANCED_FEATURES.md**
   - Leaflet Maps
   - Image optimization
   - Infinite scroll

10. **LEARNING_GUIDE_10_DEPLOYMENT.md**
    - Environment variables
    - Production build
    - Deployment

---

## 🏗️ Dự Án Là Gì?

**Global Heritage** là một website quản lý và hiển thị thông tin về các di sản thế giới.

### Có 2 Phần Chính:

```
┌─────────────────────────────────────────────────────────┐
│                    GLOBAL HERITAGE                       │
└─────────────────────────────────────────────────────────┘
                            │
                ┌───────────┴───────────┐
                │                       │
        ┌───────▼────────┐      ┌──────▼──────┐
        │   BACKEND      │      │  FRONTEND   │
        │   (Laravel)    │◄────►│   (React)   │
        │   Admin Panel  │ API  │  Public Web │
        └───────┬────────┘      └──────┬──────┘
                │                      │
        ┌───────▼────────┐      ┌──────▼──────┐
        │   DATABASE     │      │   BROWSER   │
        │    (MySQL)     │      │   (Users)   │
        └────────────────┘      └─────────────┘
```

---

## 🎨 Backend (Laravel) - Admin Panel

### Mục Đích:
Dành cho **Admin và Moderator** để quản lý nội dung.

### Chức Năng:
- ✅ Đăng nhập/đăng xuất
- ✅ Quản lý monuments (di tích)
- ✅ Quản lý gallery (hình ảnh)
- ✅ Quản lý blog posts
- ✅ Xem feedback từ users
- ✅ Quản lý users
- ✅ Upload hình ảnh

### Công Nghệ:
- **Laravel 10+** - PHP Framework
- **MySQL** - Database
- **Bootstrap 5** - UI Framework
- **Cloudinary** - Image hosting

### URL:
```
http://localhost:8000/admin
```

---

## 🌐 Frontend (React) - Public Website

### Mục Đích:
Dành cho **người dùng thông thường** xem thông tin.

### Chức Năng:
- ✅ Xem danh sách monuments
- ✅ Xem chi tiết monument
- ✅ Xem gallery hình ảnh
- ✅ Đọc blog posts
- ✅ Gửi feedback
- ✅ Liên hệ
- ✅ Xem bản đồ

### Công Nghệ:
- **React 19** - JavaScript Library
- **Tailwind CSS** - Styling
- **Leaflet** - Maps
- **Axios** - HTTP requests

### URL:
```
http://localhost:3000
```

---

## 🔄 Luồng Hoạt Động

### Ví Dụ: User Xem Danh Sách Monuments

```
1. User mở browser → http://localhost:3000/monuments

2. React App load → Monuments.jsx component

3. Component gọi API:
   fetch('http://localhost:8000/api/monuments')

4. Laravel nhận request → MonumentController@index

5. Controller query database:
   Monument::with('translations')->paginate(24)

6. Database trả về data → Controller format thành JSON

7. API trả về JSON cho React:
   {
     "data": [
       {
         "id": 1,
         "title": "Angkor Wat",
         "latitude": 13.4125,
         "longitude": 103.8667,
         ...
       }
     ]
   }

8. React nhận data → Render UI → User thấy danh sách
```

---

## 📂 Cấu Trúc Thư Mục

### Backend (Laravel):
```
eproject/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          ← Controllers cho admin panel
│   │   │   │   ├── MonumentController.php
│   │   │   │   ├── GalleryController.php
│   │   │   │   └── PostController.php
│   │   │   └── Api/            ← Controllers cho API
│   │   │       ├── MonumentController.php
│   │   │       └── GalleryController.php
│   │   └── Middleware/         ← Bảo mật, kiểm tra quyền
│   │       ├── AdminMiddleware.php
│   │       └── CheckOwnershipMiddleware.php
│   └── Models/                 ← Đại diện cho tables trong DB
│       ├── Monument.php
│       ├── Gallery.php
│       ├── Post.php
│       └── User.php
├── database/
│   └── migrations/             ← Tạo/sửa database structure
├── resources/
│   └── views/
│       └── admin/              ← HTML templates cho admin
├── routes/
│   ├── web.php                 ← Routes cho admin panel
│   └── api.php                 ← Routes cho API
└── config/
    └── cors.php                ← Cấu hình CORS
```

### Frontend (React):
```
frontend/
├── public/
│   └── index.html              ← HTML chính
├── src/
│   ├── components/
│   │   └── Layout/
│   │       ├── Navbar.jsx      ← Menu điều hướng
│   │       └── Footer.jsx      ← Footer
│   ├── pages/                  ← Các trang
│   │   ├── Home.jsx
│   │   ├── Monuments.jsx
│   │   ├── MonumentDetail.jsx
│   │   ├── Gallery.jsx
│   │   ├── Blog.jsx
│   │   └── Contact.jsx
│   ├── contexts/
│   │   └── LanguageContext.jsx ← Đa ngôn ngữ
│   ├── config/
│   │   └── api.js              ← API endpoints
│   ├── services/
│   │   └── api.js              ← Axios config
│   ├── App.js                  ← Component chính
│   └── index.js                ← Entry point
├── .env                        ← Environment variables
└── package.json                ← Dependencies
```

---

## 🗄️ Database Structure

### Các Tables Chính:

```sql
users                    ← Admin, Moderator accounts
├── id
├── name
├── email
├── password
├── role (admin/moderator)
└── avatar

monuments                ← Di tích
├── id
├── title
├── description
├── latitude
├── longitude
├── zone (East/West/North/South/Central)
├── is_world_wonder
└── created_by (user_id)

monument_translations    ← Bản dịch tiếng Việt
├── id
├── monument_id
├── language (vi/en)
├── title
└── description

galleries                ← Hình ảnh
├── id
├── monument_id
├── title
├── image_path (Cloudinary URL)
└── category

posts                    ← Blog posts
├── id
├── title
├── content
├── image
└── created_by

feedbacks                ← Đánh giá từ users
├── id
├── monument_id
├── name
├── email
├── rating (1-5)
└── message
```

---

## 🔑 Khái Niệm Quan Trọng

### 1. **MVC Pattern** (Model-View-Controller)

```
┌─────────┐      ┌────────────┐      ┌───────┐
│  VIEW   │◄─────│ CONTROLLER │◄─────│ MODEL │
│ (HTML)  │      │   (Logic)  │      │  (DB) │
└─────────┘      └────────────┘      └───────┘
```

- **Model**: Tương tác với database (Monument.php)
- **View**: Hiển thị HTML (monuments/index.blade.php)
- **Controller**: Xử lý logic (MonumentController.php)

### 2. **API (Application Programming Interface)**

Cách để Frontend và Backend giao tiếp:

```
Frontend (React)  ──HTTP Request──►  Backend (Laravel)
                                     │
                                     ├─ Process
                                     ├─ Query DB
                                     └─ Format JSON
                  ◄──JSON Response──
```

### 3. **Component (React)**

Một phần của UI có thể tái sử dụng:

```jsx
function Navbar() {
  return (
    <nav>
      <a href="/">Home</a>
      <a href="/monuments">Monuments</a>
    </nav>
  );
}
```

### 4. **State (React)**

Dữ liệu thay đổi trong component:

```jsx
const [monuments, setMonuments] = useState([]);
// monuments: dữ liệu hiện tại
// setMonuments: function để thay đổi dữ liệu
```

---

## 🚀 Cách Chạy Dự Án

### 1. Start Backend (Laravel):
```bash
cd eproject
php artisan serve
# → http://localhost:8000
```

### 2. Start Frontend (React):
```bash
cd frontend
npm start
# → http://localhost:3000
```

### 3. Access:
- Admin Panel: http://localhost:8000/admin
- Public Website: http://localhost:3000
- API: http://localhost:8000/api/monuments

---

## 📊 Luồng Dữ Liệu

### Tạo Monument Mới:

```
1. Admin login → /admin/monuments/create

2. Fill form → Submit

3. MonumentController@store
   ├─ Validate data
   ├─ Upload image to Cloudinary
   ├─ Save to database
   └─ Redirect to list

4. Database updated

5. Frontend gọi API → Nhận data mới → Hiển thị
```

---

## 🎓 Bước Tiếp Theo

Bây giờ bạn đã hiểu tổng quan, hãy đọc tiếp:

1. ✅ **LEARNING_GUIDE_02_BACKEND_BASICS.md** - Học Laravel cơ bản
2. **LEARNING_GUIDE_03_DATABASE.md** - Hiểu database
3. **LEARNING_GUIDE_05_FRONTEND_BASICS.md** - Học React cơ bản

---

## 💡 Tips Học Code

### 1. Đọc Code Từ Trên Xuống
Bắt đầu từ file chính (App.js, web.php) rồi đi sâu vào từng phần.

### 2. Chạy Thử Và Sửa
Thay đổi một dòng code → Xem kết quả → Hiểu nó làm gì.

### 3. Debug Bằng Console.log
```javascript
console.log('Data:', monuments);
```

### 4. Đọc Documentation
- Laravel: https://laravel.com/docs
- React: https://react.dev

### 5. Hỏi Khi Không Hiểu
Đừng ngại hỏi về bất kỳ dòng code nào!

---

## 📝 Bài Tập Thực Hành

### Bài 1: Tìm Hiểu Routes
1. Mở file `routes/web.php`
2. Tìm route `/admin/monuments`
3. Xem nó gọi controller nào?

### Bài 2: Xem API Response
1. Mở browser
2. Vào: http://localhost:8000/api/monuments
3. Xem JSON response

### Bài 3: Sửa Text
1. Mở `frontend/src/pages/Home.jsx`
2. Tìm text "Welcome to Global Heritage"
3. Đổi thành "Chào mừng đến Global Heritage"
4. Refresh browser → Xem thay đổi

---

**Tiếp theo: LEARNING_GUIDE_02_BACKEND_BASICS.md** →

