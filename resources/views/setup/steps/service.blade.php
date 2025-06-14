@extends('setup.layout')

@section('title', 'Cài đặt Module Dịch vụ')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                <span>Bước {{ $currentStepNumber }} / {{ $totalSteps }}</span>
                <span>{{ round(($currentStepNumber / $totalSteps) * 100) }}% hoàn thành</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full transition-all duration-300" 
                     style="width: {{ ($currentStepNumber / $totalSteps) * 100 }}%"></div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-6">
                <div class="flex items-center">
                    <div class="bg-white/20 rounded-lg p-3 mr-4">
                        <i class="fas fa-wrench-screwdriver text-2xl text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">Cài đặt Module Dịch vụ</h1>
                        <p class="text-blue-100 mt-1">Thiết lập trang dịch vụ và quản lý portfolio</p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form id="serviceForm" class="p-8">
                @csrf
                
                <!-- Enable Service Module -->
                <div class="mb-8">
                    <div class="flex items-start space-x-4 p-6 bg-gray-50 rounded-xl">
                        <div class="flex items-center h-5">
                            <input id="enable_service" 
                                   name="enable_service" 
                                   type="checkbox" 
                                   value="1"
                                   checked
                                   class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        </div>
                        <div class="flex-1">
                            <label for="enable_service" class="text-lg font-semibold text-gray-900 cursor-pointer">
                                Kích hoạt Module Dịch vụ
                            </label>
                            <p class="text-gray-600 mt-1">
                                Tạo trang dịch vụ với khả năng quản lý danh mục, giá cả, tính năng và portfolio
                            </p>
                            <div class="mt-3 text-sm text-gray-500">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    <div class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-2"></i>
                                        Quản lý dịch vụ với Filament Admin
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-2"></i>
                                        Trang dịch vụ responsive với filter
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-2"></i>
                                        Quản lý giá cả và tính năng
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-check text-green-500 mr-2"></i>
                                        SEO-friendly URLs và meta tags
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service Configuration -->
                <div id="serviceConfig" class="space-y-6">
                    <!-- Service Title -->
                    <div>
                        <label for="service_title" class="block text-sm font-medium text-gray-700 mb-2">
                            Tiêu đề trang dịch vụ
                        </label>
                        <input type="text" 
                               id="service_title" 
                               name="service_title" 
                               value="Dịch vụ của chúng tôi"
                               placeholder="VD: Dịch vụ chuyên nghiệp"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <p class="text-sm text-gray-500 mt-1">Tiêu đề hiển thị trên trang danh sách dịch vụ</p>
                    </div>

                    <!-- Service Description -->
                    <div>
                        <label for="service_description" class="block text-sm font-medium text-gray-700 mb-2">
                            Mô tả trang dịch vụ
                        </label>
                        <textarea id="service_description" 
                                  name="service_description" 
                                  rows="3"
                                  placeholder="VD: Chúng tôi cung cấp các dịch vụ chuyên nghiệp với chất lượng cao và giá cả hợp lý"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">Chúng tôi cung cấp các dịch vụ chuyên nghiệp với chất lượng cao</textarea>
                        <p class="text-sm text-gray-500 mt-1">Mô tả ngắn hiển thị dưới tiêu đề trang dịch vụ</p>
                    </div>

                    <!-- Sample Data -->
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-6">
                        <div class="flex items-start space-x-4">
                            <div class="flex items-center h-5">
                                <input id="create_sample_data" 
                                       name="create_sample_data" 
                                       type="checkbox" 
                                       value="1"
                                       checked
                                       class="w-5 h-5 text-amber-600 bg-gray-100 border-gray-300 rounded focus:ring-amber-500 focus:ring-2">
                            </div>
                            <div class="flex-1">
                                <label for="create_sample_data" class="text-lg font-semibold text-amber-900 cursor-pointer">
                                    Tạo dữ liệu mẫu
                                </label>
                                <p class="text-amber-700 mt-1">
                                    Tạo 8 dịch vụ mẫu với đầy đủ thông tin để bạn có thể xem trước và tùy chỉnh
                                </p>
                                <div class="mt-3 text-sm text-amber-600">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        <div>• Thiết kế Website (15M VNĐ)</div>
                                        <div>• Ứng dụng Mobile (50M VNĐ)</div>
                                        <div>• E-commerce (30M VNĐ)</div>
                                        <div>• SEO & Marketing (8M VNĐ)</div>
                                        <div>• Logo & Branding (5M VNĐ)</div>
                                        <div>• Tư vấn chuyển đổi số</div>
                                        <div>• Bảo trì Website (2M VNĐ)</div>
                                        <div>• Hệ thống CRM (25M VNĐ)</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-8 border-t border-gray-200">
                    <div class="flex space-x-3">
                        <button type="button"
                                onclick="goToPreviousStep()"
                                class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Quay lại
                        </button>

                        <button type="button"
                                id="resetBtn"
                                class="inline-flex items-center px-6 py-3 border border-orange-300 text-orange-600 rounded-lg hover:bg-orange-50 transition-colors">
                            <i class="fas fa-undo mr-2"></i>Reset Service
                        </button>
                    </div>

                    <div class="flex space-x-4">
                        <button type="button"
                                onclick="skipStep()"
                                class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-forward mr-2"></i>
                            Bỏ qua
                        </button>
                        
                        <button type="submit" 
                                id="submitBtn"
                                class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-medium rounded-lg hover:from-blue-700 hover:to-indigo-800 transition-all duration-200 transform hover:scale-105 shadow-lg">
                            <span id="submitText">Tiếp tục</span>
                            <i class="fas fa-arrow-right ml-2" id="submitIcon"></i>
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" id="loadingIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <div class="bg-white rounded-xl p-6 shadow-lg">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-100 rounded-lg p-3 mr-3">
                        <i class="fas fa-cogs text-blue-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900">Quản lý dễ dàng</h3>
                </div>
                <p class="text-gray-600 text-sm">Interface admin thân thiện với đầy đủ tính năng quản lý dịch vụ</p>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-lg">
                <div class="flex items-center mb-4">
                    <div class="bg-green-100 rounded-lg p-3 mr-3">
                        <i class="fas fa-mobile-alt text-green-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900">Responsive Design</h3>
                </div>
                <p class="text-gray-600 text-sm">Giao diện tối ưu cho mọi thiết bị từ desktop đến mobile</p>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-lg">
                <div class="flex items-center mb-4">
                    <div class="bg-purple-100 rounded-lg p-3 mr-3">
                        <i class="fas fa-search text-purple-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900">SEO Optimized</h3>
                </div>
                <p class="text-gray-600 text-sm">Tối ưu SEO với meta tags, structured data và URLs thân thiện</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const enableCheckbox = document.getElementById('enable_service');
    const configSection = document.getElementById('serviceConfig');
    const form = document.getElementById('serviceForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitIcon = document.getElementById('submitIcon');
    const loadingIcon = document.getElementById('loadingIcon');
    const resetBtn = document.getElementById('resetBtn');

    // Toggle config section based on enable checkbox
    function toggleConfigSection() {
        if (enableCheckbox.checked) {
            configSection.style.display = 'block';
            configSection.style.opacity = '1';
        } else {
            configSection.style.display = 'none';
        }
    }

    enableCheckbox.addEventListener('change', toggleConfigSection);
    toggleConfigSection(); // Initial state

    // Form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Show loading state
        submitBtn.disabled = true;
        submitText.textContent = 'Đang xử lý...';
        submitIcon.classList.add('hidden');
        loadingIcon.classList.remove('hidden');

        try {
            const formData = new FormData(form);
            
            const response = await fetch('{{ route("setup.process", "service") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });

            const result = await response.json();

            if (result.success) {
                // Show success message
                showNotification('success', result.message);
                
                // Wait a bit then redirect
                setTimeout(() => {
                    if (result.next_step === 'complete') {
                        window.location.href = '{{ route("setup.index") }}?completed=1&message=' + encodeURIComponent(result.message);
                    } else {
                        window.location.href = '{{ route("setup.step", "") }}/' + result.next_step;
                    }
                }, 1500);
            } else {
                throw new Error(result.message || 'Có lỗi xảy ra');
            }
        } catch (error) {
            console.error('Setup error:', error);

            // Hiển thị lỗi chi tiết hơn
            let errorMessage = 'Có lỗi xảy ra khi cấu hình service:\n\n';

            if (error.response && error.response.data) {
                if (error.response.data.message) {
                    errorMessage += '• ' + error.response.data.message + '\n';
                }
                if (error.response.data.error) {
                    errorMessage += '• Chi tiết: ' + error.response.data.error + '\n';
                }
                if (error.response.data.errors) {
                    errorMessage += '• Validation errors:\n';
                    Object.keys(error.response.data.errors).forEach(field => {
                        error.response.data.errors[field].forEach(err => {
                            errorMessage += '  - ' + field + ': ' + err + '\n';
                        });
                    });
                }
            } else {
                errorMessage += '• ' + (error.message || 'Unknown error');
            }

            alert(errorMessage);

            // Reset button state
            submitBtn.disabled = false;
            submitText.textContent = 'Tiếp tục';
            submitIcon.classList.remove('hidden');
            loadingIcon.classList.add('hidden');
        }
    });

    // Handle reset button
    resetBtn.addEventListener('click', function() {
        if (confirm('Bạn có chắc chắn muốn reset service step? Tất cả dữ liệu và files liên quan đến service sẽ bị xóa.')) {
            resetBtn.disabled = true;
            resetBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang reset...';

            fetch('{{ route("setup.reset.step", "service") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Reset service step thành công!');
                    window.location.reload();
                } else {
                    alert('Lỗi: ' + (data.message || 'Có lỗi xảy ra khi reset'));
                    resetBtn.disabled = false;
                    resetBtn.innerHTML = '<i class="fas fa-undo mr-2"></i>Reset Service';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi reset');
                resetBtn.disabled = false;
                resetBtn.innerHTML = '<i class="fas fa-undo mr-2"></i>Reset Service';
            });
        }
    });
});

function skipStep() {
    const enableCheckbox = document.getElementById('enable_service');
    enableCheckbox.checked = false;
    document.getElementById('serviceForm').dispatchEvent(new Event('submit'));
}

function goToPreviousStep() {
    window.location.href = '{{ route("setup.step", "staff") }}';
}

function showNotification(type, message) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation-triangle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 5000);
}
</script>
@endsection
