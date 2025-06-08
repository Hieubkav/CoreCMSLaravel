# 📚 Course Categories Component

## 📋 Tổng quan

Component `course-categories` hiển thị giao diện danh mục khóa học theo layout grid responsive giống như hình mẫu. Mỗi danh mục hiển thị hình ảnh, tên, số lượng khóa học và có thể click để xem các khóa học thuộc danh mục đó.

## 🎯 Tính năng

### Layout Grid Responsive
- **Mobile**: 2 cột
- **Tablet**: 3 cột  
- **Desktop**: 4 cột
- **Large Desktop**: 6 cột

### Thông tin danh mục
- **Hình ảnh**: Thumbnail hoặc placeholder với gradient màu
- **Tên danh mục**: Với hover effect
- **Mô tả**: Tự động cắt bớt nếu quá dài
- **Số lượng khóa học**: Badge hiển thị số khóa học
- **Icon**: SVG icon theo loại danh mục
- **Màu sắc**: Gradient background theo màu đại diện

### Tương tác
- **Hover effects**: Scale image và đổi màu text
- **Click**: Dẫn đến trang danh mục khóa học
- **Responsive**: Tự động điều chỉnh layout

## 🏗️ Cấu trúc dữ liệu

### Model CatCourse
```php
// Bảng cat_courses
'name' => 'string',              // Tên danh mục
'slug' => 'string|unique',       // Slug SEO-friendly
'seo_title' => 'string|nullable', // Tiêu đề SEO
'seo_description' => 'text|nullable', // Mô tả SEO
'og_image_link' => 'string|nullable', // Hình ảnh OG
'image' => 'string|nullable',    // Hình ảnh danh mục
'description' => 'text|nullable', // Mô tả danh mục
'parent_id' => 'foreignId|nullable', // Danh mục cha
'order' => 'integer|default:0',  // Thứ tự hiển thị
'status' => 'enum:active,inactive|default:active' // Trạng thái
```

### Relationships
```php
// CatCourse Model
public function courses(): HasMany
{
    return $this->hasMany(Course::class, 'cat_course_id');
}

// Course Model  
public function courseCategory(): BelongsTo
{
    return $this->belongsTo(CatCourse::class, 'cat_course_id');
}
```

### ViewServiceProvider
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

## 📁 File liên quan

### Component View
- `resources/views/components/storefront/course-categories.blade.php`

### Model & Migration
- `app/Models/CatCourse.php`
- `database/migrations/2025_06_03_215649_create_cat_courses_table.php`
- `database/migrations/2025_06_03_215802_add_cat_course_id_to_courses_table.php`

### Seeder
- `database/seeders/CatCourseSeeder.php`

### Controller & Routes
- `app/Http/Controllers/CourseController.php` (method `catCategory`)
- `routes/web.php` (route `courses.cat-category`)

### Views
- `resources/views/courses/cat-category.blade.php`

## 🎨 Thiết kế

### Màu sắc mặc định
- **Excel & VBA**: #059669 (Green)
- **Kế toán**: #dc2626 (Red)
- **Quản lý**: #7c3aed (Purple)
- **Tin học văn phòng**: #2563eb (Blue)
- **Phân tích dữ liệu**: #ea580c (Orange)
- **Kỹ năng mềm**: #db2777 (Pink)
- **Lập trình**: #059669 (Emerald)
- **Marketing**: #dc2626 (Red)

### Icons hỗ trợ
- `excel`: File icon
- `calculator`: Calculator icon
- `users`: Users icon
- `computer`: Computer icon
- `chart`: Chart icon
- `heart`: Heart icon
- `code`: Code icon
- `megaphone`: Megaphone icon

### Responsive Breakpoints
```css
/* Mobile */
@media (max-width: 640px) {
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

/* Tablet */
@media (min-width: 641px) and (max-width: 768px) {
    grid-template-columns: repeat(3, minmax(0, 1fr));
}

/* Desktop */
@media (min-width: 769px) and (max-width: 1024px) {
    grid-template-columns: repeat(4, minmax(0, 1fr));
}

/* Large Desktop */
@media (min-width: 1025px) {
    grid-template-columns: repeat(6, minmax(0, 1fr));
}
```

## 🚀 Sử dụng

### Trong trang chủ
```blade
<!-- Danh mục khóa học -->
<section class="animate-on-scroll py-12 md:py-16 bg-white">
    @include('components.storefront.course-categories')
</section>
```

### Kiểm tra dữ liệu
```blade
@if($courseCategoriesGrid->isNotEmpty())
    <!-- Component content -->
@endif
```

## 🔧 Cấu hình

### Tạo dữ liệu mẫu
```bash
php artisan db:seed --class=CatCourseSeeder
```

### Clear cache
```bash
php artisan cache:clear
```

### Test component
Truy cập: `/test-course-categories`

## 📝 Lưu ý

1. **Cache**: Component sử dụng cache 2 giờ
2. **Fallback**: Có placeholder gradient nếu không có hình ảnh
3. **SEO**: Slug và meta tags được tối ưu
4. **Performance**: Eager loading và withCount để tránh N+1
5. **Responsive**: Layout tự động điều chỉnh theo màn hình
6. **Accessibility**: Alt text và semantic HTML

## 🐛 Troubleshooting

### Component không hiển thị
1. Kiểm tra có dữ liệu trong bảng `cat_courses`
2. Clear cache: `php artisan cache:clear`
3. Kiểm tra ViewServiceProvider

### Hình ảnh không hiển thị
1. Kiểm tra storage link: `php artisan storage:link`
2. Kiểm tra đường dẫn image trong database
3. Placeholder gradient sẽ hiển thị nếu không có ảnh

### Route không hoạt động
1. Kiểm tra route `courses.cat-category` đã được định nghĩa
2. Kiểm tra method `catCategory` trong CourseController
3. Kiểm tra slug của danh mục

### Màu sắc không hiển thị
1. Kiểm tra trường `color` trong database
2. Sử dụng `display_color` accessor để có màu mặc định
3. Kiểm tra CSS inline style
