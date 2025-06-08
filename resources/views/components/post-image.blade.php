@props([
    'post' => null,
    'size' => 'large', // small, medium, large
    'showFallback' => true,
    'eager' => false
])

@php
    // Xác định kích thước
    $sizeClasses = [
        'small' => 'h-32',
        'medium' => 'h-48', 
        'large' => 'h-64'
    ];
    $heightClass = $sizeClasses[$size] ?? 'h-64';
    
    // Xác định loading strategy
    $loading = $eager ? 'eager' : 'lazy';
    
    // Kiểm tra ảnh tồn tại
    $hasImage = isset($post->thumbnail) && !empty($post->thumbnail) && \Illuminate\Support\Facades\Storage::disk('public')->exists($post->thumbnail);

    // Icon fallback theo type - đơn giản hóa
    $iconClass = match($post->type ?? 'normal') {
        'news' => 'fas fa-newspaper',
        'service' => 'fas fa-cogs',
        'course' => 'fas fa-graduation-cap',
        default => 'fas fa-file-alt'
    };
@endphp

<div class="relative w-full {{ $heightClass }} overflow-hidden rounded-lg">
    @if($hasImage)
        <!-- Ảnh thực tế - giữ nguyên tỷ lệ -->
        <img src="{{ asset('storage/' . $post->thumbnail) }}"
             alt="{{ $post->title }}"
             class="w-full h-full object-contain bg-white"
             loading="{{ $loading }}"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
        
        @if($showFallback)
            <!-- Fallback UI khi ảnh lỗi -->
            <div class="absolute inset-0 bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center" style="display: none;">
                <i class="fas fa-image text-4xl text-red-300"></i>
            </div>
        @endif
    @else
        @if($showFallback)
            <!-- Fallback UI khi không có ảnh -->
            <div class="w-full h-full bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center">
                <i class="{{ $iconClass }} text-4xl text-red-300"></i>
            </div>
        @endif
    @endif
</div>
