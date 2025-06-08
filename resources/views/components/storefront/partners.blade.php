@php
    // KISS: Lấy dữ liệu đơn giản
    try {
        $partnersData = \App\Models\Partner::where('status', 'active')
            ->orderBy('order')
            ->orderBy('name')
            ->get();
    } catch (\Exception $e) {
        $partnersData = collect();
    }
@endphp

@if($partnersData->count() > 0)
<!-- KISS: Partners Grid đơn giản -->
<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
    @foreach($partnersData as $partner)
    <div class="bg-white rounded-lg p-4 border border-gray-100 hover:shadow-md transition-shadow">
        <!-- Logo -->
        @if($partner->logo_link)
            <img
                src="{{ asset('storage/' . $partner->logo_link) }}"
                alt="{{ $partner->name }}"
                class="h-12 w-auto mx-auto object-contain"
                loading="lazy"
                onerror="handleImageError(this)">
        @else
            <!-- Default icon -->
            <div class="h-12 w-12 bg-red-50 rounded-lg flex items-center justify-center mx-auto">
                <i class="fas fa-handshake text-red-500"></i>
            </div>
        @endif

        <!-- Name -->
        <p class="text-xs text-gray-600 text-center mt-2 truncate">{{ $partner->name }}</p>
    </div>
    @endforeach
</div>
@else
<!-- KISS: Fallback đơn giản -->
<div class="text-center py-8">
    <div class="w-16 h-16 bg-red-50 rounded-lg flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-handshake text-2xl text-red-500"></i>
    </div>
    <p class="text-gray-500">Chưa có đối tác nào</p>
</div>
@endif

{{-- KISS: Không cần global handler và CSS/JS phức tạp --}}