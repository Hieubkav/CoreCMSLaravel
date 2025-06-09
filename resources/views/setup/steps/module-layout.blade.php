@extends('setup.layout')

@section('title', 'Module: Layout Components - Core Framework Setup')
@section('description', 'Cài đặt các thành phần layout: Header, Footer, Navigation, Sidebar')

@section('content')
<div class="text-center mb-8">
    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-th-large text-2xl text-indigo-600"></i>
    </div>
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Layout Components Module</h2>
    <p class="text-gray-600">
        Các thành phần layout cơ bản: Header, Footer, Navigation, Sidebar và Menu system.
    </p>
</div>

<!-- Module Configuration Form -->
<form id="module-form" onsubmit="configureModule(event)">
    <div class="space-y-8">
        
        <!-- Module Enable/Disable -->
        <div class="border border-gray-200 rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-toggle-on text-indigo-600 mr-2"></i>
                    Cài đặt Module
                </h3>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="enable_module" name="enable_module" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    <span class="ml-3 text-sm font-medium text-gray-900">Bật module này</span>
                </label>
            </div>
            
            <div id="module-content" class="space-y-6">
                <!-- Module Description -->
                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                    <h4 class="font-semibold text-indigo-800 mb-2">Tính năng của module:</h4>
                    <ul class="text-indigo-700 text-sm space-y-1">
                        <li>• <strong>Header Component:</strong> Logo, navigation, search, user menu</li>
                        <li>• <strong>Footer Component:</strong> Links, contact info, social media</li>
                        <li>• <strong>Navigation System:</strong> Multi-level menu với dropdown</li>
                        <li>• <strong>Sidebar Components:</strong> Widget areas, filters</li>
                        <li>• <strong>Breadcrumb:</strong> Navigation breadcrumb tự động</li>
                        <li>• <strong>Mobile Menu:</strong> Responsive hamburger menu</li>
                    </ul>
                </div>

                <!-- Layout Components Selection -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Chọn components cần cài đặt:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_header" 
                                       name="enable_header" 
                                       checked
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="enable_header" class="ml-2 block text-sm text-gray-900">
                                    <strong>Header Component</strong> - Logo, nav, search
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_footer" 
                                       name="enable_footer" 
                                       checked
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="enable_footer" class="ml-2 block text-sm text-gray-900">
                                    <strong>Footer Component</strong> - Links, contact
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_navigation" 
                                       name="enable_navigation" 
                                       checked
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="enable_navigation" class="ml-2 block text-sm text-gray-900">
                                    <strong>Navigation System</strong> - Multi-level menu
                                </label>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_sidebar" 
                                       name="enable_sidebar" 
                                       checked
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="enable_sidebar" class="ml-2 block text-sm text-gray-900">
                                    <strong>Sidebar Components</strong> - Widget areas
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_breadcrumb" 
                                       name="enable_breadcrumb" 
                                       checked
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="enable_breadcrumb" class="ml-2 block text-sm text-gray-900">
                                    <strong>Breadcrumb</strong> - Navigation trail
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_mobile_menu" 
                                       name="enable_mobile_menu" 
                                       checked
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="enable_mobile_menu" class="ml-2 block text-sm text-gray-900">
                                    <strong>Mobile Menu</strong> - Responsive menu
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Header Configuration -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Cấu hình Header:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="header_style" class="block text-sm font-medium text-gray-700 mb-2">
                                Kiểu Header
                            </label>
                            <select id="header_style" name="header_style" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <option value="classic">Classic - Logo trái, menu phải</option>
                                <option value="centered" selected>Centered - Logo giữa, menu dưới</option>
                                <option value="minimal">Minimal - Logo trái, menu inline</option>
                                <option value="modern">Modern - Logo lớn, menu overlay</option>
                            </select>
                        </div>
                        <div>
                            <label for="header_position" class="block text-sm font-medium text-gray-700 mb-2">
                                Vị trí Header
                            </label>
                            <select id="header_position" name="header_position" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <option value="static" selected>Static - Bình thường</option>
                                <option value="sticky">Sticky - Dính khi scroll</option>
                                <option value="fixed">Fixed - Cố định</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Footer Configuration -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Cấu hình Footer:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="footer_columns" class="block text-sm font-medium text-gray-700 mb-2">
                                Số cột Footer
                            </label>
                            <select id="footer_columns" name="footer_columns" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <option value="1">1 cột</option>
                                <option value="2">2 cột</option>
                                <option value="3" selected>3 cột</option>
                                <option value="4">4 cột</option>
                            </select>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="footer_social" 
                                       name="footer_social" 
                                       checked
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="footer_social" class="ml-2 block text-sm text-gray-900">
                                    Hiển thị social media links
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="footer_newsletter" 
                                       name="footer_newsletter" 
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="footer_newsletter" class="ml-2 block text-sm text-gray-900">
                                    Form đăng ký newsletter
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Frontend Components -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Components sẽ được tạo:</h4>
                    <div class="space-y-2">
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-header text-indigo-500 mr-3"></i>
                            <span class="text-sm"><strong>header.blade.php:</strong> Header component với navigation</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-bars text-blue-500 mr-3"></i>
                            <span class="text-sm"><strong>navigation.blade.php:</strong> Multi-level menu system</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-mobile-alt text-green-500 mr-3"></i>
                            <span class="text-sm"><strong>mobile-menu.blade.php:</strong> Responsive mobile menu</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-columns text-purple-500 mr-3"></i>
                            <span class="text-sm"><strong>sidebar.blade.php:</strong> Sidebar với widget areas</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-shoe-prints text-orange-500 mr-3"></i>
                            <span class="text-sm"><strong>breadcrumb.blade.php:</strong> Navigation breadcrumb</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-grip-horizontal text-gray-500 mr-3"></i>
                            <span class="text-sm"><strong>footer.blade.php:</strong> Footer với links và contact</span>
                        </div>
                    </div>
                </div>

                <!-- Sample Data Configuration -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <input type="checkbox" 
                               id="create_sample_data" 
                               name="create_sample_data" 
                               checked
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="create_sample_data" class="ml-2 block text-sm font-medium text-gray-900">
                            Tạo dữ liệu mẫu
                        </label>
                    </div>
                    
                    <div id="sample-data-options" class="ml-6 space-y-2 text-sm text-gray-600">
                        <div>• Menu items mẫu với 2-3 cấp</div>
                        <div>• Footer links và thông tin liên hệ</div>
                        <div>• Social media links mẫu</div>
                        <div>• Widget content cho sidebar</div>
                        <div>• Logo placeholder và branding</div>
                    </div>
                </div>

                <!-- Advanced Options -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Tùy chọn nâng cao:</h4>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="enable_search" 
                                   name="enable_search" 
                                   checked
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="enable_search" class="ml-2 block text-sm text-gray-900">
                                Bật search box trong header
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="enable_user_menu" 
                                   name="enable_user_menu" 
                                   checked
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="enable_user_menu" class="ml-2 block text-sm text-gray-900">
                                User menu (login/register/profile)
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="enable_mega_menu" 
                                   name="enable_mega_menu" 
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="enable_mega_menu" class="ml-2 block text-sm text-gray-900">
                                Mega menu cho navigation
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Skip Option -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-yellow-600 mt-1 mr-3"></i>
                <div>
                    <h4 class="font-semibold text-yellow-800">Module bắt buộc</h4>
                    <p class="text-yellow-700 text-sm mt-1">
                        Layout Components là module cơ bản cần thiết cho mọi website. 
                        Tuy nhiên bạn vẫn có thể bỏ qua nếu muốn tự tạo layout.
                    </p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center">
            <button type="button" 
                    onclick="skipModule()"
                    class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fas fa-skip-forward mr-2"></i>
                Bỏ qua module này
            </button>
            
            <button type="submit" 
                    class="px-8 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                <i class="fas fa-check mr-2"></i>
                Cài đặt module
            </button>
        </div>
    </div>
</form>

<!-- Navigation -->
<div id="navigation-section" class="hidden border-t pt-6 mt-6">
    <div class="flex justify-between">
        <a href="{{ route('setup.step', 'module-ecommerce') }}" 
           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Quay lại
        </a>
        
        <button onclick="goToNextStep('module-settings')"
                class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
            Tiếp theo
            <i class="fas fa-arrow-right ml-2"></i>
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
function configureModule(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());
    
    // Convert checkboxes to boolean
    data.enable_module = document.getElementById('enable_module').checked;
    data.create_sample_data = document.getElementById('create_sample_data').checked;
    data.enable_header = document.getElementById('enable_header').checked;
    data.enable_footer = document.getElementById('enable_footer').checked;
    data.enable_navigation = document.getElementById('enable_navigation').checked;
    data.enable_sidebar = document.getElementById('enable_sidebar').checked;
    data.enable_breadcrumb = document.getElementById('enable_breadcrumb').checked;
    data.enable_mobile_menu = document.getElementById('enable_mobile_menu').checked;
    data.footer_social = document.getElementById('footer_social').checked;
    data.footer_newsletter = document.getElementById('footer_newsletter').checked;
    data.enable_search = document.getElementById('enable_search').checked;
    data.enable_user_menu = document.getElementById('enable_user_menu').checked;
    data.enable_mega_menu = document.getElementById('enable_mega_menu').checked;
    data.module_key = 'layout_components';
    
    showLoading('Đang cấu hình Layout Components module...');
    
    submitStep('{{ route('setup.process', 'module-layout') }}', data, (response) => {
        document.getElementById('navigation-section').classList.remove('hidden');
        
        // Auto proceed to next step after 2 seconds
        setTimeout(() => {
            goToNextStep('module-settings');
        }, 2000);
    });
}

function skipModule() {
    const data = {
        enable_module: false,
        create_sample_data: false,
        module_key: 'layout_components',
        skipped: true
    };
    
    showLoading('Đang bỏ qua module...');
    
    submitStep('{{ route('setup.process', 'module-layout') }}', data, (response) => {
        document.getElementById('navigation-section').classList.remove('hidden');
        
        // Auto proceed to next step after 1 second
        setTimeout(() => {
            goToNextStep('module-settings');
        }, 1000);
    });
}

// Toggle module content and sample data options
document.addEventListener('DOMContentLoaded', function() {
    const enableToggle = document.getElementById('enable_module');
    const moduleContent = document.getElementById('module-content');
    const sampleDataToggle = document.getElementById('create_sample_data');
    const sampleDataOptions = document.getElementById('sample-data-options');
    
    enableToggle.addEventListener('change', function() {
        if (this.checked) {
            moduleContent.style.display = 'block';
        } else {
            moduleContent.style.display = 'none';
        }
    });
    
    sampleDataToggle.addEventListener('change', function() {
        if (this.checked) {
            sampleDataOptions.style.display = 'block';
        } else {
            sampleDataOptions.style.display = 'none';
        }
    });
    
    console.log('Layout Components module page loaded');
});
</script>
@endpush
