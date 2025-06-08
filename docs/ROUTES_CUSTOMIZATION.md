# 🛣️ Core Framework - Routes Customization Guide

## 📋 Tổng quan

File `routes/web.php` trong Core Framework được thiết kế để dễ dàng tùy chỉnh cho các dự án khác nhau. Đây là hướng dẫn chi tiết để tùy chỉnh routes theo nhu cầu dự án.

## 🎯 Cấu trúc Routes hiện tại

### 1. Setup System Routes
```php
Route::prefix('setup')->name('setup.')->group(function () {
    Route::get('/', [SetupController::class, 'index'])->name('index');
    Route::get('/step/{step}', [SetupController::class, 'step'])->name('step');
    Route::post('/process/{step}', [SetupController::class, 'process'])->name('process');
    Route::post('/complete', [SetupController::class, 'complete'])->name('complete');
});
```
**Mục đích**: Setup wizard cho dự án mới
**Tùy chỉnh**: Có thể thay đổi prefix từ `setup` sang `install`, `configure`, etc.

### 2. Homepage Routes
```php
Route::controller(MainController::class)->group(function () {
    Route::get('/', 'storeFront')->name('storeFront');
});
```
**Mục đích**: Trang chủ
**Tùy chỉnh**: Có thể thay đổi method name từ `storeFront` sang `index`, `home`, etc.

### 3. Courses Routes
```php
Route::controller(CourseController::class)->group(function () {
    Route::get('/khoa-hoc', 'index')->name('courses.index');
    Route::get('/khoa-hoc/danh-muc/{slug}', 'category')->name('courses.category');
    Route::get('/khoa-hoc/{slug}', 'show')->name('courses.show');
    Route::get('/api/courses/search', 'searchSuggestions')->name('courses.search');
});
```
**Mục đích**: Quản lý khóa học
**Tùy chỉnh**: Thay đổi URL slug theo ngôn ngữ

### 4. Students/Users Routes
```php
Route::controller(StudentController::class)->group(function () {
    Route::get('/dang-ky-hoc-vien', 'register')->name('students.register');
    Route::post('/dang-ky-hoc-vien', 'store')->name('students.store');
    Route::get('/hoc-vien/profile', 'profile')->name('students.profile');
});
```
**Mục đích**: Quản lý học viên/người dùng
**Tùy chỉnh**: Có thể thay đổi thành users, members, customers

## 🔧 Hướng dẫn tùy chỉnh

### 1. Thay đổi URL Slugs theo ngôn ngữ

#### Tiếng Anh:
```php
// Courses
Route::get('/courses', 'index')->name('courses.index');
Route::get('/courses/category/{slug}', 'category')->name('courses.category');
Route::get('/courses/{slug}', 'show')->name('courses.show');

// Posts
Route::get('/posts', 'index')->name('posts.index');
Route::get('/posts/category/{slug}', 'category')->name('posts.category');
Route::get('/posts/{slug}', 'show')->name('posts.show');

// Users
Route::get('/register', 'register')->name('users.register');
Route::get('/profile', 'profile')->name('users.profile');
```

#### Tiếng Pháp:
```php
// Formations
Route::get('/formations', 'index')->name('courses.index');
Route::get('/formations/categorie/{slug}', 'category')->name('courses.category');
Route::get('/formations/{slug}', 'show')->name('courses.show');

// Articles
Route::get('/articles', 'index')->name('posts.index');
Route::get('/articles/categorie/{slug}', 'category')->name('posts.category');
Route::get('/articles/{slug}', 'show')->name('posts.show');
```

#### Tiếng Đức:
```php
// Kurse
Route::get('/kurse', 'index')->name('courses.index');
Route::get('/kurse/kategorie/{slug}', 'category')->name('courses.category');
Route::get('/kurse/{slug}', 'show')->name('courses.show');
```

### 2. Thay đổi Controller Names

#### E-commerce Project:
```php
// Products thay vì Courses
Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'index')->name('products.index');
    Route::get('/products/category/{slug}', 'category')->name('products.category');
    Route::get('/products/{slug}', 'show')->name('products.show');
});

// Customers thay vì Students
Route::controller(CustomerController::class)->group(function () {
    Route::get('/register', 'register')->name('customers.register');
    Route::get('/account', 'profile')->name('customers.profile');
});
```

#### Blog Project:
```php
// Articles thay vì Courses
Route::controller(ArticleController::class)->group(function () {
    Route::get('/articles', 'index')->name('articles.index');
    Route::get('/articles/category/{slug}', 'category')->name('articles.category');
    Route::get('/articles/{slug}', 'show')->name('articles.show');
});

// Subscribers thay vì Students
Route::controller(SubscriberController::class)->group(function () {
    Route::get('/subscribe', 'register')->name('subscribers.register');
    Route::get('/my-account', 'profile')->name('subscribers.profile');
});
```

### 3. Thêm Authentication Routes

```php
/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
```

### 4. Thêm API Routes

```php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/courses/search', [CourseController::class, 'searchSuggestions'])->name('courses.search');
    Route::get('/posts/search', [PostController::class, 'searchSuggestions'])->name('posts.search');
    Route::get('/stats', [StatsController::class, 'index'])->name('stats');
});
```

### 5. Thêm Admin Routes Protection

```php
/*
|--------------------------------------------------------------------------
| Admin Routes (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('courses', AdminCourseController::class);
    Route::resource('posts', AdminPostController::class);
});
```

## 📝 Template Routes cho các loại dự án

### E-commerce Template:
```php
// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/category/{slug}', [ProductController::class, 'category'])->name('products.category');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Cart & Checkout
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
```

### Blog Template:
```php
// Articles
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/category/{slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Authors
Route::get('/authors', [AuthorController::class, 'index'])->name('authors.index');
Route::get('/authors/{slug}', [AuthorController::class, 'show'])->name('authors.show');
```

### Portfolio Template:
```php
// Projects
Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
Route::get('/portfolio/category/{slug}', [PortfolioController::class, 'category'])->name('portfolio.category');
Route::get('/portfolio/{slug}', [PortfolioController::class, 'show'])->name('portfolio.show');

// Services
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('services.show');
```

## 🚀 Best Practices

### 1. Naming Conventions
- **Route names**: Sử dụng dot notation (`resource.action`)
- **URL slugs**: Sử dụng kebab-case
- **Controller methods**: Sử dụng camelCase

### 2. Route Organization
- Group related routes together
- Sử dụng route model binding cho {slug} parameters
- Thêm middleware phù hợp cho từng group

### 3. SEO-Friendly URLs
```php
// Good
Route::get('/courses/web-development', [CourseController::class, 'show']);

// Bad
Route::get('/course?id=123', [CourseController::class, 'show']);
```

### 4. API Versioning
```php
Route::prefix('api/v1')->name('api.v1.')->group(function () {
    // API routes
});
```

---

**Core Framework** - Flexible routing for any project! 🛣️
