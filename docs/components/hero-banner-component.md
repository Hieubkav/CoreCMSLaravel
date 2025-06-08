# Hero Banner Slider Component

## Tổng quan

Hero Banner Slider là component chính hiển thị ở đầu trang chủ, sử dụng dữ liệu từ model Sliders thông qua ViewServiceProvider để tạo ra một slider đẹp mắt và responsive.

## Tính năng chính

### 1. **Tích hợp dữ liệu thực từ database**
- Sử dụng dữ liệu từ model `Sliders` thông qua `ViewServiceProvider`
- Dữ liệu được preload và cached để tối ưu performance
- Tự động ẩn component nếu không có dữ liệu slider

### 2. **Responsive design**
- **Mobile**: Chiều cao 350px (sm: 450px)
- **Desktop**: Chiều cao 550px (lg: 700px)
- Layout tự động điều chỉnh theo số lượng slides
- Ảnh không bị cắt trên cả mobile và desktop

### 3. **Xử lý ảnh tối ưu**
- Ảnh được chuyển thành WebP format tự động
- Tên file SEO-friendly dựa theo tên slider
- Lazy loading cho ảnh (trừ slide đầu tiên)
- Fallback placeholder nếu ảnh lỗi

### 4. **Tương tác người dùng**
- Auto-play với interval 8 giây (chậm hơn để người dùng có thời gian đọc nội dung)
- Pause khi hover (chỉ trên desktop)
- Navigation controls (prev/next buttons)
- Indicators với progress bar animation 8 giây
- Nút liên kết tinh tế ở góc phải trên (khi có link)
- Keyboard accessibility

### 5. **SEO và Accessibility**
- Alt text tự động tạo từ tiêu đề nếu không điền thủ công
- Aria labels cho các controls
- Semantic HTML structure
- Schema markup ready

## Cấu trúc dữ liệu

### Model Sliders
```php
// Các trường trong bảng sliders
- id: Primary key
- image_link: Đường dẫn ảnh (WebP format)
- title: Tiêu đề slider
- description: Mô tả chi tiết
- link: Liên kết đích
- alt_text: Alt text cho SEO
- order: Thứ tự hiển thị
- status: Trạng thái (active/inactive)
```

### Kích thước ảnh khuyến nghị
- **Desktop**: 1920x1080px (16:9)
- **Mobile**: 800x450px (16:9)
- **Format**: WebP (tự động chuyển đổi)
- **Quality**: 85% (cân bằng chất lượng và dung lượng)

## Cách sử dụng

### 1. Trong Blade template
```blade
{{-- Sử dụng component --}}
@include('components.storefront.hero-banner')
```

### 2. Quản lý dữ liệu trong Filament Admin
- Truy cập: Admin Panel > Sliders
- Upload ảnh: Tự động resize và chuyển WebP
- Sắp xếp: Drag & drop theo thứ tự
- Trạng thái: Toggle hiển thị/ẩn

### 3. ViewServiceProvider integration
Dữ liệu được tự động inject vào view thông qua:
```php
// app/Providers/ViewServiceProvider.php
'sliders' => Slider::where('status', 'active')
    ->orderBy('order')
    ->get()
```

## Tùy chỉnh

### 1. Thay đổi timing
```javascript
// Trong component, thay đổi interval (mặc định 8000ms)
this.interval = setInterval(() => this.nextSlide(), 10000); // 10 giây
```

### 2. Tùy chỉnh CSS
```css
/* Thay đổi chiều cao */
.hero-slide {
    min-height: 600px; /* Desktop */
}

@media (max-width: 640px) {
    .hero-slide {
        min-height: 400px; /* Mobile */
    }
}
```

### 3. Thêm hiệu ứng
```css
/* Thêm hiệu ứng Ken Burns */
.hero-slide img {
    animation: kenburns 7s ease-in-out infinite alternate;
}
```

## Performance

### 1. **Caching**
- Dữ liệu slider được cache 30 phút
- Tự động clear cache khi có thay đổi
- Sử dụng `ClearsViewCache` trait

### 2. **Image optimization**
- WebP format giảm 25-35% dung lượng
- Lazy loading cho ảnh không hiển thị
- Responsive images với srcset (có thể mở rộng)

### 3. **JavaScript optimization**
- Sử dụng Alpine.js (lightweight)
- Event delegation
- Memory leak prevention

## Troubleshooting

### 1. Slider không hiển thị
- Kiểm tra có dữ liệu trong bảng `sliders`
- Đảm bảo `status = 'active'`
- Verify ViewServiceProvider đã được register

### 2. Ảnh không load
- Kiểm tra đường dẫn trong `storage/app/public`
- Chạy `php artisan storage:link`
- Verify file permissions

### 3. Animation không mượt
- Kiểm tra CSS `will-change` properties
- Đảm bảo `transform-style: preserve-3d`
- Optimize image sizes

## Tính năng mới

### 1. **Alt text tự động**
- Nếu không điền Alt text, hệ thống tự động tạo từ tiêu đề
- Format: "{Tiêu đề} - Vũ Phúc Baking"
- Giúp tối ưu SEO và accessibility

### 2. **Nút liên kết tinh tế**
- Hiển thị ở góc phải trên khi có link
- Thiết kế không đè lên nội dung chính
- Icon external link với hover effect
- Responsive cho cả mobile và desktop

### 3. **Thời gian chuyển slider**
- Tăng từ 6 giây lên 8 giây
- Cho người dùng thời gian đọc nội dung
- Progress bar animation đồng bộ

## Best Practices

### 1. **Content**
- Tiêu đề ngắn gọn, súc tích (< 60 ký tự)
- Mô tả rõ ràng, hấp dẫn (< 150 ký tự)
- Call-to-action rõ ràng
- Alt text có thể để trống để tự động tạo

### 2. **Images**
- Sử dụng ảnh chất lượng cao
- Đảm bảo contrast tốt với text
- Tránh ảnh có quá nhiều chi tiết nhỏ

### 3. **Performance**
- Giới hạn 3-5 slides
- Optimize image sizes
- Monitor Core Web Vitals

## Tích hợp với các component khác

Hero Banner có thể kết hợp với:
- **Categories showcase**: Hiển thị ngay sau hero
- **Featured products**: Cross-promotion
- **Blog posts**: Content marketing
- **Partners**: Trust building
