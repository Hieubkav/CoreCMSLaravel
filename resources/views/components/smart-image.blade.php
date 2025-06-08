@props([
    'src' => '',
    'alt' => '',
    'class' => '',
    'width' => null,
    'height' => null,
    'priority' => false, // true cho ảnh above fold
    'aspectRatio' => null,
    'fallbackIcon' => 'fas fa-image',
    'fallbackType' => 'default'
])

@php
    // Xử lý đường dẫn ảnh
    $imagePath = $src;
    if (str_starts_with($src, asset('storage/'))) {
        $imagePath = str_replace(asset('storage/'), '', $src);
    } elseif (str_starts_with($src, 'storage/')) {
        $imagePath = str_replace('storage/', '', $src);
    }

    // Kiểm tra ảnh tồn tại
    $imageExists = !empty($imagePath) && \Storage::exists('public/' . $imagePath);
    $imageUrl = $imageExists ? asset('storage/' . $imagePath) : null;

    // Fallback icons theo type
    $fallbackIcons = [
        'course' => 'fas fa-graduation-cap',
        'news' => 'fas fa-newspaper',
        'partner' => 'fas fa-handshake',
        'album' => 'fas fa-images',
        'testimonial' => 'fas fa-quote-left',
        'default' => 'fas fa-image'
    ];

    $fallbackIcon = $fallbackIcons[$fallbackType] ?? $fallbackIcons['default'];

    // Tạo attributes đơn giản
    $imageAttributes = [];
    if ($imageExists) {
        $imageAttributes = [
            'src' => $imageUrl,
            'alt' => $alt,
            'class' => $class,
            'loading' => $priority ? 'eager' : 'lazy',
            'decoding' => 'async',
            'onerror' => 'handleImageError(this)',
            'style' => 'transition: opacity 0.3s ease;'
        ];

        // Thêm width/height nếu có
        if ($width) $imageAttributes['width'] = $width;
        if ($height) $imageAttributes['height'] = $height;
    }

    // Thêm aspect ratio class
    $aspectRatioClass = '';
    if ($aspectRatio) {
        $aspectRatioClass = match($aspectRatio) {
            '16:9' => 'aspect-[16/9]',
            '4:3' => 'aspect-[4/3]',
            '1:1' => 'aspect-square',
            '3:2' => 'aspect-[3/2]',
            '21:9' => 'aspect-[21/9]',
            default => $aspectRatio
        };
    }

    if (isset($imageAttributes['class'])) {
        $imageAttributes['class'] = trim($imageAttributes['class'] . ' ' . $aspectRatioClass);
    }
@endphp

<div class="smart-image-container relative overflow-hidden {{ $aspectRatio ? $aspectRatioClass : '' }}">
    @if($imageExists)
        {{-- Main image với native lazy loading --}}
        <img {!! collect($imageAttributes)->map(fn($value, $key) => $key.'="'.$value.'"')->implode(' ') !!}
             data-smart-image="true">

        {{-- Fallback UI - ẩn mặc định --}}
        <div class="fallback-placeholder w-full h-full bg-gray-50 flex items-center justify-center absolute inset-0"
             style="display: none;">
            <i class="{{ $fallbackIcon }} text-2xl text-gray-400"></i>
        </div>
    @else
        {{-- Hiển thị fallback ngay khi không có ảnh --}}
        <div class="fallback-placeholder w-full h-full bg-gray-50 flex items-center justify-center">
            <i class="{{ $fallbackIcon }} text-2xl text-gray-400"></i>
        </div>
    @endif
</div>

{{-- KISS: Không cần JavaScript phức tạp, dùng global handler trong layout --}}
