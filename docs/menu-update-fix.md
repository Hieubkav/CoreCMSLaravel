# Khắc phục vấn đề Menu động không cập nhật

## Vấn đề
Khi thêm menu item mới trong Filament admin panel, menu động ở navbar không được cập nhật ngay lập tức.

## Nguyên nhân
1. **Cache không được clear đúng cách**: MenuItem model có cả Observer riêng và ClearsViewCache trait gây xung đột
2. **ClearsViewCache trait thiếu case cho MenuItem**: Rơi vào default case và clear tất cả cache thay vì chỉ navigation cache
3. **ViewServiceProvider thiếu menuItems**: Không có menuItems trong navigation_data cache

## Giải pháp đã áp dụng

### 1. Cập nhật ClearsViewCache trait
```php
// app/Traits/ClearsViewCache.php
case 'App\Models\MenuItem':
    ViewServiceProvider::refreshCache('navigation');
    break;
```

### 2. Xóa MenuItemObserver riêng biệt
- Xóa file `app/Observers/MenuItemObserver.php`
- Xóa đăng ký trong `EventServiceProvider.php`
- Sử dụng ClearsViewCache trait thống nhất

### 3. Cập nhật ViewServiceProvider
```php
// app/Providers/ViewServiceProvider.php
'menuItems' => MenuItem::where('status', 'active')
    ->whereNull('parent_id')
    ->with(['children' => function ($query) {
        $query->where('status', 'active')->orderBy('order');
    }])
    ->orderBy('order')
    ->get(),
```

### 4. Cập nhật DynamicMenu component
```php
// app/Livewire/Public/DynamicMenu.php
protected $listeners = ['refreshMenu' => 'loadMenuItems'];

public function loadMenuItems()
{
    $this->menuItems = ViewDataHelper::get('menuItems', collect([]));
}
```

## Cách hoạt động

1. **Khi thêm/sửa/xóa MenuItem**:
   - ClearsViewCache trait tự động trigger
   - Clear cache `navigation_data`
   - Menu mới được load từ database

2. **Khi load trang**:
   - DynamicMenu component load data từ ViewDataHelper
   - ViewDataHelper lấy từ cache hoặc database nếu cache expired

3. **Manual refresh**:
   - Có thể gọi `Livewire.dispatch('refreshMenu')` để force refresh
   - Hoặc clear cache thủ công qua route `/clear-cache`

## Test

### Trang test: `/test-menu`
- Hiển thị menu hiện tại
- Nút refresh menu manual
- Nút clear cache

### Cách test:
1. Mở `/test-menu`
2. Thêm menu item mới trong Filament admin
3. Quay lại trang test và refresh để xem menu đã cập nhật

### Command test:
```bash
php artisan test:menu-update
```

## Lưu ý

- Cache `navigation_data` có thời gian sống 2 giờ
- Tự động clear khi có thay đổi MenuItem
- Livewire component có thể refresh manual qua event
- Không cần broadcasting cho real-time update (đơn giản hóa)

## Files đã thay đổi

1. `app/Traits/ClearsViewCache.php` - Thêm case cho MenuItem
2. `app/Providers/ViewServiceProvider.php` - Thêm menuItems vào navigation_data
3. `app/Livewire/Public/DynamicMenu.php` - Cập nhật load method và listeners
4. `app/Providers/EventServiceProvider.php` - Xóa MenuItemObserver
5. `routes/web.php` - Thêm test routes
6. `resources/views/test-menu.blade.php` - Trang test

## Kết quả
Menu động giờ đây sẽ tự động cập nhật khi có thay đổi trong database, đảm bảo tính nhất quán và real-time.
