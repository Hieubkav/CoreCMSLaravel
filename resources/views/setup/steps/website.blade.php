@extends('setup.layout')

@section('title', 'Cấu hình Website - Core Framework Setup')
@section('description', 'Thiết lập thông tin đầy đủ cho website của bạn')

@section('content')
<!-- Header Section - Compact -->
<div class="text-center mb-8">
    <div class="w-16 h-16 bg-gradient-to-br from-red-50 to-red-100 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
        <i class="fas fa-globe text-2xl text-red-600"></i>
    </div>
    <h1 class="text-2xl lg:text-3xl font-light text-gray-900 mb-3">Cấu hình Website</h1>
    <p class="text-base text-gray-600 max-w-2xl mx-auto">
        Thiết lập thông tin cơ bản cho website của bạn. Tất cả thông tin này có thể thay đổi sau trong admin panel.
    </p>
</div>

<!-- Website Form - Ultra Wide & Compact -->
<div class="max-w-[120rem] mx-auto">
    <form id="website-form" onsubmit="configureWebsite(event)">
        <div class="space-y-8">

            <!-- Basic Information Section -->
            <div class="bg-white rounded-2xl p-6 lg:p-8 shadow-sm border border-gray-100">
                <div class="mb-6">
                    <h3 class="text-xl font-light text-gray-900 mb-2 flex items-center">
                        <div class="w-7 h-7 bg-blue-50 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-info-circle text-blue-600 text-sm"></i>
                        </div>
                        Thông tin cơ bản
                    </h3>
                    <p class="text-gray-600 ml-10 text-sm">Thông tin chính về website của bạn</p>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 xl:gap-8">
                    <!-- Site Name -->
                    <div>
                        <label for="site_name" class="block text-sm font-medium text-gray-900 mb-2">
                            Tên Website <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="site_name"
                               name="site_name"
                               required
                               class="w-full px-4 py-3 text-base border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 bg-gray-50 focus:bg-white"
                               placeholder="Ví dụ: Công ty ABC">
                        <div id="site_name-error" class="text-red-600 text-sm mt-1 hidden"></div>
                    </div>

                    <!-- Slogan -->
                    <div>
                        <label for="slogan" class="block text-sm font-medium text-gray-900 mb-2">
                            Slogan
                        </label>
                        <input type="text"
                               id="slogan"
                               name="slogan"
                               class="w-full px-4 py-3 text-base border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 bg-gray-50 focus:bg-white"
                               placeholder="Slogan ngắn gọn của website">
                    </div>

                    <!-- Footer Description -->
                    <div class="xl:col-span-1">
                        <label for="footer_description" class="block text-sm font-medium text-gray-900 mb-2">
                            Mô tả Footer
                        </label>
                        <textarea id="footer_description"
                                  name="footer_description"
                                  rows="3"
                                  class="w-full px-4 py-3 text-base border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 bg-gray-50 focus:bg-white resize-none"
                                  placeholder="Mô tả ngắn gọn hiển thị ở footer website"></textarea>
                    </div>
                </div>
            </div>

            <!-- SEO & Contact Combined Section -->
            <div class="bg-white rounded-2xl p-6 lg:p-8 shadow-sm border border-gray-100">
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                    <!-- SEO Information -->
                    <div>
                        <div class="mb-6">
                            <h3 class="text-xl font-light text-gray-900 mb-2 flex items-center">
                                <div class="w-7 h-7 bg-green-50 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-search text-green-600 text-sm"></i>
                                </div>
                                Thông tin SEO
                            </h3>
                            <p class="text-gray-600 ml-10 text-sm">Tối ưu hóa website cho công cụ tìm kiếm</p>
                        </div>
                        <div class="space-y-4">
                            <!-- SEO Title -->
                            <div>
                                <label for="seo_title" class="block text-sm font-medium text-gray-900 mb-2">
                                    Tiêu đề SEO
                                </label>
                                <input type="text"
                                       id="seo_title"
                                       name="seo_title"
                                       class="w-full px-4 py-3 text-base border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 bg-gray-50 focus:bg-white"
                                       placeholder="Tiêu đề hiển thị trên Google (tự động từ tên website nếu để trống)">
                                <p class="text-gray-500 text-xs mt-1">Nên từ 50-60 ký tự</p>
                            </div>

                            <!-- SEO Description -->
                            <div>
                                <label for="seo_description" class="block text-sm font-medium text-gray-900 mb-2">
                                    Mô tả SEO
                                </label>
                                <textarea id="seo_description"
                                          name="seo_description"
                                          rows="3"
                                          class="w-full px-4 py-3 text-base border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 bg-gray-50 focus:bg-white resize-none"
                                          placeholder="Mô tả ngắn gọn về website hiển thị trên Google"></textarea>
                                <p class="text-gray-500 text-xs mt-1">Nên từ 150-160 ký tự</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div>
                        <div class="mb-6">
                            <h3 class="text-xl font-light text-gray-900 mb-2 flex items-center">
                                <div class="w-7 h-7 bg-blue-50 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-phone text-blue-600 text-sm"></i>
                                </div>
                                Thông tin liên hệ
                            </h3>
                            <p class="text-gray-600 ml-10 text-sm">Thông tin để khách hàng liên hệ với bạn</p>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <!-- Contact Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-900 mb-2">
                                    Email liên hệ
                                </label>
                                <input type="email"
                                       id="email"
                                       name="email"
                                       class="w-full px-4 py-3 text-base border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 bg-gray-50 focus:bg-white"
                                       placeholder="contact@example.com">
                                <div id="email-error" class="text-red-600 text-sm mt-1 hidden"></div>
                            </div>

                            <!-- Hotline -->
                            <div>
                                <label for="hotline" class="block text-sm font-medium text-gray-900 mb-2">
                                    Hotline
                                </label>
                                <input type="tel"
                                       id="hotline"
                                       name="hotline"
                                       class="w-full px-4 py-3 text-base border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 bg-gray-50 focus:bg-white"
                                       placeholder="0123 456 789">
                            </div>

                            <!-- Working Hours -->
                            <div>
                                <label for="working_hours" class="block text-sm font-medium text-gray-900 mb-2">
                                    Giờ làm việc
                                </label>
                                <input type="text"
                                       id="working_hours"
                                       name="working_hours"
                                       class="w-full px-4 py-3 text-base border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 bg-gray-50 focus:bg-white"
                                       placeholder="Thứ 2 - Thứ 6: 8:00 - 17:00">
                            </div>

                            <!-- Address -->
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-900 mb-2">
                                    Địa chỉ
                                </label>
                                <textarea id="address"
                                          name="address"
                                          rows="3"
                                          class="w-full px-4 py-3 text-base border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 bg-gray-50 focus:bg-white resize-none"
                                          placeholder="Địa chỉ công ty hoặc cửa hàng"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Social Media Section -->
            <div class="bg-white rounded-2xl p-6 lg:p-8 shadow-sm border border-gray-100">
                <div class="mb-6">
                    <h3 class="text-xl font-light text-gray-900 mb-2 flex items-center">
                        <div class="w-7 h-7 bg-purple-50 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-share-alt text-purple-600 text-sm"></i>
                        </div>
                        Mạng xã hội
                    </h3>
                    <p class="text-gray-600 ml-10 text-sm">Kết nối với khách hàng qua các nền tảng xã hội</p>
                </div>
                <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
                    <!-- Facebook -->
                    <div>
                        <label for="facebook_link" class="block text-sm font-medium text-gray-900 mb-2">
                            <div class="flex items-center">
                                <div class="w-5 h-5 bg-blue-50 rounded-lg flex items-center justify-center mr-2">
                                    <i class="fab fa-facebook text-blue-600 text-xs"></i>
                                </div>
                                Facebook
                            </div>
                        </label>
                        <input type="url"
                               id="facebook_link"
                               name="facebook_link"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 bg-gray-50 focus:bg-white"
                               placeholder="https://facebook.com/yourpage">
                    </div>

                    <!-- Zalo -->
                    <div>
                        <label for="zalo_link" class="block text-sm font-medium text-gray-900 mb-2">
                            <div class="flex items-center">
                                <div class="w-5 h-5 bg-blue-50 rounded-lg flex items-center justify-center mr-2">
                                    <i class="fas fa-comment text-blue-500 text-xs"></i>
                                </div>
                                Zalo
                            </div>
                        </label>
                        <input type="url"
                               id="zalo_link"
                               name="zalo_link"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 bg-gray-50 focus:bg-white"
                               placeholder="https://zalo.me/yourpage">
                    </div>

                    <!-- YouTube -->
                    <div>
                        <label for="youtube_link" class="block text-sm font-medium text-gray-900 mb-2">
                            <div class="flex items-center">
                                <div class="w-5 h-5 bg-red-50 rounded-lg flex items-center justify-center mr-2">
                                    <i class="fab fa-youtube text-red-600 text-xs"></i>
                                </div>
                                YouTube
                            </div>
                        </label>
                        <input type="url"
                               id="youtube_link"
                               name="youtube_link"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 bg-gray-50 focus:bg-white"
                               placeholder="https://youtube.com/yourchannel">
                    </div>

                    <!-- TikTok -->
                    <div>
                        <label for="tiktok_link" class="block text-sm font-medium text-gray-900 mb-2">
                            <div class="flex items-center">
                                <div class="w-5 h-5 bg-gray-50 rounded-lg flex items-center justify-center mr-2">
                                    <i class="fab fa-tiktok text-black text-xs"></i>
                                </div>
                                TikTok
                            </div>
                        </label>
                        <input type="url"
                               id="tiktok_link"
                               name="tiktok_link"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 bg-gray-50 focus:bg-white"
                               placeholder="https://tiktok.com/@youraccount">
                    </div>

                    <!-- Messenger -->
                    <div>
                        <label for="messenger_link" class="block text-sm font-medium text-gray-900 mb-2">
                            <div class="flex items-center">
                                <div class="w-5 h-5 bg-blue-50 rounded-lg flex items-center justify-center mr-2">
                                    <i class="fab fa-facebook-messenger text-blue-500 text-xs"></i>
                                </div>
                                Messenger
                            </div>
                        </label>
                        <input type="url"
                               id="messenger_link"
                               name="messenger_link"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-200 bg-gray-50 focus:bg-white"
                               placeholder="https://m.me/yourpage">
                    </div>
                </div>
            </div>


            <!-- Bottom Section: Configuration Note & Tools -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <!-- Configuration Note -->
                <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border border-yellow-200 rounded-2xl p-6">
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-yellow-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-info-circle text-yellow-600 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-medium text-yellow-800 mb-3">Thông tin cấu hình</h4>
                            <div class="space-y-2 text-yellow-700 text-sm">
                                <p class="flex items-start">
                                    <i class="fas fa-check text-yellow-600 mt-0.5 mr-2 flex-shrink-0 text-xs"></i>
                                    Chỉ có <strong>tên website</strong> là bắt buộc, tất cả thông tin khác đều tùy chọn
                                </p>
                                <p class="flex items-start">
                                    <i class="fas fa-check text-yellow-600 mt-0.5 mr-2 flex-shrink-0 text-xs"></i>
                                    Tất cả thông tin này có thể được thay đổi sau trong bảng điều khiển admin
                                </p>
                                <p class="flex items-start">
                                    <i class="fas fa-check text-yellow-600 mt-0.5 mr-2 flex-shrink-0 text-xs"></i>
                                    Thông tin SEO giúp tối ưu hóa website trên Google
                                </p>
                                <p class="flex items-start">
                                    <i class="fas fa-check text-yellow-600 mt-0.5 mr-2 flex-shrink-0 text-xs"></i>
                                    Thông tin liên hệ sẽ hiển thị trên footer và trang liên hệ
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Development Tools (Local Only) -->
                @if(app()->environment('local'))
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 border border-gray-200 rounded-2xl p-6">
                    <div class="flex flex-col gap-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-xl flex items-center justify-center mr-3">
                                <i class="fas fa-flask text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-medium text-gray-900">Development Tools</h4>
                                <p class="text-gray-600 text-xs">Chỉ hiển thị trong môi trường local để hỗ trợ testing</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <button type="button"
                                    onclick="fillDefaultValues()"
                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md text-sm">
                                <i class="fas fa-bolt mr-2"></i>
                                Quick Fill
                            </button>
                            <button type="button"
                                    onclick="clearAllFields()"
                                    class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md text-sm">
                                <i class="fas fa-eraser mr-2"></i>
                                Clear All
                            </button>
                        </div>
                    </div>
                </div>
                @else
                <div></div>
                @endif
            </div>

            <!-- Submit Button -->
            <div class="text-center pt-6">
                <button type="submit"
                        class="px-10 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white text-base font-medium rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-save mr-2"></i>
                    Lưu cấu hình và tiếp tục
                </button>
                <p class="text-gray-500 text-sm mt-3">Bước 3 / 17 - Cấu hình website</p>
            </div>
        </div>
    </form>
</div>

<!-- Navigation -->
<div id="navigation-section" class="hidden max-w-[120rem] mx-auto border-t border-gray-200 pt-8 mt-8">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
        <a href="{{ route('setup.step', 'admin') }}"
           class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-200 font-medium text-sm">
            <i class="fas fa-arrow-left mr-2"></i>
            Quay lại bước trước
        </a>

        <button onclick="goToNextStep('frontend-config')"
                class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white rounded-lg transition-all duration-200 font-medium shadow-lg hover:shadow-xl text-sm">
            Tiếp theo: Cấu hình Frontend
            <i class="fas fa-arrow-right ml-2"></i>
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
function configureWebsite(event) {
    event.preventDefault();

    // Clear previous errors
    clearErrors();

    // Validate form
    if (!validateForm()) {
        return;
    }

    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());

    showLoading('Đang lưu cấu hình website...');

    submitStep('{{ route('setup.process', 'website') }}', data, (response) => {
        document.getElementById('navigation-section').classList.remove('hidden');

        // Auto proceed to next step after 2 seconds
        setTimeout(() => {
            goToNextStep('frontend-config');
        }, 2000);
    }, (error) => {
        console.error('Website configuration error:', error);

        // Handle specific validation errors
        if (error.errors) {
            Object.keys(error.errors).forEach(field => {
                const message = Array.isArray(error.errors[field]) ? error.errors[field][0] : error.errors[field];
                showError(field, message);
            });
        }
    });
}

function validateForm() {
    let isValid = true;

    // Validate site name (required)
    const siteName = document.getElementById('site_name').value.trim();
    if (!siteName) {
        showError('site_name', 'Vui lòng nhập tên website');
        isValid = false;
    }

    // Validate email if provided
    const email = document.getElementById('email').value.trim();
    if (email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            showError('email', 'Email không hợp lệ');
            isValid = false;
        }
    }

    // Validate URLs if provided
    const urlFields = ['facebook_link', 'zalo_link', 'youtube_link', 'tiktok_link', 'messenger_link'];
    urlFields.forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (field && field.value.trim()) {
            try {
                new URL(field.value.trim());
            } catch (e) {
                showError(fieldName, 'URL không hợp lệ');
                isValid = false;
            }
        }
    });

    return isValid;
}

function showError(fieldId, message) {
    const field = document.getElementById(fieldId);
    const errorDiv = document.getElementById(`${fieldId}-error`);

    if (field) {
        field.classList.add('border-red-500');
    }

    if (errorDiv) {
        errorDiv.textContent = message;
        errorDiv.classList.remove('hidden');
    }
}

function clearErrors() {
    const errorDivs = document.querySelectorAll('[id$="-error"]');
    errorDivs.forEach(div => {
        div.classList.add('hidden');
    });

    const fields = document.querySelectorAll('.border-red-500');
    fields.forEach(field => {
        field.classList.remove('border-red-500');
    });
}

// Auto-fill default values for testing (optimized)
function fillDefaultValues() {
    const defaultValues = {
        'site_name': 'Core Framework Test',
        'slogan': 'Framework Laravel tối ưu',
        'footer_description': 'Core Framework - Giải pháp phát triển website nhanh chóng và hiệu quả.',
        'seo_title': 'Core Framework - Laravel Framework tối ưu',
        'seo_description': 'Core Framework cung cấp nền tảng Laravel được tối ưu hóa để phát triển website nhanh chóng.',
        'email': 'admin@coreframework.test',
        'hotline': '0123 456 789',
        'address': 'Việt Nam',
        'working_hours': 'T2-T6: 8:00-17:00'
    };

    // Optimized fill - no events to avoid lag
    Object.keys(defaultValues).forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field && !field.value.trim()) {
            field.value = defaultValues[fieldId];
        }
    });

    console.log('✅ Default values filled for testing');
}

// Clear all form fields
function clearAllFields() {
    const inputs = document.querySelectorAll('#website-form input, #website-form textarea');

    inputs.forEach(input => {
        input.value = '';

        // Remove error states
        input.classList.remove('border-red-500');
        const errorDiv = document.getElementById(`${input.id}-error`);
        if (errorDiv) {
            errorDiv.classList.add('hidden');
        }

        // Reset label colors
        const label = input.previousElementSibling;
        if (label && label.tagName === 'LABEL') {
            label.style.color = '';
        }

        // Trigger input event
        input.dispatchEvent(new Event('input', { bubbles: true }));
    });

    console.log('✅ All fields cleared');
}

// Real-time validation
document.addEventListener('DOMContentLoaded', function() {
    // Auto-fill default values for testing (only in local environment)
    @if(app()->environment('local'))
    fillDefaultValues();
    @endif

    const inputs = document.querySelectorAll('input, textarea');

    inputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.classList.contains('border-red-500')) {
                this.classList.remove('border-red-500');
                const errorDiv = document.getElementById(`${this.id}-error`);
                if (errorDiv) {
                    errorDiv.classList.add('hidden');
                }
            }
        });
    });

    // Simplified - only essential features for performance
});
</script>
@endpush
