# 🚀 Core Laravel Framework - Setup Wizard

## 🎯 Giới thiệu

Core Laravel Framework là một framework mạnh mẽ và linh hoạt được xây dựng trên Laravel, cung cấp setup wizard hoàn chỉnh với 9 modules để tạo ra các loại website khác nhau một cách nhanh chóng.

## ✨ Tính năng chính

### 🎨 **9 Modules đầy đủ**
1. **System Configuration** - Cấu hình theme, màu sắc, fonts
2. **User Roles & Permissions** - Phân quyền người dùng với Spatie
3. **Blog/Posts** - Hệ thống quản lý bài viết, tin tức
4. **Staff Management** - Quản lý nhân sự, team members
5. **Content Sections** - Gallery, FAQ, Testimonials, Services, etc.
6. **Layout Components** - Menu động, search, notifications
7. **E-commerce** - Hệ thống bán hàng hoàn chỉnh
8. **Settings Expansion** - Cài đặt website mở rộng
9. **Web Design Management** - Quản lý giao diện trang chủ

### 🛠 **Setup Wizard thông minh**
- ✅ 6 bước setup tự động
- ✅ Kiểm tra môi trường (local/production)
- ✅ Tự động disable trong production
- ✅ Dữ liệu mẫu tiếng Việt
- ✅ Module selection linh hoạt

## 🚀 Cài đặt nhanh

### **Bước 1: Clone & Setup**
```bash
git clone [repository-url] my-project
cd my-project
composer install
cp .env.example .env
php artisan key:generate
```

### **Bước 2: Database**
```bash
# Cấu hình database trong .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=corelaravel
DB_USERNAME=root
DB_PASSWORD=
```

### **Bước 3: Chạy Setup Wizard**
```bash
php artisan serve
```
Truy cập: **http://127.0.0.1:8000/setup**

## 📋 Hướng dẫn Setup Wizard

### **Bước 1: Database Configuration**
- Test kết nối database
- Tự động chạy migrations
- Tạo tất cả tables cần thiết

### **Bước 2: Admin Account**
- Tạo tài khoản admin đầu tiên
- Thiết lập quyền hạn

### **Bước 3: Website Settings**
- Cấu hình thông tin website cơ bản
- Logo, favicon, thông tin liên hệ

### **Bước 4: Advanced Configuration**
- Cài đặt cache, performance
- Tối ưu hóa hệ thống

### **Bước 5: Sample Data**
- Chọn có import dữ liệu mẫu không
- Dữ liệu hoàn toàn tiếng Việt

### **Bước 6: Module Selection**
- Chọn modules cần cài đặt
- Modules bắt buộc vs tùy chọn
- Cài đặt tự động

## 🎯 Các loại Website có thể tạo

### 🏢 **Corporate Website**
```
Modules: System Config + Blog + Staff + Content Sections + Web Design
Thời gian: ~5 phút setup
```

### 🛒 **E-commerce Platform**
```
Modules: All modules (đặc biệt E-commerce)
Thời gian: ~10 phút setup
```

### 📚 **Educational Platform**
```
Modules: System Config + Blog + Staff + Content Sections + User Roles
Thời gian: ~7 phút setup
```

### 📰 **News/Blog Website**
```
Modules: System Config + Blog + Content Sections + Web Design
Thời gian: ~5 phút setup
```

## 🔧 Quản trị sau khi cài đặt

### **Admin Panel**
```
URL: http://127.0.0.1:8000/admin
Login: [admin account từ setup]
```

### **Web Design Management**
```
URL: http://127.0.0.1:8000/admin/manage-web-design
Features: Drag-drop sections, customize content, colors
```

### **Module Management**
```php
// Kiểm tra modules đã cài
App\Models\SetupModule::getInstalledModules()

// Cài thêm module
App\Actions\ProcessModuleSelection::run([
    'selected_modules' => ['ecommerce'],
    'install_sample_data' => true
])
```

## 📚 Documentation

- **Setup Guide:** `docs/SETUP_WIZARD_FINAL_STATUS.md`
- **Web Design:** `docs/WEB_DESIGN_MANAGEMENT_COMPLETION.md`
- **Development:** `docs/development.md`
- **Environment:** `docs/SETUP_WIZARD_ENVIRONMENT.md`

## 🎯 Production Deployment

### **Environment Setup**
```bash
# Set production environment
APP_ENV=production
APP_DEBUG=false

# Setup wizard sẽ tự động disable
```

### **Optimization**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

## 🤝 Support

- **Documentation:** Xem thư mục `docs/`
- **Issues:** Tạo issue trong repository
- **Features:** Đóng góp qua pull requests

## 📄 License

MIT License - Xem file `LICENSE` để biết thêm chi tiết.

---

**🎉 Chúc bạn xây dựng website thành công với Core Laravel Framework!**

*Framework được phát triển với ❤️ bởi Augment Agent*
