# Setup Wizard FileUpload Fix Documentation

## 🎯 Vấn đề đã giải quyết

Lỗi "foreach() argument must be of type array|object, string given" xảy ra trong Filament FileUpload components khi:
- Database lưu file paths dưới dạng string: `"logos/logo.webp"`
- Filament FileUpload mong đợi array: `["logos/logo.webp"]`

## 🔧 Giải pháp áp dụng

### 1. Tạo Trait `HandlesFileUploadFields`
**File:** `app/Traits/HandlesFileUploadFields.php`

```php
trait HandlesFileUploadFields
{
    // Chuyển string → array khi mount
    protected function convertFileFieldsToArray(array $data, array $fileFields): array
    
    // Chuyển array → string khi save
    protected function convertFileFieldsToString(array $data, array $fileFields): array
    
    // Danh sách file fields mặc định
    protected function getDefaultFileUploadFields(): array
}
```

### 2. Cập nhật ManageSettings Pages

**Files cập nhật:**
- `app/Filament/Admin/Pages/ManageSettings.php` ✅
- `storage/setup-templates/filament/pages/ManageSettings.php` ✅

**Thay đổi:**
```php
class ManageSettings extends Page
{
    use HandlesFileUploadFields;  // ← Thêm trait
    
    public function mount(): void
    {
        // Sử dụng trait method
        $this->data = $this->convertFileFieldsToArray($this->data, $fileFields);
    }
    
    public function save(): void
    {
        // Sử dụng trait method
        $data = $this->convertFileFieldsToString($data, $fileFields);
    }
}
```

### 3. Cập nhật Setup Flow

**ProcessAdminStep:** Thêm sinh UserResource sau khi tạo admin
```php
// Sinh UserResource sau khi tạo admin thành công
$generateResult = \App\Actions\Setup\CodeGenerator::generateForStep('admin');
```

**CodeGenerator:** Thêm step 'admin' mapping
```php
'admin' => [
    'filament_resources' => ['UserResource.php'],
],
```

## 🚀 Kết quả

### ✅ Đã sửa
- Lỗi foreach trong `/admin/manage-settings`
- Setup wizard flow hoạt động đúng: `/setup` → `/setup/step/database` → `/setup/step/admin` → `/setup/step/website`
- UserResource + RoleResource được sinh tự động ở bước admin
- ManageSettings được sinh tự động ở bước website
- Roles và Permissions cơ bản được tạo tự động

### 🎯 Flow hoạt động
1. **Reset:** `/setup/reset` - Xóa dữ liệu và file generated
2. **Database:** `/setup/step/database` - Tạo bảng cấu hình
3. **Admin:** `/setup/step/admin` - Tạo admin + sinh UserResource + RoleResource + tạo roles/permissions
4. **Website:** `/setup/step/website` - Lưu settings + sinh ManageSettings

### 🔐 Admin Step Details
**Khi submit form ở `/setup/step/admin`:**
- Tạo admin user với thông tin đã nhập
- Sinh UserResource.php và các Pages (List, Create, Edit, View)
- Sinh RoleResource.php và các Pages (List, Create, Edit, View)
- Tạo 3 roles cơ bản: Super Admin, Admin, User
- Tạo 9 permissions cơ bản: view_users, create_users, edit_users, delete_users, view_roles, create_roles, edit_roles, delete_roles, manage_permissions
- Gán permissions cho roles tương ứng
- User được tạo tự động gán role Super Admin

### 🎯 Sidebar Navigation
**Resources hiển thị trong "Quản lý hệ thống":**
- ✅ UserResource - Quản lý người dùng
- ✅ RoleResource - Quản lý vai trò
- ✅ Cả hai đều có navigation group và sort order đúng

### 🔄 Tái sử dụng
- Trait `HandlesFileUploadFields` có thể dùng cho bất kỳ Filament page nào có FileUpload
- Template ManageSettings đã được cập nhật để tránh lỗi tương tự trong tương lai
- Setup wizard đã ổn định và sẵn sàng cho production

## 📝 Lưu ý
- Tất cả Filament pages có FileUpload nên sử dụng trait này
- File fields phổ biến: `logo_link`, `favicon_link`, `placeholder_image`, `image_link`, `avatar_link`
- Trait tự động xử lý conversion giữa string ↔ array
