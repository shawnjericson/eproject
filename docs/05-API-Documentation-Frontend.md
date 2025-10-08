# 🌐 API Documentation & Frontend Integration - Hướng dẫn chi tiết

## 🏗️ Tổng quan API Architecture

Hệ thống API được thiết kế RESTful với authentication, authorization và performance optimization:

- **Base URL**: `/api`
- **Authentication**: Laravel Sanctum (Token-based)
- **Response Format**: JSON
- **Pagination**: Laravel's built-in pagination
- **Rate Limiting**: Configurable per endpoint
- **CORS**: Enabled for frontend integration

---

## 🔐 1. AUTHENTICATION ENDPOINTS

### **POST /api/login**
Đăng nhập và nhận access token

**Request:**
```json
{
    "email": "admin@example.com",
    "password": "password123"
}
```

**Response (200):**
```json
{
    "user": {
        "id": 1,
        "name": "Admin User",
        "email": "admin@example.com",
        "role": "admin",
        "status": "active",
        "avatar_url": "https://example.com/avatar.jpg"
    },
    "token": "1|abc123def456...",
    "expires_at": "2024-01-01T00:00:00.000000Z"
}
```

**Response (401):**
```json
{
    "error": "Invalid credentials"
}
```

### **POST /api/logout** 🔒
Đăng xuất và revoke token

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
    "message": "Logged out successfully"
}
```

### **GET /api/me** 🔒
Lấy thông tin user hiện tại

**Response (200):**
```json
{
    "id": 1,
    "name": "Admin User",
    "email": "admin@example.com",
    "role": "admin",
    "status": "active",
    "avatar_url": "https://example.com/avatar.jpg",
    "profile_completion": 85,
    "created_at": "2024-01-01T00:00:00.000000Z"
}
```

---

## 📝 2. POSTS ENDPOINTS

### **GET /api/posts**
Lấy danh sách bài viết (public - chỉ approved posts)

**Query Parameters:**
- `page` (int): Trang hiện tại
- `per_page` (int): Số items per page (default: 10, max: 50)
- `search` (string): Tìm kiếm theo title/content
- `language` (string): vi|en (default: vi)

**Example Request:**
```
GET /api/posts?page=1&per_page=10&search=heritage&language=vi
```

**Response (200):**
```json
{
    "data": [
        {
            "id": 1,
            "title": "Di sản văn hóa Việt Nam",
            "description": "Khám phá những di sản văn hóa...",
            "content": "Nội dung chi tiết...",
            "image_url": "https://cloudinary.com/image.jpg",
            "status": "approved",
            "published_at": "2024-01-01T00:00:00.000000Z",
            "creator": {
                "id": 1,
                "name": "Admin User",
                "avatar_url": "https://example.com/avatar.jpg"
            },
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        }
    ],
    "links": {
        "first": "/api/posts?page=1",
        "last": "/api/posts?page=5",
        "prev": null,
        "next": "/api/posts?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 5,
        "per_page": 10,
        "to": 10,
        "total": 50
    }
}
```

### **GET /api/posts/{id}**
Lấy chi tiết một bài viết

**Response (200):**
```json
{
    "id": 1,
    "title": "Di sản văn hóa Việt Nam",
    "description": "Khám phá những di sản văn hóa...",
    "content": "Nội dung chi tiết đầy đủ...",
    "image_url": "https://cloudinary.com/image.jpg",
    "status": "approved",
    "published_at": "2024-01-01T00:00:00.000000Z",
    "creator": {
        "id": 1,
        "name": "Admin User",
        "avatar_url": "https://example.com/avatar.jpg"
    },
    "translations": [
        {
            "language": "vi",
            "title": "Di sản văn hóa Việt Nam",
            "content": "Nội dung tiếng Việt..."
        },
        {
            "language": "en", 
            "title": "Vietnamese Cultural Heritage",
            "content": "English content..."
        }
    ],
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z"
}
```

### **POST /api/posts** 🔒
Tạo bài viết mới

**Request:**
```json
{
    "language": "vi",
    "title": "Tiêu đề bài viết",
    "description": "Mô tả ngắn",
    "content": "Nội dung đầy đủ...",
    "status": "draft",
    "image": "base64_image_data_or_file_upload"
}
```

**Response (201):**
```json
{
    "id": 2,
    "title": "Tiêu đề bài viết",
    "status": "draft",
    "created_at": "2024-01-01T00:00:00.000000Z",
    "message": "Post created successfully"
}
```

---

## 🏛️ 3. MONUMENTS ENDPOINTS

### **GET /api/monuments**
Lấy danh sách di tích

**Query Parameters:**
- `page`, `per_page`: Pagination
- `search`: Tìm kiếm theo title/location
- `zone`: North|South|Central|East|West
- `is_world_wonder`: true|false
- `lat`, `lng`, `radius`: Geographic search (radius in km)
- `language`: vi|en

**Example Request:**
```
GET /api/monuments?zone=North&is_world_wonder=true&language=vi&per_page=12
```

**Response (200):**
```json
{
    "data": [
        {
            "id": 1,
            "title": "Vịnh Hạ Long",
            "description": "Di sản thiên nhiên thế giới...",
            "location": "Quảng Ninh, Việt Nam",
            "zone": "North",
            "coordinates": {
                "lat": 20.9101,
                "lng": 107.1839
            },
            "is_world_wonder": true,
            "image_url": "https://cloudinary.com/halong.jpg",
            "gallery_count": 25,
            "average_rating": 4.8,
            "total_reviews": 156,
            "created_at": "2024-01-01T00:00:00.000000Z"
        }
    ],
    "meta": {
        "current_page": 1,
        "total": 50
    }
}
```

### **GET /api/monuments/{id}**
Chi tiết di tích với gallery và reviews

**Response (200):**
```json
{
    "id": 1,
    "title": "Vịnh Hạ Long",
    "description": "Di sản thiên nhiên thế giới...",
    "history": "Lịch sử hình thành...",
    "content": "Nội dung chi tiết...",
    "location": "Quảng Ninh, Việt Nam",
    "zone": "North",
    "coordinates": {
        "lat": 20.9101,
        "lng": 107.1839
    },
    "map_embed": "<iframe src='...'></iframe>",
    "is_world_wonder": true,
    "image_url": "https://cloudinary.com/halong.jpg",
    "gallery": [
        {
            "id": 1,
            "title": "Sunset at Ha Long Bay",
            "image_url": "https://cloudinary.com/full.jpg",
            "thumbnail_url": "https://cloudinary.com/thumb.jpg",
            "blur_hash": "https://cloudinary.com/blur.jpg",
            "category": "North"
        }
    ],
    "reviews": [
        {
            "id": 1,
            "name": "John Doe",
            "message": "Amazing place to visit!",
            "rating": 5,
            "created_at": "Jan 15, 2024"
        }
    ],
    "rating_summary": {
        "average": 4.8,
        "total": 156,
        "distribution": {
            "5": 120,
            "4": 25,
            "3": 8,
            "2": 2,
            "1": 1
        }
    }
}
```

### **POST /api/monuments/{id}/feedback**
Gửi đánh giá cho di tích

**Request:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "message": "Amazing place to visit! Highly recommended.",
    "rating": 5
}
```

**Response (201):**
```json
{
    "message": "Thank you for your feedback! It will be reviewed before being published.",
    "feedback": {
        "id": 10,
        "name": "John Doe",
        "message": "Amazing place to visit! Highly recommended.",
        "rating": 5,
        "status": "pending",
        "created_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

**Response (429 - Rate Limited):**
```json
{
    "error": "You can only submit one review per monument per day."
}
```

---

## 📸 4. GALLERY ENDPOINTS

### **GET /api/gallery**
Lấy danh sách hình ảnh

**Query Parameters:**
- `monument_id`: Filter by monument
- `category`: Filter by category
- `search`: Search by title/description
- `page`, `per_page`: Pagination

**Response (200):**
```json
{
    "data": [
        {
            "id": 1,
            "title": "Sunset at Ha Long Bay",
            "description": "Beautiful sunset view",
            "category": "North",
            "image_url": "https://cloudinary.com/full.jpg",
            "thumbnail_url": "https://cloudinary.com/thumb.jpg",
            "blur_hash": "https://cloudinary.com/blur.jpg",
            "monument": {
                "id": 1,
                "title": "Vịnh Hạ Long",
                "zone": "North"
            },
            "created_at": "2024-01-01T00:00:00.000000Z"
        }
    ]
}
```

### **GET /api/gallery/categories**
Lấy danh sách categories với số lượng

**Response (200):**
```json
[
    {
        "category": "North",
        "count": 45
    },
    {
        "category": "South", 
        "count": 32
    },
    {
        "category": "Central",
        "count": 28
    }
]
```

---

## 📊 5. DASHBOARD & ANALYTICS

### **GET /api/dashboard/stats** 🔒
Thống kê tổng quan cho admin dashboard

**Response (200):**
```json
{
    "overview": {
        "total_monuments": 50,
        "total_posts": 25,
        "total_gallery_images": 200,
        "total_feedbacks": 156,
        "pending_approvals": 5
    },
    "recent_activity": [
        {
            "type": "feedback",
            "message": "New review for Vịnh Hạ Long",
            "created_at": "2024-01-01T00:00:00.000000Z"
        }
    ],
    "popular_monuments": [
        {
            "id": 1,
            "title": "Vịnh Hạ Long",
            "views": 1250,
            "rating": 4.8
        }
    ],
    "monthly_stats": {
        "visitors": [120, 150, 180, 200],
        "reviews": [15, 20, 25, 30],
        "months": ["Jan", "Feb", "Mar", "Apr"]
    }
}
```

### **GET /api/visitor/stats**
Thống kê visitor (public)

**Response (200):**
```json
{
    "total_visitors": 5420,
    "today_visitors": 45,
    "online_now": 12,
    "monthly_growth": 15.5,
    "popular_pages": [
        {
            "url": "/monuments/1",
            "title": "Vịnh Hạ Long",
            "views": 1250
        }
    ]
}
```

---

## ⚡ 6. FRONTEND INTEGRATION PATTERNS

### 🎣 React Hooks for API

#### **useApi Hook**
```jsx
// hooks/useApi.js
import { useState, useEffect } from 'react';

export const useApi = (url, options = {}) => {
    const [data, setData] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        const fetchData = async () => {
            try {
                setLoading(true);
                const token = localStorage.getItem('auth_token');
                
                const response = await fetch(`/api${url}`, {
                    headers: {
                        'Content-Type': 'application/json',
                        ...(token && { Authorization: `Bearer ${token}` }),
                        ...options.headers
                    },
                    ...options
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();
                setData(result);
            } catch (err) {
                setError(err.message);
            } finally {
                setLoading(false);
            }
        };

        fetchData();
    }, [url]);

    return { data, loading, error, refetch: () => fetchData() };
};
```

#### **useMonuments Hook**
```jsx
// hooks/useMonuments.js
import { useState, useEffect } from 'react';
import { useApi } from './useApi';

export const useMonuments = (filters = {}) => {
    const [monuments, setMonuments] = useState([]);
    const [pagination, setPagination] = useState({});
    const [loading, setLoading] = useState(true);

    const buildUrl = () => {
        const params = new URLSearchParams();
        Object.entries(filters).forEach(([key, value]) => {
            if (value) params.append(key, value);
        });
        return `/monuments?${params.toString()}`;
    };

    const { data, loading: apiLoading, error } = useApi(buildUrl());

    useEffect(() => {
        if (data) {
            setMonuments(data.data || []);
            setPagination(data.meta || {});
        }
        setLoading(apiLoading);
    }, [data, apiLoading]);

    const loadMore = async () => {
        if (pagination.next_page_url) {
            try {
                const response = await fetch(pagination.next_page_url);
                const newData = await response.json();
                setMonuments(prev => [...prev, ...newData.data]);
                setPagination(newData.meta);
            } catch (err) {
                console.error('Failed to load more:', err);
            }
        }
    };

    return {
        monuments,
        pagination,
        loading,
        error,
        loadMore,
        hasMore: !!pagination.next_page_url
    };
};
```

### 🎨 React Components

#### **MonumentCard Component**
```jsx
// components/MonumentCard.jsx
import React from 'react';
import { Link } from 'react-router-dom';

const MonumentCard = ({ monument }) => {
    const StarRating = ({ rating }) => (
        <div className="flex items-center">
            {[1, 2, 3, 4, 5].map(star => (
                <span
                    key={star}
                    className={`text-sm ${
                        star <= Math.round(rating) ? 'text-yellow-400' : 'text-gray-300'
                    }`}
                >
                    ★
                </span>
            ))}
            <span className="ml-1 text-sm text-gray-600">
                ({monument.total_reviews})
            </span>
        </div>
    );

    return (
        <div className="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
            <div className="relative">
                <img
                    src={monument.image_url}
                    alt={monument.title}
                    className="w-full h-48 object-cover"
                />
                {monument.is_world_wonder && (
                    <div className="absolute top-2 right-2 bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                        UNESCO
                    </div>
                )}
                <div className="absolute bottom-2 left-2 bg-blue-500 text-white px-2 py-1 rounded text-xs">
                    {monument.zone}
                </div>
            </div>
            
            <div className="p-4">
                <h3 className="font-semibold text-lg mb-2 line-clamp-2">
                    {monument.title}
                </h3>
                <p className="text-gray-600 text-sm mb-3 line-clamp-3">
                    {monument.description}
                </p>
                
                <div className="flex items-center justify-between mb-3">
                    <StarRating rating={monument.average_rating} />
                    <span className="text-sm text-gray-500">
                        {monument.gallery_count} photos
                    </span>
                </div>
                
                <div className="flex items-center text-sm text-gray-500 mb-3">
                    <svg className="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fillRule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clipRule="evenodd" />
                    </svg>
                    {monument.location}
                </div>
                
                <Link
                    to={`/monuments/${monument.id}`}
                    className="block w-full bg-blue-600 text-white text-center py-2 rounded-lg hover:bg-blue-700 transition-colors"
                >
                    Explore
                </Link>
            </div>
        </div>
    );
};

export default MonumentCard;
```

#### **InfiniteScroll Component**
```jsx
// components/InfiniteScroll.jsx
import React, { useEffect, useRef } from 'react';

const InfiniteScroll = ({ 
    children, 
    hasMore, 
    loading, 
    onLoadMore,
    threshold = 100 
}) => {
    const sentinelRef = useRef();

    useEffect(() => {
        const observer = new IntersectionObserver(
            (entries) => {
                if (entries[0].isIntersecting && hasMore && !loading) {
                    onLoadMore();
                }
            },
            { threshold: 0.1 }
        );

        if (sentinelRef.current) {
            observer.observe(sentinelRef.current);
        }

        return () => observer.disconnect();
    }, [hasMore, loading, onLoadMore]);

    return (
        <>
            {children}
            {hasMore && (
                <div ref={sentinelRef} className="flex justify-center py-4">
                    {loading ? (
                        <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    ) : (
                        <div className="text-gray-500">Scroll for more...</div>
                    )}
                </div>
            )}
        </>
    );
};

export default InfiniteScroll;
```

### 🔄 State Management with Context

#### **AppContext**
```jsx
// context/AppContext.jsx
import React, { createContext, useContext, useReducer } from 'react';

const AppContext = createContext();

const initialState = {
    user: null,
    language: 'vi',
    theme: 'light',
    monuments: [],
    posts: [],
    loading: false,
    error: null
};

const appReducer = (state, action) => {
    switch (action.type) {
        case 'SET_USER':
            return { ...state, user: action.payload };
        case 'SET_LANGUAGE':
            return { ...state, language: action.payload };
        case 'SET_MONUMENTS':
            return { ...state, monuments: action.payload };
        case 'ADD_MONUMENTS':
            return { 
                ...state, 
                monuments: [...state.monuments, ...action.payload] 
            };
        case 'SET_LOADING':
            return { ...state, loading: action.payload };
        case 'SET_ERROR':
            return { ...state, error: action.payload };
        default:
            return state;
    }
};

export const AppProvider = ({ children }) => {
    const [state, dispatch] = useReducer(appReducer, initialState);

    const actions = {
        setUser: (user) => dispatch({ type: 'SET_USER', payload: user }),
        setLanguage: (lang) => dispatch({ type: 'SET_LANGUAGE', payload: lang }),
        setMonuments: (monuments) => dispatch({ type: 'SET_MONUMENTS', payload: monuments }),
        addMonuments: (monuments) => dispatch({ type: 'ADD_MONUMENTS', payload: monuments }),
        setLoading: (loading) => dispatch({ type: 'SET_LOADING', payload: loading }),
        setError: (error) => dispatch({ type: 'SET_ERROR', payload: error })
    };

    return (
        <AppContext.Provider value={{ state, actions }}>
            {children}
        </AppContext.Provider>
    );
};

export const useApp = () => {
    const context = useContext(AppContext);
    if (!context) {
        throw new Error('useApp must be used within AppProvider');
    }
    return context;
};
```

---

## 🚀 7. PERFORMANCE OPTIMIZATION

### 📈 Caching Strategies

#### **API Response Caching**
```jsx
// utils/apiCache.js
class ApiCache {
    constructor(ttl = 300000) { // 5 minutes default
        this.cache = new Map();
        this.ttl = ttl;
    }

    get(key) {
        const item = this.cache.get(key);
        if (!item) return null;

        if (Date.now() > item.expiry) {
            this.cache.delete(key);
            return null;
        }

        return item.data;
    }

    set(key, data) {
        this.cache.set(key, {
            data,
            expiry: Date.now() + this.ttl
        });
    }

    clear() {
        this.cache.clear();
    }
}

export const apiCache = new ApiCache();

// Enhanced fetch with caching
export const cachedFetch = async (url, options = {}) => {
    const cacheKey = `${url}_${JSON.stringify(options)}`;
    
    // Check cache first
    const cached = apiCache.get(cacheKey);
    if (cached && !options.skipCache) {
        return cached;
    }

    // Fetch from API
    const response = await fetch(url, options);
    const data = await response.json();

    // Cache the response
    if (response.ok) {
        apiCache.set(cacheKey, data);
    }

    return data;
};
```

### 🖼️ Image Optimization

#### **Progressive Image Loading**
```jsx
// components/ProgressiveImage.jsx
import React, { useState, useEffect } from 'react';

const ProgressiveImage = ({ 
    src, 
    blurSrc, 
    alt, 
    className = '',
    ...props 
}) => {
    const [imageLoaded, setImageLoaded] = useState(false);
    const [imageSrc, setImageSrc] = useState(blurSrc || '');

    useEffect(() => {
        const img = new Image();
        img.onload = () => {
            setImageSrc(src);
            setImageLoaded(true);
        };
        img.src = src;
    }, [src]);

    return (
        <div className={`relative overflow-hidden ${className}`}>
            <img
                src={imageSrc}
                alt={alt}
                className={`transition-all duration-300 ${
                    imageLoaded ? 'filter-none' : 'filter blur-sm'
                }`}
                {...props}
            />
            {!imageLoaded && (
                <div className="absolute inset-0 bg-gray-200 animate-pulse" />
            )}
        </div>
    );
};

export default ProgressiveImage;
```

---

## 📊 8. ERROR HANDLING & MONITORING

### 🚨 Error Boundary
```jsx
// components/ErrorBoundary.jsx
import React from 'react';

class ErrorBoundary extends React.Component {
    constructor(props) {
        super(props);
        this.state = { hasError: false, error: null };
    }

    static getDerivedStateFromError(error) {
        return { hasError: true, error };
    }

    componentDidCatch(error, errorInfo) {
        console.error('Error caught by boundary:', error, errorInfo);
        
        // Send to monitoring service
        if (window.gtag) {
            window.gtag('event', 'exception', {
                description: error.toString(),
                fatal: false
            });
        }
    }

    render() {
        if (this.state.hasError) {
            return (
                <div className="min-h-screen flex items-center justify-center bg-gray-50">
                    <div className="text-center">
                        <h1 className="text-2xl font-bold text-gray-900 mb-4">
                            Oops! Something went wrong
                        </h1>
                        <p className="text-gray-600 mb-6">
                            We're sorry for the inconvenience. Please try refreshing the page.
                        </p>
                        <button
                            onClick={() => window.location.reload()}
                            className="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700"
                        >
                            Refresh Page
                        </button>
                    </div>
                </div>
            );
        }

        return this.props.children;
    }
}

export default ErrorBoundary;
```

---

## 📊 9. TỔNG KẾT API & FRONTEND

### 🎯 Key Features

#### ✅ **API Design**
- **RESTful Architecture**: Consistent URL patterns and HTTP methods
- **Authentication**: Token-based with Laravel Sanctum
- **Pagination**: Efficient data loading with meta information
- **Filtering & Search**: Comprehensive query parameters
- **Rate Limiting**: Spam protection and resource management
- **Error Handling**: Consistent error responses with proper HTTP codes

#### ✅ **Frontend Integration**
- **React Hooks**: Custom hooks for API consumption
- **State Management**: Context API for global state
- **Performance**: Caching, lazy loading, infinite scroll
- **Progressive Enhancement**: Image optimization, error boundaries
- **Responsive Design**: Mobile-first approach
- **Accessibility**: ARIA labels, keyboard navigation

#### ✅ **Performance Optimization**
- **API Caching**: Client-side caching with TTL
- **Image Optimization**: Progressive loading, multiple formats
- **Code Splitting**: Lazy loading of components
- **Bundle Optimization**: Tree shaking, minification
- **CDN Integration**: Static asset delivery

### 🔄 **Complete Data Flow**

```
Frontend Request Flow:
1. 🎨 React Component → Custom Hook (useMonuments)
2. 🔄 Hook → API Cache Check
3. 🌐 Cache Miss → Fetch from /api/monuments
4. 🔐 Add Authorization Header (if authenticated)
5. 📊 Laravel API → Controller → Model → Database
6. 📤 JSON Response → Frontend
7. 💾 Cache Response → Update Component State
8. 🎨 Re-render with New Data

Error Handling Flow:
1. 🚨 API Error → Custom Hook Error State
2. 📊 Error Boundary Catches Render Errors
3. 📈 Analytics Tracking (Google Analytics)
4. 🔄 Retry Logic for Network Errors
5. 👤 User-friendly Error Messages
```

### 🚀 **Production Considerations**

#### **Security**
- HTTPS enforcement
- CORS configuration
- Rate limiting per IP/user
- Input validation and sanitization
- SQL injection prevention
- XSS protection

#### **Scalability**
- Database indexing
- Redis caching
- CDN for static assets
- Load balancing
- Database connection pooling
- Queue system for heavy tasks

#### **Monitoring**
- API response times
- Error rates and types
- User engagement metrics
- Performance bottlenecks
- Resource usage monitoring

---

## 🎯 **Kết luận**

Hệ thống đã được thiết kế với architecture hiện đại, tối ưu cho performance và user experience. Với documentation chi tiết này, developers có thể:

1. **Hiểu rõ API structure** và cách sử dụng
2. **Implement frontend** với React patterns tối ưu
3. **Optimize performance** với caching và lazy loading
4. **Handle errors** gracefully với proper error boundaries
5. **Scale system** khi traffic tăng cao

Toàn bộ codebase được tổ chức theo best practices của Laravel và React, đảm bảo maintainability và extensibility trong tương lai! 🚀
