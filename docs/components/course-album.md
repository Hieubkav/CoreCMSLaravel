# 🎓 Course Album Component

## 📋 Tổng quan

Component `course-album` hiển thị giao diện album khóa học theo 3 chuyên mục chính: **Kỹ năng**, **Kỹ thuật**, và **Hội thảo**. Mỗi chuyên mục hiển thị 1 khóa học mới nhất với đầy đủ thông tin và các nút hành động.

## 🎯 Tính năng

### Hiển thị theo chuyên mục
- **3 cột responsive**: Kỹ năng, Kỹ thuật, Hội thảo
- **1 khóa học mới nhất** cho mỗi chuyên mục
- **Tự động ẩn** nếu không có dữ liệu

### Thông tin khóa học
- **Hình ảnh**: Thumbnail với hover effect
- **Tên khóa học**: Với link đến trang chi tiết
- **Mô tả ngắn**: Tự động cắt bớt nếu quá dài
- **Ngày khai giảng**: Format dd/mm/yyyy
- **Thời lượng**: Hiển thị số giờ học
- **Cấp độ**: Badge với màu sắc phân biệt
- **Giá**: Hiển thị giá hoặc "Miễn phí"

### Nút hành động
- **Xem chi tiết**: Link đến trang chi tiết khóa học
- **Đăng ký ngay**: Link đến Google Form (nếu có)
- **Tham gia nhóm học**: Link đến nhóm Zalo/Facebook (nếu có)
- **Xem tất cả khóa học**: Link đến danh sách khóa học theo chuyên mục

## 🏗️ Cấu trúc dữ liệu

### Model Course
```php
// Các trường mới được thêm
'gg_form' => 'string|nullable',           // Link Google Form đăng ký
'group_link' => 'string|nullable',        // Link nhóm Zalo/Facebook
'show_form_link' => 'boolean|default:true',   // Hiển thị link form
'show_group_link' => 'boolean|default:true',  // Hiển thị link nhóm
```

### ViewServiceProvider
Component nhận dữ liệu từ `ViewServiceProvider` với key `$courseCategories`:

```php
'courseCategories' => CatPost::where('status', 'active')
    ->whereHas('courses', function($query) {
        $query->where('status', 'active');
    })
    ->with(['courses' => function($query) {
        $query->where('status', 'active')
            ->select([...])
            ->orderBy('created_at', 'desc')
            ->take(1);
    }])
    ->whereIn('slug', ['ky-nang', 'ky-thuat', 'hoi-thao'])
    ->get()
    ->map(function($category) {
        $category->latest_course = $category->courses->first();
        unset($category->courses);
        return $category;
    });
```

## 📁 File liên quan

### Component View
- `resources/views/components/storefront/course-album.blade.php`

### Seeder
- `database/seeders/CourseCategorySeeder.php`

### Migration
- `database/migrations/2025_06_03_214150_add_form_and_group_fields_to_courses_table.php`

### Model
- `app/Models/Course.php` (đã cập nhật fillable và casts)

## 🎨 Thiết kế

### Responsive Design
- **Mobile**: 1 cột
- **Tablet**: 2 cột
- **Desktop**: 3 cột

### Màu sắc
- **Header chuyên mục**: Gradient đỏ (from-red-600 to-red-700)
- **Nút chính**: Đỏ (#dc2626)
- **Nút phụ**: Xám (#6b7280)
- **Nút nhóm**: Xanh dương (#2563eb)

### Hiệu ứng
- **Hover**: Scale image 105%
- **Transition**: 300ms ease
- **Shadow**: Hover shadow-xl

## 🚀 Sử dụng

### Trong trang chủ
```blade
<!-- Album khóa học theo chuyên mục -->
<section class="animate-on-scroll py-12 md:py-16 bg-gray-50">
    @include('components.storefront.course-album')
</section>
```

### Kiểm tra dữ liệu
```blade
@if($courseCategories->isNotEmpty())
    <!-- Component content -->
@endif
```

## 🔧 Cấu hình

### Tạo danh mục
Chạy seeder để tạo 3 danh mục chính:
```bash
php artisan db:seed --class=CourseCategorySeeder
```

### Clear cache
```bash
php artisan cache:clear
```

### Test component
Truy cập: `/test-course-album`

## 📝 Lưu ý

1. **Dữ liệu cache**: Component sử dụng cache 2 giờ
2. **Fallback image**: Có placeholder nếu không có thumbnail
3. **SEO friendly**: Alt text tự động từ title
4. **Performance**: Eager loading để tránh N+1 query
5. **Responsive**: Tự động điều chỉnh layout theo màn hình

## 🐛 Troubleshooting

### Component không hiển thị
1. Kiểm tra có dữ liệu trong database
2. Clear cache: `php artisan cache:clear`
3. Kiểm tra ViewServiceProvider

### Hình ảnh không hiển thị
1. Kiểm tra storage link: `php artisan storage:link`
2. Kiểm tra đường dẫn thumbnail trong database
3. Placeholder sẽ hiển thị nếu ảnh lỗi

### Link không hoạt động
1. Kiểm tra routes đã được định nghĩa
2. Kiểm tra slug của category và course
3. Kiểm tra URL trong gg_form và group_link
