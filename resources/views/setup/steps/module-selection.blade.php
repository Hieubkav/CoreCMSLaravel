@extends('setup.layout')

@section('title', 'Chọn Module - Core Framework Setup')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-4">
            <i class="fas fa-puzzle-piece text-2xl text-red-600"></i>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Chọn Module cần cài đặt</h1>
        <p class="text-gray-600">
            Lựa chọn các module phù hợp với dự án của bạn. Các module bắt buộc sẽ được tự động cài đặt.
        </p>
    </div>

    <!-- Module Selection Form -->
    <form id="module-selection-form" onsubmit="selectModules(event)" class="space-y-6">
        @csrf
        
        <!-- Required Modules -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-4">
                <i class="fas fa-star text-blue-600 mr-2"></i>
                Module Bắt buộc (Tự động cài đặt)
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @php
                    $availableModules = \App\Models\SetupModule::getAvailableModules();
                    $requiredModules = array_filter($availableModules, fn($module) => $module['required']);
                @endphp
                
                @foreach($requiredModules as $moduleName => $moduleInfo)
                <div class="bg-white border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mr-3">
                            <i class="{{ $moduleInfo['icon'] }} text-blue-600 text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">{{ $moduleInfo['title'] }}</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ $moduleInfo['description'] }}</p>
                            <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mt-2">
                                Bắt buộc
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Optional Modules -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-check-square text-green-600 mr-2"></i>
                Module Tùy chọn
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @php
                    $optionalModules = array_filter($availableModules, fn($module) => !$module['required']);
                @endphp
                
                @foreach($optionalModules as $moduleName => $moduleInfo)
                <div class="border border-gray-200 rounded-lg p-4 hover:border-red-300 transition-colors">
                    <label class="flex items-start cursor-pointer">
                        <input type="checkbox" 
                               name="selected_modules[]" 
                               value="{{ $moduleName }}"
                               class="mt-1 mr-3 text-red-600 focus:ring-red-500 rounded">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <i class="{{ $moduleInfo['icon'] }} text-gray-600 text-lg mr-2"></i>
                                <h4 class="font-semibold text-gray-900">{{ $moduleInfo['title'] }}</h4>
                            </div>
                            <p class="text-sm text-gray-600">{{ $moduleInfo['description'] }}</p>
                        </div>
                    </label>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Sample Data Option -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-yellow-900 mb-4">
                <i class="fas fa-database text-yellow-600 mr-2"></i>
                Dữ liệu mẫu
            </h3>
            <label class="flex items-start cursor-pointer">
                <input type="checkbox" 
                       name="install_sample_data" 
                       value="1"
                       checked
                       class="mt-1 mr-3 text-yellow-600 focus:ring-yellow-500 rounded">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Cài đặt dữ liệu mẫu</h4>
                    <p class="text-sm text-gray-600 mb-3">
                        Tạo dữ liệu mẫu bằng tiếng Việt cho các module được chọn để bạn có thể bắt đầu nhanh chóng.
                    </p>
                    <ul class="text-xs text-gray-500 space-y-1 ml-4">
                        <li>• Bài viết và danh mục mẫu</li>
                        <li>• Sản phẩm và danh mục (nếu chọn E-commerce)</li>
                        <li>• Nhân viên và thông tin liên hệ</li>
                        <li>• Nội dung trang chủ hoàn chỉnh</li>
                        <li>• Menu và cấu trúc navigation</li>
                    </ul>
                </div>
            </label>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center pt-6">
            <button type="button" 
                    onclick="goToPreviousStep('sample-data')"
                    class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Quay lại
            </button>
            
            <button type="submit" 
                    class="px-8 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="fas fa-arrow-right mr-2"></i>
                Tiếp tục cài đặt
            </button>
        </div>
    </form>

    <!-- Progress Info -->
    <div class="mt-8 text-center">
        <p class="text-sm text-gray-500">
            Bước 6/6 - Sau khi chọn module, hệ thống sẽ tự động cài đặt và cấu hình
        </p>
    </div>
</div>

<!-- Loading Modal -->
<div id="loading-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
        <div class="text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-red-600 mx-auto mb-4"></div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Đang xử lý...</h3>
            <p class="text-gray-600" id="loading-text">Đang lưu lựa chọn modules...</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function selectModules(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());
    
    // Convert selected_modules to array if it exists
    const selectedModules = formData.getAll('selected_modules[]');
    data.selected_modules = selectedModules;
    
    showLoading('Đang lưu lựa chọn modules...');
    
    submitStep('{{ route('setup.process', 'module-selection') }}', data, (response) => {
        showAlert('Đã lưu lựa chọn modules thành công!', 'success');
        
        // Show selected modules info
        if (response.modules_to_install && response.modules_to_install.length > 0) {
            console.log('Modules sẽ được cài đặt:', response.modules_to_install);
        }
        
        // Auto proceed to next step after 2 seconds
        setTimeout(() => {
            completeSetup(); // Hoặc chuyển đến bước cài đặt modules
        }, 2000);
    });
}

function goToPreviousStep(step) {
    window.location.href = `{{ route('setup.step', '') }}/${step}`;
}

function showLoading(text) {
    document.getElementById('loading-text').textContent = text;
    document.getElementById('loading-modal').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loading-modal').classList.add('hidden');
}

// Module selection helpers
document.addEventListener('DOMContentLoaded', function() {
    // Add visual feedback for checkbox selection
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const card = this.closest('.border');
            if (card) {
                if (this.checked) {
                    card.classList.add('border-red-300', 'bg-red-50');
                } else {
                    card.classList.remove('border-red-300', 'bg-red-50');
                }
            }
        });
    });
});
</script>
@endpush
