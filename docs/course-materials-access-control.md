# Hệ thống Phân loại Tài liệu Khóa học

## Tổng quan

Hệ thống phân loại tài liệu khóa học được thiết kế để quản lý quyền truy cập tài liệu một cách linh hoạt và bảo mật:

- **Tài liệu mở (Public)**: Ai cũng có thể xem và tải về
- **Tài liệu khóa (Enrolled)**: Chỉ học viên đăng ký khóa học mới được truy cập

## Cấu trúc Database

### Trường access_type mới

```sql
ALTER TABLE course_materials ADD COLUMN access_type ENUM('public', 'enrolled') DEFAULT 'public';
```

### Migration

```php
// database/migrations/2025_06_04_140022_add_access_type_to_course_materials_table.php
$table->enum('access_type', ['public', 'enrolled'])
      ->default('public')
      ->after('material_type')
      ->comment('public: tài liệu mở, enrolled: tài liệu khóa (chỉ học viên đăng ký)');
```

## Model Updates

### CourseMaterial Model

#### Fillable Fields
```php
protected $fillable = [
    // ... existing fields
    'access_type',
    // ... other fields
];
```

#### Casts
```php
protected $casts = [
    // ... existing casts
    'access_type' => 'string',
];
```

#### Scopes mới
```php
public function scopePublic($query)
{
    return $query->where('access_type', 'public');
}

public function scopeEnrolledOnly($query)
{
    return $query->where('access_type', 'enrolled');
}

public function scopeByAccessType($query, $accessType)
{
    return $query->where('access_type', $accessType);
}
```

#### Accessors mới
```php
public function getAccessTypeDisplayAttribute()
{
    $types = [
        'public' => 'Tài liệu mở',
        'enrolled' => 'Tài liệu khóa'
    ];
    return $types[$this->access_type] ?? 'Không xác định';
}

public function getAccessTypeBadgeColorAttribute()
{
    $colors = [
        'public' => 'bg-green-100 text-green-800',
        'enrolled' => 'bg-blue-100 text-blue-800'
    ];
    return $colors[$this->access_type] ?? 'bg-gray-100 text-gray-800';
}
```

#### Helper Methods
```php
public function isPublic()
{
    return $this->access_type === 'public';
}

public function isEnrolledOnly()
{
    return $this->access_type === 'enrolled';
}

public function canAccess($user = null, $course = null)
{
    // Logic kiểm tra quyền truy cập
}
```

## Filament Admin Updates

### Form Schema
```php
Forms\Components\Select::make('access_type')
    ->label('Phân loại truy cập')
    ->required()
    ->options([
        'public' => '🌐 Tài liệu mở (Ai cũng xem được)',
        'enrolled' => '🔒 Tài liệu khóa (Chỉ học viên đăng ký)'
    ])
    ->default('public')
    ->helperText('Tài liệu mở: Hiển thị cho tất cả mọi người. Tài liệu khóa: Chỉ học viên đăng ký khóa học mới được xem và tải.')
    ->columnSpanFull(),
```

### Table Columns
```php
Tables\Columns\TextColumn::make('access_type')
    ->label('Phân loại')
    ->badge()
    ->color(fn (string $state): string => match ($state) {
        'public' => 'success',
        'enrolled' => 'info',
        default => 'gray',
    })
    ->formatStateUsing(fn (string $state): string => match ($state) {
        'public' => 'Tài liệu mở',
        'enrolled' => 'Tài liệu khóa',
        default => $state,
    }),
```

## Controller Logic

### CourseController Updates

```php
// Get public materials (tài liệu mở - ai cũng xem được)
$publicMaterials = $course->materials()
    ->where('access_type', 'public')
    ->where('status', 'active')
    ->orderBy('order')
    ->get();

// Get enrolled materials (tài liệu khóa - chỉ học viên đăng ký)
$enrolledMaterials = $course->materials()
    ->where('access_type', 'enrolled')
    ->where('status', 'active')
    ->orderBy('order')
    ->get();

// Kiểm tra user hiện tại có đăng ký khóa học không
$user = auth()->user();
$isEnrolled = false;
if ($user) {
    $isEnrolled = $course->students()->where('student_id', $user->id)->exists();
}
```

## Frontend Display

### Tài liệu mở (Public Materials)
- Hiển thị với icon 🌐 và màu xanh lá
- Ai cũng có thể xem và tải về
- Badge: "Miễn phí cho tất cả"

### Tài liệu khóa (Enrolled Materials)
- Hiển thị với icon 🔒 và màu xanh dương
- Chỉ học viên đăng ký mới được truy cập
- Badge: "Chỉ học viên đăng ký"
- Hiển thị thông báo đăng ký nếu chưa đăng ký

### Fallback UI cho ảnh
- Gradient background đỏ-trắng
- Icon graduation cap
- Thông tin khóa học
- Decorative elements

## Seeder

### UpdateCourseMaterialsAccessTypeSeeder
```bash
php artisan db:seed --class=UpdateCourseMaterialsAccessTypeSeeder
```

Seeder này sẽ:
- Cập nhật tài liệu preview thành 'public'
- Phân chia 50% tài liệu thành 'enrolled'
- Hiển thị thống kê kết quả

## Sử dụng

### Trong Admin Panel
1. Vào CourseMaterial Resource
2. Chọn "Phân loại truy cập" khi tạo/sửa tài liệu
3. Chọn "Tài liệu mở" hoặc "Tài liệu khóa"

### Trong Frontend
- Tài liệu mở: Hiển thị ngay cho tất cả người dùng
- Tài liệu khóa: Chỉ hiển thị cho học viên đã đăng ký
- Người chưa đăng ký sẽ thấy thông báo khuyến khích đăng ký

## Lợi ích

1. **Bảo mật**: Kiểm soát quyền truy cập tài liệu
2. **Linh hoạt**: Dễ dàng phân loại tài liệu
3. **UX tốt**: Giao diện rõ ràng, dễ hiểu
4. **Khuyến khích đăng ký**: Tạo động lực cho người dùng đăng ký khóa học
5. **Backward compatibility**: Vẫn hỗ trợ hệ thống is_preview cũ

## Tương lai

Có thể mở rộng thêm các loại access_type khác:
- `premium`: Tài liệu cao cấp
- `instructor_only`: Chỉ giảng viên
- `admin_only`: Chỉ admin
