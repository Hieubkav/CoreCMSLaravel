@extends('setup.layout')

@section('title', 'Module: Content Sections - Core Framework Setup')
@section('description', 'Cài đặt các thành phần nội dung: Slider, Gallery, FAQ, Testimonials')

@section('content')
<div class="text-center mb-8">
    <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-th-large text-2xl text-teal-600"></i>
    </div>
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Content Sections Module</h2>
    <p class="text-gray-600">
        Các thành phần nội dung thiết yếu cho website: Slider, Gallery, FAQ, Testimonials và nhiều hơn nữa.
    </p>
</div>

<!-- Module Configuration Form -->
<form id="module-form" onsubmit="configureModule(event)">
    <div class="space-y-8">
        
        <!-- Module Enable/Disable -->
        <div class="border border-gray-200 rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-toggle-on text-teal-600 mr-2"></i>
                    Cài đặt Module
                </h3>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="enable_module" name="enable_module" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-teal-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-600"></div>
                    <span class="ml-3 text-sm font-medium text-gray-900">Bật module này</span>
                </label>
            </div>
            
            <div id="module-content" class="space-y-6">
                <!-- Module Description -->
                <div class="bg-teal-50 border border-teal-200 rounded-lg p-4">
                    <h4 class="font-semibold text-teal-800 mb-2">Tính năng của module:</h4>
                    <ul class="text-teal-700 text-sm space-y-1">
                        <li>• <strong>Hero Sliders:</strong> Banner chính với hiệu ứng chuyển động</li>
                        <li>• <strong>Image Gallery:</strong> Thư viện ảnh với lightbox</li>
                        <li>• <strong>FAQ System:</strong> Hệ thống hỏi đáp thông minh</li>
                        <li>• <strong>Testimonials:</strong> Đánh giá và phản hồi khách hàng</li>
                        <li>• <strong>Call-to-Action:</strong> Các section kêu gọi hành động</li>
                        <li>• <strong>Content Blocks:</strong> Khối nội dung tùy chỉnh</li>
                    </ul>
                </div>

                <!-- Content Components Selection -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Chọn components cần cài đặt:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_sliders" 
                                       name="enable_sliders" 
                                       checked
                                       class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                                <label for="enable_sliders" class="ml-2 block text-sm text-gray-900">
                                    <strong>Hero Sliders</strong> - Banner chính
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_gallery" 
                                       name="enable_gallery" 
                                       checked
                                       class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                                <label for="enable_gallery" class="ml-2 block text-sm text-gray-900">
                                    <strong>Image Gallery</strong> - Thư viện ảnh
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_faq" 
                                       name="enable_faq" 
                                       checked
                                       class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                                <label for="enable_faq" class="ml-2 block text-sm text-gray-900">
                                    <strong>FAQ System</strong> - Hỏi đáp
                                </label>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_testimonials" 
                                       name="enable_testimonials" 
                                       checked
                                       class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                                <label for="enable_testimonials" class="ml-2 block text-sm text-gray-900">
                                    <strong>Testimonials</strong> - Đánh giá
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_cta" 
                                       name="enable_cta" 
                                       checked
                                       class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                                <label for="enable_cta" class="ml-2 block text-sm text-gray-900">
                                    <strong>Call-to-Action</strong> - Kêu gọi hành động
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_content_blocks" 
                                       name="enable_content_blocks" 
                                       class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                                <label for="enable_content_blocks" class="ml-2 block text-sm text-gray-900">
                                    <strong>Content Blocks</strong> - Khối nội dung
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
                            <i class="fas fa-images text-teal-500 mr-3"></i>
                            <span class="text-sm"><strong>hero-slider.blade.php:</strong> Slider chính với Swiper.js</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-th text-blue-500 mr-3"></i>
                            <span class="text-sm"><strong>image-gallery.blade.php:</strong> Gallery với lightbox</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-question-circle text-green-500 mr-3"></i>
                            <span class="text-sm"><strong>faq-section.blade.php:</strong> FAQ với accordion</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-quote-left text-purple-500 mr-3"></i>
                            <span class="text-sm"><strong>testimonials.blade.php:</strong> Đánh giá khách hàng</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-bullhorn text-orange-500 mr-3"></i>
                            <span class="text-sm"><strong>cta-section.blade.php:</strong> Call-to-action sections</span>
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
                               class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                        <label for="create_sample_data" class="ml-2 block text-sm font-medium text-gray-900">
                            Tạo dữ liệu mẫu
                        </label>
                    </div>
                    
                    <div id="sample-data-options" class="ml-6 space-y-2 text-sm text-gray-600">
                        <div>• 5 hero sliders với hình ảnh đẹp</div>
                        <div>• 20 ảnh gallery từ picsum.photos</div>
                        <div>• 10 câu hỏi FAQ phổ biến</div>
                        <div>• 8 testimonials từ khách hàng</div>
                        <div>• 3 CTA sections mẫu</div>
                    </div>
                </div>

                <!-- Advanced Options -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Tùy chọn nâng cao:</h4>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="enable_animations" 
                                   name="enable_animations" 
                                   checked
                                   class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                            <label for="enable_animations" class="ml-2 block text-sm text-gray-900">
                                Bật animations và transitions
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="enable_lazy_loading" 
                                   name="enable_lazy_loading" 
                                   checked
                                   class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                            <label for="enable_lazy_loading" class="ml-2 block text-sm text-gray-900">
                                Bật lazy loading cho hình ảnh
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="enable_seo_optimization" 
                                   name="enable_seo_optimization" 
                                   checked
                                   class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                            <label for="enable_seo_optimization" class="ml-2 block text-sm text-gray-900">
                                Tối ưu SEO cho content sections
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
                    <h4 class="font-semibold text-yellow-800">Module tùy chọn</h4>
                    <p class="text-yellow-700 text-sm mt-1">
                        Bạn có thể bỏ qua module này nếu website không cần các thành phần nội dung phức tạp. 
                        Module có thể được cài đặt sau thông qua admin panel.
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
                    class="px-8 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors">
                <i class="fas fa-check mr-2"></i>
                Cài đặt module
            </button>
        </div>
    </div>
</form>

<!-- Navigation -->
<div id="navigation-section" class="hidden border-t pt-6 mt-6">
    <div class="flex justify-between">
        <a href="{{ route('setup.step', 'module-staff') }}" 
           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Quay lại
        </a>
        
        <button onclick="goToNextStep('module-ecommerce')"
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
    data.enable_sliders = document.getElementById('enable_sliders').checked;
    data.enable_gallery = document.getElementById('enable_gallery').checked;
    data.enable_faq = document.getElementById('enable_faq').checked;
    data.enable_testimonials = document.getElementById('enable_testimonials').checked;
    data.enable_cta = document.getElementById('enable_cta').checked;
    data.enable_content_blocks = document.getElementById('enable_content_blocks').checked;
    data.enable_animations = document.getElementById('enable_animations').checked;
    data.enable_lazy_loading = document.getElementById('enable_lazy_loading').checked;
    data.enable_seo_optimization = document.getElementById('enable_seo_optimization').checked;
    data.module_key = 'content_sections';
    
    showLoading('Đang cấu hình Content Sections module...');
    
    submitStep('{{ route('setup.process', 'module-content') }}', data, (response) => {
        document.getElementById('navigation-section').classList.remove('hidden');
        
        // Auto proceed to next step after 2 seconds
        setTimeout(() => {
            goToNextStep('module-ecommerce');
        }, 2000);
    });
}

function skipModule() {
    const data = {
        enable_module: false,
        create_sample_data: false,
        module_key: 'content_sections',
        skipped: true
    };
    
    showLoading('Đang bỏ qua module...');
    
    submitStep('{{ route('setup.process', 'module-content') }}', data, (response) => {
        document.getElementById('navigation-section').classList.remove('hidden');
        
        // Auto proceed to next step after 1 second
        setTimeout(() => {
            goToNextStep('module-ecommerce');
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
    
    console.log('Content Sections module page loaded');
});
</script>
@endpush
