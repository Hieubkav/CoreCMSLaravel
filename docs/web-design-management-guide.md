# 🎨 Hướng dẫn Quản lý Giao diện Website - WebDesign

## 📋 Tổng quan

Model WebDesign cho phép quản lý hoàn toàn giao diện trang chủ website VBA Vũ Phúc thông qua Filament Admin Panel. Bạn có thể điều khiển thứ tự hiển thị, ẩn/hiện các section và tùy chỉnh nội dung một cách dễ dàng.

## ✨ Tính năng chính

### 🎯 Điều khiển Section
- **Ẩn/Hiện**: Toggle on/off từng section
- **Sắp xếp thứ tự**: Thay đổi thứ tự hiển thị (1-10)
- **Tùy chỉnh nội dung**: Chỉnh sửa tiêu đề, mô tả
- **Thay đổi màu nền**: Chọn từ palette màu có sẵn
- **Hiệu ứng**: Áp dụng animation cho section

### 📱 Các Section có thể quản lý

1. **🎯 Hero Banner** (Thứ tự: 1)
   - Banner chính trang chủ
   - Chỉ có thể ẩn/hiện và thay đổi thứ tự

2. **📚 Giới thiệu khóa học** (Thứ tự: 2)
   - Tiêu đề: "Khóa học VBA Excel chuyên nghiệp"
   - Mô tả: "Nâng cao kỹ năng Excel..."
   - Màu nền: bg-white
   - Component: @livewire('courses-overview')

3. **📸 Thư viện tài liệu** (Thứ tự: 3)
   - Hiển thị album và tài liệu khóa học
   - Màu nền: bg-gray-25
   - Component: album-timeline

4. **👥 Nhóm học tập** (Thứ tự: 4)
   - Các nhóm Facebook/Zalo
   - Màu nền: bg-white
   - Component: course-groups

5. **📋 Khóa học theo chuyên mục** (Thứ tự: 5)
   - Danh sách khóa học theo category
   - Màu nền: bg-gray-25
   - Component: course-categories-sections

6. **⭐ Đánh giá từ học viên** (Thứ tự: 6)
   - Testimonials của học viên
   - Màu nền: bg-white
   - Component: testimonials

7. **❓ Câu hỏi thường gặp** (Thứ tự: 7)
   - FAQ section
   - Màu nền: bg-gray-25
   - Component: faq-section

8. **🤝 Đối tác tin cậy** (Thứ tự: 8)
   - Danh sách đối tác
   - Màu nền: bg-white
   - Component: partners

9. **📰 Bài viết mới nhất** (Thứ tự: 9)
   - Blog posts
   - Màu nền: bg-gray-25
   - Component: blog-posts

10. **🎯 Call to Action** (Thứ tự: 10)
    - CTA cuối trang
    - Màu nền: gradient đỏ
    - Component: homepage-cta

## 🛠️ Cách sử dụng

### 1. Truy cập trang quản lý
- Đăng nhập Admin Panel: `/admin`
- Vào **Hệ Thống** → **Quản Lý Giao Diện**

### 2. Sử dụng Thao tác nhanh
- **✅ Hiện tất cả**: Bật hiển thị cho tất cả sections
- **❌ Ẩn tất cả**: Tắt hiển thị cho tất cả sections
- **🔢 Tự động sắp xếp**: Sắp xếp thứ tự từ 1-10
- **🔄 Reset mặc định**: Khôi phục về cài đặt ban đầu

### 3. Cấu hình từng Section
- **🔘 Toggle Hiển thị**: Bật/tắt section
- **📍 Thứ tự**: Nhập số từ 1-10 với icon mũi tên
- **👁️ Xem trước**: Preview nội dung section
- **📝 Tiêu đề**: Chỉnh sửa tiêu đề với icon bút chì
- **📄 Mô tả**: Thay đổi mô tả với icon tài liệu
- **🎨 Màu nền**: Chọn từ dropdown với emoji
- **✨ Hiệu ứng**: Chọn animation với icon ngôi sao

### 4. Lưu thay đổi
- **👁️ Xem trước**: Mở trang chủ trong tab mới
- **💾 Lưu cài đặt giao diện**: Áp dụng thay đổi
- Thống kê realtime: Hiển thị số sections đang hoạt động
- Thay đổi áp dụng ngay lập tức, cache tự động clear

## 🎨 Palette màu có sẵn

- **bg-white**: Trắng (#ffffff)
- **bg-gray-25**: Xám nhạt (#fafafa)
- **bg-red-25**: Đỏ nhạt (#fef7f7)
- **bg-red-50**: Đỏ rất nhạt (#fef2f2)

## ⚡ Hiệu ứng Animation

- **animate-fade-in-optimized**: Fade In (mặc định)
- **animate-slide-up**: Slide Up
- **animate-bounce-in**: Bounce In
- **Không có hiệu ứng**: Tắt animation

## 📊 Dashboard Widget

WebDesign có widget riêng hiển thị:
- Số section đang hiển thị
- Trạng thái Hero Banner
- Trạng thái Courses Overview

## 🔧 Technical Details

### Model Structure
```php
// app/Models/WebDesign.php
- hero_banner_enabled, hero_banner_order
- courses_overview_enabled, courses_overview_order, courses_overview_title, etc.
- album_timeline_enabled, album_timeline_order, album_timeline_title, etc.
// ... cho tất cả sections
```

### Cache Management
- Cache key: `web_design_settings`
- TTL: 3600 seconds (1 hour)
- Auto clear khi update

### View Integration
- ViewServiceProvider tự động inject `$webDesign`
- storeFront.blade.php sử dụng dynamic rendering
- Fallback values nếu không có config

## 🚀 Best Practices

1. **Thứ tự hợp lý**: Hero Banner luôn đầu tiên, CTA cuối cùng
2. **Màu nền xen kẽ**: Trắng và xám nhạt để tạo contrast
3. **Không tắt quá nhiều**: Giữ ít nhất 5-6 section
4. **Test sau khi thay đổi**: Kiểm tra trang chủ sau mỗi lần save

## 🐛 Troubleshooting

### Section không hiển thị
1. Kiểm tra toggle "Hiển thị section"
2. Verify dữ liệu section có tồn tại
3. Clear cache: `php artisan cache:clear`

### Thứ tự không đúng
1. Kiểm tra số thứ tự (1-10)
2. Không trùng lặp số thứ tự
3. Refresh trang sau khi save

### Lỗi khi save
1. Kiểm tra validation rules
2. Xem Laravel logs
3. Verify database connection

---

**Phiên bản**: 1.0  
**Cập nhật**: {{ date('d/m/Y') }}  
**Tác giả**: VBA Vũ Phúc Development Team
