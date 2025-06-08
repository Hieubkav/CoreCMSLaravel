# 🚀 Core Framework - Hướng dẫn Setup

## 📋 Tổng quan

Core Framework cung cấp một wizard setup thông minh giúp bạn cài đặt dự án mới một cách nhanh chóng và dễ dàng. Wizard sẽ hướng dẫn bạn qua 5 bước cơ bản để có một website hoàn chỉnh.

## 🛠️ Yêu cầu hệ thống

### Server Requirements
- **PHP**: >= 8.1
- **Database**: MySQL >= 8.0 hoặc PostgreSQL >= 13
- **Web Server**: Apache hoặc Nginx
- **Composer**: Latest version
- **Node.js**: >= 16.x
- **NPM**: >= 8.x

### PHP Extensions
- BCMath, Ctype, Fileinfo
- JSON, Mbstring, OpenSSL
- PDO, Tokenizer, XML
- GD hoặc Imagick (cho xử lý ảnh)

## 🚀 Cài đặt nhanh

### Bước 1: Download và cài đặt dependencies

```bash
# Clone repository
git clone [repository-url] my-project
cd my-project

# Cài đặt PHP dependencies
composer install

# Cài đặt Node.js dependencies
npm install

# Tạo file environment
cp .env.example .env

# Tạo application key
php artisan key:generate

# Build assets
npm run build
```

### Bước 2: Cấu hình Database

Chỉnh sửa file `.env` với thông tin database của bạn:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Bước 3: Chạy Setup Wizard

```bash
# Khởi động server
php artisan serve

# Truy cập setup wizard
# Mở browser và vào: http://localhost:8000/setup
```

## 🎯 Setup Wizard - 5 Bước

### Bước 1: Cấu hình Database
- **Mục đích**: Kiểm tra kết nối database và tạo bảng
- **Thời gian**: 1-2 phút
- **Thao tác**:
  - Nhấn "Kiểm tra kết nối Database"
  - Nếu thành công, nhấn "Tạo bảng Database"
  - Hệ thống sẽ tự động tạo tất cả bảng cần thiết

### Bước 2: Tạo tài khoản Admin
- **Mục đích**: Tạo tài khoản quản trị viên đầu tiên
- **Thời gian**: 1 phút
- **Thông tin cần nhập**:
  - Họ và tên
  - Email (sẽ dùng để đăng nhập)
  - Mật khẩu (tối thiểu 8 ký tự)
  - Xác nhận mật khẩu

### Bước 3: Cấu hình Website
- **Mục đích**: Thiết lập thông tin cơ bản của website
- **Thời gian**: 2-3 phút
- **Thông tin cần nhập**:
  - Tên website (bắt buộc)
  - Mô tả website (cho SEO)
  - Từ khóa SEO
  - Thông tin liên hệ (email, phone, địa chỉ)

### Bước 4: Cấu hình nâng cao
- **Mục đích**: Tùy chỉnh hiệu suất và tính năng nâng cao
- **Thời gian**: 2-3 phút
- **Cấu hình bao gồm**:
  - **Xử lý hình ảnh**: Chất lượng WebP, kích thước tối đa
  - **SEO**: Tự động tạo meta tags, mô tả mặc định
  - **Hiệu suất**: Cache, eager loading, pagination
  - **Email**: SMTP configuration (tùy chọn)

### Bước 5: Dữ liệu mẫu
- **Mục đích**: Import dữ liệu mẫu để bắt đầu nhanh
- **Thời gian**: 2-5 phút
- **Lựa chọn**:
  - **Import dữ liệu mẫu** (khuyến nghị): Tạo sẵn khóa học, bài viết, học viên mẫu
  - **Bắt đầu với dữ liệu trống**: Chỉ có cấu trúc cơ bản

## 📊 Dữ liệu mẫu bao gồm

Khi chọn import dữ liệu mẫu, hệ thống sẽ tạo:

### Khóa học (6 khóa học)
- 3 nhóm chuyên mục: Kỹ năng, Kỹ thuật, Hội thảo
- Mỗi nhóm có 2 khóa học với đầy đủ thông tin
- Tài liệu, hình ảnh, và mô tả chi tiết

### Bài viết (8 bài viết)
- 4 danh mục: Tin tức, Hướng dẫn, Kinh nghiệm, Thông báo
- Mỗi danh mục có 2 bài viết với nội dung mẫu
- Hình ảnh đại diện và SEO tags

### Học viên (20 học viên)
- Thông tin đa dạng về độ tuổi, nghề nghiệp
- Đã đăng ký các khóa học khác nhau
- Dữ liệu tiến độ học tập

### Giảng viên (3 giảng viên)
- Hồ sơ chi tiết với kinh nghiệm, chuyên môn
- Liên kết với các khóa học tương ứng
- Thông tin liên hệ và mạng xã hội

### Cấu hình khác
- Menu navigation hoàn chỉnh
- Slider banner cho trang chủ
- Cài đặt SEO và social media
- Partner logos và testimonials

## 🔧 Cấu hình sau Setup

### Truy cập Admin Panel
```
URL: http://your-domain.com/admin
Email: [email bạn đã tạo]
Password: [password bạn đã tạo]
```

### Tùy chỉnh giao diện
1. **Logo & Favicon**: Admin → Settings → Website
2. **Màu sắc**: Chỉnh sửa file `tailwind.config.js`
3. **Menu**: Admin → Menu Items
4. **Slider**: Admin → Sliders

### Thêm nội dung
1. **Khóa học**: Admin → Courses → Create
2. **Bài viết**: Admin → Posts → Create
3. **Học viên**: Admin → Students → Create
4. **Giảng viên**: Admin → Instructors → Create

## 🚨 Troubleshooting

### Lỗi Database Connection
```bash
# Kiểm tra thông tin database trong .env
# Đảm bảo database đã được tạo
# Kiểm tra user có quyền truy cập
```

### Lỗi Permission
```bash
# Cấp quyền cho thư mục storage và bootstrap/cache
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Lỗi Assets
```bash
# Rebuild assets
npm run build

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Setup lại từ đầu
```bash
# Xóa database và tạo lại
php artisan migrate:fresh

# Hoặc reset setup flag
php artisan tinker
>>> App\Models\Setting::where('key', 'setup_completed')->delete();
```

## 📞 Hỗ trợ

### Tài liệu
- [Core Framework Guide](CORE_FRAMEWORK_GUIDE.md)
- [Development Guide](development.md)
- [API Documentation](api.md)

### Liên hệ
- GitHub Issues: Báo cáo lỗi và yêu cầu tính năng
- Documentation: Hướng dẫn chi tiết trong thư mục `/docs`

---

**Core Framework v1.0** - Setup made simple! 🚀
