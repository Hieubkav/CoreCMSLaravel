@extends('setup.layout')

@section('title', 'Module: Blog & Posts - Core Framework Setup')
@section('description', 'Cài đặt hệ thống blog, bài viết và quản lý nội dung')

@section('content')
<div class="text-center mb-8">
    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-blog text-2xl text-green-600"></i>
    </div>
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Blog & Posts Module</h2>
    <p class="text-gray-600">
        Hệ thống blog hoàn chỉnh với bài viết, danh mục và quản lý nội dung.
    </p>
</div>

<!-- Module Configuration Form -->
<form id="module-form" onsubmit="configureModule(event)">
    <div class="space-y-8">
        
        <!-- Module Enable/Disable -->
        <div class="border border-gray-200 rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-toggle-on text-green-600 mr-2"></i>
                    Cài đặt Module
                </h3>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="enable_module" name="enable_module" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                    <span class="ml-3 text-sm font-medium text-gray-900">Bật module này</span>
                </label>
            </div>
            
            <div id="module-content" class="space-y-6">
                <!-- Module Description -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <h4 class="font-semibold text-green-800 mb-2">Tính năng của module:</h4>
                    <ul class="text-green-700 text-sm space-y-1">
                        <li>• <strong>Posts Management:</strong> Tạo, chỉnh sửa và quản lý bài viết</li>
                        <li>• <strong>Categories:</strong> Phân loại bài viết theo danh mục</li>
                        <li>• <strong>Post Types:</strong> News, Blog, Policy pages</li>
                        <li>• <strong>SEO Optimization:</strong> Meta tags, slug tự động</li>
                        <li>• <strong>Image Management:</strong> Upload và tối ưu hình ảnh</li>
                        <li>• <strong>Frontend Views:</strong> Blog listing, detail, filter</li>
                    </ul>
                </div>

                <!-- Post Types Configuration -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Loại bài viết sẽ được hỗ trợ:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="flex items-center p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <i class="fas fa-newspaper text-blue-600 mr-3"></i>
                            <div>
                                <div class="font-medium text-blue-900">News</div>
                                <div class="text-sm text-blue-700">Tin tức</div>
                            </div>
                        </div>
                        <div class="flex items-center p-3 bg-purple-50 border border-purple-200 rounded-lg">
                            <i class="fas fa-blog text-purple-600 mr-3"></i>
                            <div>
                                <div class="font-medium text-purple-900">Blog</div>
                                <div class="text-sm text-purple-700">Bài viết blog</div>
                            </div>
                        </div>
                        <div class="flex items-center p-3 bg-gray-50 border border-gray-200 rounded-lg">
                            <i class="fas fa-file-alt text-gray-600 mr-3"></i>
                            <div>
                                <div class="font-medium text-gray-900">Policy</div>
                                <div class="text-sm text-gray-700">Trang chính sách</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Frontend Components -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Components sẽ được tạo:</h4>
                    <div class="space-y-2">
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-list text-green-500 mr-3"></i>
                            <span class="text-sm"><strong>blog-listing.blade.php:</strong> Danh sách bài viết với pagination</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-eye text-blue-500 mr-3"></i>
                            <span class="text-sm"><strong>post-detail.blade.php:</strong> Chi tiết bài viết + related posts</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-filter text-purple-500 mr-3"></i>
                            <span class="text-sm"><strong>post-filter.blade.php:</strong> Livewire filter component</span>
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
                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <label for="create_sample_data" class="ml-2 block text-sm font-medium text-gray-900">
                            Tạo dữ liệu mẫu
                        </label>
                    </div>
                    
                    <div id="sample-data-options" class="ml-6 space-y-2 text-sm text-gray-600">
                        <div>• 15 bài viết mẫu (5 tin tức, 5 blog, 5 chính sách)</div>
                        <div>• 5 danh mục bài viết</div>
                        <div>• Hình ảnh placeholder từ picsum.photos</div>
                        <div>• SEO data hoàn chỉnh</div>
                    </div>
                </div>

                <!-- Advanced Options -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Tùy chọn nâng cao:</h4>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="enable_comments" 
                                   name="enable_comments" 
                                   class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <label for="enable_comments" class="ml-2 block text-sm text-gray-900">
                                Bật hệ thống bình luận
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="enable_tags" 
                                   name="enable_tags" 
                                   checked
                                   class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <label for="enable_tags" class="ml-2 block text-sm text-gray-900">
                                Bật hệ thống tags
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="enable_featured" 
                                   name="enable_featured" 
                                   checked
                                   class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <label for="enable_featured" class="ml-2 block text-sm text-gray-900">
                                Bật tính năng bài viết nổi bật
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
                        Bạn có thể bỏ qua module này nếu website không cần tính năng blog. 
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
                    class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-check mr-2"></i>
                Cài đặt module
            </button>
        </div>
    </div>
</form>

<!-- Navigation -->
<div id="navigation-section" class="hidden border-t pt-6 mt-6">
    <div class="flex justify-between">
        <a href="{{ route('setup.step', 'module-user-roles') }}" 
           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Quay lại
        </a>
        
        <button onclick="goToNextStep('module-staff')"
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
    data.enable_comments = document.getElementById('enable_comments').checked;
    data.enable_tags = document.getElementById('enable_tags').checked;
    data.enable_featured = document.getElementById('enable_featured').checked;
    data.module_key = 'blog_posts';
    
    showLoading('Đang cấu hình Blog & Posts module và generate code...');

    submitStep('{{ route('setup.process', 'module-blog') }}', data, (response) => {
        document.getElementById('navigation-section').classList.remove('hidden');

        // Show additional success message for generation
        if (response.generation_results && response.generation_results.generation) {
            showAlert('✅ Blog module đã được cài đặt và code đã được generate thành công!', 'success');
        }

        // Auto proceed to next step after 3 seconds (longer to see results)
        setTimeout(() => {
            clearGenerationResults(); // Clear before moving to next step
            goToNextStep('module-staff');
        }, 3000);
    });
}

function skipModule() {
    const data = {
        enable_module: false,
        create_sample_data: false,
        module_key: 'blog_posts',
        skipped: true
    };
    
    showLoading('Đang bỏ qua module...');
    
    submitStep('{{ route('setup.process', 'module-blog') }}', data, (response) => {
        document.getElementById('navigation-section').classList.remove('hidden');
        
        // Auto proceed to next step after 1 second
        setTimeout(() => {
            goToNextStep('module-staff');
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
    
    console.log('Blog & Posts module page loaded');
});
</script>
@endpush
