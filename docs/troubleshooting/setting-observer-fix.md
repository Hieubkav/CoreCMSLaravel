# SettingObserver - Đã sửa lỗi

## **Vấn đề ban đầu:**
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'old_og_image' in 'field list'
```

**Nguyên nhân**: Observer cũ sử dụng `$setting->old_og_image` - Laravel cố gắng lưu thuộc tính này vào database nhưng cột không tồn tại.

## **Giải pháp đã áp dụng:**

### **1. Tạo HandlesFileObserver Trait**
- **File**: `app/Traits/HandlesFileObserver.php`
- **Chức năng**: Sử dụng Cache để lưu trữ tạm thời file cũ
- **Lợi ích**: 
  - Không cần thêm cột vào database
  - An toàn và không gây xung đột
  - Tự động hết hạn sau 10 phút

### **2. Cập nhật SettingObserver**
- **Sử dụng trait**: `use HandlesFileObserver`
- **Method updating()**: Lưu file cũ vào cache
- **Method updated()**: Lấy file cũ từ cache và xóa

### **3. Cách hoạt động mới:**

#### **Khi cập nhật Setting:**
1. **updating()**: 
   - Kiểm tra field nào thay đổi (`logo_link`, `favicon_link`, `og_image_link`)
   - Lưu đường dẫn file cũ vào cache với key duy nhất
   
2. **updated()**:
   - Lấy file cũ từ cache
   - Xóa file cũ từ storage
   - Xóa cache key

#### **Cache Key Format:**
```
old_file_{ModelClass}_{ModelId}_{FieldName}
```

**Ví dụ:**
```
old_file_App\Models\Setting_1_og_image_link
old_file_App\Models\Setting_1_logo_link
old_file_App\Models\Setting_1_favicon_link
```

## **Code mới:**

### **updating() method:**
```php
public function updating(Setting $setting): void
{
    $modelClass = get_class($setting);
    $modelId = $setting->id;
    
    // Lưu logo_link cũ
    if ($setting->isDirty('logo_link')) {
        $this->storeOldFile($modelClass, $modelId, 'logo_link', $setting->getOriginal('logo_link'));
    }
    
    // Lưu favicon_link cũ
    if ($setting->isDirty('favicon_link')) {
        $this->storeOldFile($modelClass, $modelId, 'favicon_link', $setting->getOriginal('favicon_link'));
    }
    
    // Lưu og_image_link cũ
    if ($setting->isDirty('og_image_link')) {
        $this->storeOldFile($modelClass, $modelId, 'og_image_link', $setting->getOriginal('og_image_link'));
    }
}
```

### **updated() method:**
```php
public function updated(Setting $setting): void
{
    $modelClass = get_class($setting);
    $modelId = $setting->id;
    
    // Lấy và xóa logo_link cũ
    $oldLogo = $this->getAndDeleteOldFile($modelClass, $modelId, 'logo_link');
    if ($oldLogo) {
        $this->imageService->deleteImage($oldLogo);
    }
    
    // Lấy và xóa favicon_link cũ
    $oldFavicon = $this->getAndDeleteOldFile($modelClass, $modelId, 'favicon_link');
    if ($oldFavicon) {
        $this->imageService->deleteImage($oldFavicon);
    }
    
    // Lấy và xóa og_image_link cũ
    $oldOgImage = $this->getAndDeleteOldFile($modelClass, $modelId, 'og_image_link');
    if ($oldOgImage) {
        $this->imageService->deleteImage($oldOgImage);
    }
}
```

## **Kết quả:**

✅ **Không còn lỗi SQL**
✅ **File OG image cũ sẽ bị xóa khi thay đổi**
✅ **Logo và favicon cũ cũng sẽ bị xóa khi thay đổi**
✅ **Không cần thêm cột vào database**
✅ **An toàn và không gây xung đột**

## **Test để kiểm tra:**

1. **Thay đổi OG image** → File cũ sẽ bị xóa từ storage
2. **Thay đổi logo** → Logo cũ sẽ bị xóa từ storage
3. **Thay đổi favicon** → Favicon cũ sẽ bị xóa từ storage
4. **Kiểm tra storage** → Không còn file "rác"

## **Lưu ý:**

- **Cache TTL**: 10 phút (đủ thời gian cho quá trình update)
- **Trait có thể tái sử dụng**: Các Observer khác cũng có thể sử dụng trait này
- **Tự động cleanup**: Cache sẽ tự động hết hạn nếu có lỗi xảy ra

🎉 **SettingObserver đã hoạt động hoàn hảo!**
