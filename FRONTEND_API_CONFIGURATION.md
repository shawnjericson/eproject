# Frontend API Configuration Guide

## 🎯 Overview
All API URLs in the frontend are now centralized and use environment variables for easy deployment configuration.

## ✅ What Was Changed

### Files Modified:
1. ✅ `frontend/src/config/api.js` - Centralized API configuration
2. ✅ `frontend/src/services/api.js` - Axios instance configuration
3. ✅ `frontend/src/pages/Home.jsx` - Monuments & feedback API
4. ✅ `frontend/src/pages/Monuments.jsx` - Monuments list API
5. ✅ `frontend/src/pages/MonumentDetail.jsx` - Monument detail & reviews API
6. ✅ `frontend/src/pages/Gallery.jsx` - Gallery & categories API
7. ✅ `frontend/src/pages/Blog.jsx` - Posts list API
8. ✅ `frontend/src/pages/BlogDetail.jsx` - Post detail API
9. ✅ `frontend/src/pages/Feedback.jsx` - Feedback submission API

### Files Created:
1. ✅ `frontend/.env` - Development environment variables
2. ✅ `frontend/.env.example` - Example environment file
3. ✅ `frontend/.env.production` - Production environment template

---

## 📁 File Structure

```
frontend/
├── .env                    # Development config (gitignored)
├── .env.example            # Example for developers
├── .env.production         # Production config template
└── src/
    ├── config/
    │   └── api.js          # Centralized API endpoints
    ├── services/
    │   └── api.js          # Axios instance with interceptors
    └── pages/
        ├── Home.jsx
        ├── Monuments.jsx
        ├── MonumentDetail.jsx
        ├── Gallery.jsx
        ├── Blog.jsx
        ├── BlogDetail.jsx
        └── Feedback.jsx
```

---

## 🔧 Configuration Files

### 1. `frontend/.env` (Development)
```env
# API Configuration
REACT_APP_API_URL=http://127.0.0.1:8000
```

### 2. `frontend/.env.production` (Production)
```env
# Production API Configuration
REACT_APP_API_URL=https://your-production-domain.com
```

### 3. `frontend/src/config/api.js`
```javascript
// API Configuration
const API_BASE_URL = process.env.REACT_APP_API_URL || 'http://127.0.0.1:8000';

export const API_ENDPOINTS = {
  gallery: `${API_BASE_URL}/api/gallery`,
  galleryCategories: `${API_BASE_URL}/api/gallery/categories`,
  monuments: `${API_BASE_URL}/api/monuments`,
  posts: `${API_BASE_URL}/api/posts`,
  contact: `${API_BASE_URL}/api/contact`,
  feedback: `${API_BASE_URL}/api/feedback`,
};

export default API_BASE_URL;
```

### 4. `frontend/src/services/api.js`
```javascript
import axios from 'axios';

const API_BASE_URL = process.env.REACT_APP_API_URL 
  ? `${process.env.REACT_APP_API_URL}/api`
  : 'http://127.0.0.1:8000/api';

const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});
```

---

## 🚀 Usage Examples

### Before (Hardcoded):
```javascript
// ❌ Bad - Hardcoded URL
const response = await fetch('http://127.0.0.1:8000/api/monuments');
```

### After (Environment Variable):
```javascript
// ✅ Good - Using centralized config
import { API_ENDPOINTS } from '../config/api';

const response = await fetch(API_ENDPOINTS.monuments);
```

---

## 📊 Available Endpoints

| Endpoint | URL | Usage |
|----------|-----|-------|
| `API_ENDPOINTS.gallery` | `/api/gallery` | Gallery images list |
| `API_ENDPOINTS.galleryCategories` | `/api/gallery/categories` | Gallery categories |
| `API_ENDPOINTS.monuments` | `/api/monuments` | Monuments list |
| `API_ENDPOINTS.posts` | `/api/posts` | Blog posts list |
| `API_ENDPOINTS.contact` | `/api/contact` | Contact form submission |
| `API_ENDPOINTS.feedback` | `/api/feedback` | Feedback/reviews |

---

## 🛠️ Development Setup

### Step 1: Copy Environment File
```bash
cd frontend
cp .env.example .env
```

### Step 2: Configure Development URL
Edit `frontend/.env`:
```env
REACT_APP_API_URL=http://127.0.0.1:8000
```

### Step 3: Restart Development Server
```bash
npm start
```
**⚠️ IMPORTANT:** Must restart to load new environment variables!

---

## 🌐 Production Deployment

### Option 1: Using .env.production File

1. **Edit `frontend/.env.production`:**
```env
REACT_APP_API_URL=https://api.yoursite.com
```

2. **Build for production:**
```bash
cd frontend
npm run build
```

The build process automatically uses `.env.production` values.

### Option 2: Using Build-time Environment Variables

```bash
cd frontend
REACT_APP_API_URL=https://api.yoursite.com npm run build
```

### Option 3: Using CI/CD Environment Variables

Most hosting platforms (Vercel, Netlify, etc.) allow setting environment variables in their dashboard:

**Vercel:**
1. Go to Project Settings → Environment Variables
2. Add: `REACT_APP_API_URL` = `https://api.yoursite.com`
3. Redeploy

**Netlify:**
1. Go to Site Settings → Build & Deploy → Environment
2. Add: `REACT_APP_API_URL` = `https://api.yoursite.com`
3. Trigger new deploy

---

## 🔍 Verification

### Check Current API URL:
```javascript
// Add this temporarily to any component
console.log('API Base URL:', process.env.REACT_APP_API_URL);
```

### Test API Connection:
```bash
# Open browser console on your React app
fetch(process.env.REACT_APP_API_URL + '/api/monuments')
  .then(r => r.json())
  .then(d => console.log('API Response:', d))
```

---

## 🐛 Troubleshooting

### Issue: API calls still use localhost in production

**Cause:** Environment variable not set or React not restarted

**Solution:**
1. Check `.env.production` file exists
2. Rebuild: `npm run build`
3. Clear browser cache
4. Check build output for correct URL

### Issue: "Failed to fetch" errors

**Cause:** CORS not configured for production domain

**Solution:**
Update Laravel `config/cors.php`:
```php
'allowed_origins' => [
    'http://localhost:3000',
    'http://127.0.0.1:3000',
    'https://your-production-domain.com', // Add this
],
```

### Issue: Environment variable not loading

**Cause:** Variable name doesn't start with `REACT_APP_`

**Solution:**
All custom environment variables in Create React App MUST start with `REACT_APP_`

✅ Correct: `REACT_APP_API_URL`
❌ Wrong: `API_URL`

---

## 📝 Best Practices

### 1. Never Commit .env Files
```gitignore
# .gitignore
.env
.env.local
.env.development.local
.env.test.local
.env.production.local
```

### 2. Always Provide .env.example
```env
# .env.example
REACT_APP_API_URL=http://127.0.0.1:8000
```

### 3. Document Required Variables
In README or this file, list all required environment variables.

### 4. Use Fallback Values
```javascript
const API_BASE_URL = process.env.REACT_APP_API_URL || 'http://127.0.0.1:8000';
```

### 5. Validate Environment Variables
```javascript
if (!process.env.REACT_APP_API_URL) {
  console.warn('⚠️ REACT_APP_API_URL not set, using default');
}
```

---

## 🎯 Migration Checklist

- [x] Create `frontend/src/config/api.js`
- [x] Update `frontend/src/services/api.js`
- [x] Replace hardcoded URLs in all pages
- [x] Create `.env` file
- [x] Create `.env.example` file
- [x] Create `.env.production` template
- [x] Update `.gitignore` to exclude `.env`
- [x] Test development build
- [ ] Test production build
- [ ] Update deployment documentation
- [ ] Configure production environment variables

---

## 📚 Additional Resources

- [Create React App - Environment Variables](https://create-react-app.dev/docs/adding-custom-environment-variables/)
- [Axios Documentation](https://axios-http.com/docs/intro)
- [Laravel CORS Configuration](https://laravel.com/docs/10.x/routing#cors)

---

## 🔗 Related Files

- `frontend/src/config/api.js` - API endpoints configuration
- `frontend/src/services/api.js` - Axios instance with interceptors
- `frontend/.env` - Development environment variables
- `frontend/.env.production` - Production environment template
- `config/cors.php` - Laravel CORS configuration (backend)

---

## 📞 Support

If you encounter issues:
1. Check console for error messages
2. Verify `.env` file exists and has correct values
3. Restart development server after changing `.env`
4. Clear browser cache
5. Check Laravel backend CORS configuration

