# 🎯 Course Groups Component - Nhóm Khóa học Facebook/Zalo

## 📋 Tổng quan

Component `course-groups` hiển thị các nhóm khóa học nổi bật với link tham gia Facebook/Zalo, giúp học viên kết nối với cộng đồng học tập và nhận hỗ trợ từ giảng viên.

## 🎨 Thiết kế

### Giao diện
- **Layout**: Grid responsive 1-2-3 cột (mobile-tablet-desktop)
- **Style**: Minimalist với gradient background và hover effects
- **Colors**: Blue và Purple gradient theme
- **Animation**: Smooth transitions và hover transforms

### Thành phần chính
1. **Section Header**: Tiêu đề và mô tả section
2. **Course Cards**: Grid hiển thị các khóa học
3. **Call to Action**: Nút xem tất cả khóa học

## 🏗️ Cấu trúc dữ liệu

### Model Course - Các trường liên quan
```php
'group_link' => 'string|nullable',        // Link nhóm Facebook/Zalo
'show_group_link' => 'boolean|default:true',  // Hiển thị link nhóm
'gg_form' => 'string|nullable',           // Link Google Form đăng ký
'show_form_link' => 'boolean|default:true',   // Hiển thị link form
```

### ViewServiceProvider
Component nhận dữ liệu từ `ViewServiceProvider` với key `$courseGroups`:

```php
'courseGroups' => Cache::remember('storefront_course_groups', 3600, function () {
    return Course::where('status', 'active')
        ->where('show_group_link', true)
        ->whereNotNull('group_link')
        ->with(['category:id,name,slug', 'instructor:id,name'])
        ->select([
            'id', 'title', 'slug', 'description', 'thumbnail', 'level',
            'group_link', 'category_id', 'instructor_id', 'start_date',
            'duration_hours', 'max_students'
        ])
        ->orderBy('order', 'asc')
        ->orderBy('created_at', 'desc')
        ->take(6)
        ->get();
}),
```

## 🎯 Tính năng

### Hiển thị thông tin khóa học
- **Hình ảnh**: Thumbnail với fallback gradient
- **Level Badge**: Màu sắc theo cấp độ (Cơ bản/Trung cấp/Nâng cao)
- **Category Badge**: Hiển thị danh mục khóa học
- **Thông tin chi tiết**: Thời lượng, số học viên tối đa
- **Giảng viên**: Avatar và tên giảng viên

### Nút hành động
- **Tham gia nhóm**: Link đến nhóm Facebook/Zalo (target="_blank")
- **Chi tiết khóa học**: Link đến trang chi tiết khóa học

### Responsive Design
- **Mobile**: 1 cột, stack buttons
- **Tablet**: 2 cột
- **Desktop**: 3 cột

## 🚀 Cách sử dụng

### 1. Tích hợp vào trang
```blade
<!-- Nhóm khóa học Facebook/Zalo -->
<section class="animate-on-scroll py-12 md:py-16 bg-gradient-to-br from-blue-50 via-white to-purple-50">
    @include('components.storefront.course-groups')
</section>
```

### 2. Kiểm tra dữ liệu
Component tự động ẩn nếu không có dữ liệu:
```blade
@if(isset($courseGroups) && $courseGroups->isNotEmpty())
    <!-- Component content -->
@endif
```

### 3. Test component
Truy cập `/test-course-groups` để kiểm tra component hoạt động.

## 🛠️ Cấu hình

### Thêm dữ liệu mẫu
Chạy seeder để tạo dữ liệu test:
```bash
php artisan db:seed --class=CourseGroupSeeder
```

### Cache Management
Component sử dụng cache 1 giờ. Clear cache khi cần:
```php
// Clear specific cache
Cache::forget('storefront_course_groups');

// Clear all storefront cache
ViewServiceProvider::refreshCache('courses');
```

## 🎨 Customization

### Thay đổi số lượng hiển thị
Trong ViewServiceProvider, thay đổi `take(6)` thành số mong muốn.

### Thay đổi màu sắc level badges
```blade
@if($course->level === 'beginner') bg-green-100 text-green-800
@elseif($course->level === 'intermediate') bg-yellow-100 text-yellow-800
@else bg-red-100 text-red-800
@endif
```

### Thay đổi gradient background
```blade
<section class="bg-gradient-to-br from-blue-50 via-white to-purple-50">
```

## 📱 SEO & Performance

### Tối ưu hóa
- **Lazy loading**: Hình ảnh sử dụng `loading="lazy"`
- **Alt text**: Tự động từ title khóa học
- **Cache**: Dữ liệu cache 1 giờ
- **Responsive images**: Tự động scale theo container

### Link attributes
- **External links**: `target="_blank" rel="noopener noreferrer"`
- **Internal links**: SEO-friendly routes

## 🔧 Troubleshooting

### Component không hiển thị
1. Kiểm tra có dữ liệu trong database:
   ```sql
   SELECT * FROM courses WHERE status = 'active' AND show_group_link = 1 AND group_link IS NOT NULL;
   ```

2. Clear cache:
   ```bash
   php artisan cache:clear
   ```

3. Kiểm tra ViewServiceProvider đã load đúng dữ liệu.

### Styling issues
1. Đảm bảo Tailwind CSS được compile đúng
2. Kiểm tra CSS conflicts với các components khác
3. Test responsive trên nhiều thiết bị

## 📊 Analytics

### Tracking clicks
Có thể thêm Google Analytics tracking cho các nút:
```blade
onclick="gtag('event', 'join_group', {'course_id': '{{ $course->id }}'})"
```

### Performance monitoring
Monitor cache hit rate và loading time của component.
