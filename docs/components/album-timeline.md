# 📚 Album Timeline Component (Redesigned)

## 📋 Tổng quan

Component `album-timeline` đã được tái cấu trúc hoàn toàn thành giao diện grid cards hiện đại, thay thế layout timeline cũ. Component hiển thị các album khóa học với file PDF và ảnh phụ trong thiết kế card đẹp mắt và responsive.

## 🎯 Tính năng

### 🎨 Giao diện Grid Cards
- **Grid layout** 3 cột responsive (1 cột mobile, 2 cột tablet, 3 cột desktop)
- **Card design** hiện đại với shadow và hover effects
- **Animation** fadeInUp mượt mà với staggered delay
- **Fallback UI** đẹp mắt cho ảnh và dữ liệu trống

### 📄 PDF Viewer
- **Modal PDF viewer** với iframe
- **Download tracking** tự động
- **View count** tự động tăng khi xem
- **Responsive modal** với close button

### 🖼️ Image Gallery
- **Lightbox gallery** với navigation
- **Keyboard support** (Arrow keys, Escape)
- **Loading states** và error handling
- **Image counter** hiển thị vị trí ảnh
- **Caption support** cho từng ảnh

### 📊 Statistics
- **View count** - Số lượt xem PDF
- **Download count** - Số lượt tải PDF
- **Real-time updates** qua API

## 🏗️ Cấu trúc Database

### Model Album
```php
// Bảng albums
'title' => 'string',                    // Tiêu đề album
'description' => 'text|nullable',       // Mô tả album
'seo_title' => 'string|nullable',       // Tiêu đề SEO
'seo_description' => 'text|nullable',   // Mô tả SEO
'og_image_link' => 'string|nullable',   // Link ảnh OG
'slug' => 'string|unique',              // Slug SEO-friendly
'pdf_file' => 'string|nullable',        // Đường dẫn file PDF
'thumbnail' => 'string|nullable',       // Ảnh thumbnail
'published_date' => 'date|nullable',    // Ngày xuất bản
'status' => 'enum:active,inactive',     // Trạng thái
'order' => 'integer|default:0',         // Thứ tự hiển thị
'featured' => 'boolean|default:false',  // Nổi bật
'total_pages' => 'integer|nullable',    // Tổng số trang PDF
'file_size' => 'bigInteger|nullable',   // Kích thước file (bytes)
'download_count' => 'integer|default:0', // Số lượt tải
'view_count' => 'integer|default:0',    // Số lượt xem
```

### Model AlbumImage
```php
// Bảng album_images
'album_id' => 'foreignId',              // ID album
'image_path' => 'string',               // Đường dẫn ảnh
'alt_text' => 'string|nullable',        // Alt text
'caption' => 'text|nullable',           // Chú thích ảnh
'order' => 'integer|default:0',         // Thứ tự hiển thị
'status' => 'enum:active,inactive',     // Trạng thái
'is_featured' => 'boolean|default:false', // Ảnh nổi bật
'file_size' => 'bigInteger|nullable',   // Kích thước file
'width' => 'integer|nullable',          // Chiều rộng
'height' => 'integer|nullable',         // Chiều cao
```

## 🔧 API Endpoints

### Album Statistics
```php
POST /api/albums/{album}/view          // Tăng view count
POST /api/albums/{album}/download      // Tăng download count
GET  /api/albums/{album}/images        // Lấy danh sách ảnh
```

## 🎨 Thiết kế UI/UX

### Màu sắc
- **Primary**: Red (#dc2626) - Màu chủ đạo
- **Secondary**: Gray (#6b7280) - Màu phụ
- **Success**: Green (#059669) - Thành công
- **Warning**: Yellow (#d97706) - Cảnh báo

### Typography
- **Heading**: Font-weight bold, responsive sizes
- **Body**: Font-weight normal, line-height 1.6
- **Caption**: Font-size small, opacity 0.75

### Animation
- **fadeInUp**: Hiệu ứng fade in từ dưới lên
- **slideInLeft/Right**: Hiệu ứng slide cho timeline items
- **pulse**: Hiệu ứng nhấp nháy cho timeline dots
- **hover effects**: Scale, shadow, color transitions

## 📱 Responsive Design

### Breakpoints
- **Mobile**: < 768px - Single column, simplified layout
- **Tablet**: 768px - 1024px - Two columns, medium spacing
- **Desktop**: > 1024px - Full timeline layout

### Mobile Optimizations
- Timeline dots ẩn trên mobile
- Single column layout
- Touch-friendly buttons
- Optimized modal sizes

## 🚀 Performance

### Caching
- **ViewServiceProvider**: Cache 2 giờ cho albums data
- **Image lazy loading**: Chỉ load ảnh khi cần
- **API optimization**: Chỉ load ảnh khi mở gallery

### SEO
- **Structured data**: Schema markup cho albums
- **Meta tags**: Auto-generated từ album data
- **Alt texts**: Tự động từ title nếu không có
- **Semantic HTML**: Proper heading hierarchy

## 🔧 Cài đặt và Sử dụng

### 1. Migration
```bash
php artisan migrate
```

### 2. Seeder (Optional)
```bash
php artisan db:seed --class=AlbumSeeder
```

### 3. Storage Setup
```bash
php artisan storage:link
```

### 4. Sử dụng trong View
```blade
@include('components.storefront.album-timeline')
```

## 🎯 Tối ưu hóa

### Performance Tips
1. **Optimize images**: Sử dụng WebP format
2. **Lazy loading**: Implement cho ảnh và PDF
3. **CDN**: Sử dụng CDN cho static assets
4. **Caching**: Cache API responses

### SEO Tips
1. **Alt texts**: Luôn điền alt text cho ảnh
2. **Meta descriptions**: Tối ưu mô tả SEO
3. **Schema markup**: Thêm structured data
4. **Page speed**: Optimize loading times

## 🐛 Troubleshooting

### Common Issues
1. **PDF không hiển thị**: Kiểm tra file path và permissions
2. **Ảnh không load**: Kiểm tra storage link
3. **API errors**: Kiểm tra CSRF token
4. **Animation lag**: Reduce animation complexity

### Debug Mode
```php
// Enable debug trong ViewServiceProvider
'albums' => Cache::remember('storefront_albums', 1, function () {
    // Debug query here
});
```
