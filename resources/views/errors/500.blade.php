@extends('layouts.shop')

@section('title', 'Lỗi hệ thống - 500')
@section('description', 'Đã xảy ra lỗi hệ thống. Chúng tôi đang khắc phục sự cố này. Vui lòng thử lại sau ít phút.')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-50 via-white to-orange-50 flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto text-center">
        <!-- 500 Animation -->
        <div class="relative mb-8">
            <div class="text-8xl sm:text-9xl lg:text-[12rem] font-bold text-red-100 select-none animate-pulse">
                500
            </div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-32 h-32 sm:w-40 sm:h-40 lg:w-48 lg:h-48 bg-red-600 rounded-full flex items-center justify-center shadow-2xl animate-pulse">
                    <svg class="w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Error Message -->
        <div class="mb-8">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 font-montserrat">
                Lỗi hệ thống
            </h1>
            <p class="text-lg sm:text-xl text-gray-600 mb-6 max-w-2xl mx-auto leading-relaxed">
                Đã xảy ra lỗi không mong muốn. Đội ngũ kỹ thuật của chúng tôi đã được thông báo và đang khắc phục sự cố này.
                Vui lòng thử lại sau ít phút.
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12">
            <a href="{{ route('storeFront') }}" 
               class="inline-flex items-center px-8 py-4 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 min-w-[200px]">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Về trang chủ
            </a>
            
            <button onclick="location.reload()" 
                    class="inline-flex items-center px-8 py-4 bg-white text-red-600 font-semibold rounded-lg border-2 border-red-600 hover:bg-red-50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 min-w-[200px]">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Thử lại
            </button>
        </div>

        <!-- Status Info -->
        <div class="bg-white rounded-2xl shadow-xl p-8 max-w-2xl mx-auto mb-8">
            <div class="flex items-center justify-center mb-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <h2 class="text-xl font-bold text-gray-900 mb-3 font-montserrat">
                Thông tin sự cố
            </h2>
            <div class="text-left space-y-2 text-gray-600">
                <p><strong>Thời gian:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
                <p><strong>Mã lỗi:</strong> 500 - Internal Server Error</p>
                <p><strong>Trạng thái:</strong> <span class="text-yellow-600 font-medium">Đang khắc phục</span></p>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="text-center">
            <p class="text-gray-600 mb-4">
                Nếu sự cố vẫn tiếp tục, vui lòng liên hệ với chúng tôi:
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                @if(isset($settings->phone) && !empty($settings->phone))
                <a href="tel:{{ $settings->phone }}" 
                   class="inline-flex items-center text-red-600 hover:text-red-700 font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    {{ $settings->phone }}
                </a>
                @endif
                
                @if(isset($settings->email) && !empty($settings->email))
                <a href="mailto:{{ $settings->email }}" 
                   class="inline-flex items-center text-red-600 hover:text-red-700 font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    {{ $settings->email }}
                </a>
                @endif
            </div>
        </div>

        <!-- Technical Details (for development) -->
        @if(config('app.debug'))
        <div class="mt-8 bg-gray-100 rounded-lg p-4 text-left">
            <h3 class="font-bold text-gray-900 mb-2">Chi tiết kỹ thuật (Development Mode):</h3>
            <div class="text-sm text-gray-700 space-y-1">
                <p><strong>URL:</strong> {{ request()->fullUrl() }}</p>
                <p><strong>Method:</strong> {{ request()->method() }}</p>
                <p><strong>User Agent:</strong> {{ request()->userAgent() }}</p>
                <p><strong>IP:</strong> {{ request()->ip() }}</p>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // Auto refresh after 30 seconds
    setTimeout(function() {
        if (confirm('Trang sẽ được tải lại để thử kết nối. Bạn có muốn tiếp tục?')) {
            location.reload();
        }
    }, 30000);

    // Add some interactive animations
    document.addEventListener('DOMContentLoaded', function() {
        // Animate warning icon
        const warningIcon = document.querySelector('.animate-pulse');
        if (warningIcon) {
            setInterval(() => {
                warningIcon.style.transform = `scale(${1 + Math.sin(Date.now() / 1000) * 0.05})`;
            }, 50);
        }
    });
</script>
@endpush
@endsection
