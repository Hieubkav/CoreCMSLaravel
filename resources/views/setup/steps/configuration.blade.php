@extends('setup.layout')

@section('title', 'Cấu hình nâng cao - Core Framework Setup')
@section('description', 'Cấu hình các tính năng nâng cao cho dự án của bạn')

@section('content')
<div class="text-center mb-8">
    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-palette text-2xl text-indigo-600"></i>
    </div>
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Cấu hình System & Theme</h2>
    <p class="text-gray-600">
        Tùy chỉnh giao diện, màu sắc, font chữ và các tính năng nâng cao cho dự án của bạn.
    </p>
</div>

<!-- Configuration Form -->
<form id="config-form" onsubmit="saveConfiguration(event)">
    <div class="space-y-8">

        <!-- Theme Configuration Section -->
        <div class="border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-paint-brush text-purple-600 mr-2"></i>
                Cấu hình Theme & Giao diện
            </h3>

            <!-- Theme Mode -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Chế độ Theme
                </label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="theme_mode" value="light_only" checked class="text-red-600 focus:ring-red-500">
                        <span class="ml-2 text-sm">Chỉ sáng</span>
                    </label>
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="theme_mode" value="dark_only" class="text-red-600 focus:ring-red-500">
                        <span class="ml-2 text-sm">Chỉ tối</span>
                    </label>
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="theme_mode" value="both" class="text-red-600 focus:ring-red-500">
                        <span class="ml-2 text-sm">Cả hai</span>
                    </label>
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="theme_mode" value="none" class="text-red-600 focus:ring-red-500">
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
                        <input type="radio" name="design_style" value="minimalism" checked class="text-red-600 focus:ring-red-500 mb-2">
                        <i class="fas fa-minus text-lg text-gray-600 mb-1"></i>
                        <span class="text-sm text-center">Minimalism</span>
                    </label>
                    <label class="flex flex-col items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="design_style" value="glassmorphism" class="text-red-600 focus:ring-red-500 mb-2">
                        <i class="fas fa-gem text-lg text-gray-600 mb-1"></i>
                        <span class="text-sm text-center">Glassmorphism</span>
                    </label>
                    <label class="flex flex-col items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="design_style" value="modern" class="text-red-600 focus:ring-red-500 mb-2">
                        <i class="fas fa-rocket text-lg text-gray-600 mb-1"></i>
                        <span class="text-sm text-center">Modern</span>
                    </label>
                    <label class="flex flex-col items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="design_style" value="classic" class="text-red-600 focus:ring-red-500 mb-2">
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
                        <input type="radio" name="icon_system" value="fontawesome" checked class="text-red-600 focus:ring-red-500 mr-3">
                        <div class="flex items-center">
                            <i class="fab fa-font-awesome text-lg text-gray-600 mr-2"></i>
                            <span class="text-sm">Font Awesome</span>
                        </div>
                    </label>
                    <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="icon_system" value="heroicons" class="text-red-600 focus:ring-red-500 mr-3">
                        <div class="flex items-center">
                            <i class="fas fa-icons text-lg text-gray-600 mr-2"></i>
                            <span class="text-sm">Heroicons</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- Admin Panel Configuration -->
        <div class="border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-user-cog text-indigo-600 mr-2"></i>
                Cấu hình Admin Panel
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

        <!-- Error Pages Configuration -->
        <div class="border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                Trang lỗi tùy chỉnh
            </h3>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="checkbox" name="error_pages[]" value="404" checked class="text-red-600 focus:ring-red-500">
                    <span class="ml-2 text-sm">404 - Không tìm thấy</span>
                </label>
                <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="checkbox" name="error_pages[]" value="500" checked class="text-red-600 focus:ring-red-500">
                    <span class="ml-2 text-sm">500 - Lỗi server</span>
                </label>
                <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="checkbox" name="error_pages[]" value="maintenance" class="text-red-600 focus:ring-red-500">
                    <span class="ml-2 text-sm">Bảo trì</span>
                </label>
                <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                    <input type="checkbox" name="error_pages[]" value="offline" class="text-red-600 focus:ring-red-500">
                    <span class="ml-2 text-sm">Offline</span>
                </label>
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
                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
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

        <!-- Image Processing Section -->
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
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
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
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
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
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
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
                           class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
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
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                           placeholder="Mô tả mặc định cho SEO">
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
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="query_cache" class="ml-2 block text-sm text-gray-900">
                            Bật cache query
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="eager_loading" 
                               name="eager_loading" 
                               checked
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="eager_loading" class="ml-2 block text-sm text-gray-900">
                            Bật eager loading
                        </label>
                    </div>
                    
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="asset_optimization" 
                               name="asset_optimization" 
                               checked
                               class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
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
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    </div>
                    
                    <div>
                        <label for="pagination_size" class="block text-sm font-medium text-gray-700 mb-2">
                            Số item mỗi trang
                        </label>
                        <select id="pagination_size" 
                                name="pagination_size"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <option value="10">10</option>
                            <option value="25" selected>25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Email Configuration -->
        <div class="border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-envelope text-orange-600 mr-2"></i>
                Cấu hình Email (Tùy chọn)
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="mail_host" class="block text-sm font-medium text-gray-700 mb-2">
                        SMTP Host
                    </label>
                    <input type="text" 
                           id="mail_host" 
                           name="mail_host" 
                           placeholder="smtp.gmail.com"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                </div>
                
                <div>
                    <label for="mail_port" class="block text-sm font-medium text-gray-700 mb-2">
                        SMTP Port
                    </label>
                    <input type="number" 
                           id="mail_port" 
                           name="mail_port" 
                           placeholder="587"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                </div>
                
                <div>
                    <label for="mail_username" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Username
                    </label>
                    <input type="email" 
                           id="mail_username" 
                           name="mail_username" 
                           placeholder="your-email@gmail.com"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                </div>
                
                <div>
                    <label for="mail_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Password
                    </label>
                    <input type="password" 
                           id="mail_password" 
                           name="mail_password" 
                           placeholder="App password hoặc mật khẩu email"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                </div>
            </div>
            
            <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-yellow-600 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-semibold text-yellow-800">Lưu ý về Email</h4>
                        <p class="text-yellow-700 text-sm mt-1">
                            Cấu hình email là tùy chọn. Nếu không cấu hình, hệ thống sẽ sử dụng log driver để ghi email vào file log.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" 
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors">
            <i class="fas fa-save mr-2"></i>
            Lưu cấu hình
        </button>
    </div>
</form>

<!-- Navigation -->
<div id="navigation-section" class="hidden border-t pt-6 mt-6">
    <div class="flex justify-between">
        <button onclick="history.back()" 
                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Quay lại
        </button>
        
        <button onclick="goToNextStep('sample-data')"
                class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
            Tiếp theo
            <i class="fas fa-arrow-right ml-2"></i>
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
function saveConfiguration(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());

    // Handle theme mode radio buttons
    data.theme_mode = document.querySelector('input[name="theme_mode"]:checked').value;
    data.design_style = document.querySelector('input[name="design_style"]:checked').value;
    data.icon_system = document.querySelector('input[name="icon_system"]:checked').value;

    // Handle error pages checkboxes
    const errorPages = [];
    document.querySelectorAll('input[name="error_pages[]"]:checked').forEach(checkbox => {
        errorPages.push(checkbox.value);
    });
    data.error_pages = errorPages;

    // Convert checkboxes to boolean
    data.visitor_analytics_enabled = document.getElementById('visitor_analytics_enabled').checked;
    data.seo_auto_generate = document.getElementById('seo_auto_generate').checked;
    data.query_cache = document.getElementById('query_cache').checked;
    data.eager_loading = document.getElementById('eager_loading').checked;
    data.asset_optimization = document.getElementById('asset_optimization').checked;

    showLoading('Đang lưu cấu hình System & Theme...');

    submitStep('{{ route('setup.process', 'configuration') }}', data, (response) => {
        document.getElementById('navigation-section').classList.remove('hidden');

        // Auto proceed to next step after 2 seconds
        setTimeout(() => {
            goToNextStep('sample-data');
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

// Load saved configuration if exists
document.addEventListener('DOMContentLoaded', function() {
    // Setup color picker synchronization
    const colorPairs = [
        ['primary_color', 'primary_color'],
        ['secondary_color', 'secondary_color'],
        ['accent_color', 'accent_color'],
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

    // Add preview functionality for theme changes
    document.querySelectorAll('input[name="theme_mode"], input[name="design_style"]').forEach(input => {
        input.addEventListener('change', function() {
            updatePreview();
        });
    });

    console.log('System Configuration page loaded with advanced options');
});

function updatePreview() {
    // This function can be expanded to show live preview of theme changes
    const themeMode = document.querySelector('input[name="theme_mode"]:checked').value;
    const designStyle = document.querySelector('input[name="design_style"]:checked').value;

    // You can add live preview logic here
    console.log('Theme preview updated:', { themeMode, designStyle });
}
</script>
@endpush
