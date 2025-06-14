@extends('setup.layout')

@section('title', 'Cấu hình Database - Core Framework Setup')
@section('description', 'Kiểm tra kết nối database và tạo bảng dữ liệu')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="text-center mb-12">
        <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-soft">
            <i class="fas fa-database text-2xl text-blue-600"></i>
        </div>
        <h2 class="text-3xl font-light text-slate-800 mb-4">Cấu hình Database</h2>
        <p class="text-lg text-slate-600 leading-relaxed max-w-2xl mx-auto">
            Chúng tôi sẽ kiểm tra kết nối database và tạo các bảng cần thiết cho hệ thống.
        </p>
    </div>

    <!-- Database Info -->
    <div class="glass-card rounded-2xl p-8 mb-8 shadow-soft border border-slate-100">
        <div class="flex items-center mb-6">
            <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-info-circle text-slate-600"></i>
            </div>
            <h3 class="text-xl font-semibold text-slate-800">Thông tin Database hiện tại</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="flex justify-between items-center py-3 border-b border-slate-100">
                    <span class="text-slate-600 font-medium">Driver:</span>
                    <span class="font-semibold text-slate-800 bg-slate-100 px-3 py-1 rounded-lg">{{ config('database.default') }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-slate-100">
                    <span class="text-slate-600 font-medium">Host:</span>
                    <span class="font-semibold text-slate-800 bg-slate-100 px-3 py-1 rounded-lg">{{ config('database.connections.' . config('database.default') . '.host') }}</span>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex justify-between items-center py-3 border-b border-slate-100">
                    <span class="text-slate-600 font-medium">Port:</span>
                    <span class="font-semibold text-slate-800 bg-slate-100 px-3 py-1 rounded-lg">{{ config('database.connections.' . config('database.default') . '.port') }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-slate-100">
                    <span class="text-slate-600 font-medium">Database:</span>
                    <span class="font-semibold text-slate-800 bg-slate-100 px-3 py-1 rounded-lg">{{ config('database.connections.' . config('database.default') . '.database') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Test Connection -->
    <div class="mb-8">
        <button id="test-connection-btn"
                onclick="testConnection()"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-6 rounded-2xl transition-all duration-200 shadow-soft hover:shadow-hover transform hover:-translate-y-0.5">
            <i class="fas fa-plug mr-3"></i>
            Kiểm tra kết nối Database
        </button>
    </div>

    <!-- Connection Status -->
    <div id="connection-status" class="hidden mb-8">
        <!-- Will be populated by JavaScript -->
    </div>

    <!-- Migration Section -->
    <div id="migration-section" class="hidden">
        <div class="glass-card rounded-2xl p-8 shadow-soft border border-slate-100">
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-database text-emerald-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-slate-800">Tạo bảng Database</h3>
            </div>
            <p class="text-slate-600 mb-6 leading-relaxed">
                Hệ thống sẽ tạo tất cả các bảng cần thiết cho ứng dụng.
                Quá trình này có thể mất vài phút.
            </p>

            <div class="glass-card bg-amber-50 border border-amber-200 rounded-2xl p-6 mb-6">
                <div class="flex items-start">
                    <div class="w-8 h-8 bg-amber-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-amber-600"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-amber-800 mb-2">Lưu ý quan trọng</h4>
                        <p class="text-amber-700 text-sm leading-relaxed">
                            Nếu database đã có dữ liệu, tất cả sẽ bị xóa và thay thế.
                            Hãy đảm bảo bạn đã sao lưu dữ liệu quan trọng.
                        </p>
                    </div>
                </div>
            </div>

            <button id="run-migration-btn"
                    onclick="runMigration()"
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-4 px-6 rounded-2xl transition-all duration-200 shadow-soft hover:shadow-hover transform hover:-translate-y-0.5">
                <i class="fas fa-play mr-3"></i>
                Tạo bảng Database
            </button>
        </div>
    </div>

    <!-- Navigation -->
    <div id="navigation-section" class="hidden border-t border-slate-200 pt-8 mt-8">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <a href="{{ route('setup.index') }}"
               class="px-6 py-3 bg-slate-100 text-slate-700 font-medium rounded-2xl hover:bg-slate-200 transition-all duration-200 border border-slate-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Quay lại
            </a>

            <button id="next-step-btn"
                    onclick="goToNextStep('admin')"
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
    statusDiv.className = `glass-card rounded-2xl p-6 shadow-soft border ${
        success ? 'border-emerald-200 bg-emerald-50' : 'border-red-200 bg-red-50'
    }`;

    statusDiv.innerHTML = `
        <div class="flex items-start">
            <div class="w-10 h-10 ${success ? 'bg-emerald-100' : 'bg-red-100'} rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                <i class="fas fa-${success ? 'check-circle text-emerald-600' : 'times-circle text-red-600'}"></i>
            </div>
            <div class="flex-1">
                <h4 class="font-semibold text-lg ${success ? 'text-emerald-800' : 'text-red-800'} mb-2">
                    ${success ? 'Kết nối thành công!' : 'Kết nối thất bại!'}
                </h4>
                <p class="${success ? 'text-emerald-700' : 'text-red-700'} leading-relaxed">
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
