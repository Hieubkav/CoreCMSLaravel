# 📚 Course Categories Sections Component

## 📋 Tổng quan

Component `course-categories-sections` hiển thị khóa học theo từng danh mục riêng biệt, mỗi danh mục là một section độc lập với layout grid responsive giống như hình mẫu. Đây là component tổng hợp sử dụng `course-category-section` để hiển thị tất cả danh mục có khóa học.

## 🎯 Tính năng

### Layout Sections
- **Mỗi danh mục**: Một section riêng biệt
- **Background xen kẽ**: Trắng và xám nhạt
- **Header danh mục**: Icon, tên, mô tả, nút "Xem tất cả"
- **Grid khóa học**: 2-6 cột responsive

### Responsive Design
- **Mobile**: 2 cột
- **Tablet**: 3 cột  
- **Desktop**: 4 cột
- **Large Desktop**: 6 cột

### Thông tin khóa học
- **Hình ảnh**: Thumbnail hoặc placeholder gradient
- **Giá**: Badge hiển thị giá hoặc "Free"
- **Level**: Badge cấp độ
- **Thời lượng**: Số giờ học
- **Giảng viên**: Tên instructor
- **Featured**: Badge "HOT" cho khóa học nổi bật

## 🏗️ Cấu trúc Component

### Component chính
```blade
<!-- course-categories-sections.blade.php -->
@if(isset($courseCategoriesGrid) && $courseCategoriesGrid->isNotEmpty())
    @foreach($courseCategoriesGrid as $category)
        @if($hasActiveCourses)
            <section class="py-12 md:py-16 {{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
                @include('components.storefront.course-category-section', [
                    'category' => $category,
                    'limit' => 8
                ])
            </section>
        @endif
    @endforeach
@endif
```

### Component con
```blade
<!-- course-category-section.blade.php -->
@props(['category', 'limit' => 8])

@php
    $courses = $category->courses()
        ->where('status', 'active')
        ->with(['courseCategory'])
        ->orderBy('is_featured', 'desc')
        ->orderBy('order')
        ->take($limit)
        ->get();
@endphp
```

## 🎨 Thiết kế

### Header danh mục
- **Icon/Image**: Circular với gradient background
- **Tên danh mục**: Typography lớn, bold
- **Mô tả**: Text mô tả ngắn gọn
- **Nút "Xem tất cả"**: Link đến trang danh mục

### Card khóa học
- **Aspect ratio**: Square (1:1) cho consistency
- **Hover effects**: Scale image, đổi màu title
- **Price badge**: Top-right corner
- **Featured badge**: Top-left corner "HOT"
- **Meta info**: Duration, level, instructor

### Color scheme
- **Background**: Xen kẽ white và gray-50
- **Price badges**: Red (có phí), Green (miễn phí)
- **Featured badge**: Yellow "HOT"
- **Level badges**: Gray background

## 📁 File liên quan

### Components
- `resources/views/components/storefront/course-categories-sections.blade.php`
- `resources/views/components/storefront/course-category-section.blade.php`

### Usage
- `resources/views/shop/storeFront.blade.php`
- `resources/views/test/course-categories-sections.blade.php`

### Data Source
- `app/Providers/ViewServiceProvider.php` (courseCategoriesGrid)

## 🚀 Sử dụng

### Trong trang chủ
```blade
<!-- Khóa học theo từng danh mục -->
<div class="animate-on-scroll">
    @include('components.storefront.course-categories-sections')
</div>
```

### Tùy chỉnh limit
```blade
@include('components.storefront.course-category-section', [
    'category' => $category,
    'limit' => 12  // Hiển thị tối đa 12 khóa học
])
```

## ⚙️ Cấu hình

### Dữ liệu từ ViewServiceProvider
```php
'courseCategoriesGrid' => Cache::remember('storefront_course_categories_grid', 7200, function () {
    return CatCourse::where('status', 'active')
        ->withCount(['courses' => function($query) {
            $query->where('status', 'active');
        }])
        ->orderBy('order')
        ->orderBy('name')
        ->get();
});
```

### Query khóa học
```php
$courses = $category->courses()
    ->where('status', 'active')
    ->with(['courseCategory'])
    ->orderBy('is_featured', 'desc')  // Nổi bật trước
    ->orderBy('order')                // Theo thứ tự
    ->orderBy('created_at', 'desc')   // Mới nhất
    ->take($limit)
    ->get();
```

## 🎯 Logic hiển thị

### Kiểm tra danh mục có khóa học
```php
$hasActiveCourses = $category->courses()
    ->where('status', 'active')
    ->exists();
```

### Background xen kẽ
```blade
{{ $loop->even ? 'bg-gray-50' : 'bg-white' }}
```

### Placeholder image
```blade
@if($course->thumbnail)
    <img src="{{ asset('storage/' . $course->thumbnail) }}" />
@else
    <div class="bg-gradient-to-br from-red-500 to-red-600">
        <!-- SVG icon -->
    </div>
@endif
```

## 📱 Responsive CSS

```css
/* Mobile */
@media (max-width: 640px) {
    .grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

/* Tablet */
@media (min-width: 641px) and (max-width: 768px) {
    .md\:grid-cols-3 {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}

/* Desktop */
@media (min-width: 769px) and (max-width: 1024px) {
    .lg\:grid-cols-4 {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }
}

/* Large Desktop */
@media (min-width: 1025px) {
    .xl\:grid-cols-6 {
        grid-template-columns: repeat(6, minmax(0, 1fr));
    }
}
```

## 🔧 Customization

### Thay đổi số cột
```blade
<!-- Thay đổi classes grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
```

### Thay đổi limit mặc định
```php
@props(['category', 'limit' => 12])  // Tăng từ 8 lên 12
```

### Custom styling
```css
/* Thêm vào component */
<style>
    .custom-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }
</style>
```

## 📝 Lưu ý

1. **Performance**: Component sử dụng eager loading và limit để tối ưu
2. **Cache**: Dữ liệu được cache 2 giờ trong ViewServiceProvider
3. **Fallback**: Có placeholder gradient nếu không có hình ảnh
4. **SEO**: Tất cả links đều có proper routing
5. **Accessibility**: Alt text và semantic HTML
6. **Responsive**: Layout tự động điều chỉnh theo màn hình

## 🐛 Troubleshooting

### Component không hiển thị
1. Kiểm tra có dữ liệu trong `$courseCategoriesGrid`
2. Kiểm tra các danh mục có khóa học active không
3. Clear cache: `php artisan cache:clear`

### Hình ảnh không hiển thị
1. Kiểm tra storage link: `php artisan storage:link`
2. Kiểm tra placeholder image tại `public/images/placeholder-course.jpg`
3. Fallback gradient sẽ hiển thị nếu không có ảnh

### Layout bị vỡ
1. Kiểm tra Tailwind CSS classes
2. Kiểm tra responsive breakpoints
3. Test trên các kích thước màn hình khác nhau

### Performance chậm
1. Kiểm tra N+1 queries
2. Sử dụng eager loading: `with(['courseCategory'])`
3. Giảm limit nếu cần thiết
