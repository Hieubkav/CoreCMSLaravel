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
        <div class="max-w-6xl mx-auto mb-8">
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
                $totalSteps = count($stepKeys);

                // Group steps for better display
                $groupedSteps = [
                    'core' => array_filter($allSteps, fn($s) => $s['group'] === 'core'),
                    'system' => array_filter($allSteps, fn($s) => $s['group'] === 'system'),
                    'modules' => array_filter($allSteps, fn($s) => $s['group'] === 'modules'),
                    'final' => array_filter($allSteps, fn($s) => $s['group'] === 'final')
                ];
            @endphp

            <!-- Compact Progress Indicator -->
            <div class="bg-white rounded-lg border p-4 mb-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-sm font-medium text-gray-700">
                        Bước {{ $currentStepNumber }} / {{ $totalSteps }}
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ $allSteps[$currentStepKey]['title'] ?? 'Unknown' }}
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-red-600 h-2 rounded-full transition-all duration-300"
                         style="width: {{ (($currentStepNumber - 1) / ($totalSteps - 1)) * 100 }}%"></div>
                </div>

                <!-- Group Indicators -->
                <div class="flex justify-between mt-3 text-xs">
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
                            <div class="w-3 h-3 rounded-full mr-2
                                {{ $isGroupCompleted ? 'bg-green-500' : ($isGroupActive ? 'bg-red-500' : 'bg-gray-300') }}">
                            </div>
                            <span class="text-gray-600 capitalize">
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
        function submitStep(url, data, successCallback, errorCallback) {
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
                        if (errorCallback) {
                            errorCallback(response.data);
                        }
                    }
                })
                .catch(error => {
                    hideLoading();
                    const message = error.response?.data?.error ||
                                  error.response?.data?.message ||
                                  'Có lỗi xảy ra khi xử lý yêu cầu';
                    showAlert(message, 'error');
                    console.error('Setup Error:', error);
                    if (errorCallback) {
                        errorCallback(error);
                    }
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
