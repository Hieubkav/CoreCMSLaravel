@extends('layouts.shop')

@section('title', 'Cấu hình Hệ thống - Core Framework')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Cấu hình Hệ thống</h1>
                    <p class="text-gray-600 mt-1">
                        Tùy chỉnh giao diện và cấu hình hệ thống (chỉ trong môi trường development)
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('filament.admin.pages.dashboard') }}"
                       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-cog mr-2"></i>
                        Admin Panel
                    </a>
                    <form action="{{ route('system-config.reset') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Bạn có chắc muốn reset về cấu hình mặc định?')"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-undo mr-2"></i>
                            Reset
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Environment Notice -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
                <div>
                    <h3 class="text-yellow-800 font-semibold">Development Mode</h3>
                    <p class="text-yellow-700 text-sm">
                        Trang này chỉ có thể truy cập trong môi trường <strong>local</strong>. 
                        Trong production, cấu hình hệ thống chỉ có thể thay đổi qua Admin Panel.
                    </p>
                </div>
            </div>
        </div>

        <!-- Configuration Form -->
        <form action="{{ route('system-config.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Theme Configuration -->
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-paint-brush text-blue-600 mr-2"></i>
                    Cấu hình Theme
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Chế độ Theme</label>
                        <select name="theme_mode" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            @foreach(\App\Models\SystemConfiguration::getThemeModeOptions() as $value => $label)
                                <option value="{{ $value }}" {{ $config->theme_mode === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phong cách Thiết kế</label>
                        <select name="design_style" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            @foreach(\App\Models\SystemConfiguration::getDesignStyleOptions() as $value => $label)
                                <option value="{{ $value }}" {{ $config->design_style === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Color Configuration -->
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-palette text-green-600 mr-2"></i>
                    Cấu hình Màu sắc
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Màu Chính</label>
                        <div class="flex items-center space-x-3">
                            <input type="color" 
                                   name="primary_color" 
                                   value="{{ $config->primary_color }}"
                                   class="w-12 h-10 border border-gray-300 rounded">
                            <input type="text" 
                                   value="{{ $config->primary_color }}"
                                   class="flex-1 border border-gray-300 rounded-lg px-3 py-2"
                                   readonly>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Màu cho buttons, links, highlights</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Màu Phụ</label>
                        <div class="flex items-center space-x-3">
                            <input type="color" 
                                   name="secondary_color" 
                                   value="{{ $config->secondary_color }}"
                                   class="w-12 h-10 border border-gray-300 rounded">
                            <input type="text" 
                                   value="{{ $config->secondary_color }}"
                                   class="flex-1 border border-gray-300 rounded-lg px-3 py-2"
                                   readonly>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Màu nền chính</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Màu Nhấn</label>
                        <div class="flex items-center space-x-3">
                            <input type="color" 
                                   name="accent_color" 
                                   value="{{ $config->accent_color }}"
                                   class="w-12 h-10 border border-gray-300 rounded">
                            <input type="text" 
                                   value="{{ $config->accent_color }}"
                                   class="flex-1 border border-gray-300 rounded-lg px-3 py-2"
                                   readonly>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Màu cho borders, backgrounds nhẹ</p>
                    </div>
                </div>
            </div>

            <!-- Font Configuration -->
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-font text-purple-600 mr-2"></i>
                    Cấu hình Typography
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Font Chính</label>
                        <select name="primary_font" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            @foreach(\App\Models\SystemConfiguration::getFontOptions() as $value => $label)
                                <option value="{{ $value }}" {{ $config->primary_font === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Font Phụ</label>
                        <select name="secondary_font" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            @foreach(\App\Models\SystemConfiguration::getFontOptions() as $value => $label)
                                <option value="{{ $value }}" {{ $config->secondary_font === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Font Bổ sung</label>
                        <select name="tertiary_font" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            @foreach(\App\Models\SystemConfiguration::getFontOptions() as $value => $label)
                                <option value="{{ $value }}" {{ $config->tertiary_font === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- System Features -->
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-cogs text-gray-600 mr-2"></i>
                    Tính năng Hệ thống
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hệ thống Icon</label>
                        <select name="icon_system" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            @foreach(\App\Models\SystemConfiguration::getIconSystemOptions() as $value => $label)
                                <option value="{{ $value }}" {{ $config->icon_system === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="visitor_analytics_enabled" 
                                   value="1"
                                   {{ $config->visitor_analytics_enabled ? 'checked' : '' }}
                                   class="mr-2 text-blue-600">
                            <span class="text-sm font-medium text-gray-700">Bật Analytics</span>
                        </label>
                        <p class="text-xs text-gray-500 mt-1">Theo dõi thống kê visitor</p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Lưu Cấu hình
                </button>
            </div>
        </form>

        <!-- Current Configuration Preview -->
        <div class="bg-white rounded-lg shadow-sm border p-6 mt-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-eye text-indigo-600 mr-2"></i>
                Preview Cấu hình Hiện tại
            </h2>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-4 border rounded-lg">
                    <div class="w-8 h-8 mx-auto mb-2 rounded" style="background-color: {{ $config->primary_color }}"></div>
                    <p class="text-xs text-gray-600">Màu Chính</p>
                    <p class="text-xs font-mono">{{ $config->primary_color }}</p>
                </div>
                
                <div class="text-center p-4 border rounded-lg">
                    <div class="w-8 h-8 mx-auto mb-2 rounded border" style="background-color: {{ $config->secondary_color }}"></div>
                    <p class="text-xs text-gray-600">Màu Phụ</p>
                    <p class="text-xs font-mono">{{ $config->secondary_color }}</p>
                </div>
                
                <div class="text-center p-4 border rounded-lg">
                    <div class="w-8 h-8 mx-auto mb-2 rounded" style="background-color: {{ $config->accent_color }}"></div>
                    <p class="text-xs text-gray-600">Màu Nhấn</p>
                    <p class="text-xs font-mono">{{ $config->accent_color }}</p>
                </div>
                
                <div class="text-center p-4 border rounded-lg">
                    <p class="text-sm font-medium mb-1" style="font-family: {{ $config->primary_font }}">Aa</p>
                    <p class="text-xs text-gray-600">{{ $config->primary_font }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
        <i class="fas fa-check mr-2"></i>
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
        <i class="fas fa-times mr-2"></i>
        {{ session('error') }}
    </div>
@endif

<script>
// Auto-hide notifications
setTimeout(() => {
    const notifications = document.querySelectorAll('.fixed.bottom-4');
    notifications.forEach(notification => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    });
}, 3000);

// Color picker sync
document.querySelectorAll('input[type="color"]').forEach(colorInput => {
    colorInput.addEventListener('change', function() {
        const textInput = this.nextElementSibling;
        textInput.value = this.value;
    });
});
</script>
@endsection
