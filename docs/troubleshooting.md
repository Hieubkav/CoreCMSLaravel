# Troubleshooting

## 🚨 Lỗi thường gặp

### 1. Setup Wizard không hoạt động

**Triệu chứng:** Không thể truy cập `/setup`

**Nguyên nhân:**
- Database chưa được cấu hình
- File .env chưa đúng
- Permissions không đủ

**Giải pháp:**
```bash
# Kiểm tra .env
cp .env.example .env
php artisan key:generate

# Kiểm tra database connection
php artisan migrate:status

# Fix permissions
chmod -R 755 storage bootstrap/cache
```

### 2. Admin Panel không truy cập được

**Triệu chứng:** Lỗi 500 khi vào `/admin`

**Nguyên nhân:**
- Chưa chạy migrations
- User admin chưa được tạo
- Cache cũ

**Giải pháp:**
```bash
# Chạy migrations
php artisan migrate

# Tạo user admin
php artisan make:filament-user

# Clear cache
php artisan optimize:clear
```

### 3. Ảnh không hiển thị

**Triệu chứng:** Ảnh upload không hiển thị

**Nguyên nhân:**
- Storage link chưa được tạo
- Permissions thư mục storage

**Giải pháp:**
```bash
# Tạo storage link
php artisan storage:link

# Fix permissions
chmod -R 755 storage
```

### 4. CSS/JS không load

**Triệu chứng:** Giao diện bị vỡ

**Nguyên nhân:**
- Assets chưa được build
- Mix manifest không tìm thấy

**Giải pháp:**
```bash
# Install dependencies
npm install

# Build assets
npm run build

# Hoặc cho development
npm run dev
```

### 5. Database connection failed

**Triệu chứng:** Lỗi kết nối database

**Nguyên nhân:**
- Thông tin database sai trong .env
- Database chưa được tạo
- MySQL/PostgreSQL chưa chạy

**Giải pháp:**
```bash
# Kiểm tra .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=corelaravel
DB_USERNAME=root
DB_PASSWORD=your_password

# Tạo database
mysql -u root -p
CREATE DATABASE corelaravel;

# Test connection
php artisan migrate:status
```

### 6. Composer install failed

**Triệu chứng:** Lỗi khi chạy composer install

**Nguyên nhân:**
- PHP version không đủ
- Extensions thiếu
- Memory limit thấp

**Giải pháp:**
```bash
# Kiểm tra PHP version
php -v

# Cài đặt extensions cần thiết
# Ubuntu/Debian:
sudo apt install php8.1-mysql php8.1-xml php8.1-curl php8.1-zip

# Tăng memory limit
php -d memory_limit=512M composer install
```

### 7. NPM install failed

**Triệu chứng:** Lỗi khi chạy npm install

**Nguyên nhân:**
- Node.js version cũ
- Cache npm bị lỗi
- Network issues

**Giải pháp:**
```bash
# Clear npm cache
npm cache clean --force

# Delete node_modules
rm -rf node_modules package-lock.json

# Reinstall
npm install

# Hoặc dùng yarn
yarn install
```

### 8. Permission denied errors

**Triệu chứng:** Lỗi permission khi upload file

**Nguyên nhân:**
- Web server không có quyền ghi
- SELinux blocking

**Giải pháp:**
```bash
# Fix permissions
sudo chown -R www-data:www-data storage bootstrap/cache
chmod -R 755 storage bootstrap/cache

# SELinux (CentOS/RHEL)
sudo setsebool -P httpd_can_network_connect 1
sudo setsebool -P httpd_unified 1
```

### 9. Queue jobs không chạy

**Triệu chứng:** Email không gửi, jobs không xử lý

**Nguyên nhân:**
- Queue worker chưa chạy
- Queue driver chưa cấu hình

**Giải pháp:**
```bash
# Chạy queue worker
php artisan queue:work

# Hoặc dùng supervisor (production)
# /etc/supervisor/conf.d/laravel-worker.conf
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work
directory=/path/to/project
user=www-data
numprocs=8
redirect_stderr=true
stdout_logfile=/path/to/worker.log
```

### 10. Slow performance

**Triệu chứng:** Website chậm

**Nguyên nhân:**
- Cache chưa được bật
- Database queries không tối ưu
- Images chưa optimize

**Giải pháp:**
```bash
# Enable caching
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader

# Enable OPcache trong php.ini
opcache.enable=1
opcache.memory_consumption=128
```

## 🔍 Debug Tools

### Laravel Telescope

```bash
# Install Telescope (development only)
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

### Debug Bar

```bash
# Install Debug Bar
composer require barryvdh/laravel-debugbar --dev
```

### Logs

```bash
# Xem logs realtime
tail -f storage/logs/laravel.log

# Clear logs
> storage/logs/laravel.log
```

## 📊 Performance Monitoring

### Check PHP configuration

```bash
php -i | grep -E "(memory_limit|max_execution_time|upload_max_filesize)"
```

### Database queries

```bash
# Enable query logging trong AppServiceProvider
DB::listen(function ($query) {
    Log::info($query->sql, $query->bindings);
});
```

### Server resources

```bash
# Check disk space
df -h

# Check memory
free -m

# Check CPU
top
```

## 📞 Nhận hỗ trợ

### Thông tin cần cung cấp

1. **PHP version:** `php -v`
2. **Laravel version:** `php artisan --version`
3. **Error logs:** `storage/logs/laravel.log`
4. **Server info:** OS, web server, database
5. **Steps to reproduce:** Các bước tái hiện lỗi

### Kênh hỗ trợ

- **GitHub Issues:** [Repository Issues](link)
- **Email:** support@example.com
- **Discord:** [Server link]
- **Documentation:** [README.md](../README.md)

### Trước khi báo lỗi

1. ✅ Đã đọc documentation
2. ✅ Đã search existing issues
3. ✅ Đã thử các giải pháp cơ bản
4. ✅ Có thông tin chi tiết về lỗi
5. ✅ Có steps to reproduce
