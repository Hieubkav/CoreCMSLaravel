# 🎓 CatCourse Resource - Quản lý Danh mục Khóa học

## 📋 Tổng quan

CatCourse Resource là giao diện quản lý danh mục khóa học trong Filament Admin Panel. Cho phép tạo, chỉnh sửa, xóa và quản lý các danh mục khóa học với đầy đủ tính năng.

## 🎯 Tính năng chính

### Form quản lý
- **Thông tin cơ bản**: Tên, slug, mô tả
- **Hiển thị & Thiết kế**: Hình ảnh, màu sắc, icon
- **Cấu trúc**: Danh mục cha, thứ tự, trạng thái
- **SEO**: Tiêu đề, mô tả, hình ảnh OG

### Table hiển thị
- **Hình ảnh**: Circular thumbnail
- **Thông tin**: Tên, slug, màu sắc, icon
- **Thống kê**: Số lượng khóa học
- **Quản lý**: Trạng thái, thứ tự, danh mục cha

### Tính năng nâng cao
- **Reorderable**: Kéo thả để sắp xếp thứ tự
- **Filters**: Lọc theo trạng thái, danh mục cha
- **Search**: Tìm kiếm theo tên, slug
- **Bulk actions**: Xóa nhiều record cùng lúc

## 🏗️ Cấu trúc Form

### Section 1: Thông tin cơ bản
```php
Forms\Components\TextInput::make('name')
    ->label('Tên danh mục')
    ->required()
    ->live(onBlur: true)
    ->afterStateUpdated() // Auto generate slug

Forms\Components\TextInput::make('slug')
    ->label('Slug')
    ->required()
    ->unique(ignoreRecord: true)

Forms\Components\Textarea::make('description')
    ->label('Mô tả')
    ->rows(3)
```

### Section 2: Hiển thị & Thiết kế
```php
Forms\Components\FileUpload::make('image')
    ->label('Hình ảnh danh mục')
    ->image()
    ->directory('cat-courses')
    ->imageEditor()

Forms\Components\ColorPicker::make('color')
    ->label('Màu sắc đại diện')
    ->default('#dc2626')

Forms\Components\Select::make('icon')
    ->label('Icon')
    ->options([
        'excel' => 'Excel',
        'calculator' => 'Calculator',
        // ... more icons
    ])
```

### Section 3: Cấu trúc & Trạng thái
```php
Forms\Components\Select::make('parent_id')
    ->label('Danh mục cha')
    ->relationship('parent', 'name')

Forms\Components\TextInput::make('order')
    ->label('Thứ tự hiển thị')
    ->numeric()
    ->default(0)

Forms\Components\Select::make('status')
    ->label('Trạng thái')
    ->options([
        'active' => 'Hiển thị',
        'inactive' => 'Ẩn',
    ])
```

### Section 4: SEO (Collapsible)
```php
Forms\Components\TextInput::make('seo_title')
    ->label('Tiêu đề SEO')
    ->maxLength(255)

Forms\Components\Textarea::make('seo_description')
    ->label('Mô tả SEO')
    ->maxLength(160)

Forms\Components\FileUpload::make('og_image_link')
    ->label('Hình ảnh OG')
    ->image()
```

## 📊 Cấu trúc Table

### Columns chính
```php
Tables\Columns\ImageColumn::make('image')
    ->label('Hình ảnh')
    ->circular()
    ->size(50)

Tables\Columns\TextColumn::make('name')
    ->label('Tên danh mục')
    ->searchable()
    ->sortable()
    ->weight('bold')

Tables\Columns\ColorColumn::make('color')
    ->label('Màu sắc')

Tables\Columns\TextColumn::make('courses_count')
    ->label('Số khóa học')
    ->counts('courses')
    ->badge()
    ->color('success')

Tables\Columns\SelectColumn::make('status')
    ->label('Trạng thái')
    ->options([
        'active' => 'Hiển thị',
        'inactive' => 'Ẩn',
    ])
```

### Filters
```php
Tables\Filters\SelectFilter::make('status')
    ->label('Trạng thái')
    ->options([
        'active' => 'Hiển thị',
        'inactive' => 'Ẩn',
    ])

Tables\Filters\SelectFilter::make('parent_id')
    ->label('Danh mục cha')
    ->relationship('parent', 'name')
```

### Actions
```php
Tables\Actions\ViewAction::make()->label('Xem')
Tables\Actions\EditAction::make()->label('Sửa')
Tables\Actions\DeleteAction::make()->label('Xóa')
```

## 🎨 Icons hỗ trợ

| Icon | Mô tả | Sử dụng cho |
|------|-------|-------------|
| `excel` | File icon | Excel & VBA |
| `calculator` | Calculator icon | Kế toán |
| `users` | Users icon | Quản lý |
| `computer` | Computer icon | Tin học văn phòng |
| `chart` | Chart icon | Phân tích dữ liệu |
| `heart` | Heart icon | Kỹ năng mềm |
| `code` | Code icon | Lập trình |
| `megaphone` | Megaphone icon | Marketing |

## 🔧 Navigation

### Vị trí trong menu
- **Group**: "Quản lý khóa học"
- **Sort**: 1 (đầu tiên)
- **Icon**: `heroicon-o-academic-cap`
- **Badge**: Số lượng danh mục active

### Pages
- **List**: `/admin/cat-courses`
- **Create**: `/admin/cat-courses/create`
- **View**: `/admin/cat-courses/{id}`
- **Edit**: `/admin/cat-courses/{id}/edit`

## 🚀 Sử dụng

### Tạo danh mục mới
1. Vào "Danh mục khóa học" → "Tạo danh mục mới"
2. Điền thông tin cơ bản (tên sẽ tự động tạo slug)
3. Upload hình ảnh và chọn màu sắc
4. Chọn icon phù hợp
5. Cấu hình thứ tự và trạng thái
6. Tùy chọn: Điền thông tin SEO

### Quản lý thứ tự
- Kéo thả các dòng để sắp xếp thứ tự
- Hoặc chỉnh sửa trường "order" trong form

### Lọc và tìm kiếm
- Sử dụng filters để lọc theo trạng thái
- Search box để tìm theo tên hoặc slug

## 📝 Lưu ý quan trọng

### Auto-generation
- **Slug**: Tự động tạo từ tên khi tạo mới
- **SEO Title**: Sử dụng tên danh mục nếu để trống
- **Cache**: Tự động clear cache khi có thay đổi

### Validation
- **Name**: Required, max 255 ký tự
- **Slug**: Required, unique, alpha_dash
- **Color**: Default #dc2626
- **Status**: Default 'active'

### Relationships
- **Parent-Child**: Hỗ trợ danh mục con
- **Courses**: One-to-many với Course model
- **Count**: Tự động đếm số khóa học

### Performance
- **Eager Loading**: Sử dụng withCount cho courses
- **Cache**: Tích hợp ClearsViewCache trait
- **Pagination**: 10, 25, 50 items per page

## 🐛 Troubleshooting

### Không thấy trong menu
1. Kiểm tra đã chạy `php artisan filament:optimize`
2. Clear cache: `php artisan cache:clear`
3. Kiểm tra permissions

### Hình ảnh không upload
1. Kiểm tra storage link: `php artisan storage:link`
2. Kiểm tra quyền thư mục storage
3. Kiểm tra file size limits

### Slug duplicate error
1. Kiểm tra unique constraint
2. Sử dụng ignoreRecord: true trong validation
3. Manual check trong database

### Cache không clear
1. Kiểm tra ClearsViewCache trait
2. Manual clear: `php artisan cache:clear`
3. Kiểm tra ViewServiceProvider
