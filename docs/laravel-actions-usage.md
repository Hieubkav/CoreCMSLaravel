# 🚀 Core Framework - Laravel Actions Guide

## 📋 Tổng quan

Core Framework đã được refactor hoàn toàn để sử dụng Laravel Actions theo nguyên tắc KISS (Keep It Simple, Stupid). Tất cả Services và Traits phức tạp đã được thay thế bằng các Actions đơn giản, dễ hiểu và dễ maintain.

## 🎯 Nguyên tắc KISS được áp dụng

- **Mỗi Action chỉ làm 1 việc** - Single Responsibility
- **Không có dependency injection phức tạp** - Sử dụng static methods
- **Code đơn giản, dễ đọc** - Không có abstraction không cần thiết
- **Dễ test** - Mỗi action có thể test độc lập
- **Tái sử dụng cao** - Có thể dùng ở nhiều nơi khác nhau

## 📁 Danh sách Actions đã tạo

### 🖼️ Image Processing Actions
- `ConvertImageToWebp` - Chuyển ảnh sang WebP với chất lượng tối ưu
- `CreateSeoImageName` - Tạo tên file ảnh SEO-friendly
- `GetImageDirectory` - Lấy đường dẫn thư mục cho từng loại ảnh
- `CreateFilamentImageUpload` - Tạo FileUpload component với WebP conversion

### 🗂️ File Management Actions
- `DeleteFileFromStorage` - Xóa file từ storage
- `ClearViewCache` - Clear cache dựa trên model type

### ⚙️ Setup Actions
- `SetupDatabase` - Setup database cho dự án mới
- `CreateAdminUser` - Tạo tài khoản admin đầu tiên
- `SaveWebsiteSettings` - Lưu cài đặt website cơ bản
- `SaveAdvancedConfiguration` - Lưu cấu hình nâng cao
- `ImportSampleData` - Import dữ liệu mẫu

### 📊 Statistics Actions
- `GetVisitorStats` - Lấy thống kê visitor đơn giản

### 🛠️ Development Actions
- `CreateModelObserver` - Tạo Observer template sử dụng Actions

## 🔧 Cách sử dụng Actions

### 1. Image Processing

#### ConvertImageToWebp
```php
use App\Actions\ConvertImageToWebp;

// Chuyển ảnh sang WebP với kích thước tùy chỉnh
$result = ConvertImageToWebp::run(
    $file,                    // UploadedFile
    'courses/thumbnails',     // directory
    'custom-name',           // tên tùy chỉnh (optional)
    1200,                    // width (optional)
    630                      // height (optional)
);
```

#### CreateSeoImageName
```php
use App\Actions\CreateSeoImageName;

// Tạo tên file SEO-friendly
$fileName = CreateSeoImageName::forCourse('Khóa học Laravel');
// Result: course-khoa-hoc-laravel-20240120123456.webp

$fileName = CreateSeoImageName::forPost('Bài viết hay');
// Result: post-bai-viet-hay-20240120123456.webp
```

#### GetImageDirectory
```php
use App\Actions\GetImageDirectory;

// Lấy thư mục cho từng loại ảnh
$directory = GetImageDirectory::forCourse();     // courses/thumbnails
$directory = GetImageDirectory::forPost();       // posts/thumbnails
$directory = GetImageDirectory::forAvatar();     // testimonials/avatars
```

### 2. File Management

#### DeleteFileFromStorage
```php
use App\Actions\DeleteFileFromStorage;

// Xóa file đơn lẻ
$success = DeleteFileFromStorage::run('path/to/file.jpg');

// Xóa file từ model
$success = DeleteFileFromStorage::fromModel($course, 'thumbnail_link');

// Xóa file cũ khi update
$success = DeleteFileFromStorage::oldFile($course, 'thumbnail_link');

// Xóa nhiều file
$results = DeleteFileFromStorage::multiple([
    'file1.jpg', 'file2.jpg', 'file3.jpg'
]);
```

#### ClearViewCache
```php
use App\Actions\ClearViewCache;

// Clear cache cho model cụ thể
ClearViewCache::forModel($course);

// Clear cache theo class name
ClearViewCache::run('App\Models\Course');

// Clear tất cả cache
ClearViewCache::all();
```

### 3. Filament Integration

#### CreateFilamentImageUpload
```php
use App\Actions\CreateFilamentImageUpload;

// Trong Filament Resource Form
Forms\Components\Section::make('Ảnh đại diện')
    ->schema([
        // Thay vì viết FileUpload phức tạp
        CreateFilamentImageUpload::forCourse(),

        // Hoặc tùy chỉnh
        CreateFilamentImageUpload::custom([
            'field' => 'banner_image',
            'label' => 'Ảnh banner',
            'directory' => 'banners',
            'maxWidth' => 1920,
            'maxHeight' => 600,
            'aspectRatios' => ['16:9'],
            'required' => true
        ])
    ]);
```

**Trước (Code phức tạp):**
```php
Forms\Components\FileUpload::make('thumbnail_link')
    ->label('Ảnh đại diện')
    ->image()
    ->directory('courses/thumbnails')
    ->imageEditor()
    ->imageEditorAspectRatios(['16:9'])
    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
    ->maxSize(5120)
    ->saveUploadedFileUsing(function ($file, $get) {
        $title = $get('title') ?? 'course';
        $seoFileName = CreateSeoImageName::forCourse($title);

        return ConvertImageToWebp::run(
            $file,
            'courses/thumbnails',
            $seoFileName,
            1200,
            630
        );
    })
    ->helperText('Ảnh sẽ được tự động chuyển sang WebP...')
```

**Sau (Đơn giản với Action):**
```php
CreateFilamentImageUpload::forCourse()
```

## Ưu điểm của Laravel Actions

1. **Đơn giản hơn**: Không cần inject service
2. **Linh hoạt hơn**: Có thể dùng như static method hoặc instance
3. **Testable**: Dễ test hơn
4. **Reusable**: Có thể dùng ở nhiều nơi khác nhau
5. **Organized**: Tách biệt logic business khỏi controller/resource

## So sánh với Service

| Aspect | Service | Action |
|--------|---------|--------|
| Cách gọi | `app(Service::class)->method()` | `Action::run()` |
| Dependency Injection | Cần inject | Không cần |
| Static usage | Không | Có |
| Testing | Phức tạp hơn | Đơn giản hơn |
| Organization | Service layer | Action layer |

## Kết luận

Laravel Actions cung cấp cách tiếp cận hiện đại và đơn giản hơn cho việc tổ chức business logic. Action `ConvertImageToWebp` có thể thay thế hoàn toàn `SimpleWebpService` với cú pháp gọn gàng và dễ sử dụng hơn.
