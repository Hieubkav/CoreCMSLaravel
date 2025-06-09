@extends('setup.layout')

@section('title', 'Module: Web Design Management - Core Framework Setup')
@section('description', 'Hệ thống quản lý thiết kế website: Themes, Colors, Layouts, CSS Customization')

@section('content')
<div class="text-center mb-8">
    <div class="w-16 h-16 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-paint-brush text-2xl text-pink-600"></i>
    </div>
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Web Design Management Module</h2>
    <p class="text-gray-600">
        Hệ thống quản lý thiết kế website hoàn chỉnh với theme editor, color management và CSS customization.
    </p>
</div>

<!-- Module Configuration Form -->
<form id="module-form" onsubmit="configureModule(event)">
    <div class="space-y-8">
        
        <!-- Module Enable/Disable -->
        <div class="border border-gray-200 rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-toggle-on text-pink-600 mr-2"></i>
                    Cài đặt Module
                </h3>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="enable_module" name="enable_module" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-pink-600"></div>
                    <span class="ml-3 text-sm font-medium text-gray-900">Bật module này</span>
                </label>
            </div>
            
            <div id="module-content" class="space-y-6">
                <!-- Module Description -->
                <div class="bg-pink-50 border border-pink-200 rounded-lg p-4">
                    <h4 class="font-semibold text-pink-800 mb-2">Tính năng của module:</h4>
                    <ul class="text-pink-700 text-sm space-y-1">
                        <li>• <strong>Theme Management:</strong> Tạo, chỉnh sửa và quản lý themes</li>
                        <li>• <strong>Color Palette:</strong> Quản lý bảng màu và color schemes</li>
                        <li>• <strong>Layout Builder:</strong> Drag & drop layout builder</li>
                        <li>• <strong>CSS Editor:</strong> Live CSS editor với preview</li>
                        <li>• <strong>Font Management:</strong> Upload và quản lý fonts</li>
                        <li>• <strong>Design Templates:</strong> Pre-built design templates</li>
                    </ul>
                </div>

                <!-- Design Features Selection -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Chọn tính năng thiết kế cần cài đặt:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_theme_manager" 
                                       name="enable_theme_manager" 
                                       checked
                                       class="h-4 w-4 text-pink-600 focus:ring-pink-500 border-gray-300 rounded">
                                <label for="enable_theme_manager" class="ml-2 block text-sm text-gray-900">
                                    <strong>Theme Manager</strong> - Quản lý themes
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_color_palette" 
                                       name="enable_color_palette" 
                                       checked
                                       class="h-4 w-4 text-pink-600 focus:ring-pink-500 border-gray-300 rounded">
                                <label for="enable_color_palette" class="ml-2 block text-sm text-gray-900">
                                    <strong>Color Palette</strong> - Bảng màu
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_layout_builder" 
                                       name="enable_layout_builder" 
                                       class="h-4 w-4 text-pink-600 focus:ring-pink-500 border-gray-300 rounded">
                                <label for="enable_layout_builder" class="ml-2 block text-sm text-gray-900">
                                    <strong>Layout Builder</strong> - Drag & drop
                                </label>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_css_editor" 
                                       name="enable_css_editor" 
                                       checked
                                       class="h-4 w-4 text-pink-600 focus:ring-pink-500 border-gray-300 rounded">
                                <label for="enable_css_editor" class="ml-2 block text-sm text-gray-900">
                                    <strong>CSS Editor</strong> - Live CSS editing
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_font_manager" 
                                       name="enable_font_manager" 
                                       checked
                                       class="h-4 w-4 text-pink-600 focus:ring-pink-500 border-gray-300 rounded">
                                <label for="enable_font_manager" class="ml-2 block text-sm text-gray-900">
                                    <strong>Font Manager</strong> - Quản lý fonts
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_design_templates" 
                                       name="enable_design_templates" 
                                       checked
                                       class="h-4 w-4 text-pink-600 focus:ring-pink-500 border-gray-300 rounded">
                                <label for="enable_design_templates" class="ml-2 block text-sm text-gray-900">
                                    <strong>Design Templates</strong> - Templates có sẵn
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Theme Configuration -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Cấu hình Theme mặc định:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="default_theme" class="block text-sm font-medium text-gray-700 mb-2">
                                Theme mặc định
                            </label>
                            <select id="default_theme" name="default_theme" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <option value="minimalist" selected>Minimalist - Tối giản</option>
                                <option value="modern">Modern - Hiện đại</option>
                                <option value="classic">Classic - Cổ điển</option>
                                <option value="creative">Creative - Sáng tạo</option>
                            </select>
                        </div>
                        <div>
                            <label for="color_scheme" class="block text-sm font-medium text-gray-700 mb-2">
                                Color Scheme
                            </label>
                            <select id="color_scheme" name="color_scheme" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <option value="red-white" selected>Red & White</option>
                                <option value="blue-gray">Blue & Gray</option>
                                <option value="green-white">Green & White</option>
                                <option value="purple-white">Purple & White</option>
                                <option value="custom">Custom Colors</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Design Tools -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Design Tools sẽ được tích hợp:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                            <input type="checkbox" 
                                   id="tool_color_picker" 
                                   name="design_tools[]" 
                                   value="color_picker"
                                   checked
                                   class="h-4 w-4 text-pink-600 focus:ring-pink-500 border-gray-300 rounded">
                            <label for="tool_color_picker" class="ml-2 block text-sm text-gray-900">
                                <strong>Color Picker</strong> - Chọn màu trực quan
                            </label>
                        </div>
                        
                        <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                            <input type="checkbox" 
                                   id="tool_gradient_generator" 
                                   name="design_tools[]" 
                                   value="gradient_generator"
                                   class="h-4 w-4 text-pink-600 focus:ring-pink-500 border-gray-300 rounded">
                            <label for="tool_gradient_generator" class="ml-2 block text-sm text-gray-900">
                                <strong>Gradient Generator</strong> - Tạo gradient
                            </label>
                        </div>
                        
                        <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                            <input type="checkbox" 
                                   id="tool_shadow_generator" 
                                   name="design_tools[]" 
                                   value="shadow_generator"
                                   checked
                                   class="h-4 w-4 text-pink-600 focus:ring-pink-500 border-gray-300 rounded">
                            <label for="tool_shadow_generator" class="ml-2 block text-sm text-gray-900">
                                <strong>Shadow Generator</strong> - Tạo box-shadow
                            </label>
                        </div>
                        
                        <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                            <input type="checkbox" 
                                   id="tool_border_radius" 
                                   name="design_tools[]" 
                                   value="border_radius"
                                   checked
                                   class="h-4 w-4 text-pink-600 focus:ring-pink-500 border-gray-300 rounded">
                            <label for="tool_border_radius" class="ml-2 block text-sm text-gray-900">
                                <strong>Border Radius Tool</strong> - Bo góc
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Admin Resources -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Filament Resources sẽ được tạo:</h4>
                    <div class="space-y-2">
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-palette text-pink-500 mr-3"></i>
                            <span class="text-sm"><strong>ThemeResource:</strong> Quản lý themes và templates</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-tint text-blue-500 mr-3"></i>
                            <span class="text-sm"><strong>ColorPaletteResource:</strong> Quản lý bảng màu</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-code text-green-500 mr-3"></i>
                            <span class="text-sm"><strong>CustomCSSResource:</strong> CSS customization</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-font text-purple-500 mr-3"></i>
                            <span class="text-sm"><strong>FontResource:</strong> Font management</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-th-large text-orange-500 mr-3"></i>
                            <span class="text-sm"><strong>LayoutResource:</strong> Layout templates</span>
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
                               class="h-4 w-4 text-pink-600 focus:ring-pink-500 border-gray-300 rounded">
                        <label for="create_sample_data" class="ml-2 block text-sm font-medium text-gray-900">
                            Tạo dữ liệu mẫu
                        </label>
                    </div>
                    
                    <div id="sample-data-options" class="ml-6 space-y-2 text-sm text-gray-600">
                        <div>• 5 theme templates có sẵn</div>
                        <div>• 10 color palettes đa dạng</div>
                        <div>• Custom CSS examples</div>
                        <div>• Google Fonts integration</div>
                        <div>• Layout templates cho các page types</div>
                    </div>
                </div>

                <!-- Advanced Options -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Tùy chọn nâng cao:</h4>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="enable_live_preview" 
                                   name="enable_live_preview" 
                                   checked
                                   class="h-4 w-4 text-pink-600 focus:ring-pink-500 border-gray-300 rounded">
                            <label for="enable_live_preview" class="ml-2 block text-sm text-gray-900">
                                Live preview khi chỉnh sửa design
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="enable_version_control" 
                                   name="enable_version_control" 
                                   class="h-4 w-4 text-pink-600 focus:ring-pink-500 border-gray-300 rounded">
                            <label for="enable_version_control" class="ml-2 block text-sm text-gray-900">
                                Version control cho design changes
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="enable_responsive_preview" 
                                   name="enable_responsive_preview" 
                                   checked
                                   class="h-4 w-4 text-pink-600 focus:ring-pink-500 border-gray-300 rounded">
                            <label for="enable_responsive_preview" class="ml-2 block text-sm text-gray-900">
                                Responsive preview (mobile, tablet, desktop)
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Warning Notice -->
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-orange-600 mt-1 mr-3"></i>
                        <div>
                            <h4 class="font-semibold text-orange-800">Lưu ý quan trọng</h4>
                            <p class="text-orange-700 text-sm mt-1">
                                Web Design Management là module phức tạp với nhiều tính năng nâng cao. 
                                Đảm bảo server có đủ tài nguyên và bạn hiểu rõ về CSS/HTML.
                            </p>
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
                        Web Design Management cung cấp khả năng tùy chỉnh giao diện mạnh mẽ. 
                        Khuyến nghị cài đặt để có control hoàn toàn về thiết kế.
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
                    class="px-8 py-3 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors">
                <i class="fas fa-check mr-2"></i>
                Cài đặt module
            </button>
        </div>
    </div>
</form>

<!-- Navigation -->
<div id="navigation-section" class="hidden border-t pt-6 mt-6">
    <div class="flex justify-between">
        <a href="{{ route('setup.step', 'module-settings') }}" 
           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Quay lại
        </a>
        
        <button onclick="goToNextStep('module-advanced')"
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
    data.enable_theme_manager = document.getElementById('enable_theme_manager').checked;
    data.enable_color_palette = document.getElementById('enable_color_palette').checked;
    data.enable_layout_builder = document.getElementById('enable_layout_builder').checked;
    data.enable_css_editor = document.getElementById('enable_css_editor').checked;
    data.enable_font_manager = document.getElementById('enable_font_manager').checked;
    data.enable_design_templates = document.getElementById('enable_design_templates').checked;
    data.enable_live_preview = document.getElementById('enable_live_preview').checked;
    data.enable_version_control = document.getElementById('enable_version_control').checked;
    data.enable_responsive_preview = document.getElementById('enable_responsive_preview').checked;
    
    // Handle design tools
    const designTools = [];
    document.querySelectorAll('input[name="design_tools[]"]:checked').forEach(checkbox => {
        designTools.push(checkbox.value);
    });
    data.design_tools = designTools;
    
    data.module_key = 'web_design_management';
    
    showLoading('Đang cấu hình Web Design Management module...');
    
    submitStep('{{ route('setup.process', 'module-webdesign') }}', data, (response) => {
        document.getElementById('navigation-section').classList.remove('hidden');
        
        // Auto proceed to next step after 2 seconds
        setTimeout(() => {
            goToNextStep('module-advanced');
        }, 2000);
    });
}

function skipModule() {
    const data = {
        enable_module: false,
        create_sample_data: false,
        module_key: 'web_design_management',
        skipped: true
    };
    
    showLoading('Đang bỏ qua module...');
    
    submitStep('{{ route('setup.process', 'module-webdesign') }}', data, (response) => {
        document.getElementById('navigation-section').classList.remove('hidden');
        
        // Auto proceed to next step after 1 second
        setTimeout(() => {
            goToNextStep('module-advanced');
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
    
    console.log('Web Design Management module page loaded');
});
</script>
@endpush
