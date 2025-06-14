<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cài đặt Core Framework')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        .glass-card { backdrop-filter: blur(20px); background: rgba(255, 255, 255, 0.8); }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .shadow-soft { box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); }
        .shadow-hover { box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12); }
        .generation-progress {
            transition: all 0.3s ease;
        }
        .generation-item {
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .success-pulse {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen">
    <div class="container mx-auto px-6 py-12 max-w-7xl">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-3xl mb-6 shadow-soft">
                <i class="fas fa-cogs text-2xl text-blue-600"></i>
            </div>
            <h1 class="text-4xl font-light text-slate-800 mb-4 tracking-tight">
                Core Framework <span class="font-semibold text-blue-600">Setup</span>
            </h1>
            <p class="text-lg text-slate-600 leading-relaxed">
                @yield('description', 'Thiết lập dự án của bạn')
            </p>
        </div>

        <!-- Progress Bar -->
        <div class="max-w-5xl mx-auto mb-12">
            @php
                $allSteps = [
                    'database' => ['title' => 'Database', 'group' => 'core'],
                    'admin' => ['title' => 'Admin', 'group' => 'core'],
                    'website' => ['title' => 'Website', 'group' => 'core'],
                    'sample-data' => ['title' => 'Sample Data', 'group' => 'core'],
                    'frontend-config' => ['title' => 'Frontend', 'group' => 'system'],
                    'admin-config' => ['title' => 'Admin Panel', 'group' => 'system'],
                    'module-user-roles' => ['title' => 'User Roles', 'group' => 'modules'],
                    'module-blog' => ['title' => 'Blog', 'group' => 'modules'],
                    'module-staff' => ['title' => 'Staff', 'group' => 'modules'],
                    'module-content' => ['title' => 'Content', 'group' => 'modules'],
                    'module-ecommerce' => ['title' => 'E-commerce', 'group' => 'modules'],
                    'modules-summary' => ['title' => 'Summary', 'group' => 'final'],
                    'installation' => ['title' => 'Install', 'group' => 'final'],
                    'complete' => ['title' => 'Complete', 'group' => 'final']
                ];

                $stepKeys = array_keys($allSteps);
                $currentStepKey = $step ?? 'database';
                $currentIndex = array_search($currentStepKey, $stepKeys);
                if ($currentIndex === false) $currentIndex = 0;

                // Get actual step number from step data
                $currentStepNumber = $allSteps[$currentStepKey]['step'] ?? ($currentIndex + 1);
                $totalSteps = count($allSteps);

                // Debug: Log step calculation (only in local)
                if (app()->environment('local')) {
                    \Log::info('Step calculation debug', [
                        'currentStepKey' => $currentStepKey,
                        'currentIndex' => $currentIndex,
                        'stepFromData' => $allSteps[$currentStepKey]['step'] ?? 'not found',
                        'calculatedStepNumber' => $currentStepNumber,
                        'totalSteps' => $totalSteps,
                        'allStepsCount' => count($allSteps)
                    ]);
                }

                // Group steps for better display
                $groupedSteps = [
                    'core' => array_filter($allSteps, fn($s) => $s['group'] === 'core'),
                    'system' => array_filter($allSteps, fn($s) => $s['group'] === 'system'),
                    'modules' => array_filter($allSteps, fn($s) => $s['group'] === 'modules'),
                    'final' => array_filter($allSteps, fn($s) => $s['group'] === 'final')
                ];
            @endphp

            <!-- Compact Progress Indicator -->
            <div class="glass-card rounded-2xl p-6 mb-8 shadow-soft border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-100 rounded-xl flex items-center justify-center mr-3">
                            <span class="text-blue-600 font-semibold text-sm">{{ $currentStepNumber }}</span>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-slate-700">
                                Bước {{ $currentStepNumber }} / {{ $totalSteps }}
                            </div>
                            <div class="text-xs text-slate-500">
                                {{ $allSteps[$currentStepKey]['title'] ?? 'Unknown' }}
                            </div>
                        </div>
                    </div>
                    <div class="text-sm text-slate-500 font-medium">
                        {{ round((($currentStepNumber - 1) / ($totalSteps - 1)) * 100) }}%
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="w-full bg-slate-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full transition-all duration-500"
                         style="width: {{ (($currentStepNumber - 1) / ($totalSteps - 1)) * 100 }}%"></div>
                </div>

                <!-- Group Indicators -->
                <div class="flex justify-between mt-4 text-xs">
                    @foreach($groupedSteps as $groupName => $groupSteps)
                        @php
                            $groupStepNumbers = [];
                            foreach ($groupSteps as $stepData) {
                                if (isset($stepData['step'])) {
                                    $groupStepNumbers[] = $stepData['step'];
                                }
                            }

                            if (!empty($groupStepNumbers)) {
                                $groupStartStep = min($groupStepNumbers);
                                $groupEndStep = max($groupStepNumbers);
                                $isGroupActive = $currentStepNumber >= $groupStartStep && $currentStepNumber <= $groupEndStep;
                                $isGroupCompleted = $currentStepNumber > $groupEndStep;
                            } else {
                                $isGroupActive = false;
                                $isGroupCompleted = false;
                            }
                        @endphp
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full mr-2 transition-colors duration-300
                                {{ $isGroupCompleted ? 'bg-emerald-500' : ($isGroupActive ? 'bg-blue-500' : 'bg-slate-300') }}">
                            </div>
                            <span class="text-slate-600 font-medium capitalize">
                                {{ $groupName === 'core' ? 'Cơ bản' :
                                   ($groupName === 'system' ? 'Hệ thống' :
                                   ($groupName === 'modules' ? 'Modules' : 'Hoàn thành')) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="w-full">
            @yield('content')
        </div>

        <!-- Footer -->
        <div class="text-center mt-16">
            <p class="text-slate-500 text-sm font-light">
                Core Framework v1.0 - Powered by Laravel
            </p>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm items-center justify-center hidden z-50">
        <div class="glass-card rounded-3xl p-8 max-w-sm mx-4 shadow-hover border border-slate-200">
            <div class="text-center">
                <div class="w-12 h-12 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mx-auto mb-4"></div>
                <p class="text-slate-600 font-medium" id="loading-text">Đang xử lý...</p>
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

        // AJAX form submission helper with generation progress
        function submitStep(url, data, successCallback, errorCallback) {
            showLoading();

            axios.post(url, data)
                .then(response => {
                    hideLoading();
                    if (response.data.success) {
                        showAlert(response.data.message, 'success');

                        // Show generation results if available
                        if (response.data.generation_results) {
                            showGenerationResults(response.data.generation_results);
                        }

                        if (successCallback) {
                            successCallback(response.data);
                        }
                    } else {
                        // Handle validation errors
                        if (response.data.errors) {
                            handleValidationErrors(response.data.errors);
                            showAlert('Vui lòng kiểm tra lại thông tin đã nhập', 'error');
                        } else {
                            showAlert(response.data.error || response.data.message || 'Có lỗi xảy ra', 'error');
                        }

                        if (errorCallback) {
                            errorCallback(response.data);
                        }
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Setup Error Details:', error);

                    let message = 'Có lỗi xảy ra khi xử lý yêu cầu';
                    let details = [];

                    if (error.response) {
                        // Server responded with error status
                        const status = error.response.status;
                        const data = error.response.data;

                        if (status === 422 && data.errors) {
                            // Validation errors
                            handleValidationErrors(data.errors);
                            message = 'Dữ liệu không hợp lệ. Vui lòng kiểm tra lại.';
                        } else if (status === 419) {
                            message = 'Phiên làm việc đã hết hạn. Vui lòng tải lại trang.';
                        } else if (status === 500) {
                            message = 'Lỗi server nội bộ. Vui lòng thử lại sau.';
                            if (data.message) {
                                details.push('Chi tiết: ' + data.message);
                            }
                        } else if (data.error) {
                            message = data.error;
                        } else if (data.message) {
                            message = data.message;
                        } else {
                            message = `Lỗi HTTP ${status}`;
                        }

                        // Add technical details for debugging
                        details.push(`HTTP ${status}: ${error.response.statusText}`);
                        if (data.file && data.line) {
                            details.push(`File: ${data.file}:${data.line}`);
                        }

                    } else if (error.request) {
                        // Network error
                        message = 'Không thể kết nối đến server. Vui lòng kiểm tra kết nối mạng.';
                        details.push('Network Error: ' + error.message);
                    } else {
                        // Other error
                        message = 'Lỗi không xác định: ' + error.message;
                    }

                    // Show detailed error in development
                    if (window.location.hostname === '127.0.0.1' || window.location.hostname === 'localhost') {
                        if (details.length > 0) {
                            message += '\n\nChi tiết lỗi (chỉ hiển thị trong development):\n' + details.join('\n');
                        }
                    }

                    showAlert(message, 'error');

                    if (errorCallback) {
                        errorCallback(error);
                    }
                });
        }

        // Handle validation errors
        function handleValidationErrors(errors) {
            Object.keys(errors).forEach(field => {
                const errorMessage = Array.isArray(errors[field]) ? errors[field][0] : errors[field];
                showFieldError(field, errorMessage);
            });
        }

        // Show field-specific error
        function showFieldError(fieldName, message) {
            const field = document.getElementById(fieldName);
            const errorDiv = document.getElementById(`${fieldName}-error`);

            if (field) {
                field.classList.add('border-red-500');
                field.focus();
            }

            if (errorDiv) {
                errorDiv.textContent = message;
                errorDiv.classList.remove('hidden');
            } else {
                // If no specific error div, show in alert
                console.warn(`No error div found for field: ${fieldName}`);
            }
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

        // Show generation results
        function showGenerationResults(results) {
            // Create or update generation results container
            let container = document.getElementById('generation-results');
            if (!container) {
                container = document.createElement('div');
                container.id = 'generation-results';
                container.className = 'mt-6 p-4 bg-green-50 border border-green-200 rounded-lg generation-progress';

                // Insert after loading container or at the end of main content
                const loadingContainer = document.getElementById('loading-container');
                const mainContent = document.querySelector('.max-w-4xl') || document.querySelector('.container');
                if (loadingContainer && loadingContainer.parentNode) {
                    loadingContainer.parentNode.insertBefore(container, loadingContainer.nextSibling);
                } else if (mainContent) {
                    mainContent.appendChild(container);
                }
            }

            let html = '<div class="flex items-center mb-3"><i class="fas fa-check-circle text-green-600 mr-2"></i><h3 class="text-lg font-semibold text-green-800">✅ Code Generation Completed!</h3></div>';

            if (results.generation) {
                html += '<div class="space-y-2">';
                for (const [type, items] of Object.entries(results.generation)) {
                    if (Array.isArray(items) && items.length > 0) {
                        html += `<div class="generation-item">
                            <div class="flex items-center text-sm text-green-700">
                                <i class="fas fa-file-code mr-2"></i>
                                <span class="font-medium capitalize">${type}:</span>
                                <span class="ml-1">${items.length} files created</span>
                            </div>
                            <div class="ml-6 text-xs text-green-600 space-y-1">
                                ${items.map(item => `<div>• ${item}</div>`).join('')}
                            </div>
                        </div>`;
                    }
                }
                html += '</div>';
            }

            if (results.migration) {
                html += `<div class="generation-item mt-3 p-2 bg-blue-50 border border-blue-200 rounded">
                    <div class="flex items-center text-sm text-blue-700">
                        <i class="fas fa-database mr-2"></i>
                        <span>🗄️ ${results.migration}</span>
                    </div>
                </div>`;
            }

            if (results.generation_error) {
                html += `<div class="generation-item mt-3 p-2 bg-red-50 border border-red-200 rounded">
                    <div class="flex items-center text-sm text-red-700">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span>❌ Generation Error: ${results.generation_error}</span>
                    </div>
                </div>`;
            }

            container.innerHTML = html;
            container.classList.add('success-pulse');

            // Remove pulse animation after 2 seconds
            setTimeout(() => {
                container.classList.remove('success-pulse');
            }, 2000);
        }

        // Clear generation results
        function clearGenerationResults() {
            const container = document.getElementById('generation-results');
            if (container) {
                container.remove();
            }
        }
    </script>

    @stack('scripts')
</body>
</html>
