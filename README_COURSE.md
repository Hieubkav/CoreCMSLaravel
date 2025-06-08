# 🎓 VBA Vũ Phúc - Hệ thống Khóa học

Website khóa học Excel VBA chuyên nghiệp với hệ thống quản lý học viên và tài liệu hiện đại.

## ✨ Tính năng chính

### 🎯 Hệ thống Khóa học
- **Quản lý khóa học đa cấp độ**: Beginner, Intermediate, Advanced
- **Tài liệu phong phú**: PDF, Video, Code samples, Templates
- **Theo dõi tiến độ học tập**: Progress tracking, completion certificates
- **Đăng ký học viên**: Form đăng ký thông minh với validation

### 👥 Quản lý Học viên
- **Profile học viên chi tiết**: Thông tin cá nhân, mục tiêu học tập
- **Enrollment system**: Đăng ký khóa học với status tracking
- **Dashboard học viên**: Theo dõi khóa học đã đăng ký

### 🎨 Giao diện Modern
- **Responsive design**: Tối ưu cho mọi thiết bị
- **Livewire components**: Real-time search, filtering, sorting
- **Tailwind CSS**: Design system nhất quán và đẹp mắt
- **SEO optimized**: Meta tags, OG images, structured data

### ⚡ Performance
- **Smart caching**: ViewServiceProvider với cache strategy
- **Database optimization**: Eager loading, proper indexing
- **Image optimization**: WebP format, lazy loading

## 🛠️ Công nghệ sử dụng

- **Backend**: Laravel 10, PHP 8.1+
- **Frontend**: Livewire 3, Tailwind CSS 3
- **Admin Panel**: Filament 3
- **Database**: MySQL 8.0+
- **Cache**: Redis (optional)
- **Assets**: Vite, Node.js 16+

## 🚀 Cài đặt nhanh

### 1. Clone và cài đặt dependencies
```bash
git clone [repository-url]
cd vba_vuphuc
composer install
npm install
```

### 2. Cấu hình môi trường
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Cấu hình database trong `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vba_vuphuc
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Setup database với dữ liệu mẫu
```bash
php artisan migrate
php artisan course:reset --fresh --seed
```

### 5. Build assets và khởi động
```bash
npm run build
php artisan serve
```

## 🎯 Commands hữu ích

### Reset dữ liệu khóa học
```bash
# Reset và seed lại dữ liệu
php artisan course:reset --seed

# Fresh migrate và seed (xóa tất cả)
php artisan course:reset --fresh --seed

# Chỉ reset dữ liệu khóa học
php artisan course:reset
```

### Cache management
```bash
# Clear all cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 📊 Dữ liệu mẫu

Sau khi chạy seeders, bạn sẽ có:
- **5 danh mục khóa học**: Excel VBA, Kế toán, Quản lý, Tin học văn phòng, Phân tích dữ liệu
- **5 khóa học chi tiết**: Với giá, mô tả, requirements, learning outcomes
- **50 học viên mẫu**: Với thông tin đầy đủ và enrollment data
- **25+ tài liệu khóa học**: PDF, Video, Templates, Code samples
- **15+ hình ảnh khóa học**: Main images và gallery
- **Menu navigation**: Cấu trúc menu chuyên nghiệp

## 🌐 Truy cập

- **Website**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin
- **Khóa học**: http://localhost:8000/khoa-hoc
- **Đăng ký học viên**: http://localhost:8000/dang-ky-hoc-vien

## 📚 Documentation

- [Hướng dẫn hệ thống khóa học](docs/COURSE_SYSTEM_GUIDE.md)
- [API Documentation](docs/API.md)
- [Deployment Guide](docs/DEPLOYMENT.md)

## 🔧 Development

### Cấu trúc thư mục quan trọng
```
app/
├── Models/
│   ├── Course.php              # Model khóa học
│   ├── Student.php             # Model học viên
│   ├── CourseMaterial.php      # Model tài liệu
│   └── CourseImage.php         # Model hình ảnh
├── Http/Controllers/
│   ├── CourseController.php    # Controller khóa học
│   └── StudentController.php   # Controller học viên
├── Livewire/
│   ├── CourseList.php          # Component danh sách khóa học
│   ├── CourseCard.php          # Component card khóa học
│   └── EnrollmentForm.php      # Component đăng ký
└── Providers/
    └── ViewServiceProvider.php # Cache và data sharing

resources/views/
├── courses/                    # Views khóa học
├── students/                   # Views học viên
└── livewire/                   # Livewire component views

database/
├── migrations/                 # Database migrations
└── seeders/                    # Data seeders
```

### Testing
```bash
# Chạy tests
php artisan test

# Test với coverage
php artisan test --coverage
```

## 🤝 Contributing

1. Fork repository
2. Tạo feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Tạo Pull Request

## 📄 License

MIT License - xem file [LICENSE](LICENSE) để biết thêm chi tiết.

## 👨‍💻 Author

**VBA Vũ Phúc Team**
- Website: [vbavuphuc.com](https://vbavuphuc.com)
- Email: contact@vbavuphuc.com

---

⭐ **Star repo này nếu bạn thấy hữu ích!**
