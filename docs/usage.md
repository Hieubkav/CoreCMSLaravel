# Hướng dẫn sử dụng

## 🎯 Admin Panel

### Truy cập Admin

Truy cập: `http://your-domain.com/admin`

Đăng nhập bằng tài khoản admin đã tạo trong Setup Wizard.

### Dashboard

- **Tổng quan:** Thống kê tổng quan website
- **Lượt truy cập:** Số visitor hôm nay
- **Nội dung:** Số bài viết, sản phẩm
- **Đơn hàng:** Thống kê bán hàng (nếu có)

## 📝 Quản lý nội dung

### Posts (Bài viết)

**Tạo bài viết mới:**
1. Vào **Posts** → **Create**
2. Nhập tiêu đề, nội dung
3. Chọn danh mục
4. Upload ảnh đại diện
5. Cấu hình SEO
6. **Save**

**Quản lý danh mục:**
1. Vào **Post Categories**
2. **Create** danh mục mới
3. Thiết lập thứ tự hiển thị

### Products (Sản phẩm)

**Thêm sản phẩm:**
1. Vào **Products** → **Create**
2. Nhập tên, mô tả, giá
3. Upload ảnh sản phẩm
4. Chọn danh mục
5. Thiết lập kho hàng
6. **Save**

**Quản lý đơn hàng:**
1. Vào **Orders**
2. Xem chi tiết đơn hàng
3. Cập nhật trạng thái
4. In hóa đơn

### Staff (Nhân viên)

**Thêm nhân viên:**
1. Vào **Staff** → **Create**
2. Nhập thông tin cá nhân
3. Upload ảnh đại diện
4. Thêm mô tả, chức vụ
5. Liên kết mạng xã hội
6. **Save**

## 🎨 Tùy chỉnh giao diện

### Web Design Management

**Thay đổi màu sắc:**
1. Vào **Web Design Management**
2. Tab **Colors & Typography**
3. Chọn màu chủ đạo
4. Chọn font chữ
5. **Save & Preview**

**Tùy chỉnh layout:**
1. Tab **Layout & Components**
2. Bật/tắt các section
3. Sắp xếp thứ tự
4. **Save**

**CSS tùy chỉnh:**
1. Tab **Custom CSS**
2. Thêm CSS riêng
3. **Save & Apply**

### Settings

**Thông tin website:**
1. Vào **Settings**
2. Tab **Website Info**
3. Cập nhật tên, logo, favicon
4. Thông tin liên hệ
5. **Save**

**SEO Settings:**
1. Tab **SEO Configuration**
2. Meta title, description
3. Google Analytics
4. Social media tags
5. **Save**

## 🔍 Tìm kiếm & Analytics

### Search Management

**Xem từ khóa phổ biến:**
1. Vào **Advanced Search**
2. Xem **Popular Searches**
3. Phân tích xu hướng

**Cải thiện kết quả:**
1. Xem **Failed Searches**
2. Thêm nội dung liên quan
3. Tối ưu từ khóa

### Analytics

**Xem báo cáo:**
1. Vào **Analytics Reporting**
2. Dashboard tổng quan
3. Báo cáo chi tiết theo thời gian
4. Phân tích hành vi người dùng

## 🤖 Automation

### Workflow Management

**Tạo workflow tự động:**
1. Vào **Automation Workflow**
2. **Create** workflow mới
3. Chọn trigger (kích hoạt)
4. Thiết lập actions (hành động)
5. **Save & Activate**

**Ví dụ workflows:**
- Gửi email chào mừng user mới
- Backup database hàng ngày
- Gửi báo cáo hàng tuần
- Dọn dẹp cache định kỳ

## 🌐 Đa ngôn ngữ

### Multi-Language

**Thêm ngôn ngữ mới:**
1. Vào **Multi Language**
2. **Create** language mới
3. Nhập các bản dịch
4. **Activate**

**Quản lý bản dịch:**
1. Chọn nhóm bản dịch
2. Cập nhật từng ngôn ngữ
3. **Save**

## 👥 Phân quyền

### User Roles

**Tạo vai trò mới:**
1. Vào **Roles**
2. **Create** role mới
3. Chọn permissions
4. **Save**

**Gán quyền cho user:**
1. Vào **Users**
2. Edit user
3. Chọn roles
4. **Save**

## 📱 Frontend

### Menu

**Tùy chỉnh menu:**
1. Vào **Menu Items**
2. Drag & drop để sắp xếp
3. Thêm/xóa menu items
4. **Save**

### Homepage

**Tùy chỉnh trang chủ:**
1. Vào **Web Design Management**
2. Tab **Homepage Sections**
3. Bật/tắt sections
4. Sắp xếp thứ tự
5. **Save**

## 🔧 Bảo trì

### Cache

**Clear cache:**
```bash
php artisan optimize:clear
```

### Backup

**Backup database:**
```bash
php artisan backup:run
```

### Updates

**Cập nhật framework:**
```bash
composer update
npm update
php artisan migrate
```

## 📞 Hỗ trợ

- **Documentation:** [README.md](../README.md)
- **Issues:** GitHub Issues
- **Email:** support@example.com
