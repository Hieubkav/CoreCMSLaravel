@extends('setup.layout')

@section('title', 'Tạo tài khoản Admin - Core Framework Setup')
@section('description', 'Tạo tài khoản quản trị viên đầu tiên cho hệ thống')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="text-center mb-12">
        <div class="w-20 h-20 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-soft">
            <i class="fas fa-user-shield text-2xl text-indigo-600"></i>
        </div>
        <h2 class="text-3xl font-light text-slate-800 mb-4">Tạo tài khoản Admin</h2>
        <p class="text-lg text-slate-600 leading-relaxed">
            Tạo tài khoản quản trị viên đầu tiên để truy cập vào bảng điều khiển admin.
        </p>
    </div>

    <!-- Admin Form -->
    <div class="glass-card rounded-2xl p-8 shadow-soft border border-slate-100">
        <form id="admin-form" onsubmit="createAdmin(event)">
            <div class="space-y-6">
                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-800 mb-3">
                        Họ và tên <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="name"
                           name="name"
                           required
                           class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-slate-50 focus:bg-white shadow-soft"
                           placeholder="Nhập họ và tên của bạn">
                    <div id="name-error" class="text-red-600 text-sm mt-2 hidden"></div>
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-800 mb-3">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email"
                           id="email"
                           name="email"
                           required
                           class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-slate-50 focus:bg-white shadow-soft"
                           placeholder="admin@example.com">
                    <div id="email-error" class="text-red-600 text-sm mt-2 hidden"></div>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-800 mb-3">
                        Mật khẩu <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password"
                               id="password"
                               name="password"
                               required
                               minlength="8"
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-slate-50 focus:bg-white pr-12 shadow-soft"
                               placeholder="Tối thiểu 8 ký tự">
                        <button type="button"
                                onclick="togglePassword('password')"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-500 hover:text-slate-700 transition-colors">
                            <i id="password-icon" class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div id="password-error" class="text-red-600 text-sm mt-2 hidden"></div>
                    <div class="text-slate-500 text-sm mt-2">
                        Mật khẩu phải có ít nhất 8 ký tự
                    </div>
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-800 mb-3">
                        Xác nhận mật khẩu <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               required
                               class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-slate-50 focus:bg-white pr-12 shadow-soft"
                               placeholder="Nhập lại mật khẩu">
                        <button type="button"
                                onclick="togglePassword('password_confirmation')"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-500 hover:text-slate-700 transition-colors">
                            <i id="password_confirmation-icon" class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div id="password_confirmation-error" class="text-red-600 text-sm mt-2 hidden"></div>
                </div>

                <!-- Security Note -->
                <div class="glass-card bg-blue-50 border border-blue-200 rounded-2xl p-6">
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-blue-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-600"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-blue-800 mb-3">Lưu ý bảo mật</h4>
                            <ul class="text-blue-700 text-sm space-y-2">
                                <li class="flex items-start">
                                    <div class="w-1.5 h-1.5 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                                    Sử dụng mật khẩu mạnh với ít nhất 8 ký tự
                                </li>
                                <li class="flex items-start">
                                    <div class="w-1.5 h-1.5 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                                    Kết hợp chữ hoa, chữ thường, số và ký tự đặc biệt
                                </li>
                                <li class="flex items-start">
                                    <div class="w-1.5 h-1.5 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                                    Không chia sẻ thông tin đăng nhập với người khác
                                </li>
                                <li class="flex items-start">
                                    <div class="w-1.5 h-1.5 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                                    Bạn có thể thay đổi thông tin này sau khi hoàn thành cài đặt
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-4 px-6 rounded-2xl transition-all duration-200 shadow-soft hover:shadow-hover transform hover:-translate-y-0.5">
                    <i class="fas fa-user-plus mr-3"></i>
                    Tạo tài khoản Admin
                </button>
            </div>
        </form>
    </div>

    <!-- Navigation -->
    <div id="navigation-section" class="hidden border-t border-slate-200 pt-8 mt-8">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <a href="{{ route('setup.step', 'database') }}"
               class="px-6 py-3 bg-slate-100 text-slate-700 font-medium rounded-2xl hover:bg-slate-200 transition-all duration-200 border border-slate-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Quay lại
            </a>

            <button onclick="goToNextStep('website')"
                    class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-2xl transition-all duration-200 shadow-soft hover:shadow-hover">
                Tiếp theo
                <i class="fas fa-arrow-right ml-2"></i>
            </button>
        </div>
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
