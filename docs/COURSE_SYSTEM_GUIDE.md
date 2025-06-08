# 🎓 VBA Vũ Phúc - Hệ thống Khóa học

## 📋 Tổng quan

Hệ thống quản lý khóa học được thiết kế để chuyển đổi website bán hàng "Vũ Phúc" thành website khóa học "VBA Vũ Phúc" với các tính năng hiện đại và user-friendly.

## 🏗️ Kiến trúc hệ thống

### Models chính

#### 1. Course (Khóa học)
```php
- id, title, slug, description
- price, compare_price, duration_hours
- level (beginner/intermediate/advanced)
- instructor_name, instructor_bio
- requirements (JSON), what_you_learn (JSON)
- max_students, start_date, end_date
- is_featured, status, order
- SEO fields: seo_title, seo_description, og_image_link
```

#### 2. Student (Học viên)
```php
- id, name, email, phone
- birth_date, gender, address
- occupation, education_level
- learning_goals, interests (JSON)
- avatar, status
```

#### 3. CourseMaterial (Tài liệu khóa học)
```php
- id, course_id, title, description
- file_path, file_name, file_type, file_size
- is_preview, order, status
```

#### 4. CourseImage (Hình ảnh khóa học)
```php
- id, course_id, image_link, alt_text
- is_main, order, status
```

#### 5. Pivot Table: course_student
```php
- course_id, student_id
- enrolled_at, completed_at
- status, progress_percentage
- total_study_hours, final_score
```

### Controllers thông minh

#### CourseController
- `index()`: Danh sách khóa học với filter/search/sort
- `show()`: Chi tiết khóa học với related courses
- `category()`: Khóa học theo danh mục
- `searchSuggestions()`: API cho search suggestions

#### StudentController
- `register()`: Form đăng ký học viên
- `store()`: Xử lý đăng ký với validation
- `enrollCourse()`: API đăng ký khóa học
- `profile()`: Thông tin học viên

### Livewire Components

#### CourseList
- Real-time search và filtering
- Advanced filters: category, level, price range
- Sorting options
- Pagination với query string persistence

#### CourseCard
- Hiển thị thông tin khóa học compact
- Support multiple card sizes
- Auto-detect main image
- Level badges với màu sắc

#### EnrollmentForm
- Form đăng ký khóa học inline
- Validation real-time
- Check enrollment status
- Success/error handling

## 🎨 Frontend Views

### Course Views
- `courses/index.blade.php`: Trang danh sách khóa học
- `courses/show.blade.php`: Trang chi tiết khóa học
- `courses/category.blade.php`: Trang danh mục khóa học

### Student Views
- `students/register.blade.php`: Form đăng ký học viên
- `students/success.blade.php`: Trang đăng ký thành công

### Livewire Views
- `livewire/course-list.blade.php`: Component danh sách khóa học

## 🚀 Routes

### Course Routes
```php
Route::get('/khoa-hoc', [CourseController::class, 'index'])->name('courses.index');
Route::get('/khoa-hoc/danh-muc/{slug}', [CourseController::class, 'category'])->name('courses.category');
Route::get('/khoa-hoc/{slug}', [CourseController::class, 'show'])->name('courses.show');
Route::get('/api/courses/search', [CourseController::class, 'searchSuggestions']);
```

### Student Routes
```php
Route::get('/dang-ky-hoc-vien', [StudentController::class, 'register'])->name('students.register');
Route::post('/dang-ky-hoc-vien', [StudentController::class, 'store'])->name('students.store');
Route::get('/dang-ky-thanh-cong', [StudentController::class, 'success'])->name('students.success');
Route::post('/api/enroll-course', [StudentController::class, 'enrollCourse']);
```

## 🗄️ Database Seeders

### CourseSeeder
- Tạo 5 danh mục khóa học
- Tạo 5 khóa học mẫu chi tiết
- Dữ liệu thực tế và chuyên nghiệp

### StudentSeeder
- Tạo 50 học viên mẫu
- Sử dụng Faker với locale Việt Nam
- Tạo enrollment relationships

### CourseMaterialSeeder
- Tài liệu cho từng loại khóa học
- File paths và metadata
- Preview permissions

### CourseImageSeeder
- Hình ảnh cho từng khóa học
- Main image và gallery
- SEO-friendly alt texts

### MenuItemSeeder
- Cấu trúc menu chuyên nghiệp
- Sub-menu động theo categories
- Support display_only type

## ⚡ Performance Optimization

### ViewServiceProvider
- Cache riêng biệt cho từng loại dữ liệu
- Cache time khác nhau theo tần suất thay đổi
- Selective cache refresh

### Database
- Proper indexing cho performance
- Eager loading relationships
- Query optimization

### Frontend
- Livewire với debounce
- Lazy loading cho images
- Responsive design

## 🔧 Commands

### Reset Course Data
```bash
# Reset và seed lại dữ liệu
php artisan course:reset --seed

# Fresh migrate và seed
php artisan course:reset --fresh --seed

# Chỉ reset dữ liệu (không seed)
php artisan course:reset
```

## 📱 Responsive Design

- Mobile-first approach
- Tailwind CSS với utility classes
- Flexible grid systems
- Touch-friendly interfaces

## 🔍 SEO Features

- Dynamic meta titles và descriptions
- OG image support
- Structured breadcrumbs
- Schema-ready markup
- SEO-friendly URLs

## 🎯 User Experience

- Real-time search và filtering
- Loading states và animations
- Error handling và validation
- Accessibility considerations
- Progressive enhancement

## 🛡️ Security

- CSRF protection
- Input validation và sanitization
- File upload security
- SQL injection prevention
- XSS protection

## 📊 Analytics Ready

- Event tracking hooks
- Conversion tracking
- User behavior analytics
- Performance monitoring

## 🔄 Cache Strategy

### Cache Keys
- `storefront_featured_courses` (30 phút)
- `storefront_latest_courses` (30 phút)
- `storefront_course_categories` (2 giờ)
- `storefront_course_stats` (1 giờ)
- `navigation_data` (2 giờ)

### Cache Management
```php
// Clear all cache
ViewServiceProvider::clearCache();

// Refresh specific cache
ViewServiceProvider::refreshCache('courses');
```

## 🚀 Deployment

### Requirements
- PHP 8.1+
- Laravel 10+
- MySQL 8.0+
- Node.js 16+
- Composer 2+

### Setup Steps
1. Clone repository
2. `composer install`
3. `npm install && npm run build`
4. Copy `.env.example` to `.env`
5. `php artisan key:generate`
6. Configure database
7. `php artisan migrate`
8. `php artisan course:reset --seed`

## 📈 Future Enhancements

- Payment integration
- Certificate generation
- Video streaming
- Discussion forums
- Mobile app
- Advanced analytics
- Multi-language support
- API for third-party integrations
