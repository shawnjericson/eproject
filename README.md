# 🏛️ Global Heritage - Di sản Văn hóa Thế giới

[![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![React](https://img.shields.io/badge/React-19.1.1-blue.svg)](https://reactjs.org)
[![PHP](https://img.shields.io/badge/PHP-8.1+-purple.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

**Global Heritage** là một hệ thống quản lý di sản văn hóa thế giới với đầy đủ tính năng CMS và frontend hiện đại. Dự án được xây dựng với Laravel backend và React frontend, hỗ trợ đa ngôn ngữ (Tiếng Việt & English).

## 🚀 **Quick Start**

### **1. Backend Setup**
```bash
# Clone và cài đặt dependencies
git clone <repository-url> && cd eproject
composer install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate --seed

# Start server
php artisan serve
```

### **2. Frontend Setup**
```bash
# Cài đặt và chạy React frontend
cd frontend
npm install && npm start
```

### **3. Access Points**
- 🌐 **Frontend**: http://localhost:3000
- 🔧 **Admin Panel**: http://127.0.0.1:8000/admin
- 📡 **API**: http://127.0.0.1:8000/api

---

## ✨ **Tính năng chính**

| Feature | Description | Status |
|---------|-------------|--------|
| 🏛️ **Monument Management** | CRUD với multilingual, maps, SEO URLs | ✅ Complete |
| 📝 **Blog System** | Rich editor, image upload, workflow | ✅ Complete |
| 📸 **Gallery System** | Cloudinary, progressive loading, lightbox | ✅ Complete |
| ⭐ **Rating & Reviews** | 5-star system, moderation, analytics | ✅ Complete |
| 👥 **User Management** | Roles, profiles, security questions | ✅ Complete |
| 🌐 **Multilingual** | Vietnamese & English support | ✅ Complete |
| 📱 **Responsive Design** | Mobile-first, interactive maps | ✅ Complete |
| 🔐 **Security** | CSRF, rate limiting, input validation | ✅ Complete |

---

## 🛠️ **Tech Stack**

### **Backend**
```
🔧 Laravel 10.x          📊 MySQL/MariaDB
🔐 Laravel Sanctum       ☁️ Cloudinary
📝 TinyMCE Editor        🧪 PHPUnit Testing
```

### **Frontend**
```
⚛️ React 19.1.1          🎨 Tailwind CSS + Bootstrap
🗺️ React Leaflet         📡 Axios HTTP Client
🖼️ React Lightbox        🌐 Context API
```

---

## 📚 **Documentation**

Comprehensive documentation được tổ chức trong thư mục `docs/`:

| File | Description |
|------|-------------|
| 📋 [**00-PROJECT-OVERVIEW.md**](docs/00-PROJECT-OVERVIEW.md) | Tổng quan dự án và getting started |
| 👥 [**01-User-Management-Authentication.md**](docs/01-User-Management-Authentication.md) | User system và authentication |
| 📝 [**02-Posts-Management.md**](docs/02-Posts-Management.md) | Blog system với multilingual |
| 🏛️ [**03-Monuments-Management.md**](docs/03-Monuments-Management.md) | Monument management system |
| 📸 [**04-Gallery-Feedback-Systems.md**](docs/04-Gallery-Feedback-Systems.md) | Gallery và rating systems |
| 🌐 [**05-API-Documentation-Frontend.md**](docs/05-API-Documentation-Frontend.md) | Complete API reference |
| ⚛️ [**06-React-Frontend-Documentation.md**](docs/06-React-Frontend-Documentation.md) | React architecture guide |

---

## 🏗️ **Project Structure**

```
eproject/
├── 📚 docs/                    # Complete documentation
├── 🎨 frontend/               # React frontend application
│   ├── src/components/        # Reusable components
│   ├── src/pages/            # Page components
│   ├── src/contexts/         # React contexts
│   └── src/services/         # API services
├── 🔧 app/                   # Laravel backend
│   ├── Http/Controllers/     # API & Web controllers
│   ├── Models/              # Eloquent models
│   └── Services/            # Business logic
├── 📊 database/              # Migrations & seeders
├── 🌐 resources/views/       # Admin panel views
└── 🛣️ routes/               # API & web routes
```

---

## 🔧 **Development**

### **Requirements**
- PHP 8.1+ | Node.js 16+ | MySQL/MariaDB | Composer

### **Testing**
```bash
# Backend tests
php artisan test

# Frontend tests
cd frontend && npm test
```

### **API Endpoints**
```
🔐 Auth:        POST /api/login, /api/logout
🏛️ Monuments:   GET|POST|PUT|DELETE /api/monuments
📝 Posts:       GET|POST|PUT|DELETE /api/posts
📸 Gallery:     GET|POST|PUT|DELETE /api/gallery
⭐ Feedback:    GET|POST|DELETE /api/feedback
```

---

## 🚀 **Production Deployment**

```bash
# Optimize Laravel
composer install --optimize-autoloader --no-dev
php artisan config:cache && php artisan route:cache

# Build React frontend
cd frontend && npm run build

# Set production environment
# Configure .env với production database và Cloudinary
```

---

## 🎯 **Key Achievements**

- ✅ **Modern Architecture**: Laravel 10 + React 19 với best practices
- ✅ **Scalable Design**: Modular components và clean architecture
- ✅ **Performance Optimized**: Caching, CDN, lazy loading
- ✅ **Security Focused**: Multiple layers of protection
- ✅ **Production Ready**: Environment config, error handling
- ✅ **Developer Friendly**: Comprehensive documentation

---

## 📞 **Support**

- 📖 **Documentation**: Check `docs/` folder for detailed guides
- 🐛 **Issues**: Create GitHub issues for bugs
- 💡 **Features**: Submit feature requests via issues
- 📧 **Contact**: Reach out to development team

---

## 📄 **License**

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

---

**🎉 Global Heritage - Bringing world cultural heritage to the digital age!** 🌍
