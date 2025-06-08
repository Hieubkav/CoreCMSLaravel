<div class="p-6">
    <div class="text-center">
        <img src="{{ asset('storage/' . $image->image_path) }}" 
             alt="{{ $image->title }}"
             class="max-w-full max-h-96 object-contain mx-auto rounded-lg shadow-lg">
        
        <div class="mt-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $image->title }}</h3>
            
            @if($image->description)
                <p class="text-gray-600 mb-3">{{ $image->description }}</p>
            @endif
            
            <div class="flex flex-wrap justify-center gap-4 text-sm text-gray-500">
                <div class="flex items-center">
                    <i class="fas fa-sort-numeric-up mr-1"></i>
                    <span>Thứ tự: {{ $image->order }}</span>
                </div>
                
                <div class="flex items-center">
                    <i class="fas fa-{{ $image->status === 'active' ? 'eye' : 'eye-slash' }} mr-1"></i>
                    <span>{{ $image->status === 'active' ? 'Hiển thị' : 'Ẩn' }}</span>
                </div>
                
                @if($image->is_main)
                <div class="flex items-center text-yellow-600">
                    <i class="fas fa-star mr-1"></i>
                    <span>Ảnh chính</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
