@extends('setup.layout')

@section('title', 'Cấu hình Blog/Bài viết')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-blog text-2xl text-red-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Cấu hình Blog/Bài viết</h1>
            <p class="text-gray-600">Thiết lập hệ thống blog, bài viết và danh mục cho website của bạn</p>
        </div>

        <!-- Progress -->
        <div class="mb-8">
            <div class="flex items-center justify-between text-sm text-gray-500 mb-2">
                <span>Bước {{ $currentStepNumber }} / {{ $totalSteps }}</span>
                <span>{{ round(($currentStepNumber / $totalSteps) * 100) }}% hoàn thành</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-red-600 h-2 rounded-full transition-all duration-300" 
                     style="width: {{ ($currentStepNumber / $totalSteps) * 100 }}%"></div>
            </div>
        </div>

        <!-- Form -->
        <form id="blogForm" class="space-y-6">
            @csrf
            
            <!-- Enable Blog Option -->
            <div class="bg-gray-50 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Kích hoạt hệ thống Blog</h3>
                        <p class="text-gray-600">Bật tính năng blog để quản lý bài viết, tin tức và nội dung</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="enable_blog" id="enable_blog" class="sr-only peer" value="1">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                    </label>
                </div>
            </div>

            <!-- Blog Configuration (Hidden by default) -->
            <div id="blogConfig" class="space-y-6" style="display: none;">
                
                <!-- Basic Settings -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="blog_title" class="block text-sm font-medium text-gray-700 mb-2">
                            Tiêu đề Blog <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="blog_title" 
                               id="blog_title" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                               placeholder="Ví dụ: Tin tức & Blog"
                               value="Blog">
                    </div>

                    <div>
                        <label for="posts_per_page" class="block text-sm font-medium text-gray-700 mb-2">
                            Số bài viết mỗi trang
                        </label>
                        <select name="posts_per_page" 
                                id="posts_per_page" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <option value="6">6 bài viết</option>
                            <option value="9" selected>9 bài viết</option>
                            <option value="12">12 bài viết</option>
                            <option value="15">15 bài viết</option>
                        </select>
                    </div>
                </div>

                <!-- Blog Description -->
                <div>
                    <label for="blog_description" class="block text-sm font-medium text-gray-700 mb-2">
                        Mô tả Blog
                    </label>
                    <textarea name="blog_description" 
                              id="blog_description" 
                              rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                              placeholder="Mô tả ngắn về blog của bạn..."></textarea>
                </div>

                <!-- Post Type -->
                <div>
                    <label for="default_post_type" class="block text-sm font-medium text-gray-700 mb-2">
                        Loại bài viết mặc định <span class="text-red-500">*</span>
                    </label>
                    <select name="default_post_type" 
                            id="default_post_type" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="tin_tuc" selected>Tin tức</option>
                        <option value="dich_vu">Dịch vụ</option>
                        <option value="trang_don">Trang đơn</option>
                    </select>
                </div>

                <!-- Additional Options -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               name="enable_categories" 
                               id="enable_categories" 
                               class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500"
                               value="1" 
                               checked>
                        <label for="enable_categories" class="ml-2 text-sm text-gray-700">
                            Kích hoạt danh mục bài viết
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" 
                               name="enable_featured_posts" 
                               id="enable_featured_posts" 
                               class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500"
                               value="1" 
                               checked>
                        <label for="enable_featured_posts" class="ml-2 text-sm text-gray-700">
                            Kích hoạt bài viết nổi bật
                        </label>
                    </div>
                </div>

            </div>

            <!-- Skip Option -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center">
                    <input type="checkbox" 
                           name="skip_blog" 
                           id="skip_blog" 
                           class="w-4 h-4 text-yellow-600 bg-gray-100 border-gray-300 rounded focus:ring-yellow-500">
                    <label for="skip_blog" class="ml-2 text-sm text-gray-700">
                        <strong>Bỏ qua bước này</strong> - Tôi sẽ cấu hình blog sau
                    </label>
                </div>
                <p class="text-xs text-gray-500 mt-1 ml-6">
                    Bạn có thể cài đặt blog sau thông qua admin panel
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between pt-6">
                <a href="{{ route('setup.step', 'admin-config') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Quay lại
                </a>
                
                <button type="submit" 
                        id="submitBtn"
                        class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <span id="submitText">Tiếp tục</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Loading Modal -->
<div id="loadingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-sm w-full mx-4">
        <div class="text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-red-600 mx-auto mb-4"></div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Đang cấu hình Blog...</h3>
            <p class="text-gray-600">Vui lòng đợi trong giây lát</p>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const enableBlogCheckbox = document.getElementById('enable_blog');
    const blogConfig = document.getElementById('blogConfig');
    const skipBlogCheckbox = document.getElementById('skip_blog');
    const form = document.getElementById('blogForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const loadingModal = document.getElementById('loadingModal');

    // Toggle blog configuration
    enableBlogCheckbox.addEventListener('change', function() {
        if (this.checked) {
            blogConfig.style.display = 'block';
            skipBlogCheckbox.checked = false;
        } else {
            blogConfig.style.display = 'none';
        }
    });

    // Handle skip option
    skipBlogCheckbox.addEventListener('change', function() {
        if (this.checked) {
            enableBlogCheckbox.checked = false;
            blogConfig.style.display = 'none';
        }
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading
        loadingModal.classList.remove('hidden');
        loadingModal.classList.add('flex');
        submitBtn.disabled = true;
        
        const formData = new FormData(form);
        
        fetch('{{ route("setup.process", "blog") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    window.location.href = '{{ route("setup.complete") }}';
                }
            } else {
                alert('Lỗi: ' + (data.message || 'Có lỗi xảy ra'));
                loadingModal.classList.add('hidden');
                loadingModal.classList.remove('flex');
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi xử lý yêu cầu');
            loadingModal.classList.add('hidden');
            loadingModal.classList.remove('flex');
            submitBtn.disabled = false;
        });
    });
});
</script>
@endpush
