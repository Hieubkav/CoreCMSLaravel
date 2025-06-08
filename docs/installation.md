# Hướng dẫn Cài đặt

Tài liệu này hướng dẫn chi tiết cách cài đặt dự án Vũ Phúc từ đầu.

## 📋 Yêu cầu Hệ thống

### Phần mềm bắt buộc
- **PHP**: >= 8.1
- **Composer**: Latest version
- **Node.js**: >= 16.x
- **NPM**: >= 8.x
- **Database**: MySQL 8.0+ hoặc PostgreSQL 13+

### Extensions PHP cần thiết
```bash
php -m | grep -E "(pdo|mbstring|openssl|tokenizer|xml|ctype|json|bcmath|gd|fileinfo)"
```

## 🛠️ Cài đặt từng bước

### 1. Clone Repository
```bash
git clone [repository-url] vuphuc
cd vuphuc
```

### 2. Cài đặt Dependencies

#### PHP Dependencies
```bash
composer install --ignore-platform-reqs
```

> **Lưu ý**: Sử dụng `--ignore-platform-reqs` nếu gặp lỗi trên Windows

#### JavaScript Dependencies
```bash
npm install
```

### 3. Cấu hình Môi trường

#### Tạo file .env
```bash
cp .env.example .env
```

#### Tạo Application Key
```bash
php artisan key:generate
```

#### Cấu hình Database
Chỉnh sửa file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vuphuc
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Thiết lập Database

#### Tạo Database
```sql
CREATE DATABASE vuphuc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

#### Chạy Migration
```bash
php artisan migrate
```

#### Seed dữ liệu mẫu
```bash
php artisan db:seed
```

### 5. Cấu hình Storage

#### Tạo symbolic link
```bash
php artisan storage:link
```

#### Thiết lập quyền (Linux/Mac)
```bash
chmod -R 775 storage bootstrap/cache
```

### 6. Build Assets

#### Development
```bash
npm run dev
```

#### Production
```bash
npm run build
```

### 7. Tối ưu hóa

```bash
# Cache icons
php artisan icons:cache

# Optimize Filament
php artisan filament:optimize

# Optimize Laravel
php artisan optimize
```

## 🚀 Khởi động

### Development Server
```bash
php artisan serve
```

Truy cập: http://127.0.0.1:8000

### Admin Panel
Truy cập: http://127.0.0.1:8000/admin

**Tài khoản mặc định:**
- Email: admin@vuphuc.com
- Password: password

## ✅ Kiểm tra Cài đặt

### Kiểm tra Laravel
```bash
php artisan --version
```

### Kiểm tra Filament
```bash
php artisan filament:check
```

### Kiểm tra Database
```bash
php artisan migrate:status
```

## 🔧 Troubleshooting

### Lỗi Composer trên Windows
```bash
composer install --ignore-platform-reqs
```

### Lỗi quyền Storage
```bash
# Windows
icacls storage /grant Everyone:F /T
icacls bootstrap/cache /grant Everyone:F /T

# Linux/Mac
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

### Lỗi NPM
```bash
# Xóa node_modules và cài lại
rm -rf node_modules package-lock.json
npm install
```

## 📚 Bước tiếp theo

- [Hướng dẫn phát triển](development.md)
- [Cấu trúc dự án](guides/project-structure.md)
- [Tài liệu API](api.md)
