@extends('setup.layout')

@section('title', 'Cấu hình Admin Dashboard - Core Framework Setup')
@section('description', 'Tùy chỉnh giao diện và tính năng cho admin panel')

@section('content')
<div class="text-center mb-8">
    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-user-cog text-2xl text-indigo-600"></i>
    </div>
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Cấu hình Admin Dashboard</h2>
    <p class="text-gray-600">
        Tùy chỉnh giao diện và tính năng cho bảng điều khiển quản trị.
    </p>
</div>

<!-- Admin Configuration Form -->
<form id="admin-config-form" onsubmit="saveAdminConfig(event)">
    <div class="space-y-8">
        
        <!-- Admin Panel Colors -->
        <div class="border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-palette text-indigo-600 mr-2"></i>
                Màu sắc Admin Panel
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="admin_primary_color" class="block text-sm text-gray-600 mb-2">Màu chính Admin</label>
                    <div class="flex items-center space-x-2">
                        <input type="color" id="admin_primary_color" name="admin_primary_color" value="#1f2937" 
                               class="w-12 h-10 border border-gray-300 rounded cursor-pointer">
                        <input type="text" value="#1f2937" 
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm"
                               onchange="document.getElementById('admin_primary_color').value = this.value">
                    </div>
                </div>
                <div>
                    <label for="admin_secondary_color" class="block text-sm text-gray-600 mb-2">Màu phụ Admin</label>
                    <div class="flex items-center space-x-2">
                        <input type="color" id="admin_secondary_color" name="admin_secondary_color" value="#374151" 
                               class="w-12 h-10 border border-gray-300 rounded cursor-pointer">
                        <input type="text" value="#374151" 
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm"
                               onchange="document.getElementById('admin_secondary_color').value = this.value">
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics & Tracking -->
        <div class="border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-chart-line text-green-600 mr-2"></i>
                Analytics & Tracking
            </h3>
            
            <div class="space-y-4">
                <div class="flex items-center">
                    <input type="checkbox" id="visitor_analytics_enabled" name="visitor_analytics_enabled" 
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="visitor_analytics_enabled" class="ml-2 block text-sm text-gray-900">
                        Bật theo dõi visitor analytics
                    </label>
                </div>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                        <div>
                            <h4 class="font-semibold text-blue-800">Về Visitor Analytics</h4>
                            <p class="text-blue-700 text-sm mt-1">
                                Tính năng này sẽ theo dõi số lượng visitor, page views và thống kê cơ bản. 
                                Dữ liệu được lưu trữ local, không chia sẻ với bên thứ ba.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Settings -->
        <div class="border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-tachometer-alt text-purple-600 mr-2"></i>
                Tối ưu hiệu suất
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="query_cache" 
                               name="query_cache" 
                               checked
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="query_cache" class="ml-2 block text-sm text-gray-900">
                            Bật cache query
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="eager_loading" 
                               name="eager_loading" 
                               checked
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="eager_loading" class="ml-2 block text-sm text-gray-900">
                            Bật eager loading
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="asset_optimization" 
                               name="asset_optimization" 
                               checked
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="asset_optimization" class="ml-2 block text-sm text-gray-900">
                            Tối ưu assets
                        </label>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label for="cache_duration" class="block text-sm font-medium text-gray-700 mb-2">
                            Thời gian cache (giây)
                        </label>
                        <input type="number" 
                               id="cache_duration" 
                               name="cache_duration" 
                               min="60" 
                               max="3600" 
                               value="300"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    
                    <div>
                        <label for="pagination_size" class="block text-sm font-medium text-gray-700 mb-2">
                            Số item mỗi trang
                        </label>
                        <select id="pagination_size" 
                                name="pagination_size"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="10">10</option>
                            <option value="25" selected>25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Processing -->
        <div class="border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-image text-green-600 mr-2"></i>
                Xử lý hình ảnh
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="webp_quality" class="block text-sm font-medium text-gray-700 mb-2">
                        Chất lượng WebP (%)
                    </label>
                    <input type="number" 
                           id="webp_quality" 
                           name="webp_quality" 
                           min="50" 
                           max="100" 
                           value="95"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="text-gray-500 text-xs mt-1">Khuyến nghị: 85-95</div>
                </div>
                
                <div>
                    <label for="max_width" class="block text-sm font-medium text-gray-700 mb-2">
                        Chiều rộng tối đa (px)
                    </label>
                    <input type="number" 
                           id="max_width" 
                           name="max_width" 
                           min="800" 
                           max="4000" 
                           value="1920"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                
                <div>
                    <label for="max_height" class="block text-sm font-medium text-gray-700 mb-2">
                        Chiều cao tối đa (px)
                    </label>
                    <input type="number" 
                           id="max_height" 
                           name="max_height" 
                           min="600" 
                           max="3000" 
                           value="1080"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
        </div>

        <!-- SEO Configuration -->
        <div class="border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-search text-blue-600 mr-2"></i>
                Cấu hình SEO
            </h3>
            
            <div class="space-y-4">
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="seo_auto_generate" 
                           name="seo_auto_generate" 
                           checked
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="seo_auto_generate" class="ml-2 block text-sm text-gray-900">
                        Tự động tạo meta tags SEO
                    </label>
                </div>
                
                <div>
                    <label for="default_description" class="block text-sm font-medium text-gray-700 mb-2">
                        Mô tả mặc định
                    </label>
                    <input type="text" 
                           id="default_description" 
                           name="default_description" 
                           value="Powered by Core Framework"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="Mô tả mặc định cho SEO">
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" 
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors">
            <i class="fas fa-save mr-2"></i>
            Lưu cấu hình Admin Dashboard
        </button>
    </div>
</form>

<!-- Navigation -->
<div id="navigation-section" class="hidden border-t pt-6 mt-6">
    <div class="flex justify-between">
        <a href="{{ route('setup.step', 'frontend-config') }}" 
           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Quay lại
        </a>
        
        <button onclick="goToNextStep('module-user-roles')"
                class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
            Tiếp theo
            <i class="fas fa-arrow-right ml-2"></i>
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
function saveAdminConfig(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());
    
    // Convert checkboxes to boolean
    data.visitor_analytics_enabled = document.getElementById('visitor_analytics_enabled').checked;
    data.seo_auto_generate = document.getElementById('seo_auto_generate').checked;
    data.query_cache = document.getElementById('query_cache').checked;
    data.eager_loading = document.getElementById('eager_loading').checked;
    data.asset_optimization = document.getElementById('asset_optimization').checked;
    
    showLoading('Đang lưu cấu hình Admin Dashboard...');
    
    submitStep('{{ route('setup.process', 'admin-config') }}', data, (response) => {
        document.getElementById('navigation-section').classList.remove('hidden');
        
        // Auto proceed to next step after 2 seconds
        setTimeout(() => {
            goToNextStep('module-user-roles');
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
        ['admin_primary_color', 'admin_primary_color'],
        ['admin_secondary_color', 'admin_secondary_color']
    ];
    
    colorPairs.forEach(([colorId, textId]) => {
        const colorInput = document.getElementById(colorId);
        const textInput = colorInput.nextElementSibling;
        if (colorInput && textInput) {
            syncColorPicker(colorInput, textInput);
        }
    });
    
    console.log('Admin Configuration page loaded');
});
</script>
@endpush
