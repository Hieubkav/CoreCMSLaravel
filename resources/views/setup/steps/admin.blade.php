@extends('setup.layout')

@section('title', 'Tạo tài khoản Admin - Core Framework Setup')
@section('description', 'Tạo tài khoản quản trị viên đầu tiên cho hệ thống')

@section('content')
<div class="text-center mb-8">
    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-user-shield text-2xl text-purple-600"></i>
    </div>
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Tạo tài khoản Admin</h2>
    <p class="text-gray-600">
        Tạo tài khoản quản trị viên đầu tiên để truy cập vào bảng điều khiển admin.
    </p>
</div>

<!-- Admin Form -->
<form id="admin-form" onsubmit="createAdmin(event)">
    <div class="space-y-6">
        <!-- Name Field -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                Họ và tên <span class="text-red-500">*</span>
            </label>
            <input type="text" 
                   id="name" 
                   name="name" 
                   required
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                   placeholder="Nhập họ và tên của bạn">
            <div id="name-error" class="text-red-600 text-sm mt-1 hidden"></div>
        </div>

        <!-- Email Field -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                Email <span class="text-red-500">*</span>
            </label>
            <input type="email" 
                   id="email" 
                   name="email" 
                   required
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                   placeholder="admin@example.com">
            <div id="email-error" class="text-red-600 text-sm mt-1 hidden"></div>
        </div>

        <!-- Password Field -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                Mật khẩu <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <input type="password" 
                       id="password" 
                       name="password" 
                       required
                       minlength="8"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors pr-12"
                       placeholder="Tối thiểu 8 ký tự">
                <button type="button" 
                        onclick="togglePassword('password')"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                    <i id="password-icon" class="fas fa-eye"></i>
                </button>
            </div>
            <div id="password-error" class="text-red-600 text-sm mt-1 hidden"></div>
            <div class="text-gray-500 text-sm mt-1">
                Mật khẩu phải có ít nhất 8 ký tự
            </div>
        </div>

        <!-- Confirm Password Field -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                Xác nhận mật khẩu <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <input type="password" 
                       id="password_confirmation" 
                       name="password_confirmation" 
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors pr-12"
                       placeholder="Nhập lại mật khẩu">
                <button type="button" 
                        onclick="togglePassword('password_confirmation')"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                    <i id="password_confirmation-icon" class="fas fa-eye"></i>
                </button>
            </div>
            <div id="password_confirmation-error" class="text-red-600 text-sm mt-1 hidden"></div>
        </div>

        <!-- Security Note -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                <div>
                    <h4 class="font-semibold text-blue-800">Lưu ý bảo mật</h4>
                    <ul class="text-blue-700 text-sm mt-1 space-y-1">
                        <li>• Sử dụng mật khẩu mạnh với ít nhất 8 ký tự</li>
                        <li>• Kết hợp chữ hoa, chữ thường, số và ký tự đặc biệt</li>
                        <li>• Không chia sẻ thông tin đăng nhập với người khác</li>
                        <li>• Bạn có thể thay đổi thông tin này sau khi hoàn thành cài đặt</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" 
                class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors">
            <i class="fas fa-user-plus mr-2"></i>
            Tạo tài khoản Admin
        </button>
    </div>
</form>

<!-- Navigation -->
<div id="navigation-section" class="hidden border-t pt-6 mt-6">
    <div class="flex justify-between">
        <a href="{{ route('setup.step', 'database') }}" 
           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Quay lại
        </a>
        
        <button onclick="goToNextStep('website')"
                class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
            Tiếp theo
            <i class="fas fa-arrow-right ml-2"></i>
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
function createAdmin(event) {
    event.preventDefault();
    
    // Clear previous errors
    clearErrors();
    
    // Validate form
    if (!validateForm()) {
        return;
    }
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());
    
    showLoading('Đang tạo tài khoản admin...');
    
    submitStep('{{ route('setup.process', 'admin') }}', data, (response) => {
        document.getElementById('navigation-section').classList.remove('hidden');
        
        // Auto proceed to next step after 2 seconds
        setTimeout(() => {
            goToNextStep('website');
        }, 2000);
    });
}

function validateForm() {
    let isValid = true;
    
    // Validate name
    const name = document.getElementById('name').value.trim();
    if (!name) {
        showError('name', 'Vui lòng nhập họ và tên');
        isValid = false;
    }
    
    // Validate email
    const email = document.getElementById('email').value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email) {
        showError('email', 'Vui lòng nhập email');
        isValid = false;
    } else if (!emailRegex.test(email)) {
        showError('email', 'Email không hợp lệ');
        isValid = false;
    }
    
    // Validate password
    const password = document.getElementById('password').value;
    if (!password) {
        showError('password', 'Vui lòng nhập mật khẩu');
        isValid = false;
    } else if (password.length < 8) {
        showError('password', 'Mật khẩu phải có ít nhất 8 ký tự');
        isValid = false;
    }
    
    // Validate password confirmation
    const passwordConfirmation = document.getElementById('password_confirmation').value;
    if (!passwordConfirmation) {
        showError('password_confirmation', 'Vui lòng xác nhận mật khẩu');
        isValid = false;
    } else if (password !== passwordConfirmation) {
        showError('password_confirmation', 'Mật khẩu xác nhận không khớp');
        isValid = false;
    }
    
    return isValid;
}

function showError(field, message) {
    const errorDiv = document.getElementById(`${field}-error`);
    const inputField = document.getElementById(field);
    
    errorDiv.textContent = message;
    errorDiv.classList.remove('hidden');
    inputField.classList.add('border-red-500');
}

function clearErrors() {
    const errorDivs = document.querySelectorAll('[id$="-error"]');
    const inputFields = document.querySelectorAll('input');
    
    errorDivs.forEach(div => {
        div.classList.add('hidden');
        div.textContent = '';
    });
    
    inputFields.forEach(input => {
        input.classList.remove('border-red-500');
    });
}

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(`${fieldId}-icon`);
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Real-time validation
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input');
    
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
});
</script>
@endpush
