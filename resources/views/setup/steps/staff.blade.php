@extends('setup.layout')

@section('title', 'Cấu hình Đội ngũ nhân viên')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-users text-2xl text-red-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Cấu hình Đội ngũ nhân viên</h1>
            <p class="text-gray-600">Thiết lập hệ thống quản lý nhân viên và hiển thị đội ngũ trên website</p>
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
        <form id="staffForm" class="space-y-6">
            @csrf
            
            <!-- Enable Staff Option -->
            <div class="bg-gray-50 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Kích hoạt quản lý nhân viên</h3>
                        <p class="text-gray-600">Bật tính năng quản lý và hiển thị thông tin đội ngũ nhân viên</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="enable_staff" id="enable_staff" class="sr-only peer" value="1">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                    </label>
                </div>
            </div>

            <!-- Staff Configuration (Hidden by default) -->
            <div id="staffConfig" class="space-y-6" style="display: none;">
                
                <!-- Basic Settings -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="staff_title" class="block text-sm font-medium text-gray-700 mb-2">
                            Tiêu đề trang nhân viên <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="staff_title" 
                               id="staff_title" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                               placeholder="Ví dụ: Đội ngũ của chúng tôi"
                               value="Đội ngũ của chúng tôi">
                    </div>

                    <div>
                        <label for="staff_per_page" class="block text-sm font-medium text-gray-700 mb-2">
                            Số nhân viên mỗi trang
                        </label>
                        <select name="staff_per_page" 
                                id="staff_per_page" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <option value="8">8 nhân viên</option>
                            <option value="12" selected>12 nhân viên</option>
                            <option value="16">16 nhân viên</option>
                            <option value="20">20 nhân viên</option>
                        </select>
                    </div>
                </div>

                <!-- Staff Description -->
                <div>
                    <label for="staff_description" class="block text-sm font-medium text-gray-700 mb-2">
                        Mô tả về đội ngũ
                    </label>
                    <textarea name="staff_description" 
                              id="staff_description" 
                              rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                              placeholder="Mô tả ngắn về đội ngũ nhân viên của bạn..."></textarea>
                </div>

                <!-- Additional Options -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               name="enable_positions" 
                               id="enable_positions" 
                               class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500"
                               value="1" 
                               checked>
                        <label for="enable_positions" class="ml-2 text-sm text-gray-700">
                            Hiển thị chức vụ nhân viên
                        </label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" 
                               name="enable_contact_info" 
                               id="enable_contact_info" 
                               class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500"
                               value="1" 
                               checked>
                        <label for="enable_contact_info" class="ml-2 text-sm text-gray-700">
                            Hiển thị thông tin liên hệ
                        </label>
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" 
                           name="enable_social_links" 
                           id="enable_social_links" 
                           class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500"
                           value="1" 
                           checked>
                    <label for="enable_social_links" class="ml-2 text-sm text-gray-700">
                        Kích hoạt liên kết mạng xã hội
                    </label>
                </div>

            </div>

            <!-- Create Sample Data Option -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="create_sample_data"
                               name="create_sample_data"
                               type="checkbox"
                               value="1"
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                    </div>
                    <div class="ml-3">
                        <label for="create_sample_data" class="text-sm font-medium text-blue-800">
                            Tạo dữ liệu mẫu cho đội ngũ nhân viên
                        </label>
                        <p class="text-sm text-blue-700 mt-1">
                            Tạo 6 nhân viên mẫu với đầy đủ thông tin để bạn có thể xem trước giao diện.
                        </p>
                        <div class="mt-2 text-xs text-blue-600">
                            <strong>Sẽ tạo:</strong>
                            <ul class="list-disc list-inside mt-1 space-y-1">
                                <li>6 nhân viên với các chức vụ khác nhau</li>
                                <li>Thông tin đầy đủ: ảnh, mô tả, liên hệ, mạng xã hội</li>
                                <li>Dữ liệu SEO-friendly và responsive</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Skip Option -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center">
                    <input type="checkbox"
                           name="skip_staff"
                           id="skip_staff"
                           class="w-4 h-4 text-yellow-600 bg-gray-100 border-gray-300 rounded focus:ring-yellow-500">
                    <label for="skip_staff" class="ml-2 text-sm text-gray-700">
                        <strong>Bỏ qua bước này</strong> - Tôi sẽ cấu hình nhân viên sau
                    </label>
                </div>
                <p class="text-xs text-gray-500 mt-1 ml-6">
                    Bạn có thể cài đặt quản lý nhân viên sau thông qua admin panel
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between pt-6">
                <div class="flex space-x-3">
                    <a href="{{ route('setup.step', 'blog') }}"
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Quay lại
                    </a>

                    <button type="button"
                            id="resetBtn"
                            class="px-6 py-2 border border-orange-300 text-orange-600 rounded-lg hover:bg-orange-50 transition-colors">
                        <i class="fas fa-undo mr-2"></i>Reset Staff
                    </button>
                </div>

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
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Đang cấu hình nhân viên...</h3>
            <p class="text-gray-600">Vui lòng đợi trong giây lát</p>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const enableStaffCheckbox = document.getElementById('enable_staff');
    const staffConfig = document.getElementById('staffConfig');
    const skipStaffCheckbox = document.getElementById('skip_staff');
    const createSampleDataCheckbox = document.getElementById('create_sample_data');
    const form = document.getElementById('staffForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const loadingModal = document.getElementById('loadingModal');
    const resetBtn = document.getElementById('resetBtn');

    // Toggle staff configuration
    enableStaffCheckbox.addEventListener('change', function() {
        if (this.checked) {
            staffConfig.style.display = 'block';
            skipStaffCheckbox.checked = false;
        } else {
            staffConfig.style.display = 'none';
        }
    });

    // Handle skip option
    skipStaffCheckbox.addEventListener('change', function() {
        if (this.checked) {
            enableStaffCheckbox.checked = false;
            staffConfig.style.display = 'none';
            createSampleDataCheckbox.checked = false;
            createSampleDataCheckbox.disabled = true;
        } else {
            createSampleDataCheckbox.disabled = false;
        }
    });

    // Handle sample data option
    createSampleDataCheckbox.addEventListener('change', function() {
        if (this.checked && skipStaffCheckbox.checked) {
            skipStaffCheckbox.checked = false;
            createSampleDataCheckbox.disabled = false;
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

        fetch('{{ route("setup.process", "staff") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            // Kiểm tra content type trước khi parse JSON
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                // Nếu không phải JSON, có thể là HTML error page
                return response.text().then(text => {
                    throw new Error('Server trả về HTML thay vì JSON. Có thể có lỗi server. Response: ' + text.substring(0, 200) + '...');
                });
            }
        })
        .then(data => {
            if (data.success) {
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else if (data.next_step) {
                    window.location.href = '{{ route("setup.step", "") }}/' + data.next_step;
                } else {
                    window.location.href = '{{ route("setup.complete") }}';
                }
            } else {
                // Hiển thị lỗi chi tiết hơn
                let errorMessage = 'Có lỗi xảy ra khi cấu hình staff:\n\n';

                if (data.message) {
                    errorMessage += '• ' + data.message + '\n';
                }

                if (data.error) {
                    errorMessage += '• Chi tiết: ' + data.error + '\n';
                }

                if (data.errors) {
                    errorMessage += '• Validation errors:\n';
                    Object.keys(data.errors).forEach(field => {
                        data.errors[field].forEach(error => {
                            errorMessage += '  - ' + field + ': ' + error + '\n';
                        });
                    });
                }

                alert(errorMessage);
                loadingModal.classList.add('hidden');
                loadingModal.classList.remove('flex');
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            let errorMessage = 'Có lỗi xảy ra khi xử lý yêu cầu:\n\n';

            if (error.response && error.response.data) {
                if (error.response.data.message) {
                    errorMessage += '• ' + error.response.data.message + '\n';
                }
                if (error.response.data.error) {
                    errorMessage += '• Chi tiết: ' + error.response.data.error + '\n';
                }
            } else {
                errorMessage += '• ' + error.message;
            }

            alert(errorMessage);
            loadingModal.classList.add('hidden');
            loadingModal.classList.remove('flex');
            submitBtn.disabled = false;
        });

    // Handle reset button
    resetBtn.addEventListener('click', function() {
        if (confirm('Bạn có chắc chắn muốn reset staff step? Tất cả dữ liệu và files liên quan đến staff sẽ bị xóa.')) {
            resetBtn.disabled = true;
            resetBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang reset...';

            fetch('{{ route("setup.reset.step", "staff") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Reset staff step thành công!');
                    window.location.reload();
                } else {
                    alert('Lỗi: ' + (data.message || 'Có lỗi xảy ra khi reset'));
                    resetBtn.disabled = false;
                    resetBtn.innerHTML = '<i class="fas fa-undo mr-2"></i>Reset Staff';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi reset');
                resetBtn.disabled = false;
                resetBtn.innerHTML = '<i class="fas fa-undo mr-2"></i>Reset Staff';
            });
        }
    });
});
</script>
@endpush
