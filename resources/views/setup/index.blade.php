<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cài đặt Core Framework</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        .glass-card { backdrop-filter: blur(20px); background: rgba(255, 255, 255, 0.8); }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .shadow-soft { box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); }
        .shadow-hover { box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12); }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen">
    <div class="container mx-auto px-6 py-12 max-w-6xl">
        <!-- Environment Notice -->
        @if(app()->environment('local'))
        <div class="glass-card border border-blue-100 rounded-2xl p-6 mb-8 shadow-soft">
            <div class="flex items-start">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                    <i class="fas fa-code text-blue-600"></i>
                </div>
                <div>
                    <h3 class="text-slate-800 font-semibold text-lg mb-2">Development Mode</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Bạn đang ở môi trường <span class="font-medium text-blue-600">local</span>. Setup wizard luôn có thể truy cập để test và phát triển.
                        Trong production, setup wizard sẽ tự động bị khóa sau khi hoàn thành.
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Success Message -->
        @if(request('completed') && request('message'))
        <div class="glass-card border border-emerald-100 rounded-2xl p-8 mb-8 shadow-soft">
            <div class="flex items-start">
                <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center mr-6 flex-shrink-0">
                    <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-semibold text-slate-800 mb-3">
                        Setup Hoàn Thành!
                    </h3>
                    <p class="text-slate-600 mb-6 leading-relaxed">
                        {{ request('message') }}
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('filament.admin.pages.dashboard') }}"
                           class="inline-flex items-center px-6 py-3 bg-slate-800 text-white font-medium rounded-xl hover:bg-slate-900 transition-all duration-200 shadow-soft hover:shadow-hover">
                            <i class="fas fa-user-shield mr-2"></i>
                            Vào Admin Panel
                        </a>
                        <a href="{{ route('storeFront') }}"
                           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-all duration-200 shadow-soft hover:shadow-hover">
                            <i class="fas fa-home mr-2"></i>
                            Xem Website
                        </a>
                        @if(isset($isSetupCompleted) && $isSetupCompleted)
                        <a href="/blog"
                           class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition-all duration-200 shadow-soft hover:shadow-hover">
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
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-3xl mb-8 shadow-soft">
                <i class="fas fa-cogs text-3xl text-blue-600"></i>
            </div>
            <h1 class="text-5xl font-light text-slate-800 mb-6 tracking-tight">
                Chào mừng đến với <span class="font-semibold text-blue-600">Core Framework</span>
            </h1>
            <p class="text-xl text-slate-600 max-w-3xl mx-auto mb-8 leading-relaxed">
                Hệ thống quản lý nội dung đa năng được xây dựng trên Laravel.
                Hãy bắt đầu thiết lập dự án của bạn trong vài phút.
            </p>
        </div>

        @if(isset($isSetupCompleted) && $isSetupCompleted)
        <!-- Setup Completed - Action Buttons -->
        <div class="glass-card rounded-3xl p-10 text-center mb-12 shadow-soft border border-emerald-100">
            <div class="flex items-center justify-center mb-6">
                <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mr-4">
                    <i class="fas fa-check-circle text-emerald-600 text-2xl"></i>
                </div>
                <h2 class="text-3xl font-semibold text-slate-800">Hệ thống đã được cài đặt!</h2>
            </div>
            <p class="text-xl text-slate-600 mb-8 leading-relaxed max-w-2xl mx-auto">
                Core Framework đã sẵn sàng hoạt động. Bạn có thể truy cập admin panel hoặc reset để cài lại.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('filament.admin.pages.dashboard') }}"
                   class="inline-flex items-center px-8 py-4 bg-slate-800 text-white font-semibold rounded-2xl hover:bg-slate-900 transition-all duration-200 shadow-soft hover:shadow-hover">
                    <i class="fas fa-user-shield mr-3"></i>
                    Vào Admin Panel
                </a>
                <a href="{{ route('storeFront') }}"
                   class="inline-flex items-center px-8 py-4 bg-blue-600 text-white font-semibold rounded-2xl hover:bg-blue-700 transition-all duration-200 shadow-soft hover:shadow-hover">
                    <i class="fas fa-home mr-3"></i>
                    Xem Website
                </a>
                @if(app()->environment('local'))
                <button onclick="showResetConfirmation()"
                        class="inline-flex items-center px-6 py-3 bg-slate-100 text-slate-700 font-medium rounded-2xl hover:bg-slate-200 transition-all duration-200 border border-slate-200">
                    <i class="fas fa-undo mr-2"></i>
                    Reset & Cài lại
                </button>
                @endif
            </div>
        </div>
        @else
        <!-- Ready to Start - Action Buttons -->
        <div class="glass-card rounded-3xl p-10 text-center mb-12 shadow-soft border border-blue-100">
            <div class="w-20 h-20 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-3xl flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-rocket text-blue-600 text-2xl"></i>
            </div>
            <h2 class="text-3xl font-semibold text-slate-800 mb-4">Sẵn sàng bắt đầu?</h2>
            <p class="text-xl text-slate-600 mb-8 leading-relaxed max-w-2xl mx-auto">
                Chỉ cần vài bước đơn giản để có một website hoàn chỉnh
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('setup.step', 'database') }}"
                   class="inline-flex items-center px-10 py-4 bg-blue-600 text-white font-semibold rounded-2xl hover:bg-blue-700 transition-all duration-200 shadow-soft hover:shadow-hover transform hover:-translate-y-0.5">
                    <i class="fas fa-rocket mr-3"></i>
                    Bắt đầu cài đặt ngay
                </a>
                <div class="flex items-center text-slate-500 text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    Không cần kinh nghiệm kỹ thuật
                </div>
            </div>
        </div>
        @endif

        <!-- Development Tools (Always visible in local) -->
        @if(app()->environment('local'))
        <div class="glass-card border border-amber-100 rounded-2xl p-8 mb-8 shadow-soft">
            <div class="flex items-start">
                <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center mr-6 flex-shrink-0">
                    <i class="fas fa-tools text-amber-600 text-lg"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-semibold text-slate-800 mb-3">
                        Development Tools
                    </h3>
                    <p class="text-slate-600 text-sm mb-6 leading-relaxed">
                        Các công cụ hỗ trợ development và testing. Chỉ hiển thị trong môi trường local.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <button onclick="showResetConfirmation()"
                                class="inline-flex items-center px-5 py-2.5 bg-slate-100 text-slate-700 font-medium rounded-xl hover:bg-slate-200 transition-all duration-200 border border-slate-200">
                            <i class="fas fa-undo mr-2"></i>
                            Reset Hệ thống
                        </button>
                        <a href="{{ route('filament.admin.pages.dashboard') }}"
                           class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-all duration-200 shadow-soft">
                            <i class="fas fa-user-shield mr-2"></i>
                            Admin Panel
                        </a>
                        <a href="{{ route('storeFront') }}"
                           class="inline-flex items-center px-5 py-2.5 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 transition-all duration-200 shadow-soft">
                            <i class="fas fa-home mr-2"></i>
                            Xem Website
                        </a>
                        <button onclick="clearCache()"
                                class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition-all duration-200 shadow-soft">
                            <i class="fas fa-broom mr-2"></i>
                            Clear Cache
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Footer -->
        <div class="text-center mt-16 pt-8 border-t border-slate-200">
            <p class="text-slate-500 font-light">
                Core Framework v1.0 - Được xây dựng với ❤️ bằng Laravel
            </p>
        </div>
    </div>

    <!-- Reset Confirmation Modal -->
    <div id="resetModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-6">
            <div class="glass-card rounded-3xl p-8 max-w-md w-full shadow-hover border border-slate-200">
                <div class="text-center">
                    <div class="w-20 h-20 bg-red-50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-exclamation-triangle text-2xl text-red-500"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-slate-800 mb-4">Xác nhận Reset</h3>
                    <p class="text-slate-600 mb-6 leading-relaxed">
                        Bạn có chắc chắn muốn reset toàn bộ hệ thống? Hành động này sẽ:
                    </p>
                    <ul class="text-left text-sm text-slate-600 mb-8 space-y-3">
                        <li class="flex items-center">
                            <div class="w-5 h-5 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-times text-red-500 text-xs"></i>
                            </div>
                            Xóa tất cả dữ liệu trong database
                        </li>
                        <li class="flex items-center">
                            <div class="w-5 h-5 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-times text-red-500 text-xs"></i>
                            </div>
                            Xóa tất cả files trong storage
                        </li>
                        <li class="flex items-center">
                            <div class="w-5 h-5 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-times text-red-500 text-xs"></i>
                            </div>
                            Clear tất cả cache
                        </li>
                        <li class="flex items-center">
                            <div class="w-5 h-5 bg-amber-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-exclamation text-amber-500 text-xs"></i>
                            </div>
                            Không thể hoàn tác
                        </li>
                    </ul>
                    <div class="flex gap-4">
                        <button onclick="hideResetConfirmation()"
                                class="flex-1 px-6 py-3 bg-slate-100 text-slate-700 font-medium rounded-2xl hover:bg-slate-200 transition-all duration-200">
                            Hủy
                        </button>
                        <button onclick="performReset()"
                                class="flex-1 px-6 py-3 bg-red-600 text-white font-medium rounded-2xl hover:bg-red-700 transition-all duration-200 shadow-soft">
                            <i class="fas fa-undo mr-2"></i>
                            Reset ngay
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div id="loadingModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-6">
            <div class="glass-card rounded-3xl p-8 max-w-md w-full text-center shadow-hover border border-slate-200">
                <div class="w-16 h-16 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mx-auto mb-6"></div>
                <h3 class="text-xl font-semibold text-slate-800 mb-3">Đang reset hệ thống...</h3>
                <p class="text-slate-600 leading-relaxed">Vui lòng đợi, quá trình này có thể mất vài phút.</p>
                <div class="mt-6">
                    <div id="resetProgress" class="text-sm text-slate-500 font-medium"></div>
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
