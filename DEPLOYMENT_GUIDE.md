# 🚀 Global Heritage CMS - Deployment Guide

## ✅ Pre-Deployment Checklist

### 1. Environment Configuration
- [x] Database connection working
- [x] All required tables exist
- [x] Admin user exists
- [x] API routes registered
- [x] Models working correctly
- [x] Storage permissions correct

### 2. Required Configurations

#### Database (Already configured)
```env
DB_CONNECTION=mysql
DB_HOST=103.97.126.78
DB_PORT=3306
DB_DATABASE=eproject_1
DB_USERNAME=eproject_1
DB_PASSWORD=j4i1yz8mz5a6wg8gjn0c
```

#### Mail Configuration (Need to configure)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="admin@yourdomain.com"
MAIL_FROM_NAME="Global Heritage CMS"
```

#### Cloudinary Configuration (Need to configure)
```env
CLOUDINARY_URL=cloudinary://your_cloud_name:your_api_key:your_api_secret@your_cloud_name
CLOUDINARY_CLOUD_NAME=your_cloud_name
CLOUDINARY_API_KEY=your_api_key
CLOUDINARY_API_SECRET=your_api_secret
```

## 📋 Deployment Steps

### Step 1: Prepare Environment
```bash
# Copy production environment template
cp env.production.example .env

# Generate application key
php artisan key:generate

# Configure your .env file with actual values
nano .env
```

### Step 2: Optimize for Production
```bash
# Clear and cache configurations
php artisan config:clear
php artisan config:cache

# Clear and cache routes
php artisan route:clear
php artisan route:cache

# Clear and cache views
php artisan view:clear
php artisan view:cache

# Clear application cache
php artisan cache:clear
```

### Step 3: Build Frontend
```bash
cd frontend
npm install
npm run build
cd ..
```

### Step 4: Set Permissions
```bash
# Set proper permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod -R 755 public

# Set ownership (adjust user/group as needed)
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
chown -R www-data:www-data public
```

### Step 5: Web Server Configuration

#### Apache Virtual Host Example
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /path/to/eproject/public
    
    <Directory /path/to/eproject/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    # Redirect to HTTPS
    RewriteEngine On
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</VirtualHost>

<VirtualHost *:443>
    ServerName yourdomain.com
    DocumentRoot /path/to/eproject/public
    
    <Directory /path/to/eproject/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    # SSL Configuration
    SSLEngine on
    SSLCertificateFile /path/to/your/certificate.crt
    SSLCertificateKeyFile /path/to/your/private.key
</VirtualHost>
```

#### Nginx Configuration Example
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com;
    root /path/to/eproject/public;
    index index.php;

    ssl_certificate /path/to/your/certificate.crt;
    ssl_certificate_key /path/to/your/private.key;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

### Step 6: Final Verification
```bash
# Run deployment check
php deploy_check.php

# Test API endpoints
curl https://yourdomain.com/api/health
curl https://yourdomain.com/api/posts
curl https://yourdomain.com/api/monuments
```

## 🔧 Post-Deployment Configuration

### 1. Configure Email Settings
1. Go to Admin Panel → Settings
2. Configure email settings for contact form
3. Test email functionality

### 2. Configure Cloudinary
1. Set up Cloudinary account
2. Update CLOUDINARY_* variables in .env
3. Test image upload functionality

### 3. Set Up Monitoring
- Monitor server resources
- Set up log rotation
- Configure backup strategy
- Set up SSL certificate auto-renewal

## 🚨 Troubleshooting

### Common Issues

#### 1. 500 Internal Server Error
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Check web server logs
tail -f /var/log/apache2/error.log
# or
tail -f /var/log/nginx/error.log
```

#### 2. Database Connection Issues
```bash
# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();
```

#### 3. Permission Issues
```bash
# Fix permissions
sudo chown -R www-data:www-data /path/to/eproject
sudo chmod -R 755 /path/to/eproject
```

#### 4. Cache Issues
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 📊 Performance Optimization

### 1. Enable OPcache
```ini
; php.ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=4000
opcache.revalidate_freq=60
```

### 2. Database Optimization
```sql
-- Add indexes for better performance
ALTER TABLE posts ADD INDEX idx_status_created (status, created_at);
ALTER TABLE monuments ADD INDEX idx_status_created (status, created_at);
ALTER TABLE user_notifications ADD INDEX idx_user_read (user_id, is_read);
```

### 3. CDN Configuration
- Configure Cloudinary CDN
- Enable gzip compression
- Set up browser caching

## 🔒 Security Checklist

- [ ] SSL certificate installed and working
- [ ] .env file not accessible via web
- [ ] Database credentials secure
- [ ] File upload restrictions in place
- [ ] Rate limiting configured
- [ ] CORS properly configured
- [ ] Input validation enabled
- [ ] SQL injection protection active

## 📈 Monitoring & Maintenance

### Daily
- Check error logs
- Monitor disk space
- Check database performance

### Weekly
- Review access logs
- Check for security updates
- Backup database

### Monthly
- Update dependencies
- Review performance metrics
- Test backup restoration

---

## 🎉 Deployment Complete!

Your Global Heritage CMS is now ready for production use. Make sure to:

1. Test all functionality thoroughly
2. Set up monitoring and alerts
3. Configure regular backups
4. Keep the system updated

For support, check the logs and documentation in the `docs/` directory.


