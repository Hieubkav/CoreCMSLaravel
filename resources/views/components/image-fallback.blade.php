@props([
    'model' => null,
    'imageField' => 'thumbnail',
    'aspectRatio' => 'aspect-[16/9]',
    'size' => 'medium', // small, medium, large
    'showHover' => true,
    'customIcon' => null,
    'customMessage' => null
])

@php
    // Kiểm tra ảnh có tồn tại không
    $hasImage = isset($model->{$imageField}) && !empty($model->{$imageField}) && \Illuminate\Support\Facades\Storage::disk('public')->exists($model->{$imageField});
    $imageUrl = $hasImage ? asset('storage/' . $model->{$imageField}) : null;
    $altText = $model->title ?? $model->name ?? 'Hình ảnh';

    // Icon mặc định cho bài viết (không còn type)
    $defaultIcon = 'fas fa-newspaper';

    // Xác định kích thước icon
    $iconSizes = [
        'small' => 'text-3xl',
        'medium' => 'text-5xl',
        'large' => 'text-6xl'
    ];
    $iconSize = $iconSizes[$size] ?? 'text-5xl';

    $hoverClass = $showHover ? 'group-hover:scale-105 transition-transform duration-300' : '';
@endphp

<div class="relative overflow-hidden {{ $aspectRatio }} w-full">
    @if($hasImage)
        <!-- Ảnh thực tế -->
        <img src="{{ $imageUrl }}"
             alt="{{ $altText }}"
             class="w-full h-full object-cover {{ $hoverClass }}"
             loading="lazy"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

        <!-- Fallback UI khi ảnh không load được - chỉ icon -->
        <div class="{{ $aspectRatio }} w-full bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center relative overflow-hidden" style="display: none;">
            <i class="fas fa-image {{ $iconSize }} text-red-300"></i>
        </div>
    @else
        <!-- Placeholder khi không có ảnh trong database - chỉ icon -->
        <div class="{{ $aspectRatio }} w-full bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center relative overflow-hidden">
            <i class="{{ $customIcon ?? $defaultIcon }} {{ $iconSize }} text-red-300"></i>
        </div>
    @endif
</div>
