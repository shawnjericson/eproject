# **🚀 Deployment Guide - Global Heritage Project**

## **📋 Overview**

Hướng dẫn deploy dự án Global Heritage lên production server với cấu hình tối ưu cho performance và security.

---

## **🖥️ Server Requirements**

### **Minimum Requirements**
- **OS**: Ubuntu 20.04 LTS hoặc CentOS 8+
- **RAM**: 4GB (khuyến nghị 8GB+)
- **Storage**: 50GB SSD
- **CPU**: 2 cores (khuyến nghị 4 cores+)
- **Network**: 100Mbps

### **Software Requirements**
- **PHP**: 8.1+ với extensions:
  - BCMath
  - Ctype
  - cURL
  - DOM
  - Fileinfo
  - JSON
  - Mbstring
  - OpenSSL
  - PDO
  - Tokenizer
  - XML
  - GD hoặc Imagick
  - Redis extension
- **MySQL**: 8.0+ hoặc MariaDB 10.6+
- **Nginx**: 1.18+ hoặc Apache 2.4+
- **Node.js**: 18+ (cho frontend build)
- **Composer**: 2.0+
- **Redis**: 6.0+ (cho caching)

---

## **🔧 Server Setup**

### **1. Update System**
```bash
# Ubuntu/Debian
sudo apt update && sudo apt upgrade -y

# CentOS/RHEL
sudo yum update -y
```

### **2. Install PHP 8.1**
```bash
# Ubuntu/Debian
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install php8.1-fpm php8.1-cli php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl php8.1-gd php8.1-zip php8.1-bcmath php8.1-redis -y

# CentOS/RHEL
sudo yum install epel-release -y
sudo yum install https://rpms.remirepo.net/enterprise/remi-release-8.rpm -y
sudo yum module enable php:remi-8.1 -y
sudo yum install php php-fpm php-cli php-mysqlnd php-xml php-mbstring php-curl php-gd php-zip php-bcmath php-redis -y
```

### **3. Install MySQL/MariaDB**
```bash
# Ubuntu/Debian
sudo apt install mysql-server -y

# CentOS/RHEL
sudo yum install mysql-server -y

# Start and enable MySQL
sudo systemctl start mysql
sudo systemctl enable mysql

# Secure MySQL installation
sudo mysql_secure_installation
```

### **4. Install Nginx**
```bash
# Ubuntu/Debian
sudo apt install nginx -y

# CentOS/RHEL
sudo yum install nginx -y

# Start and enable Nginx
sudo systemctl start nginx
sudo systemctl enable nginx
```

### **5. Install Node.js**
```bash
# Install Node.js 18
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs -y

# Verify installation
node --version
npm --version
```

### **6. Install Composer**
```bash
# Download and install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer

# Verify installation
composer --version
```

### **7. Install Redis**
```bash
# Ubuntu/Debian
sudo apt install redis-server -y

# CentOS/RHEL
sudo yum install redis -y

# Start and enable Redis
sudo systemctl start redis
sudo systemctl enable redis
```

---

## **🗄️ Database Setup**

### **1. Create Database and User**
```sql
-- Login to MySQL
mysql -u root -p

-- Create database
CREATE DATABASE global_heritage CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create user
CREATE USER 'heritage_user'@'localhost' IDENTIFIED BY 'strong_password_here';

-- Grant privileges
GRANT ALL PRIVILEGES ON global_heritage.* TO 'heritage_user'@'localhost';
FLUSH PRIVILEGES;

-- Exit MySQL
EXIT;
```

### **2. Test Database Connection**
```bash
mysql -u heritage_user -p global_heritage
```

---

## **📁 Application Deployment**

### **1. Clone Repository**
```bash
# Create application directory
sudo mkdir -p /var/www/global-heritage
sudo chown -R $USER:$USER /var/www/global-heritage

# Clone repository
cd /var/www/global-heritage
git clone https://github.com/your-username/global-heritage.git .

# Set proper permissions
sudo chown -R www-data:www-data /var/www/global-heritage
sudo chmod -R 755 /var/www/global-heritage
```

### **2. Install Dependencies**
```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node.js dependencies
cd frontend
npm install
npm run build
cd ..
```

### **3. Environment Configuration**
```bash
# Copy environment file
cp .env.example .env

# Edit environment file
nano .env
```

**Production .env Configuration:**
```env
APP_NAME="Global Heritage"
APP_ENV=production
APP_KEY=base64:your_generated_key_here
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=global_heritage
DB_USERNAME=heritage_user
DB_PASSWORD=strong_password_here

BROADCAST_DRIVER=log
CACHE_DRIVER=redis
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=redis
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

CLOUDINARY_URL=cloudinary://api_key:api_secret@cloud_name

VITE_APP_URL=https://yourdomain.com
```

### **4. Generate Application Key**
```bash
php artisan key:generate
```

### **5. Database Migration and Seeding**
```bash
# Run migrations
php artisan migrate --force

# Seed database (optional)
php artisan db:seed --force

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## **🌐 Nginx Configuration**

### **1. Create Nginx Configuration**
```bash
sudo nano /etc/nginx/sites-available/global-heritage
```

**Nginx Configuration:**
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/global-heritage/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    # Handle Laravel routes
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Handle API routes
    location /api {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Handle admin routes
    location /admin {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Serve React frontend
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Handle static files
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    # Security headers
    location ~ /\.(?!well-known).* {
        deny all;
    }

    # PHP handling
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_proxied expired no-cache no-store private must-revalidate auth;
    gzip_types
        text/plain
        text/css
        text/xml
        text/javascript
        application/javascript
        application/xml+rss
        application/json;
}
```

### **2. Enable Site**
```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/global-heritage /etc/nginx/sites-enabled/

# Test configuration
sudo nginx -t

# Reload Nginx
sudo systemctl reload nginx
```

---

## **🔒 SSL Certificate (Let's Encrypt)**

### **1. Install Certbot**
```bash
# Ubuntu/Debian
sudo apt install certbot python3-certbot-nginx -y

# CentOS/RHEL
sudo yum install certbot python3-certbot-nginx -y
```

### **2. Obtain SSL Certificate**
```bash
# Get certificate
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Test auto-renewal
sudo certbot renew --dry-run
```

---

## **⚡ Performance Optimization**

### **1. PHP-FPM Optimization**
```bash
sudo nano /etc/php/8.1/fpm/pool.d/www.conf
```

**PHP-FPM Configuration:**
```ini
[www]
user = www-data
group = www-data
listen = /var/run/php/php8.1-fpm.sock
listen.owner = www-data
listen.group = www-data
listen.mode = 0660

pm = dynamic
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
pm.max_requests = 1000

php_admin_value[memory_limit] = 256M
php_admin_value[max_execution_time] = 300
php_admin_value[upload_max_filesize] = 50M
php_admin_value[post_max_size] = 50M
```

### **2. Redis Configuration**
```bash
sudo nano /etc/redis/redis.conf
```

**Redis Configuration:**
```conf
maxmemory 256mb
maxmemory-policy allkeys-lru
save 900 1
save 300 10
save 60 10000
```

### **3. MySQL Optimization**
```bash
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf
```

**MySQL Configuration:**
```ini
[mysqld]
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
innodb_flush_log_at_trx_commit = 2
innodb_flush_method = O_DIRECT
query_cache_type = 1
query_cache_size = 64M
query_cache_limit = 2M
max_connections = 200
```

---

## **🔐 Security Hardening**

### **1. Firewall Configuration**
```bash
# Install UFW
sudo apt install ufw -y

# Configure firewall
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow ssh
sudo ufw allow 'Nginx Full'
sudo ufw enable
```

### **2. Fail2Ban Setup**
```bash
# Install Fail2Ban
sudo apt install fail2ban -y

# Configure Fail2Ban
sudo nano /etc/fail2ban/jail.local
```

**Fail2Ban Configuration:**
```ini
[DEFAULT]
bantime = 3600
findtime = 600
maxretry = 3

[sshd]
enabled = true
port = ssh
logpath = /var/log/auth.log

[nginx-http-auth]
enabled = true
filter = nginx-http-auth
port = http,https
logpath = /var/log/nginx/error.log

[nginx-limit-req]
enabled = true
filter = nginx-limit-req
port = http,https
logpath = /var/log/nginx/error.log
maxretry = 10
```

### **3. File Permissions**
```bash
# Set proper permissions
sudo chown -R www-data:www-data /var/www/global-heritage
sudo chmod -R 755 /var/www/global-heritage
sudo chmod -R 775 /var/www/global-heritage/storage
sudo chmod -R 775 /var/www/global-heritage/bootstrap/cache
```

---

## **📊 Monitoring Setup**

### **1. Install Monitoring Tools**
```bash
# Install htop for system monitoring
sudo apt install htop -y

# Install iotop for disk monitoring
sudo apt install iotop -y

# Install nethogs for network monitoring
sudo apt install nethogs -y
```

### **2. Log Rotation**
```bash
sudo nano /etc/logrotate.d/global-heritage
```

**Log Rotation Configuration:**
```
/var/www/global-heritage/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 644 www-data www-data
}
```

---

## **🔄 Backup Strategy**

### **1. Database Backup Script**
```bash
sudo nano /usr/local/bin/backup-db.sh
```

**Database Backup Script:**
```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/var/backups/global-heritage"
DB_NAME="global_heritage"
DB_USER="heritage_user"
DB_PASS="strong_password_here"

mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/db_backup_$DATE.sql

# Compress backup
gzip $BACKUP_DIR/db_backup_$DATE.sql

# Keep only last 7 days
find $BACKUP_DIR -name "db_backup_*.sql.gz" -mtime +7 -delete

echo "Database backup completed: db_backup_$DATE.sql.gz"
```

### **2. File Backup Script**
```bash
sudo nano /usr/local/bin/backup-files.sh
```

**File Backup Script:**
```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/var/backups/global-heritage"
APP_DIR="/var/www/global-heritage"

mkdir -p $BACKUP_DIR

# Create tar backup
tar -czf $BACKUP_DIR/files_backup_$DATE.tar.gz -C $APP_DIR .

# Keep only last 7 days
find $BACKUP_DIR -name "files_backup_*.tar.gz" -mtime +7 -delete

echo "Files backup completed: files_backup_$DATE.tar.gz"
```

### **3. Setup Cron Jobs**
```bash
# Edit crontab
sudo crontab -e

# Add backup jobs
0 2 * * * /usr/local/bin/backup-db.sh
0 3 * * * /usr/local/bin/backup-files.sh
```

---

## **🚀 Deployment Checklist**

### **Pre-Deployment**
- [ ] Server requirements met
- [ ] All software installed
- [ ] Database created and configured
- [ ] SSL certificate obtained
- [ ] Domain DNS configured

### **Deployment**
- [ ] Code deployed to server
- [ ] Dependencies installed
- [ ] Environment configured
- [ ] Database migrated
- [ ] Frontend built
- [ ] Nginx configured
- [ ] PHP-FPM optimized

### **Post-Deployment**
- [ ] Site accessible via HTTPS
- [ ] All features working
- [ ] Performance optimized
- [ ] Security hardened
- [ ] Monitoring setup
- [ ] Backup configured
- [ ] Documentation updated

---

## **🔧 Maintenance Commands**

### **Laravel Commands**
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Queue management
php artisan queue:work --daemon
php artisan queue:restart

# Maintenance mode
php artisan down
php artisan up
```

### **System Commands**
```bash
# Check system status
sudo systemctl status nginx
sudo systemctl status mysql
sudo systemctl status redis
sudo systemctl status php8.1-fpm

# Restart services
sudo systemctl restart nginx
sudo systemctl restart mysql
sudo systemctl restart redis
sudo systemctl restart php8.1-fpm

# Check logs
sudo tail -f /var/log/nginx/error.log
sudo tail -f /var/www/global-heritage/storage/logs/laravel.log
```

---

## **📞 Troubleshooting**

### **Common Issues**

#### **1. 502 Bad Gateway**
```bash
# Check PHP-FPM status
sudo systemctl status php8.1-fpm

# Check PHP-FPM logs
sudo tail -f /var/log/php8.1-fpm.log

# Restart PHP-FPM
sudo systemctl restart php8.1-fpm
```

#### **2. Database Connection Error**
```bash
# Check MySQL status
sudo systemctl status mysql

# Test database connection
mysql -u heritage_user -p global_heritage

# Check Laravel logs
tail -f /var/www/global-heritage/storage/logs/laravel.log
```

#### **3. Permission Issues**
```bash
# Fix permissions
sudo chown -R www-data:www-data /var/www/global-heritage
sudo chmod -R 755 /var/www/global-heritage
sudo chmod -R 775 /var/www/global-heritage/storage
sudo chmod -R 775 /var/www/global-heritage/bootstrap/cache
```

#### **4. SSL Certificate Issues**
```bash
# Check certificate status
sudo certbot certificates

# Renew certificate
sudo certbot renew

# Test SSL
openssl s_client -connect yourdomain.com:443
```

---

## **📈 Performance Monitoring**

### **Key Metrics to Monitor**
- **Response Time**: < 2 seconds
- **Memory Usage**: < 80% of available RAM
- **CPU Usage**: < 70% average
- **Disk Usage**: < 80% of available space
- **Database Connections**: < 80% of max_connections
- **Error Rate**: < 1% of total requests

### **Monitoring Tools**
- **htop**: System resource monitoring
- **iotop**: Disk I/O monitoring
- **nethogs**: Network monitoring
- **Laravel Telescope**: Application monitoring
- **New Relic**: APM monitoring (optional)

---

**🚀 Deployment Guide này cung cấp hướng dẫn chi tiết để deploy dự án Global Heritage lên production server một cách an toàn và hiệu quả.**


