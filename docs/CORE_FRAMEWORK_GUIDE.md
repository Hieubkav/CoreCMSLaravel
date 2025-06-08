# 🚀 Core Framework - Hướng dẫn sử dụng

## 📋 Tổng quan

Core Framework là một hệ thống quản lý nội dung đa năng được xây dựng trên Laravel 10, được thiết kế để có thể tái sử dụng cho nhiều dự án khác nhau. Framework cung cấp một nền tảng vững chắc với các tính năng cơ bản và có thể mở rộng dễ dàng.

## 🎯 Tính năng chính

### 🏗️ Core Features
- **Admin Panel**: Giao diện quản trị hiện đại với Filament 3.x
- **Content Management**: Quản lý bài viết, khóa học, học viên
- **SEO Optimization**: Tối ưu hóa SEO tự động
- **Image Processing**: Chuyển đổi ảnh sang WebP tự động
- **Responsive Design**: Giao diện tối ưu cho mọi thiết bị
- **Real-time Updates**: Cập nhật thời gian thực với Livewire

### 🔧 Technical Features
- **Laravel 10.x**: Framework PHP hiện đại
- **Filament 3.x**: Admin panel mạnh mẽ
- **Livewire 3.x**: Real-time components
- **Tailwind CSS**: Utility-first CSS framework
- **Alpine.js**: Lightweight JavaScript framework
- **MySQL/PostgreSQL**: Database support

## 🛠️ Cài đặt

### Yêu cầu hệ thống
- PHP >= 8.1
- Composer
- Node.js >= 16 & NPM
- MySQL >= 8.0 hoặc PostgreSQL >= 13
- Extensions: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, GD/Imagick

### Cài đặt nhanh

1. **Clone repository**
```bash
git clone [repository-url] my-project
cd my-project
```

2. **Cài đặt dependencies**
```bash
composer install
npm install
```

3. **Cấu hình environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Cấu hình database trong .env**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. **Chạy setup wizard**
```bash
php artisan serve
```
Truy cập `http://localhost:8000/setup` để bắt đầu wizard cài đặt.

6. **Hoặc cài đặt thủ công**
```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

### Setup Wizard

Framework cung cấp một wizard cài đặt thông minh với 4 bước:

1. **Database**: Kiểm tra kết nối và tạo bảng
2. **Admin**: Tạo tài khoản quản trị viên
3. **Website**: Cấu hình thông tin cơ bản
4. **Sample Data**: Import dữ liệu mẫu (tùy chọn)

## 📁 Cấu trúc dự án

### Models chính
```
app/Models/
├── Course.php          # Khóa học
├── Student.php         # Học viên
├── Post.php            # Bài viết
├── CatPost.php         # Danh mục bài viết
├── CatCourse.php       # Danh mục khóa học
├── Instructor.php      # Giảng viên
├── Setting.php         # Cài đặt hệ thống
├── Slider.php          # Banner slider
├── MenuItem.php        # Menu navigation
└── User.php            # Người dùng
```

### Controllers chính
```
app/Http/Controllers/
├── MainController.php      # Trang chủ
├── CourseController.php    # Khóa học
├── PostController.php      # Bài viết
├── StudentController.php   # Học viên
├── SetupController.php     # Cài đặt hệ thống
└── SearchController.php    # Tìm kiếm
```

### Livewire Components
```
app/Livewire/
├── CourseList.php          # Danh sách khóa học
├── PostList.php            # Danh sách bài viết
├── SearchComponent.php     # Tìm kiếm
└── HomePage.php            # Trang chủ
```

## 🎨 Tùy chỉnh

### Thay đổi theme và màu sắc

1. **Cấu hình Tailwind CSS**
```javascript
// tailwind.config.js
module.exports = {
    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#fef2f2',
                    500: '#ef4444',
                    600: '#dc2626',
                    // ... your custom colors
                }
            }
        }
    }
}
```

2. **Cập nhật CSS variables**
```css
/* resources/css/app.css */
:root {
    --color-primary: #dc2626;
    --color-secondary: #64748b;
    /* ... your custom variables */
}
```

### Thêm module mới

1. **Tạo Model**
```bash
php artisan make:model YourModel -m
```

2. **Tạo Filament Resource**
```bash
php artisan make:filament-resource YourModel --generate
```

3. **Tạo Controller**
```bash
php artisan make:controller YourModelController
```

4. **Thêm routes**
```php
// routes/web.php
Route::controller(YourModelController::class)->group(function () {
    Route::get('/your-route', 'index')->name('your.index');
    Route::get('/your-route/{slug}', 'show')->name('your.show');
});
```

### Cấu hình SEO

Framework tự động tối ưu hóa SEO với:
- Meta tags tự động
- Open Graph images
- Structured data
- Sitemap XML
- Robots.txt

Để tùy chỉnh SEO:
```php
// app/Models/YourModel.php
protected $fillable = [
    'title',
    'content',
    'seo_title',
    'seo_description',
    'og_image_link',
    // ...
];
```

## 🔧 Cấu hình nâng cao

### Cache Configuration

Framework sử dụng ViewServiceProvider để cache dữ liệu:

```php
// config/cache.php
'stores' => [
    'view_cache' => [
        'driver' => 'redis', // hoặc 'file'
        'connection' => 'cache',
    ],
],
```

### Image Processing

Cấu hình xử lý ảnh:
```php
// config/image.php
return [
    'webp_quality' => 95,
    'max_width' => 1920,
    'max_height' => 1080,
    'thumbnail_size' => [300, 200],
];
```

### Email Configuration

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

## 🚀 Deployment

### Production Setup

1. **Optimize for production**
```bash
composer install --optimize-autoloader --no-dev
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

2. **Set environment**
```env
APP_ENV=production
APP_DEBUG=false
```

3. **Configure web server**
- Point document root to `public/` directory
- Configure URL rewriting for Laravel
- Set proper file permissions

### Security Checklist

- [ ] Update all dependencies
- [ ] Configure HTTPS
- [ ] Set strong APP_KEY
- [ ] Configure CORS properly
- [ ] Set up rate limiting
- [ ] Configure CSP headers
- [ ] Regular security updates

## 📚 API Documentation

### REST Endpoints

```
GET /api/courses/search          # Tìm kiếm khóa học
GET /api/posts/search           # Tìm kiếm bài viết
POST /api/students/enroll       # Đăng ký khóa học
GET /api/realtime-stats         # Thống kê real-time
```

### Response Format

```json
{
    "success": true,
    "data": {
        // ... response data
    },
    "message": "Success message",
    "timestamp": "2024-01-01T00:00:00.000000Z"
}
```

## 🤝 Contributing

### Development Workflow

1. Fork repository
2. Create feature branch
3. Make changes
4. Write tests
5. Submit pull request

### Code Standards

- Follow PSR-12 coding standards
- Use meaningful variable names
- Write comprehensive comments
- Include unit tests for new features

## 📞 Support

### Documentation
- [Installation Guide](installation.md)
- [API Reference](api.md)
- [Troubleshooting](troubleshooting.md)

### Community
- GitHub Issues: Report bugs and feature requests
- Discussions: Ask questions and share ideas

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](../LICENSE) file for details.

---

**Core Framework v1.0** - Built with ❤️ using Laravel
