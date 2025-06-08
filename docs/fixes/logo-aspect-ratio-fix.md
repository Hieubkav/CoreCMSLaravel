# Sửa lỗi Logo bị méo mó trong ManageSettings

## Vấn đề
Khi upload logo trong trang Cài đặt Website (ManageSettings), hình ảnh bị méo mó hoặc thay đổi tỷ lệ do:
- Sử dụng `imageResizeMode('cover')` - cắt và méo ảnh để vừa khung cố định
- Resize cứng với kích thước 400x200 không quan tâm đến tỷ lệ gốc

## Giải pháp đã áp dụng

### 1. Thay đổi ManageSettings.php
- **Thay đổi từ `cover` thành `contain`**: Giữ nguyên tỷ lệ ảnh
- **Thêm helper text**: Thông báo cho user biết logo sẽ không bị méo
- **Thêm imageEditor()**: Cho phép user chỉnh sửa ảnh trước khi upload
- **Sử dụng phương thức mới**: `saveImageWithAspectRatio()` thay vì `saveImage()`

### 2. Thêm phương thức mới trong ImageService.php
```php
public function saveImageWithAspectRatio($image, string $directory, int $maxWidth = 400, int $maxHeight = 200, int $quality = 80, ?string $customName = null): ?string
```

**Cách hoạt động:**
- Tính toán tỷ lệ scale để ảnh vừa trong khung mà không méo
- Chỉ resize nếu ảnh lớn hơn kích thước tối đa
- Sử dụng `min($scaleWidth, $scaleHeight)` để đảm bảo ảnh không bị cắt

### 3. Cập nhật Placeholder Image
- Áp dụng cùng logic cho ảnh placeholder để đảm bảo tính nhất quán

### 4. Cập nhật Favicon và OG Image
- **Favicon**: Chuyển từ `cover` thành `contain` + `saveImageWithAspectRatio()`
- **OG Image**: Chuyển từ `cover` thành `contain` + `saveImageWithAspectRatio()`
- Thêm helper text giải thích kích thước tối ưu

## Kết quả
✅ **Logo**: Giữ nguyên tỷ lệ gốc, không bị méo mó
✅ **Favicon**: Giữ nguyên tỷ lệ, vừa trong khung 32x32px
✅ **OG Image**: Giữ nguyên tỷ lệ, vừa trong khung 1200x630px
✅ **Placeholder**: Giữ nguyên tỷ lệ, vừa trong khung 400x400px
✅ **Vẫn tối ưu hóa** thành WebP format
✅ **Vẫn tạo tên file** SEO-friendly

## Tất cả trường hình ảnh đã được sửa
- ✅ **Logo website**: Giữ nguyên tỷ lệ thương hiệu
- ✅ **Favicon**: Không bị méo trong khung vuông 32x32
- ✅ **OG Image**: Không bị méo trong khung 1200x630
- ✅ **Placeholder image**: Không méo mó khi hiển thị

## Lưu ý quan trọng
- **Tất cả hình ảnh** trong ManageSettings giờ đây đều giữ nguyên tỷ lệ gốc
- **Không còn trường hợp nào** bị méo mó hay bóp ép
- **Chất lượng hình ảnh** được bảo toàn tối đa
