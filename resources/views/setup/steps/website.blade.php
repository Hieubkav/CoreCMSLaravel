@extends('setup.layout')

@section('title', 'Cấu hình Website - Core Framework Setup')
@section('description', 'Thiết lập thông tin cơ bản cho website của bạn')

@section('content')
<div class="text-center mb-8">
    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-globe text-2xl text-green-600"></i>
    </div>
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Cấu hình Website</h2>
    <p class="text-gray-600">
        Thiết lập thông tin cơ bản cho website của bạn. Bạn có thể thay đổi các thông tin này sau.
    </p>
</div>

<!-- Website Form -->
<form id="website-form" onsubmit="configureWebsite(event)">
    <div class="space-y-6">
        <!-- Site Name -->
        <div>
            <label for="site_name" class="block text-sm font-medium text-gray-700 mb-2">
                Tên Website <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   id="site_name" 
                   name="site_name" 
                   required
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                   placeholder="Ví dụ: Công ty ABC">
            <div id="site_name-error" class="text-red-600 text-sm mt-1 hidden"></div>
        </div>

        <!-- Site Description -->
        <div>
            <label for="site_description" class="block text-sm font-medium text-gray-700 mb-2">
                Mô tả Website
            </label>
            <textarea id="site_description" 
                      name="site_description" 
                      rows="3"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                      placeholder="Mô tả ngắn gọn về website của bạn..."></textarea>
            <div class="text-gray-500 text-sm mt-1">
                Mô tả này sẽ được sử dụng cho SEO và social media
            </div>
        </div>

        <!-- Site Keywords -->
        <div>
            <label for="site_keywords" class="block text-sm font-medium text-gray-700 mb-2">
                Từ khóa SEO
            </label>
            <input type="text" 
                   id="site_keywords" 
                   name="site_keywords" 
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                   placeholder="từ khóa 1, từ khóa 2, từ khóa 3">
            <div class="text-gray-500 text-sm mt-1">
                Các từ khóa cách nhau bằng dấu phẩy
            </div>
        </div>

        <!-- Contact Information Section -->
        <div class="border-t pt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Thông tin liên hệ</h3>
            
            <!-- Contact Email -->
            <div class="mb-4">
                <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email liên hệ
                </label>
                <input type="email" 
                       id="contact_email" 
                       name="contact_email" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                       placeholder="contact@example.com">
            </div>

            <!-- Contact Phone -->
            <div class="mb-4">
                <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                    Số điện thoại
                </label>
                <input type="tel" 
                       id="contact_phone" 
                       name="contact_phone" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                       placeholder="0123 456 789">
            </div>

            <!-- Contact Address -->
            <div>
                <label for="contact_address" class="block text-sm font-medium text-gray-700 mb-2">
                    Địa chỉ
                </label>
                <textarea id="contact_address" 
                          name="contact_address" 
                          rows="2"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                          placeholder="Địa chỉ công ty hoặc tổ chức..."></textarea>
            </div>
        </div>

        <!-- Configuration Note -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                <div>
                    <h4 class="font-semibold text-blue-800">Thông tin cấu hình</h4>
                    <ul class="text-blue-700 text-sm mt-1 space-y-1">
                        <li>• Tất cả thông tin này có thể được thay đổi sau trong bảng điều khiển admin</li>
                        <li>• Chỉ có tên website là bắt buộc, các thông tin khác là tùy chọn</li>
                        <li>• Thông tin liên hệ sẽ hiển thị trên footer và trang liên hệ</li>
                        <li>• Mô tả và từ khóa giúp tối ưu hóa SEO cho website</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" 
                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors">
            <i class="fas fa-save mr-2"></i>
            Lưu cấu hình Website
        </button>
    </div>
</form>

<!-- Navigation -->
<div id="navigation-section" class="hidden border-t pt-6 mt-6">
    <div class="flex justify-between">
        <a href="{{ route('setup.step', 'admin') }}" 
           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Quay lại
        </a>
        
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
            goToNextStep('sample-data');
        }, 2000);
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
    
    // Validate contact email if provided
    const contactEmail = document.getElementById('contact_email').value.trim();
    if (contactEmail) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(contactEmail)) {
            showError('contact_email', 'Email không hợp lệ');
            isValid = false;
        }
    }
    
    return isValid;
}

function showError(field, message) {
    const errorDiv = document.getElementById(`${field}-error`);
    const inputField = document.getElementById(field);
    
    if (errorDiv) {
        errorDiv.textContent = message;
        errorDiv.classList.remove('hidden');
    }
    
    if (inputField) {
        inputField.classList.add('border-red-500');
    }
}

function clearErrors() {
    const errorDivs = document.querySelectorAll('[id$="-error"]');
    const inputFields = document.querySelectorAll('input, textarea');
    
    errorDivs.forEach(div => {
        div.classList.add('hidden');
        div.textContent = '';
    });
    
    inputFields.forEach(input => {
        input.classList.remove('border-red-500');
    });
}

// Real-time validation
document.addEventListener('DOMContentLoaded', function() {
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
    
    // Auto-fill some default values
    const siteName = document.getElementById('site_name');
    if (!siteName.value) {
        siteName.placeholder = 'Website của tôi';
    }
});
</script>
@endpush
