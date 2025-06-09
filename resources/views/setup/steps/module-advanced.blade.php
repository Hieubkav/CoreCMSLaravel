@extends('setup.layout')

@section('title', 'Module: Advanced Features - Core Framework Setup')
@section('description', 'Tính năng nâng cao: Multi-language, Search Engine, Analytics, Automation')

@section('content')
<div class="text-center mb-8">
    <div class="w-16 h-16 bg-violet-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-rocket text-2xl text-violet-600"></i>
    </div>
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Advanced Features Module</h2>
    <p class="text-gray-600">
        Các tính năng nâng cao: Multi-language, Search Engine, Analytics, Automation và AI Integration.
    </p>
</div>

<!-- Module Configuration Form -->
<form id="module-form" onsubmit="configureModule(event)">
    <div class="space-y-8">
        
        <!-- Module Enable/Disable -->
        <div class="border border-gray-200 rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-toggle-on text-violet-600 mr-2"></i>
                    Cài đặt Module
                </h3>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="enable_module" name="enable_module" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-violet-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-violet-600"></div>
                    <span class="ml-3 text-sm font-medium text-gray-900">Bật module này</span>
                </label>
            </div>
            
            <div id="module-content" class="space-y-6" style="display: none;">
                <!-- Module Description -->
                <div class="bg-violet-50 border border-violet-200 rounded-lg p-4">
                    <h4 class="font-semibold text-violet-800 mb-2">Tính năng của module:</h4>
                    <ul class="text-violet-700 text-sm space-y-1">
                        <li>• <strong>Multi-language:</strong> Hỗ trợ đa ngôn ngữ với Laravel Localization</li>
                        <li>• <strong>Advanced Search:</strong> Full-text search với Elasticsearch/Algolia</li>
                        <li>• <strong>Analytics Integration:</strong> Google Analytics, Facebook Pixel</li>
                        <li>• <strong>Automation:</strong> Scheduled tasks và workflow automation</li>
                        <li>• <strong>AI Integration:</strong> ChatGPT, image recognition</li>
                        <li>• <strong>Performance Monitoring:</strong> Real-time performance tracking</li>
                    </ul>
                </div>

                <!-- Advanced Features Selection -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Chọn tính năng nâng cao cần cài đặt:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_multilanguage" 
                                       name="enable_multilanguage" 
                                       class="h-4 w-4 text-violet-600 focus:ring-violet-500 border-gray-300 rounded">
                                <label for="enable_multilanguage" class="ml-2 block text-sm text-gray-900">
                                    <strong>Multi-language</strong> - Đa ngôn ngữ
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_advanced_search" 
                                       name="enable_advanced_search" 
                                       class="h-4 w-4 text-violet-600 focus:ring-violet-500 border-gray-300 rounded">
                                <label for="enable_advanced_search" class="ml-2 block text-sm text-gray-900">
                                    <strong>Advanced Search</strong> - Tìm kiếm nâng cao
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_analytics" 
                                       name="enable_analytics" 
                                       checked
                                       class="h-4 w-4 text-violet-600 focus:ring-violet-500 border-gray-300 rounded">
                                <label for="enable_analytics" class="ml-2 block text-sm text-gray-900">
                                    <strong>Analytics Integration</strong> - Phân tích dữ liệu
                                </label>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_automation" 
                                       name="enable_automation" 
                                       class="h-4 w-4 text-violet-600 focus:ring-violet-500 border-gray-300 rounded">
                                <label for="enable_automation" class="ml-2 block text-sm text-gray-900">
                                    <strong>Automation</strong> - Tự động hóa
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_ai_integration" 
                                       name="enable_ai_integration" 
                                       class="h-4 w-4 text-violet-600 focus:ring-violet-500 border-gray-300 rounded">
                                <label for="enable_ai_integration" class="ml-2 block text-sm text-gray-900">
                                    <strong>AI Integration</strong> - Tích hợp AI
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_performance_monitoring" 
                                       name="enable_performance_monitoring" 
                                       checked
                                       class="h-4 w-4 text-violet-600 focus:ring-violet-500 border-gray-300 rounded">
                                <label for="enable_performance_monitoring" class="ml-2 block text-sm text-gray-900">
                                    <strong>Performance Monitoring</strong> - Giám sát hiệu suất
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Multi-language Configuration -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Cấu hình Multi-language:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="default_language" class="block text-sm font-medium text-gray-700 mb-2">
                                Ngôn ngữ mặc định
                            </label>
                            <select id="default_language" name="default_language" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <option value="vi" selected>Tiếng Việt</option>
                                <option value="en">English</option>
                                <option value="zh">中文</option>
                                <option value="ja">日本語</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Ngôn ngữ hỗ trợ:</label>
                            <div class="space-y-1">
                                <div class="flex items-center">
                                    <input type="checkbox" id="lang_en" name="supported_languages[]" value="en" class="h-4 w-4 text-violet-600">
                                    <label for="lang_en" class="ml-2 text-sm">English</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="checkbox" id="lang_zh" name="supported_languages[]" value="zh" class="h-4 w-4 text-violet-600">
                                    <label for="lang_zh" class="ml-2 text-sm">中文</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Analytics Configuration -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Cấu hình Analytics:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                            <input type="checkbox" 
                                   id="analytics_google" 
                                   name="analytics_providers[]" 
                                   value="google_analytics"
                                   checked
                                   class="h-4 w-4 text-violet-600 focus:ring-violet-500 border-gray-300 rounded">
                            <label for="analytics_google" class="ml-2 block text-sm text-gray-900">
                                <strong>Google Analytics</strong> - GA4
                            </label>
                        </div>
                        
                        <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                            <input type="checkbox" 
                                   id="analytics_facebook" 
                                   name="analytics_providers[]" 
                                   value="facebook_pixel"
                                   class="h-4 w-4 text-violet-600 focus:ring-violet-500 border-gray-300 rounded">
                            <label for="analytics_facebook" class="ml-2 block text-sm text-gray-900">
                                <strong>Facebook Pixel</strong> - Meta tracking
                            </label>
                        </div>
                        
                        <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                            <input type="checkbox" 
                                   id="analytics_hotjar" 
                                   name="analytics_providers[]" 
                                   value="hotjar"
                                   class="h-4 w-4 text-violet-600 focus:ring-violet-500 border-gray-300 rounded">
                            <label for="analytics_hotjar" class="ml-2 block text-sm text-gray-900">
                                <strong>Hotjar</strong> - Heatmaps & recordings
                            </label>
                        </div>
                        
                        <div class="flex items-center p-3 border border-gray-200 rounded-lg">
                            <input type="checkbox" 
                                   id="analytics_custom" 
                                   name="analytics_providers[]" 
                                   value="custom_tracking"
                                   class="h-4 w-4 text-violet-600 focus:ring-violet-500 border-gray-300 rounded">
                            <label for="analytics_custom" class="ml-2 block text-sm text-gray-900">
                                <strong>Custom Tracking</strong> - Tự định nghĩa
                            </label>
                        </div>
                    </div>
                </div>

                <!-- AI Integration Options -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">AI Integration Options:</h4>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="ai_chatbot" 
                                   name="ai_features[]" 
                                   value="chatbot"
                                   class="h-4 w-4 text-violet-600 focus:ring-violet-500 border-gray-300 rounded">
                            <label for="ai_chatbot" class="ml-2 block text-sm text-gray-900">
                                AI Chatbot - Hỗ trợ khách hàng tự động
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="ai_content_generation" 
                                   name="ai_features[]" 
                                   value="content_generation"
                                   class="h-4 w-4 text-violet-600 focus:ring-violet-500 border-gray-300 rounded">
                            <label for="ai_content_generation" class="ml-2 block text-sm text-gray-900">
                                AI Content Generation - Tạo nội dung tự động
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="ai_image_recognition" 
                                   name="ai_features[]" 
                                   value="image_recognition"
                                   class="h-4 w-4 text-violet-600 focus:ring-violet-500 border-gray-300 rounded">
                            <label for="ai_image_recognition" class="ml-2 block text-sm text-gray-900">
                                AI Image Recognition - Nhận diện hình ảnh
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Admin Resources -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Resources sẽ được tạo:</h4>
                    <div class="space-y-2">
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-globe text-violet-500 mr-3"></i>
                            <span class="text-sm"><strong>LanguageResource:</strong> Quản lý ngôn ngữ và translations</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-search text-blue-500 mr-3"></i>
                            <span class="text-sm"><strong>SearchConfigResource:</strong> Cấu hình search engine</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-chart-bar text-green-500 mr-3"></i>
                            <span class="text-sm"><strong>AnalyticsResource:</strong> Analytics tracking codes</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-robot text-purple-500 mr-3"></i>
                            <span class="text-sm"><strong>AutomationResource:</strong> Workflow automation</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-brain text-pink-500 mr-3"></i>
                            <span class="text-sm"><strong>AIConfigResource:</strong> AI integration settings</span>
                        </div>
                    </div>
                </div>

                <!-- Sample Data Configuration -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <input type="checkbox" 
                               id="create_sample_data" 
                               name="create_sample_data" 
                               class="h-4 w-4 text-violet-600 focus:ring-violet-500 border-gray-300 rounded">
                        <label for="create_sample_data" class="ml-2 block text-sm font-medium text-gray-900">
                            Tạo cấu hình mẫu
                        </label>
                    </div>
                    
                    <div id="sample-data-options" class="ml-6 space-y-2 text-sm text-gray-600" style="display: none;">
                        <div>• Translation files cho tiếng Anh</div>
                        <div>• Google Analytics demo tracking</div>
                        <div>• Search configuration examples</div>
                        <div>• Basic automation workflows</div>
                        <div>• AI integration examples</div>
                    </div>
                </div>

                <!-- Performance Warning -->
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-red-600 mt-1 mr-3"></i>
                        <div>
                            <h4 class="font-semibold text-red-800">Cảnh báo hiệu suất</h4>
                            <p class="text-red-700 text-sm mt-1">
                                Advanced Features module có thể ảnh hưởng đến hiệu suất website. 
                                Chỉ nên cài đặt khi thực sự cần thiết và server có đủ tài nguyên.
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
                    <h4 class="font-semibold text-yellow-800">Module tùy chọn</h4>
                    <p class="text-yellow-700 text-sm mt-1">
                        Advanced Features dành cho các dự án lớn có yêu cầu cao. 
                        Bạn có thể bỏ qua và cài đặt sau khi website đã ổn định.
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
                    class="px-8 py-3 bg-violet-600 text-white rounded-lg hover:bg-violet-700 transition-colors">
                <i class="fas fa-check mr-2"></i>
                Cài đặt module
            </button>
        </div>
    </div>
</form>

<!-- Navigation -->
<div id="navigation-section" class="hidden border-t pt-6 mt-6">
    <div class="flex justify-between">
        <a href="{{ route('setup.step', 'module-webdesign') }}" 
           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Quay lại
        </a>
        
        <button onclick="goToNextStep('modules-summary')"
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
    data.enable_multilanguage = document.getElementById('enable_multilanguage').checked;
    data.enable_advanced_search = document.getElementById('enable_advanced_search').checked;
    data.enable_analytics = document.getElementById('enable_analytics').checked;
    data.enable_automation = document.getElementById('enable_automation').checked;
    data.enable_ai_integration = document.getElementById('enable_ai_integration').checked;
    data.enable_performance_monitoring = document.getElementById('enable_performance_monitoring').checked;
    
    // Handle supported languages
    const supportedLanguages = [];
    document.querySelectorAll('input[name="supported_languages[]"]:checked').forEach(checkbox => {
        supportedLanguages.push(checkbox.value);
    });
    data.supported_languages = supportedLanguages;
    
    // Handle analytics providers
    const analyticsProviders = [];
    document.querySelectorAll('input[name="analytics_providers[]"]:checked').forEach(checkbox => {
        analyticsProviders.push(checkbox.value);
    });
    data.analytics_providers = analyticsProviders;
    
    // Handle AI features
    const aiFeatures = [];
    document.querySelectorAll('input[name="ai_features[]"]:checked').forEach(checkbox => {
        aiFeatures.push(checkbox.value);
    });
    data.ai_features = aiFeatures;
    
    data.module_key = 'advanced_features';
    
    showLoading('Đang cấu hình Advanced Features module...');
    
    submitStep('{{ route('setup.process', 'module-advanced') }}', data, (response) => {
        document.getElementById('navigation-section').classList.remove('hidden');
        
        // Auto proceed to next step after 2 seconds
        setTimeout(() => {
            goToNextStep('modules-summary');
        }, 2000);
    });
}

function skipModule() {
    const data = {
        enable_module: false,
        create_sample_data: false,
        module_key: 'advanced_features',
        skipped: true
    };
    
    showLoading('Đang bỏ qua module...');
    
    submitStep('{{ route('setup.process', 'module-advanced') }}', data, (response) => {
        document.getElementById('navigation-section').classList.remove('hidden');
        
        // Auto proceed to next step after 1 second
        setTimeout(() => {
            goToNextStep('modules-summary');
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
    
    console.log('Advanced Features module page loaded');
});
</script>
@endpush
