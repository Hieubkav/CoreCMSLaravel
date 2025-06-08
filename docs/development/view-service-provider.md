# ViewServiceProvider Documentation

## Tổng quan

ViewServiceProvider được tạo để cung cấp dữ liệu từ database cho các view một cách tối ưu, tránh lỗi N+1 và cải thiện hiệu suất bằng caching.

## Cấu trúc

### 1. ViewServiceProvider (`app/Providers/ViewServiceProvider.php`)

Provider chính chịu trách nhiệm:
- Share dữ liệu global cho tất cả views
- Share dữ liệu storefront cho các view storefront
- Share dữ liệu navigation cho các layout view
- Quản lý cache tự động

### 2. ClearsViewCache Trait (`app/Traits/ClearsViewCache.php`)

Trait tự động clear cache khi có thay đổi dữ liệu trong các model:
- Setting
- Product
- CatProduct
- Post
- Partner
- Slider

### 3. ViewDataHelper (`app/Helpers/ViewDataHelper.php`)

Helper class cung cấp các method tiện ích để lấy dữ liệu cached.

### 4. ClearViewCache Command (`app/Console/Commands/ClearViewCache.php`)

Artisan command để clear cache thủ công.

## Cách sử dụng

### 1. Trong Blade Views

Dữ liệu được tự động inject vào view, bạn có thể sử dụng trực tiếp:

```blade
{{-- Sử dụng sliders --}}
@if(isset($sliders) && $sliders->count() > 0)
    @foreach($sliders as $slider)
        <div>{{ $slider->title }}</div>
    @endforeach
@endif

{{-- Sử dụng categories --}}
@if(isset($categories))
    @foreach($categories as $category)
        <div>{{ $category->name }}</div>
    @endforeach
@endif

{{-- Sử dụng featured products --}}
@if(isset($featuredProducts))
    @foreach($featuredProducts as $product)
        <div>{{ $product->name }} - {{ number_format($product->price) }}đ</div>
    @endforeach
@endif
```

### 2. Sử dụng Helper

```php
use App\Helpers\ViewDataHelper;

// Lấy tất cả dữ liệu storefront
$storefrontData = ViewDataHelper::getStorefrontData();

// Lấy dữ liệu cụ thể
$sliders = ViewDataHelper::get('sliders');
$categories = ViewDataHelper::get('categories');
```

### 3. Clear Cache

```bash
# Clear tất cả cache
php artisan view:clear-cache

# Clear cache cụ thể
php artisan view:clear-cache settings
php artisan view:clear-cache storefront
php artisan view:clear-cache navigation
```

## Dữ liệu có sẵn

### Global Data (tất cả views)
- `$globalSettings`: Collection settings theo key
- `$settings`: Setting đầu tiên (backward compatibility)

### Storefront Data
- `$sliders`: Danh sách slider active
- `$categories`: Danh sách category cha
- `$featuredProducts`: Sản phẩm nổi bật (is_hot = true)
- `$latestPosts`: Bài viết mới nhất
- `$partners`: Danh sách đối tác
- `$popularProducts`: Sản phẩm phổ biến
- `$newProducts`: Sản phẩm mới

### Navigation Data
- `$mainCategories`: Categories với children cho navigation
- `$footerCategories`: Categories cho footer
- `$recentPosts`: Bài viết gần đây cho footer

## Cache Strategy

- **Global Settings**: Cache 1 giờ (3600s)
- **Storefront Data**: Cache 30 phút (1800s)
- **Navigation Data**: Cache 2 giờ (7200s)

Cache tự động clear khi có thay đổi dữ liệu trong các model liên quan.

## Lưu ý quan trọng

1. **Model Status**: Tất cả query đều filter theo status = 'active'
2. **Relationships**: Sử dụng eager loading để tránh N+1
3. **Auto Clear**: Cache tự động clear khi CRUD model
4. **Fallback**: Luôn kiểm tra `isset()` trước khi sử dụng dữ liệu

## Troubleshooting

### Cache không clear tự động
- Kiểm tra model đã sử dụng `ClearsViewCache` trait chưa
- Chạy `php artisan view:clear-cache` thủ công

### Dữ liệu không hiển thị
- Kiểm tra view có match với pattern trong ViewServiceProvider
- Kiểm tra status của dữ liệu trong database
- Clear cache và thử lại

### Performance Issues
- Giảm thời gian cache nếu dữ liệu thay đổi thường xuyên
- Tăng thời gian cache nếu dữ liệu ít thay đổi
- Sử dụng Redis cache cho hiệu suất tốt hơn
