@extends('setup.layout')

@section('title', 'Cấu hình Database - Core Framework Setup')
@section('description', 'Kiểm tra kết nối database và tạo bảng dữ liệu')

@section('content')
<div class="text-center mb-8">
    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-database text-2xl text-blue-600"></i>
    </div>
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Cấu hình Database</h2>
    <p class="text-gray-600">
        Chúng tôi sẽ kiểm tra kết nối database và tạo các bảng cần thiết cho hệ thống.
    </p>
</div>

<!-- Database Info -->
<div class="bg-gray-50 rounded-lg p-6 mb-6">
    <h3 class="font-semibold text-gray-900 mb-4">Thông tin Database hiện tại</h3>
    <div class="grid grid-cols-2 gap-4 text-sm">
        <div>
            <span class="text-gray-600">Driver:</span>
            <span class="font-medium">{{ config('database.default') }}</span>
        </div>
        <div>
            <span class="text-gray-600">Host:</span>
            <span class="font-medium">{{ config('database.connections.' . config('database.default') . '.host') }}</span>
        </div>
        <div>
            <span class="text-gray-600">Port:</span>
            <span class="font-medium">{{ config('database.connections.' . config('database.default') . '.port') }}</span>
        </div>
        <div>
            <span class="text-gray-600">Database:</span>
            <span class="font-medium">{{ config('database.connections.' . config('database.default') . '.database') }}</span>
        </div>
    </div>
</div>

<!-- Test Connection -->
<div class="mb-6">
    <button id="test-connection-btn" 
            onclick="testConnection()"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors">
        <i class="fas fa-plug mr-2"></i>
        Kiểm tra kết nối Database
    </button>
</div>

<!-- Connection Status -->
<div id="connection-status" class="hidden mb-6">
    <!-- Will be populated by JavaScript -->
</div>

<!-- Migration Section -->
<div id="migration-section" class="hidden">
    <div class="border-t pt-6">
        <h3 class="font-semibold text-gray-900 mb-4">Tạo bảng Database</h3>
        <p class="text-gray-600 mb-4">
            Hệ thống sẽ tạo tất cả các bảng cần thiết cho ứng dụng. 
            Quá trình này có thể mất vài phút.
        </p>
        
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
            <div class="flex items-start">
                <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                <div>
                    <h4 class="font-semibold text-yellow-800">Lưu ý quan trọng</h4>
                    <p class="text-yellow-700 text-sm mt-1">
                        Nếu database đã có dữ liệu, tất cả sẽ bị xóa và thay thế. 
                        Hãy đảm bảo bạn đã sao lưu dữ liệu quan trọng.
                    </p>
                </div>
            </div>
        </div>

        <button id="run-migration-btn" 
                onclick="runMigration()"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors">
            <i class="fas fa-play mr-2"></i>
            Tạo bảng Database
        </button>
    </div>
</div>

<!-- Navigation -->
<div id="navigation-section" class="hidden border-t pt-6 mt-6">
    <div class="flex justify-between">
        <a href="{{ route('setup.index') }}" 
           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Quay lại
        </a>
        
        <button id="next-step-btn" 
                onclick="goToNextStep('admin')"
                class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
            Tiếp theo
            <i class="fas fa-arrow-right ml-2"></i>
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
function testConnection() {
    showLoading('Đang kiểm tra kết nối database...');
    
    submitStep('{{ route('setup.process', 'database') }}', {
        test_connection: true
    }, (data) => {
        showConnectionStatus(true, data.message);
        document.getElementById('migration-section').classList.remove('hidden');
    });
}

function runMigration() {
    if (!confirm('Bạn có chắc chắn muốn tạo bảng database? Dữ liệu hiện tại sẽ bị xóa.')) {
        return;
    }
    
    showLoading('Đang tạo bảng database...');
    
    submitStep('{{ route('setup.process', 'database') }}', {
        test_connection: false
    }, (data) => {
        showConnectionStatus(true, data.message);
        document.getElementById('navigation-section').classList.remove('hidden');
        
        // Auto proceed to next step after 2 seconds
        setTimeout(() => {
            goToNextStep('admin');
        }, 2000);
    });
}

function showConnectionStatus(success, message) {
    const statusDiv = document.getElementById('connection-status');
    statusDiv.className = `p-4 rounded-lg border ${
        success ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200'
    }`;
    
    statusDiv.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${success ? 'check-circle text-green-600' : 'times-circle text-red-600'} mr-3"></i>
            <div>
                <h4 class="font-semibold ${success ? 'text-green-800' : 'text-red-800'}">
                    ${success ? 'Kết nối thành công!' : 'Kết nối thất bại!'}
                </h4>
                <p class="${success ? 'text-green-700' : 'text-red-700'} text-sm mt-1">
                    ${message}
                </p>
            </div>
        </div>
    `;
    
    statusDiv.classList.remove('hidden');
}

// Override the default error handler for this page
window.addEventListener('DOMContentLoaded', function() {
    const originalSubmitStep = window.submitStep;
    window.submitStep = function(url, data, successCallback) {
        showLoading();
        
        axios.post(url, data)
            .then(response => {
                hideLoading();
                if (response.data.success) {
                    showAlert(response.data.message, 'success');
                    if (successCallback) {
                        successCallback(response.data);
                    }
                } else {
                    showAlert(response.data.error || 'Có lỗi xảy ra', 'error');
                }
            })
            .catch(error => {
                hideLoading();
                const message = error.response?.data?.error || 
                              error.response?.data?.message || 
                              'Có lỗi xảy ra khi xử lý yêu cầu';
                
                // Show connection status for database errors
                if (url.includes('database')) {
                    showConnectionStatus(false, message);
                } else {
                    showAlert(message, 'error');
                }
                console.error('Setup Error:', error);
            });
    };
});
</script>
@endpush
