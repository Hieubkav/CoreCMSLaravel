# Cấu hình Brand cho Filament Admin Panel

## Tổng quan

Filament Admin Panel đã được tùy chỉnh để sử dụng dữ liệu động từ model `Setting` thay vì các giá trị cố định.

## Cấu hình hiện tại

### 1. Brand Name (Tên thương hiệu)
- **Nguồn dữ liệu**: Trường `site_name` từ model `Setting`
- **Fallback**: "VBA Vũ Phúc" (nếu không có dữ liệu trong database)
- **Hiển thị**: Text trong header của admin panel

### 2. Brand Logo
- **Cấu hình hiện tại**: Chỉ hiển thị text, không hiển thị logo
- **Lý do**: Theo yêu cầu người dùng muốn header gọn gàng hơn
- **Có thể thay đổi**: Có (xem phần "Cách thay đổi cấu hình")

### 3. Favicon
- **Nguồn dữ liệu**: Trường `favicon_link` từ model `Setting`
- **Fallback**: `favicon.ico` trong thư mục public
- **Kiểm tra file**: Tự động kiểm tra file có tồn tại trong storage

## Cách thay đổi cấu hình

### Để hiển thị logo thay vì chỉ text:

1. Mở file `app/Providers/Filament/AdminPanelProvider.php`
2. Thay đổi constant `SHOW_LOGO` từ `false` thành `true`:

```php
private const SHOW_LOGO = true;
```

3. Clear cache:
```bash
php artisan config:clear
```

### Để thay đổi dữ liệu hiển thị:

1. Truy cập trang admin: `http://127.0.0.1:8000/admin/manage-settings`
2. Cập nhật các trường:
   - **Tên website**: Sẽ hiển thị trong header
   - **Logo**: Sẽ hiển thị nếu bật `SHOW_LOGO = true`
   - **Favicon**: Sẽ hiển thị trong tab browser

## Cấu trúc code

### Method `getBrandName()`
- Lấy tên website từ database
- Fallback về "VBA Vũ Phúc" nếu không có dữ liệu

### Method `getBrandLogo()`
- Kiểm tra constant `SHOW_LOGO`
- Nếu `true`: lấy logo từ database hoặc fallback
- Nếu `false`: trả về `null` (chỉ hiển thị text)

### Method `getFavicon()`
- Lấy favicon từ database
- Kiểm tra file có tồn tại trong storage
- Fallback về favicon mặc định

## Lưu ý

1. **Cache**: Sau khi thay đổi cấu hình, cần clear cache
2. **File storage**: Hệ thống tự động kiểm tra file có tồn tại
3. **Fallback**: Luôn có giá trị mặc định nếu database trống
4. **Performance**: Chỉ query database 1 lần khi load admin panel

## Troubleshooting

### Nếu không thấy thay đổi:
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Nếu logo không hiển thị:
1. Kiểm tra file có tồn tại trong `storage/app/public/`
2. Kiểm tra symbolic link: `php artisan storage:link`
3. Kiểm tra constant `SHOW_LOGO = true`
