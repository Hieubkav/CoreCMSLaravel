<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cài đặt Core Framework</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Environment Notice -->
        @if(app()->environment('local'))
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-blue-600 mr-3"></i>
                <div>
                    <h3 class="text-blue-800 font-semibold">Development Mode</h3>
                    <p class="text-blue-700 text-sm">
                        Bạn đang ở môi trường <strong>local</strong>. Setup wizard luôn có thể truy cập để test và phát triển.
                        Trong production, setup wizard sẽ tự động bị khóa sau khi hoàn thành.
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Success Message -->
        @if(request('completed') && request('message'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-green-800 mb-2">
                        Setup Hoàn Thành!
                    </h3>
                    <p class="text-green-700">
                        {{ request('message') }}
                    </p>
                    <div class="mt-4 flex flex-wrap gap-3">
                        <a href="{{ route('filament.admin.pages.dashboard') }}"
                           class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-user-shield mr-2"></i>
                            Vào Admin Panel
                        </a>
                        <a href="{{ route('storeFront') }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-home mr-2"></i>
                            Xem Website
                        </a>
                        @if(isset($isSetupCompleted) && $isSetupCompleted)
                        <a href="/blog"
                           class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-blog mr-2"></i>
                            Xem Blog
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-6">
                <i class="fas fa-cogs text-3xl text-red-600"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Chào mừng đến với Core Framework
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto mb-8">
                Hệ thống quản lý nội dung đa năng được xây dựng trên Laravel.
                Hãy bắt đầu thiết lập dự án của bạn trong vài phút.
            </p>
        </div>

        @if(isset($isSetupCompleted) && $isSetupCompleted)
        <!-- Setup Completed - Action Buttons -->
        <div class="bg-gradient-to-r from-green-600 to-emerald-600 rounded-lg p-8 text-white text-center mb-8">
            <div class="flex items-center justify-center mb-4">
                <i class="fas fa-check-circle text-4xl mr-3"></i>
                <h2 class="text-3xl font-bold">Hệ thống đã được cài đặt!</h2>
            </div>
            <p class="text-xl mb-6 opacity-90">
                Core Framework đã sẵn sàng hoạt động. Bạn có thể truy cập admin panel hoặc reset để cài lại.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('filament.admin.pages.dashboard') }}"
                   class="inline-flex items-center px-8 py-4 bg-white text-green-600 font-semibold rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-user-shield mr-2"></i>
                    Vào Admin Panel
                </a>
                <a href="{{ route('storeFront') }}"
                   class="inline-flex items-center px-8 py-4 bg-white/20 text-white font-semibold rounded-lg hover:bg-white/30 transition-colors">
                    <i class="fas fa-home mr-2"></i>
                    Xem Website
                </a>
                @if(app()->environment('local'))
                <button onclick="showResetConfirmation()"
                        class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-undo mr-2"></i>
                    Reset & Cài lại
                </button>
                @endif
            </div>
        </div>
        @else
        <!-- Ready to Start - Action Buttons -->
        <div class="bg-gradient-to-r from-red-600 to-orange-600 rounded-lg p-8 text-white text-center mb-8">
            <h2 class="text-3xl font-bold mb-4">Sẵn sàng bắt đầu?</h2>
            <p class="text-xl mb-6 opacity-90">
                Chỉ cần vài bước đơn giản để có một website hoàn chỉnh
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('setup.step', 'database') }}"
                   class="inline-flex items-center px-8 py-4 bg-white text-red-600 font-semibold rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-rocket mr-2"></i>
                    Bắt đầu cài đặt ngay
                </a>
                <div class="text-white/80 text-sm">
                    <i class="fas fa-info-circle mr-1"></i>
                    Không cần kinh nghiệm kỹ thuật
                </div>
            </div>
        </div>
        @endif

        <!-- Development Tools (Always visible in local) -->
        @if(app()->environment('local'))
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-tools text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-yellow-800 mb-2">
                        Development Tools
                    </h3>
                    <p class="text-yellow-700 text-sm mb-4">
                        Các công cụ hỗ trợ development và testing. Chỉ hiển thị trong môi trường local.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <button onclick="showResetConfirmation()"
                                class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-undo mr-2"></i>
                            Reset Hệ thống
                        </button>
                        <a href="{{ route('filament.admin.pages.dashboard') }}"
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-user-shield mr-2"></i>
                            Admin Panel
                        </a>
                        <a href="{{ route('storeFront') }}"
                           class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                            <i class="fas fa-home mr-2"></i>
                            Xem Website
                        </a>
                        <button onclick="clearCache()"
                                class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors">
                            <i class="fas fa-broom mr-2"></i>
                            Clear Cache
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Footer -->
        <div class="text-center mt-12 pt-8 border-t border-gray-200">
            <p class="text-gray-500">
                Core Framework v1.0 - Được xây dựng với ❤️ bằng Laravel
            </p>
        </div>
    </div>

    <!-- Reset Confirmation Modal -->
    <div id="resetModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg p-8 max-w-md w-full">
                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Xác nhận Reset</h3>
                    <p class="text-gray-600 mb-6">
                        Bạn có chắc chắn muốn reset toàn bộ hệ thống? Hành động này sẽ:
                    </p>
                    <ul class="text-left text-sm text-gray-600 mb-6 space-y-2">
                        <li class="flex items-center">
                            <i class="fas fa-times text-red-500 mr-2"></i>
                            Xóa tất cả dữ liệu trong database
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-times text-red-500 mr-2"></i>
                            Xóa tất cả files trong storage
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-times text-red-500 mr-2"></i>
                            Clear tất cả cache
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-exclamation text-orange-500 mr-2"></i>
                            Không thể hoàn tác
                        </li>
                    </ul>
                    <div class="flex gap-4">
                        <button onclick="hideResetConfirmation()"
                                class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Hủy
                        </button>
                        <button onclick="performReset()"
                                class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                            <i class="fas fa-undo mr-2"></i>
                            Reset ngay
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div id="loadingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg p-8 max-w-md w-full text-center">
                <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-red-600 mx-auto mb-4"></div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Đang reset hệ thống...</h3>
                <p class="text-gray-600">Vui lòng đợi, quá trình này có thể mất vài phút.</p>
                <div class="mt-4">
                    <div id="resetProgress" class="text-sm text-gray-500"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showResetConfirmation() {
            document.getElementById('resetModal').classList.remove('hidden');
        }

        function hideResetConfirmation() {
            document.getElementById('resetModal').classList.add('hidden');
        }

        function showLoading() {
            document.getElementById('loadingModal').classList.remove('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingModal').classList.add('hidden');
        }

        function updateProgress(message) {
            document.getElementById('resetProgress').textContent = message;
        }

        async function performReset() {
            hideResetConfirmation();
            showLoading();

            try {
                updateProgress('Đang xóa database...');

                const response = await fetch('{{ route('setup.reset') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        confirm: 'yes'
                    })
                });

                const result = await response.json();

                if (result.success) {
                    updateProgress('Reset thành công!');

                    // Show success message
                    setTimeout(() => {
                        hideLoading();
                        alert('Reset thành công! Trang sẽ được tải lại.');
                        window.location.reload();
                    }, 1000);
                } else {
                    hideLoading();
                    alert('Lỗi: ' + result.message);
                }
            } catch (error) {
                hideLoading();
                alert('Có lỗi xảy ra: ' + error.message);
            }
        }

        // Clear cache function
        async function clearCache() {
            try {
                const response = await fetch('{{ route("dev.clear-cache") }}', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (result.message) {
                    alert('✅ ' + result.message);
                } else {
                    alert('❌ Lỗi: ' + (result.error || 'Unknown error'));
                }
            } catch (error) {
                alert('❌ Có lỗi xảy ra: ' + error.message);
            }
        }

        // Close modal when clicking outside
        document.getElementById('resetModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideResetConfirmation();
            }
        });
    </script>
</body>
</html>
