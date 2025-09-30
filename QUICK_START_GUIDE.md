# Global Heritage - Quick Start Guide

Get your Global Heritage application up and running in 10 minutes!

---

## Prerequisites

Make sure you have these installed:
- PHP 8.1+
- Composer
- Node.js 16+
- npm or yarn
- MySQL 8.0+
- Git

---

## Step 1: Backend Setup (5 minutes)

### 1.1 Navigate to Backend Directory
```bash
cd eproject
```

### 1.2 Install PHP Dependencies
```bash
composer install
```

### 1.3 Configure Environment
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 1.4 Configure Database
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=global_heritage
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 1.5 Run Migrations
```bash
# Create database first
mysql -u root -p
CREATE DATABASE global_heritage;
exit;

# Run migrations
php artisan migrate

# Seed sample data (optional)
php artisan db:seed
```

### 1.6 Create Storage Link
```bash
php artisan storage:link
```

### 1.7 Start Laravel Server
```bash
php artisan serve
```

Backend is now running at: **http://localhost:8000**

---

## Step 2: Frontend Setup (5 minutes)

### 2.1 Open New Terminal

### 2.2 Navigate to Frontend Directory
```bash
cd frontend
```

### 2.3 Install Node Dependencies
```bash
npm install
```

### 2.4 Configure Environment (Optional)
Create `.env` file in frontend directory:
```env
REACT_APP_API_URL=http://localhost:8000/api/v1
```

### 2.5 Start React Development Server
```bash
npm start
```

Frontend is now running at: **http://localhost:3000**

---

## Step 3: Access the Application

### Frontend (User Interface)
Open browser: **http://localhost:3000**

Pages available:
- Home: http://localhost:3000/
- Monuments: http://localhost:3000/monuments
- Gallery: http://localhost:3000/gallery
- Contact: http://localhost:3000/contact
- Feedback: http://localhost:3000/feedback

### Backend (Admin Panel)
Open browser: **http://localhost:8000/admin**

Default credentials:
- **Admin**: admin@example.com / password
- **Moderator**: moderator@example.com / password

---

## Step 4: Verify Everything Works

### Check Backend
1. Go to http://localhost:8000/admin
2. Login with admin credentials
3. Navigate to Monuments section
4. Create a test monument

### Check Frontend
1. Go to http://localhost:3000
2. Click on "Monuments" in navigation
3. Verify the map displays
4. Check if monuments appear

### Check API
Open browser or Postman:
```
GET http://localhost:8000/api/v1/monuments
```

Should return JSON response with monuments data.

---

## Common Issues & Solutions

### Issue 1: "SQLSTATE[HY000] [1045] Access denied"
**Solution**: Check database credentials in `.env` file

### Issue 2: "Class 'App\Http\Controllers\...' not found"
**Solution**: Run `composer dump-autoload`

### Issue 3: "npm ERR! code ELIFECYCLE"
**Solution**: Delete `node_modules` and `package-lock.json`, then run `npm install` again

### Issue 4: "CORS policy error"
**Solution**: 
1. Check `config/cors.php` in Laravel
2. Add `http://localhost:3000` to allowed origins
3. Run `php artisan config:clear`

### Issue 5: Maps not displaying
**Solution**: 
1. Check internet connection (maps load from OpenStreetMap)
2. Verify Leaflet CSS is imported
3. Check browser console for errors

### Issue 6: Images not loading
**Solution**:
1. Run `php artisan storage:link`
2. Check if `storage/app/public` directory exists
3. Verify image paths in database

---

## Quick Commands Reference

### Laravel Commands
```bash
# Clear all caches
php artisan optimize:clear

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Create storage link
php artisan storage:link

# Start server
php artisan serve

# Run tests
php artisan test
```

### React Commands
```bash
# Install dependencies
npm install

# Start development server
npm start

# Build for production
npm run build

# Run tests
npm test
```

---

## Next Steps

### 1. Configure Cloudinary (Optional)
For image uploads to Cloudinary:

1. Sign up at https://cloudinary.com
2. Get your credentials
3. Add to `.env`:
```env
CLOUDINARY_CLOUD_NAME=your_cloud_name
CLOUDINARY_API_KEY=your_api_key
CLOUDINARY_API_SECRET=your_api_secret
```

### 2. Create Sample Data
```bash
# Run seeders
php artisan db:seed --class=MonumentSeeder
php artisan db:seed --class=GallerySeeder
```

### 3. Customize Design
- Frontend colors: Edit `frontend/tailwind.config.js`
- Backend theme: Edit `resources/views/layouts/admin.blade.php`

### 4. Add More Users
Go to Admin Panel > Users > Create New User

### 5. Configure Email (Optional)
For contact form emails:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
```

---

## Development Workflow

### Daily Development
1. Start Laravel: `php artisan serve`
2. Start React: `npm start`
3. Make changes
4. Test in browser
5. Commit changes: `git commit -m "Your message"`

### Before Committing
```bash
# Backend
composer dump-autoload
php artisan test

# Frontend
npm run build
```

---

## Production Deployment

### Backend (Laravel)
1. Set environment to production in `.env`:
```env
APP_ENV=production
APP_DEBUG=false
```

2. Optimize:
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

3. Deploy to server (e.g., DigitalOcean, AWS)

### Frontend (React)
1. Build production bundle:
```bash
npm run build
```

2. Deploy `build/` folder to:
   - Netlify
   - Vercel
   - AWS S3 + CloudFront
   - Your own server

---

## Useful Resources

### Documentation
- Laravel: https://laravel.com/docs
- React: https://react.dev
- Tailwind CSS: https://tailwindcss.com/docs
- Leaflet: https://leafletjs.com

### Community
- Laravel Discord: https://discord.gg/laravel
- React Discord: https://discord.gg/react
- Stack Overflow: https://stackoverflow.com

---

## Support

If you encounter issues:
1. Check error logs:
   - Laravel: `storage/logs/laravel.log`
   - React: Browser console (F12)
2. Clear caches:
   - Laravel: `php artisan optimize:clear`
   - React: Clear browser cache
3. Restart servers

---

## Summary

You should now have:
- ✅ Laravel backend running on http://localhost:8000
- ✅ React frontend running on http://localhost:3000
- ✅ Admin panel accessible at http://localhost:8000/admin
- ✅ API endpoints working at http://localhost:8000/api/v1
- ✅ Database configured and migrated
- ✅ Sample data loaded (if seeded)

**Total Setup Time**: ~10 minutes
**Status**: Ready for Development

---

## What's Next?

1. Explore the admin panel
2. Create some monuments
3. Upload images to gallery
4. Test the frontend features
5. Customize the design
6. Add your own content
7. Deploy to production

---

**Happy Coding!**

For detailed documentation, see:
- `FINAL_PROJECT_SUMMARY.md`
- `FRONTEND_COMPLETE_SUMMARY.md`
- `LARAVEL_API_SETUP.md`

