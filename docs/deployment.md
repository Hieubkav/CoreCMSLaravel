# Hướng dẫn Triển khai (Deployment)

Tài liệu này hướng dẫn cách triển khai dự án Vũ Phúc lên môi trường production.

## 🎯 Chuẩn bị Triển khai

### Checklist trước khi deploy
- [ ] Code đã được test đầy đủ
- [ ] Database migration đã sẵn sàng
- [ ] Environment variables đã được cấu hình
- [ ] SSL certificate đã được thiết lập
- [ ] Backup database hiện tại (nếu có)

### Yêu cầu Server
- **OS**: Ubuntu 20.04+ / CentOS 8+
- **Web Server**: Nginx / Apache
- **PHP**: 8.1+
- **Database**: MySQL 8.0+ / PostgreSQL 13+
- **Memory**: Tối thiểu 2GB RAM
- **Storage**: Tối thiểu 10GB

## 🖥️ Cài đặt Server

### 1. Cập nhật hệ thống
```bash
sudo apt update && sudo apt upgrade -y
```

### 2. Cài đặt PHP 8.1
```bash
sudo apt install software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update

sudo apt install php8.1 php8.1-fpm php8.1-mysql php8.1-xml php8.1-mbstring \
php8.1-curl php8.1-zip php8.1-gd php8.1-bcmath php8.1-intl php8.1-redis
```

### 3. Cài đặt Composer
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### 4. Cài đặt Node.js
```bash
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs
```

### 5. Cài đặt Nginx
```bash
sudo apt install nginx
sudo systemctl enable nginx
sudo systemctl start nginx
```

### 6. Cài đặt MySQL
```bash
sudo apt install mysql-server
sudo mysql_secure_installation
```

## 📁 Deploy Code

### 1. Clone repository
```bash
cd /var/www
sudo git clone [repository-url] vuphuc
sudo chown -R www-data:www-data vuphuc
cd vuphuc
```

### 2. Cài đặt dependencies
```bash
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

### 3. Cấu hình môi trường
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Cấu hình .env production
```env
APP_NAME="Vũ Phúc"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://vuphuc.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vuphuc_prod
DB_USERNAME=vuphuc_user
DB_PASSWORD=secure_password

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
```

### 5. Thiết lập database
```bash
# Tạo database
mysql -u root -p
CREATE DATABASE vuphuc_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'vuphuc_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON vuphuc_prod.* TO 'vuphuc_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Chạy migration
php artisan migrate --force
php artisan db:seed --force
```

### 6. Thiết lập quyền
```bash
sudo chown -R www-data:www-data /var/www/vuphuc
sudo chmod -R 755 /var/www/vuphuc
sudo chmod -R 775 /var/www/vuphuc/storage
sudo chmod -R 775 /var/www/vuphuc/bootstrap/cache
```

### 7. Tạo symbolic link
```bash
php artisan storage:link
```

## ⚙️ Cấu hình Nginx

### Tạo file cấu hình
```bash
sudo nano /etc/nginx/sites-available/vuphuc
```

### Nội dung file cấu hình
```nginx
server {
    listen 80;
    server_name vuphuc.com www.vuphuc.com;
    root /var/www/vuphuc/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Kích hoạt site
```bash
sudo ln -s /etc/nginx/sites-available/vuphuc /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

## 🔒 SSL Certificate

### Cài đặt Certbot
```bash
sudo apt install certbot python3-certbot-nginx
```

### Tạo SSL certificate
```bash
sudo certbot --nginx -d vuphuc.com -d www.vuphuc.com
```

### Auto-renewal
```bash
sudo crontab -e
# Thêm dòng sau:
0 12 * * * /usr/bin/certbot renew --quiet
```

## 🚀 Tối ưu hóa Production

### 1. Cache Laravel
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan icons:cache
php artisan filament:optimize
```

### 2. Cấu hình OPcache
```bash
sudo nano /etc/php/8.1/fpm/conf.d/10-opcache.ini
```

```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
opcache.save_comments=1
```

### 3. Cấu hình Redis (nếu sử dụng)
```bash
sudo apt install redis-server
sudo systemctl enable redis-server
sudo systemctl start redis-server
```

## 📊 Monitoring & Logs

### Log files
```bash
# Laravel logs
tail -f /var/www/vuphuc/storage/logs/laravel.log

# Nginx logs
tail -f /var/log/nginx/access.log
tail -f /var/log/nginx/error.log

# PHP-FPM logs
tail -f /var/log/php8.1-fpm.log
```

### Cron jobs
```bash
sudo crontab -e
# Thêm Laravel scheduler
* * * * * cd /var/www/vuphuc && php artisan schedule:run >> /dev/null 2>&1
```

## 🔄 Update Process

### Script update tự động
```bash
#!/bin/bash
cd /var/www/vuphuc

# Backup database
mysqldump -u vuphuc_user -p vuphuc_prod > backup_$(date +%Y%m%d_%H%M%S).sql

# Pull latest code
git pull origin main

# Update dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Run migrations
php artisan migrate --force

# Clear caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart services
sudo systemctl reload nginx
sudo systemctl restart php8.1-fpm

echo "Deployment completed successfully!"
```

## 🆘 Troubleshooting

### Lỗi thường gặp

#### 1. Permission denied
```bash
sudo chown -R www-data:www-data /var/www/vuphuc
sudo chmod -R 755 /var/www/vuphuc
```

#### 2. 500 Internal Server Error
```bash
# Kiểm tra logs
tail -f /var/log/nginx/error.log
tail -f /var/www/vuphuc/storage/logs/laravel.log
```

#### 3. Database connection failed
```bash
# Kiểm tra MySQL service
sudo systemctl status mysql
# Kiểm tra credentials trong .env
```

#### 4. Assets không load
```bash
# Rebuild assets
npm run build
# Kiểm tra symbolic link
php artisan storage:link
```

## 📞 Support

Nếu gặp vấn đề trong quá trình deploy, vui lòng:
1. Kiểm tra logs chi tiết
2. Tham khảo troubleshooting guide
3. Liên hệ team development
