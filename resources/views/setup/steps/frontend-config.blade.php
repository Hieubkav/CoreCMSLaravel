@extends('setup.layout')

@section('title', 'Cấu hình Frontend - Core Framework Setup')
@section('description', 'Tùy chỉnh theme, màu sắc và giao diện cho người dùng cuối')

@section('content')
<div class="text-center mb-8">
    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-palette text-2xl text-purple-600"></i>
    </div>
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Cấu hình Frontend</h2>
    <p class="text-gray-600">
        Tùy chỉnh giao diện, màu sắc và trải nghiệm người dùng cho website của bạn.
    </p>
</div>

<!-- Frontend Configuration Form -->
<form id="frontend-config-form" onsubmit="saveFrontendConfig(event)">
    <div class="space-y-8">
        
        <!-- Theme Configuration -->
        <div class="border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-paint-brush text-purple-600 mr-2"></i>
                Cấu hình Theme
            </h3>
            
            <!-- Theme Mode -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Chế độ Theme
                </label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="theme_mode" value="light_only" checked class="text-purple-600 focus:ring-purple-500">
                        <span class="ml-2 text-sm">Chỉ sáng</span>
                    </label>
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="theme_mode" value="dark_only" class="text-purple-600 focus:ring-purple-500">
                        <span class="ml-2 text-sm">Chỉ tối</span>
                    </label>
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="theme_mode" value="both" class="text-purple-600 focus:ring-purple-500">
                        <span class="ml-2 text-sm">Cả hai</span>
                    </label>
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="theme_mode" value="none" class="text-purple-600 focus:ring-purple-500">
                        <span class="ml-2 text-sm">Không có</span>
                    </label>
                </div>
            </div>

            <!-- Color Configuration -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Màu sắc chủ đạo
                </label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="primary_color" class="block text-sm text-gray-600 mb-2">Màu chính</label>
                        <div class="flex items-center space-x-2">
                            <input type="color" id="primary_color" name="primary_color" value="#dc2626" 
                                   class="w-12 h-10 border border-gray-300 rounded cursor-pointer">
                            <input type="text" value="#dc2626" 
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                   onchange="document.getElementById('primary_color').value = this.value">
                        </div>
                    </div>
                    <div>
                        <label for="secondary_color" class="block text-sm text-gray-600 mb-2">Màu phụ</label>
                        <div class="flex items-center space-x-2">
                            <input type="color" id="secondary_color" name="secondary_color" value="#1f2937" 
                                   class="w-12 h-10 border border-gray-300 rounded cursor-pointer">
                            <input type="text" value="#1f2937" 
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                   onchange="document.getElementById('secondary_color').value = this.value">
                        </div>
                    </div>
                    <div>
                        <label for="accent_color" class="block text-sm text-gray-600 mb-2">Màu nhấn</label>
                        <div class="flex items-center space-x-2">
                            <input type="color" id="accent_color" name="accent_color" value="#3b82f6" 
                                   class="w-12 h-10 border border-gray-300 rounded cursor-pointer">
                            <input type="text" value="#3b82f6" 
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                   onchange="document.getElementById('accent_color').value = this.value">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Font Configuration -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Cấu hình Font chữ
                </label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="primary_font" class="block text-sm text-gray-600 mb-2">Font chính</label>
                        <select id="primary_font" name="primary_font" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="Inter">Inter</option>
                            <option value="Roboto">Roboto</option>
                            <option value="Open Sans" selected>Open Sans</option>
                            <option value="Poppins">Poppins</option>
                            <option value="Nunito">Nunito</option>
                        </select>
                    </div>
                    <div>
                        <label for="secondary_font" class="block text-sm text-gray-600 mb-2">Font phụ</label>
                        <select id="secondary_font" name="secondary_font" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="Inter">Inter</option>
                            <option value="Roboto">Roboto</option>
                            <option value="Open Sans">Open Sans</option>
                            <option value="Poppins">Poppins</option>
                            <option value="Nunito" selected>Nunito</option>
                        </select>
                    </div>
                    <div>
                        <label for="tertiary_font" class="block text-sm text-gray-600 mb-2">Font tiêu đề</label>
                        <select id="tertiary_font" name="tertiary_font" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="Inter">Inter</option>
                            <option value="Roboto">Roboto</option>
                            <option value="Open Sans">Open Sans</option>
                            <option value="Poppins" selected>Poppins</option>
                            <option value="Nunito">Nunito</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Design Style -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Phong cách thiết kế
                </label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <label class="flex flex-col items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="design_style" value="minimalism" checked class="text-purple-600 focus:ring-purple-500 mb-2">
                        <i class="fas fa-minus text-lg text-gray-600 mb-1"></i>
                        <span class="text-sm text-center">Minimalism</span>
                    </label>
                    <label class="flex flex-col items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="design_style" value="glassmorphism" class="text-purple-600 focus:ring-purple-500 mb-2">
                        <i class="fas fa-gem text-lg text-gray-600 mb-1"></i>
                        <span class="text-sm text-center">Glassmorphism</span>
                    </label>
                    <label class="flex flex-col items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="design_style" value="modern" class="text-purple-600 focus:ring-purple-500 mb-2">
                        <i class="fas fa-rocket text-lg text-gray-600 mb-1"></i>
                        <span class="text-sm text-center">Modern</span>
                    </label>
                    <label class="flex flex-col items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="design_style" value="classic" class="text-purple-600 focus:ring-purple-500 mb-2">
                        <i class="fas fa-university text-lg text-gray-600 mb-1"></i>
                        <span class="text-sm text-center">Classic</span>
                    </label>
                </div>
            </div>

            <!-- Icon System -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Hệ thống Icon
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="icon_system" value="fontawesome" checked class="text-purple-600 focus:ring-purple-500 mr-3">
                        <div class="flex items-center">
                            <i class="fab fa-font-awesome text-lg text-gray-600 mr-2"></i>
                            <span class="text-sm">Font Awesome</span>
                        </div>
                    </label>
                    <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="icon_system" value="heroicons" class="text-purple-600 focus:ring-purple-500 mr-3">
                        <div class="flex items-center">
                            <i class="fas fa-icons text-lg text-gray-600 mr-2"></i>
                            <span class="text-sm">Heroicons</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Favicon Info -->
        <div class="border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-image text-blue-600 mr-2"></i>
                Favicon Website
            </h3>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-2"></i>
                    <div class="text-sm text-blue-800">
                        <p class="font-medium mb-1">Favicon tự động:</p>
                        <ul class="text-xs space-y-1">
                            <li>• Hệ thống sẽ tự động sử dụng <code>public/images/default_logo.ico</code></li>
                            <li>• Favicon sẽ được copy vào <code>public/favicon.ico</code> để browser detect</li>
                            <li>• Có thể thay đổi favicon sau trong admin panel</li>
                            <li>• Kích thước chuẩn: 32x32px hoặc 16x16px</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error Pages Configuration -->
        <div class="border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                Trang lỗi tùy chỉnh
            </h3>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="checkbox" name="error_pages[]" value="404" checked class="text-purple-600 focus:ring-purple-500">
                    <span class="ml-2 text-sm">404 - Không tìm thấy</span>
                </label>
                <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="checkbox" name="error_pages[]" value="500" checked class="text-purple-600 focus:ring-purple-500">
                    <span class="ml-2 text-sm">500 - Lỗi server</span>
                </label>
                <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="checkbox" name="error_pages[]" value="maintenance" class="text-purple-600 focus:ring-purple-500">
                    <span class="ml-2 text-sm">Bảo trì</span>
                </label>
                <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="checkbox" name="error_pages[]" value="offline" class="text-purple-600 focus:ring-purple-500">
                    <span class="ml-2 text-sm">Offline</span>
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" 
                class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors">
            <i class="fas fa-save mr-2"></i>
            Lưu cấu hình Frontend
        </button>
    </div>
</form>

<!-- Navigation -->
<div id="navigation-section" class="hidden border-t pt-6 mt-6">
    <div class="flex justify-between">
        <a href="{{ route('setup.step', 'sample-data') }}" 
           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Quay lại
        </a>
        
        <button onclick="goToNextStep('admin-config')"
                class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
            Tiếp theo
            <i class="fas fa-arrow-right ml-2"></i>
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
function saveFrontendConfig(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());

    // Handle radio buttons
    data.theme_mode = document.querySelector('input[name="theme_mode"]:checked').value;
    data.design_style = document.querySelector('input[name="design_style"]:checked').value;
    data.icon_system = document.querySelector('input[name="icon_system"]:checked').value;

    // Handle error pages checkboxes
    const errorPages = [];
    document.querySelectorAll('input[name="error_pages[]"]:checked').forEach(checkbox => {
        errorPages.push(checkbox.value);
    });
    data.error_pages = errorPages;

    showLoading('Đang lưu cấu hình Frontend...');

    submitStep('{{ route('setup.process', 'frontend-config') }}', data, (response) => {
        document.getElementById('navigation-section').classList.remove('hidden');

        // Auto proceed to next step after 2 seconds
        setTimeout(() => {
            goToNextStep('admin-config');
        }, 2000);
    });
}

// Color picker synchronization
function syncColorPicker(colorInput, textInput) {
    colorInput.addEventListener('change', function() {
        textInput.value = this.value;
    });
    
    textInput.addEventListener('change', function() {
        if (/^#[0-9A-F]{6}$/i.test(this.value)) {
            colorInput.value = this.value;
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Setup color picker synchronization
    const colorPairs = [
        ['primary_color', 'primary_color'],
        ['secondary_color', 'secondary_color'], 
        ['accent_color', 'accent_color']
    ];
    
    colorPairs.forEach(([colorId, textId]) => {
        const colorInput = document.getElementById(colorId);
        const textInput = colorInput.nextElementSibling;
        if (colorInput && textInput) {
            syncColorPicker(colorInput, textInput);
        }
    });
    
    console.log('Frontend Configuration page loaded');
});
</script>
@endpush
