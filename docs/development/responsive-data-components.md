# Nguyên Tắc "Responsive với Dữ Liệu Trống"

## Tổng quan

Đây là nguyên tắc thiết kế component quan trọng nhất trong dự án. Tất cả component phải tuân thủ nguyên tắc này để đảm bảo giao diện luôn hoạt động tốt dù dữ liệu có đầy đủ hay không.

## Nguyên tắc cốt lõi

### 1. **Kiểm tra dữ liệu nghiêm ngặt**
```php
// ✅ ĐÚNG - Kiểm tra đầy đủ
@if(isset($data) && !empty($data) && is_array($data) && count($data) > 0)
    // Hiển thị dữ liệu
@endif

// ❌ SAI - Kiểm tra không đầy đủ
@if($data)
    // Có thể lỗi nếu $data là mảng rỗng hoặc chuỗi rỗng
@endif
```

### 2. **Ẩn hoàn toàn element nếu không có dữ liệu**
```php
// ✅ ĐÚNG - Ẩn hoàn toàn
@if($hasTitle)
    <h2>{{ $title }}</h2>
@endif

// ❌ SAI - Để lại element rỗng
<h2>{{ $title ?? '' }}</h2>
```

### 3. **Layout tự động điều chỉnh**
```php
// ✅ ĐÚNG - Grid responsive theo số lượng
$gridCols = match(true) {
    $itemCount >= 4 => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4',
    $itemCount === 3 => 'grid-cols-1 md:grid-cols-3',
    $itemCount === 2 => 'grid-cols-1 md:grid-cols-2',
    $itemCount === 1 => 'grid-cols-1 max-w-md mx-auto',
    default => 'grid-cols-1'
};
```

## Ví dụ thực tế

### Component Subnav (Đã implement)

```php
@php
    // Kiểm tra dữ liệu từ ViewServiceProvider
    $email = isset($settingsData['email']) ? $settingsData['email']->value ?? null : null;
    $hotline = isset($settingsData['hotline']) ? $settingsData['hotline']->value ?? null : null;
    
    // Tạo mảng chỉ với dữ liệu có sẵn
    $contactInfo = [];
    if (!empty($email)) {
        $contactInfo[] = ['type' => 'email', 'value' => $email];
    }
    if (!empty($hotline)) {
        $contactInfo[] = ['type' => 'phone', 'value' => $hotline];
    }
    
    // Kiểm tra có dữ liệu để hiển thị
    $hasContactInfo = !empty($contactInfo);
@endphp

{{-- Chỉ hiển thị nếu có dữ liệu --}}
@if($hasContactInfo)
    <div class="subnav">
        @foreach($contactInfo as $contact)
            <a href="{{ $contact['href'] }}">{{ $contact['value'] }}</a>
        @endforeach
    </div>
@endif
```

## Checklist cho mọi Component

### ✅ **Trước khi hiển thị dữ liệu:**
- [ ] Kiểm tra `isset()`
- [ ] Kiểm tra `!empty()`
- [ ] Kiểm tra type (array, string, object)
- [ ] Kiểm tra count() cho array
- [ ] Trim() cho string

### ✅ **Layout và UI:**
- [ ] Grid/Flex tự động điều chỉnh theo số lượng item
- [ ] Không để lại khoảng trống không cần thiết
- [ ] Responsive trên tất cả breakpoints
- [ ] Fallback graceful cho missing data

### ✅ **Performance:**
- [ ] Logic kiểm tra ở đầu component (PHP block)
- [ ] Tránh logic phức tạp trong template
- [ ] Cache kết quả kiểm tra nếu cần

### ✅ **UX/UI:**
- [ ] Hover effects mượt mà
- [ ] Transition animations
- [ ] Consistent spacing
- [ ] Accessibility (alt text, aria labels)

## Patterns thường dùng

### 1. **Kiểm tra dữ liệu ViewServiceProvider**
```php
@php
    $data = $globalSettings['key']->value ?? $settings->key ?? null;
    $hasData = !empty($data) && trim($data) !== '';
@endphp

@if($hasData)
    <div>{{ $data }}</div>
@endif
```

### 2. **Grid responsive theo số lượng**
```php
@php
    $itemCount = count($items);
    $gridClass = match(true) {
        $itemCount >= 6 => 'grid-cols-2 md:grid-cols-3 lg:grid-cols-6',
        $itemCount >= 4 => 'grid-cols-2 md:grid-cols-4',
        $itemCount >= 3 => 'grid-cols-1 md:grid-cols-3',
        $itemCount === 2 => 'grid-cols-1 md:grid-cols-2',
        default => 'grid-cols-1'
    };
@endphp

<div class="grid {{ $gridClass }} gap-4">
    @foreach($items as $item)
        <div>{{ $item }}</div>
    @endforeach
</div>
```

### 3. **Social Links với icon động**
```php
@php
    $socialLinks = [];
    if (!empty($facebook)) $socialLinks[] = ['type' => 'facebook', 'url' => $facebook];
    if (!empty($youtube)) $socialLinks[] = ['type' => 'youtube', 'url' => $youtube];
    
    $hasSocial = !empty($socialLinks);
@endphp

@if($hasSocial)
    <div class="flex gap-4">
        @foreach($socialLinks as $social)
            <a href="{{ $social['url'] }}">
                @include('icons.' . $social['type'])
            </a>
        @endforeach
    </div>
@endif
```

## Lỗi thường gặp

### ❌ **Không kiểm tra dữ liệu**
```php
// SAI
<div>{{ $title }}</div> <!-- Có thể hiển thị rỗng -->

// ĐÚNG
@if(!empty($title))
    <div>{{ $title }}</div>
@endif
```

### ❌ **Layout cứng nhắc**
```php
// SAI
<div class="grid grid-cols-4"> <!-- Luôn 4 cột dù có ít item -->

// ĐÚNG
<div class="grid {{ $dynamicGridClass }}"> <!-- Tự động điều chỉnh -->
```

### ❌ **Để lại element rỗng**
```php
// SAI
<div class="social-links">
    @if($facebook)<a href="{{ $facebook }}">FB</a>@endif
    @if($youtube)<a href="{{ $youtube }}">YT</a>@endif
</div> <!-- Div rỗng nếu không có social links -->

// ĐÚNG
@if($hasSocialLinks)
    <div class="social-links">
        @foreach($socialLinks as $link)
            <a href="{{ $link['url'] }}">{{ $link['name'] }}</a>
        @endforeach
    </div>
@endif
```

## Kết luận

Nguyên tắc này đảm bảo:
- **Giao diện luôn đẹp** dù dữ liệu có đầy đủ hay không
- **Performance tốt** nhờ kiểm tra dữ liệu hiệu quả
- **Maintainable** và dễ debug
- **User Experience tốt** với layout linh hoạt

**Áp dụng cho TẤT CẢ component trong dự án!**
