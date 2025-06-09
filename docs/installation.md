# Hướng dẫn cài đặt

## 📋 Yêu cầu hệ thống

- **PHP:** 8.1 trở lên
- **Composer:** Phiên bản mới nhất
- **Node.js:** 16+ và NPM
- **Database:** MySQL 5.7+ hoặc PostgreSQL 10+
- **Web server:** Apache hoặc Nginx

## ⚡ Cài đặt nhanh

### Bước 1: Tải về

```bash
git clone https://github.com/your-repo/core-laravel-framework.git
cd core-laravel-framework
```

### Bước 2: Cài đặt dependencies

```bash
composer install
npm install
```

### Bước 3: Cấu hình môi trường

```bash
cp .env.example .env
php artisan key:generate
```

### Bước 4: Cấu hình database

Chỉnh sửa file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=corelaravel
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Bước 5: Build assets

```bash
npm run build
```

### Bước 6: Chạy Setup Wizard

```bash
php artisan serve
```

Truy cập: `http://localhost:8000/setup`

## 🎯 Setup Wizard

### Bước 1: Database
- Kiểm tra kết nối database
- Tự động chạy migrations

### Bước 2: Admin User
- Tạo tài khoản admin đầu tiên
- Thiết lập mật khẩu

### Bước 3: Website Settings
- Tên website
- Thông tin liên hệ
- Logo và favicon

### Bước 4: Chọn Modules
- Chọn 9 modules cần thiết
- Cấu hình từng module

### Bước 5: Cài đặt
- Tự động tạo code
- Chạy seeders
- Hoàn thành!

## 🔧 Cài đặt thủ công (nếu cần)

```bash
# Chạy migrations
php artisan migrate

# Chạy seeders
php artisan db:seed

# Tạo symbolic link cho storage
php artisan storage:link

# Clear cache
php artisan optimize:clear
```

## 🚀 Production

### Cấu hình web server

**Apache (.htaccess):**
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

**Nginx:**
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### Tối ưu production

```bash
# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Cache config
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize
php artisan optimize
```

## 🐛 Troubleshooting

### Lỗi thường gặp

**1. Permission denied**
```bash
chmod -R 755 storage bootstrap/cache
```

**2. Key not found**
```bash
php artisan key:generate
```

**3. Database connection failed**
- Kiểm tra thông tin database trong `.env`
- Đảm bảo database đã được tạo

**4. Node modules error**
```bash
rm -rf node_modules
npm install
```

### Logs

Kiểm tra logs tại: `storage/logs/laravel.log`

## 📞 Hỗ trợ

- GitHub Issues: [Link repository]
- Email: support@example.com
- Documentation: [docs/](../README.md)
