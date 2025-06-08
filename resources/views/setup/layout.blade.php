<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cài đặt Core Framework')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full mb-4">
                <i class="fas fa-cogs text-2xl text-red-600"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                Core Framework Setup
            </h1>
            <p class="text-gray-600">
                @yield('description', 'Thiết lập dự án của bạn')
            </p>
        </div>

        <!-- Progress Bar -->
        <div class="max-w-4xl mx-auto mb-8">
            <div class="flex items-center justify-between mb-4">
                @php
                    $steps = ['database', 'admin', 'website', 'configuration', 'sample-data'];
                    $currentIndex = array_search($step ?? 'database', $steps);
                @endphp
                
                @foreach($steps as $index => $stepName)
                    <div class="flex items-center {{ $index < count($steps) - 1 ? 'flex-1' : '' }}">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full 
                            {{ $index <= $currentIndex ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-500' }}">
                            {{ $index + 1 }}
                        </div>
                        @if($index < count($steps) - 1)
                            <div class="flex-1 h-1 mx-4 
                                {{ $index < $currentIndex ? 'bg-red-600' : 'bg-gray-200' }}">
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            
            <div class="flex justify-between text-sm text-gray-600">
                <span>Database</span>
                <span>Admin</span>
                <span>Website</span>
                <span>Cấu hình</span>
                <span>Hoàn thành</span>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow-sm border p-8">
                @yield('content')
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-gray-500 text-sm">
                Core Framework v1.0 - Powered by Laravel
            </p>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg p-8 max-w-sm mx-4">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-red-600 mx-auto mb-4"></div>
                <p class="text-gray-600" id="loading-text">Đang xử lý...</p>
            </div>
        </div>
    </div>

    <script>
        // Utility functions
        function showLoading(text = 'Đang xử lý...') {
            const overlay = document.getElementById('loading-overlay');
            document.getElementById('loading-text').textContent = text;
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
        }

        function hideLoading() {
            const overlay = document.getElementById('loading-overlay');
            overlay.classList.add('hidden');
            overlay.classList.remove('flex');
        }

        function showAlert(message, type = 'success') {
            const alertDiv = document.createElement('div');
            alertDiv.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 
                'bg-red-100 text-red-800 border border-red-200'
            }`;
            alertDiv.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            document.body.appendChild(alertDiv);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertDiv.parentElement) {
                    alertDiv.remove();
                }
            }, 5000);
        }

        // AJAX form submission helper
        function submitStep(url, data, successCallback) {
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
                    showAlert(message, 'error');
                    console.error('Setup Error:', error);
                });
        }

        // Navigation helpers
        function goToNextStep(step) {
            setTimeout(() => {
                window.location.href = `{{ route('setup.step', '') }}/${step}`;
            }, 1500);
        }

        function completeSetup() {
            submitStep('{{ route('setup.complete') }}', {}, (data) => {
                setTimeout(() => {
                    window.location.href = data.redirect || '/';
                }, 2000);
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
