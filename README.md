# Core Framework - Hệ thống Quản lý Nội dung Đa năng

Framework tái sử dụng được xây dựng trên Laravel 10 với Filament Admin Panel, tích hợp Livewire và Tailwind CSS. Phù hợp cho các dự án website, khóa học trực tuyến, blog và CMS.

## 🚀 Tính năng chính

- **Setup Wizard**: Cài đặt dự án tự động với giao diện thân thiện
- **Admin Panel**: Quản trị toàn diện với Filament 3.x
- **Content Management**: Quản lý bài viết, khóa học, học viên
- **Responsive Design**: Giao diện tối ưu cho mọi thiết bị
- **SEO Optimized**: Tối ưu hóa SEO tự động
- **Real-time Updates**: Cập nhật thời gian thực với Livewire
- **Image Optimization**: Tự động chuyển đổi ảnh sang WebP
- **Modular Architecture**: Dễ dàng mở rộng và tùy chỉnh
- **100% Clean**: Hoàn toàn tổng quát, không còn dấu ấn dự án cũ

## 📋 Yêu cầu hệ thống

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL/PostgreSQL
- Laravel 10.x

## 🛠️ Cài đặt nhanh

### Phương pháp 1: Setup Wizard (Khuyến nghị)

```bash
# Clone repository
git clone [repository-url] my-project
cd my-project

# Cài đặt dependencies
composer install
npm install

# Cấu hình environment
cp .env.example .env
php artisan key:generate

# Cấu hình database trong .env
# DB_DATABASE=your_database_name
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

# Build assets
npm run build

# Chạy server và truy cập setup wizard
php artisan serve
# Truy cập: http://localhost:8000/setup
```

### Phương pháp 2: Manual Setup

```bash
# Sau khi cài đặt dependencies và cấu hình .env
php artisan migrate:fresh --seed
php artisan storage:link
php artisan optimize
```

## 📚 Tài liệu

Xem thêm tài liệu chi tiết trong thư mục `/docs`:

### 🚀 Getting Started
- [Hướng dẫn Core Framework](docs/CORE_FRAMEWORK_GUIDE.md)
- [Hướng dẫn Setup](docs/SETUP_GUIDE.md)

### 🔧 Customization
- [Tùy chỉnh Routes](docs/ROUTES_CUSTOMIZATION.md)
- [Cấu hình Environment](docs/ENV_CUSTOMIZATION.md)

### 💻 Development
- [Laravel Actions Usage](docs/laravel-actions-usage.md)
- [Actions Refactor Summary](docs/ACTIONS_REFACTOR_SUMMARY.md)
- [Complete Cleanup Summary](docs/CLEANUP_SUMMARY.md)

## 🏗️ Cấu trúc dự án

```
core-framework/
├── app/                    # Mã nguồn ứng dụng
│   ├── Models/            # Models (Course, Post, Student, etc.)
│   ├── Http/Controllers/  # Controllers
│   ├── Livewire/         # Livewire components
│   └── Filament/         # Admin panel resources
├── docs/                   # Tài liệu dự án
├── resources/views/        # Blade templates
│   ├── setup/            # Setup wizard views
│   └── shop/             # Frontend views
├── database/seeders/       # Database seeders
└── routes/                 # Route definitions
```

## 🎯 Sử dụng cho dự án mới

1. **Clone và cài đặt**: Sử dụng setup wizard để cài đặt nhanh
2. **Tùy chỉnh Routes**: Thay đổi URL slugs theo ngôn ngữ dự án
3. **Cấu hình Environment**: Điều chỉnh .env theo loại dự án
4. **Tùy chỉnh giao diện**: Thay đổi logo, màu sắc, nội dung
5. **Mở rộng**: Thêm models và features mới dễ dàng
6. **Deploy**: Triển khai lên production với hướng dẫn chi tiết

### 📝 Hướng dẫn tùy chỉnh nhanh

#### Thay đổi ngôn ngữ URLs:
```php
// Tiếng Việt (mặc định)
/khoa-hoc, /bai-viet, /hoc-vien

// Tiếng Anh
/courses, /posts, /students

// Tiếng Pháp
/formations, /articles, /etudiants
```

#### Thay đổi tên dự án:
```env
# .env
APP_NAME="My Project Name"
DB_DATABASE=my_project_db
```

## 🤝 Đóng góp

Vui lòng đọc [CONTRIBUTING.md](docs/CONTRIBUTING.md) để biết thêm chi tiết.

## 📄 License

Dự án này được cấp phép theo MIT License.
