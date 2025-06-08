@extends('setup.layout')

@section('title', 'Dữ liệu mẫu - Core Framework Setup')
@section('description', 'Import dữ liệu mẫu để bắt đầu nhanh với website của bạn')

@section('content')
<div class="text-center mb-8">
    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-database text-2xl text-orange-600"></i>
    </div>
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Dữ liệu mẫu</h2>
    <p class="text-gray-600">
        Import dữ liệu mẫu để có thể bắt đầu sử dụng website ngay lập tức.
    </p>
</div>

<!-- Sample Data Options -->
<div class="space-y-6">
    <!-- Option 1: Import Sample Data -->
    <div class="border border-gray-200 rounded-lg p-6">
        <div class="flex items-start">
            <input type="radio" 
                   id="import-sample" 
                   name="data-option" 
                   value="import" 
                   class="mt-1 mr-4 text-red-600 focus:ring-red-500"
                   checked>
            <div class="flex-1">
                <label for="import-sample" class="block font-semibold text-gray-900 mb-2 cursor-pointer">
                    <i class="fas fa-download text-green-600 mr-2"></i>
                    Import dữ liệu mẫu (Khuyến nghị)
                </label>
                <p class="text-gray-600 mb-4">
                    Hệ thống sẽ tạo dữ liệu mẫu bao gồm:
                </p>
                <ul class="text-sm text-gray-600 space-y-1 ml-4">
                    <li>• <strong>Khóa học mẫu:</strong> 6 khóa học với đầy đủ thông tin</li>
                    <li>• <strong>Bài viết mẫu:</strong> 8 bài viết blog và tin tức</li>
                    <li>• <strong>Danh mục:</strong> Danh mục cho khóa học và bài viết</li>
                    <li>• <strong>student mẫu:</strong> 20 student với thông tin đa dạng</li>
                    <li>• <strong>instructor:</strong> 3 instructor với hồ sơ chi tiết</li>
                    <li>• <strong>Slider & Banner:</strong> Hình ảnh trang chủ</li>
                    <li>• <strong>Menu & Navigation:</strong> Cấu trúc menu hoàn chỉnh</li>
                    <li>• <strong>Cài đặt cơ bản:</strong> Logo, favicon, thông tin liên hệ</li>
                </ul>
                
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mt-4">
                    <div class="flex items-start">
                        <i class="fas fa-lightbulb text-green-600 mt-1 mr-3"></i>
                        <div>
                            <h4 class="font-semibold text-green-800">Lợi ích của dữ liệu mẫu</h4>
                            <ul class="text-green-700 text-sm mt-1 space-y-1">
                                <li>• Hiểu rõ cách thức hoạt động của hệ thống</li>
                                <li>• Có template để tùy chỉnh theo nhu cầu</li>
                                <li>• Tiết kiệm thời gian thiết lập ban đầu</li>
                                <li>• Dễ dàng test các tính năng</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Option 2: Start Fresh -->
    <div class="border border-gray-200 rounded-lg p-6">
        <div class="flex items-start">
            <input type="radio" 
                   id="start-fresh" 
                   name="data-option" 
                   value="fresh" 
                   class="mt-1 mr-4 text-red-600 focus:ring-red-500">
            <div class="flex-1">
                <label for="start-fresh" class="block font-semibold text-gray-900 mb-2 cursor-pointer">
                    <i class="fas fa-plus-circle text-blue-600 mr-2"></i>
                    Bắt đầu với dữ liệu trống
                </label>
                <p class="text-gray-600 mb-4">
                    Bắt đầu với hệ thống hoàn toàn trống, chỉ có:
                </p>
                <ul class="text-sm text-gray-600 space-y-1 ml-4">
                    <li>• Tài khoản admin đã tạo</li>
                    <li>• Cấu hình website cơ bản</li>
                    <li>• Cấu trúc database trống</li>
                    <li>• Menu mặc định</li>
                </ul>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                        <div>
                            <h4 class="font-semibold text-blue-800">Phù hợp khi</h4>
                            <ul class="text-blue-700 text-sm mt-1 space-y-1">
                                <li>• Bạn muốn tự tạo nội dung từ đầu</li>
                                <li>• Đã có kế hoạch nội dung cụ thể</li>
                                <li>• Muốn kiểm soát hoàn toàn dữ liệu</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Processing Status -->
    <div id="processing-status" class="hidden">
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-red-600 mx-auto mb-4"></div>
                <h3 class="font-semibold text-gray-900 mb-2">Đang xử lý dữ liệu...</h3>
                <p class="text-gray-600" id="processing-text">Vui lòng đợi trong giây lát</p>
                
                <div class="mt-4">
                    <div class="bg-gray-200 rounded-full h-2">
                        <div id="progress-bar" class="bg-red-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2" id="progress-text">0%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div id="action-buttons">
        <button onclick="processSampleData()" 
                class="w-full bg-orange-600 hover:bg-orange-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors">
            <i class="fas fa-rocket mr-2"></i>
            Tiếp tục với lựa chọn đã chọn
        </button>
    </div>
</div>

<!-- Navigation -->
<div id="navigation-section" class="hidden border-t pt-6 mt-6">
    <div class="flex justify-between">
        <a href="{{ route('setup.step', 'configuration') }}"
           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Quay lại
        </a>
        
        <button onclick="completeSetup()"
                class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
            <i class="fas fa-check mr-2"></i>
            Hoàn thành cài đặt
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
function processSampleData() {
    const selectedOption = document.querySelector('input[name="data-option"]:checked').value;
    const importSample = selectedOption === 'import';
    
    // Hide action buttons and show processing
    document.getElementById('action-buttons').classList.add('hidden');
    document.getElementById('processing-status').classList.remove('hidden');
    
    if (importSample) {
        simulateProgress();
        showLoading('Đang import dữ liệu mẫu...');
    } else {
        document.getElementById('processing-text').textContent = 'Đang hoàn tất cài đặt...';
        showLoading('Đang hoàn tất cài đặt...');
    }
    
    submitStep('{{ route('setup.process', 'sample-data') }}', {
        import_sample: importSample
    }, (response) => {
        document.getElementById('processing-status').classList.add('hidden');
        document.getElementById('navigation-section').classList.remove('hidden');
        
        // Show completion message
        showCompletionMessage(importSample);
    });
}

function simulateProgress() {
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    const processingText = document.getElementById('processing-text');
    
    const steps = [
        { progress: 20, text: 'Đang tạo danh mục...' },
        { progress: 40, text: 'Đang tạo khóa học...' },
        { progress: 60, text: 'Đang tạo bài viết...' },
        { progress: 80, text: 'Đang tạo student...' },
        { progress: 100, text: 'Hoàn thành!' }
    ];
    
    let currentStep = 0;
    
    const interval = setInterval(() => {
        if (currentStep < steps.length) {
            const step = steps[currentStep];
            progressBar.style.width = step.progress + '%';
            progressText.textContent = step.progress + '%';
            processingText.textContent = step.text;
            currentStep++;
        } else {
            clearInterval(interval);
        }
    }, 1000);
}

function showCompletionMessage(importedSample) {
    const message = importedSample 
        ? 'Dữ liệu mẫu đã được import thành công! Bạn có thể bắt đầu khám phá website.'
        : 'Cài đặt hoàn tất! Website của bạn đã sẵn sàng để sử dụng.';
    
    showAlert(message, 'success');
}

// Radio button change handler
document.addEventListener('DOMContentLoaded', function() {
    const radioButtons = document.querySelectorAll('input[name="data-option"]');
    
    radioButtons.forEach(radio => {
        radio.addEventListener('change', function() {
            // Update button text based on selection
            const button = document.querySelector('#action-buttons button');
            const selectedOption = this.value;
            
            if (selectedOption === 'import') {
                button.innerHTML = '<i class="fas fa-download mr-2"></i>Import dữ liệu mẫu';
                button.className = 'w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors';
            } else {
                button.innerHTML = '<i class="fas fa-plus-circle mr-2"></i>Bắt đầu với dữ liệu trống';
                button.className = 'w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors';
            }
        });
    });
});
</script>
@endpush
