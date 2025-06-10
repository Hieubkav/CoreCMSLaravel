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

        <!-- Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-6">
                <i class="fas fa-cogs text-3xl text-red-600"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Chào mừng đến với Core Framework
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Hệ thống quản lý nội dung đa năng được xây dựng trên Laravel.
                Hãy bắt đầu thiết lập dự án của bạn trong vài phút.
            </p>
        </div>

        <!-- Features Grid -->
        <div class="grid md:grid-cols-3 gap-8 mb-12">
            <div class="bg-white rounded-lg p-6 shadow-sm border">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                    <i class="fas fa-rocket text-blue-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Dễ dàng cài đặt</h3>
                <p class="text-gray-600">
                    Wizard cài đặt thông minh giúp bạn thiết lập dự án chỉ trong vài bước đơn giản.
                </p>
            </div>

            <div class="bg-white rounded-lg p-6 shadow-sm border">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                    <i class="fas fa-palette text-green-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Tùy chỉnh linh hoạt</h3>
                <p class="text-gray-600">
                    Giao diện admin hiện đại với khả năng tùy chỉnh cao, phù hợp với mọi dự án.
                </p>
            </div>

            <div class="bg-white rounded-lg p-6 shadow-sm border">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                    <i class="fas fa-shield-alt text-purple-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Bảo mật cao</h3>
                <p class="text-gray-600">
                    Được xây dựng trên Laravel với các tính năng bảo mật tiên tiến và cập nhật thường xuyên.
                </p>
            </div>
        </div>

        <!-- System Requirements -->
        <div class="bg-white rounded-lg p-8 shadow-sm border mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Yêu cầu hệ thống</h2>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold text-gray-900 mb-3">Server Requirements</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            PHP >= 8.1
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            MySQL >= 8.0 hoặc PostgreSQL >= 13
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Composer
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Node.js >= 16 & NPM
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 mb-3">PHP Extensions</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            BCMath, Ctype, Fileinfo
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            JSON, Mbstring, OpenSSL
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            PDO, Tokenizer, XML
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            GD hoặc Imagick (cho xử lý ảnh)
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Setup Steps Preview -->
        <div class="bg-white rounded-lg p-8 shadow-sm border mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-list-ol text-red-600 mr-3"></i>
                Quy trình cài đặt chi tiết (18+ bước)
            </h2>
            <p class="text-gray-600 mb-8">
                Setup wizard được thiết kế với 18+ bước chi tiết để đảm bảo cài đặt hoàn hảo và tùy chỉnh theo nhu cầu của bạn.
            </p>

            <!-- Core Setup Steps -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                    <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-cog text-blue-600 text-sm"></i>
                    </div>
                    Phần 1: Cài đặt cơ bản (4 bước)
                </h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="flex items-center p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-blue-600 font-semibold text-sm">1</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-blue-900">Database</h4>
                            <p class="text-blue-700 text-sm">Kết nối và migration</p>
                        </div>
                    </div>
                    <div class="flex items-center p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-blue-600 font-semibold text-sm">2</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-blue-900">Admin Account</h4>
                            <p class="text-blue-700 text-sm">Tạo tài khoản quản trị</p>
                        </div>
                    </div>
                    <div class="flex items-center p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-blue-600 font-semibold text-sm">3</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-blue-900">Website Info</h4>
                            <p class="text-blue-700 text-sm">Thông tin cơ bản</p>
                        </div>
                    </div>
                    <div class="flex items-center p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-blue-600 font-semibold text-sm">4</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-blue-900">Sample Data</h4>
                            <p class="text-blue-700 text-sm">Dữ liệu mẫu (tùy chọn)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Configuration Steps -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-purple-900 mb-4 flex items-center">
                    <div class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-palette text-purple-600 text-sm"></i>
                    </div>
                    Phần 2: Cấu hình hệ thống (2 bước)
                </h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="flex items-center p-4 bg-purple-50 border border-purple-200 rounded-lg">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-purple-600 font-semibold text-sm">5</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-purple-900">Frontend Config</h4>
                            <p class="text-purple-700 text-sm">Theme, màu sắc, font chữ</p>
                        </div>
                    </div>
                    <div class="flex items-center p-4 bg-purple-50 border border-purple-200 rounded-lg">
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-purple-600 font-semibold text-sm">6</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-purple-900">Admin Dashboard</h4>
                            <p class="text-purple-700 text-sm">Analytics, performance</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Module Selection Steps -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-green-900 mb-4 flex items-center">
                    <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-puzzle-piece text-green-600 text-sm"></i>
                    </div>
                    Phần 3: Chọn modules (9 bước - tùy chọn)
                </h3>
                <div class="grid md:grid-cols-3 gap-3">
                    <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg">
                        <div class="w-7 h-7 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-green-600 font-semibold text-xs">7</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-green-900 text-sm">User Roles</h4>
                            <p class="text-green-700 text-xs">Phân quyền</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg">
                        <div class="w-7 h-7 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-green-600 font-semibold text-xs">8</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-green-900 text-sm">Blog & Posts</h4>
                            <p class="text-green-700 text-xs">Tin tức, bài viết</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg">
                        <div class="w-7 h-7 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-green-600 font-semibold text-xs">9</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-green-900 text-sm">Staff</h4>
                            <p class="text-green-700 text-xs">Quản lý nhân viên</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg">
                        <div class="w-7 h-7 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-green-600 font-semibold text-xs">10</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-green-900 text-sm">Content</h4>
                            <p class="text-green-700 text-xs">Slider, Gallery, FAQ</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg">
                        <div class="w-7 h-7 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-green-600 font-semibold text-xs">11</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-green-900 text-sm">E-commerce</h4>
                            <p class="text-green-700 text-xs">Sản phẩm, đơn hàng</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg">
                        <div class="w-7 h-7 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-green-600 font-semibold text-xs">12</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-green-900 text-sm">Layout</h4>
                            <p class="text-green-700 text-xs">Header, Footer</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg">
                        <div class="w-7 h-7 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-green-600 font-semibold text-xs">13</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-green-900 text-sm">Settings</h4>
                            <p class="text-green-700 text-xs">Cấu hình mở rộng</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg">
                        <div class="w-7 h-7 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-green-600 font-semibold text-xs">14</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-green-900 text-sm">Web Design</h4>
                            <p class="text-green-700 text-xs">Quản lý thiết kế</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg">
                        <div class="w-7 h-7 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-green-600 font-semibold text-xs">15</span>
                        </div>
                        <div>
                            <h4 class="font-medium text-green-900 text-sm">Advanced</h4>
                            <p class="text-green-700 text-xs">Tính năng nâng cao</p>
                        </div>
                    </div>
                </div>
                <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-yellow-600 mt-1 mr-3"></i>
                        <div>
                            <h4 class="font-semibold text-yellow-800">Modules tùy chọn</h4>
                            <p class="text-yellow-700 text-sm">
                                Mỗi module có thể được bật/tắt riêng biệt. Bạn có thể bỏ qua các module không cần thiết
                                và cài đặt sau thông qua admin panel.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Final Steps -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-orange-900 mb-4 flex items-center">
                    <div class="w-6 h-6 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-flag-checkered text-orange-600 text-sm"></i>
                    </div>
                    Phần 4: Hoàn thành (3 bước)
                </h3>
                <div class="grid md:grid-cols-3 gap-4">
                    <div class="flex items-center p-4 bg-orange-50 border border-orange-200 rounded-lg">
                        <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-orange-600 font-semibold text-sm">16</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-orange-900">Summary</h4>
                            <p class="text-orange-700 text-sm">Xem lại cấu hình</p>
                        </div>
                    </div>
                    <div class="flex items-center p-4 bg-orange-50 border border-orange-200 rounded-lg">
                        <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-orange-600 font-semibold text-sm">17</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-orange-900">Installation</h4>
                            <p class="text-orange-700 text-sm">Tiến trình cài đặt</p>
                        </div>
                    </div>
                    <div class="flex items-center p-4 bg-orange-50 border border-orange-200 rounded-lg">
                        <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mr-4">
                            <span class="text-orange-600 font-semibold text-sm">18</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-orange-900">Complete</h4>
                            <p class="text-orange-700 text-sm">Hoàn tất & truy cập</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Installation Statistics -->
        <div class="bg-gradient-to-r from-red-50 to-orange-50 rounded-lg p-8 border border-red-200 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">
                Thống kê cài đặt dự kiến
            </h2>
            <div class="grid md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-clock text-2xl text-red-600"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">5-15 phút</div>
                    <div class="text-sm text-gray-600">Thời gian cài đặt</div>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-file-code text-2xl text-blue-600"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">150+ files</div>
                    <div class="text-sm text-gray-600">Files sẽ được tạo</div>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-database text-2xl text-green-600"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">25+ tables</div>
                    <div class="text-sm text-gray-600">Database tables</div>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-puzzle-piece text-2xl text-purple-600"></i>
                    </div>
                    <div class="text-2xl font-bold text-gray-900">9 modules</div>
                    <div class="text-sm text-gray-600">Modules tùy chọn</div>
                </div>
            </div>
        </div>

        <!-- Key Features -->
        <div class="bg-white rounded-lg p-8 shadow-sm border mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">
                Tính năng nổi bật của Setup Wizard
            </h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="text-center p-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-magic text-indigo-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Smart Configuration</h3>
                    <p class="text-gray-600 text-sm">
                        Tự động phát hiện và cấu hình tối ưu cho môi trường của bạn
                    </p>
                </div>
                <div class="text-center p-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-plug text-green-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Modular System</h3>
                    <p class="text-gray-600 text-sm">
                        Chọn chỉ những module cần thiết, tiết kiệm tài nguyên
                    </p>
                </div>
                <div class="text-center p-4">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-paint-brush text-yellow-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Theme Customization</h3>
                    <p class="text-gray-600 text-sm">
                        Tùy chỉnh màu sắc, font chữ và giao diện ngay từ đầu
                    </p>
                </div>
                <div class="text-center p-4">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-chart-line text-red-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Real-time Progress</h3>
                    <p class="text-gray-600 text-sm">
                        Theo dõi tiến trình cài đặt với progress bar chi tiết
                    </p>
                </div>
                <div class="text-center p-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-undo text-purple-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Rollback Support</h3>
                    <p class="text-gray-600 text-sm">
                        Có thể quay lại bước trước hoặc thay đổi cấu hình
                    </p>
                </div>
                <div class="text-center p-4">
                    <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-seedling text-teal-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Sample Data</h3>
                    <p class="text-gray-600 text-sm">
                        Dữ liệu mẫu tiếng Việt để bắt đầu nhanh chóng
                    </p>
                </div>
            </div>
        </div>

        @if(isset($isSetupCompleted) && $isSetupCompleted)
        <!-- Setup Completed - Reset Option -->
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
        <!-- Ready to Start -->
        <div class="bg-gradient-to-r from-red-600 to-orange-600 rounded-lg p-8 text-white text-center mb-8">
            <h2 class="text-3xl font-bold mb-4">Sẵn sàng bắt đầu?</h2>
            <p class="text-xl mb-6 opacity-90">
                Chỉ cần 18 bước đơn giản để có một website hoàn chỉnh
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

        <!-- Quick Preview -->
        <div class="bg-white rounded-lg p-8 shadow-sm border mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">
                Preview các bước chính
            </h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="text-center p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                    <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-database text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Database Setup</h3>
                    <p class="text-gray-600 text-sm">Kết nối database và tạo bảng tự động</p>
                </div>
                <div class="text-center p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                    <div class="w-16 h-16 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-palette text-2xl text-purple-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Theme Config</h3>
                    <p class="text-gray-600 text-sm">Chọn màu sắc và thiết kế giao diện</p>
                </div>
                <div class="text-center p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                    <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-puzzle-piece text-2xl text-green-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Module Selection</h3>
                    <p class="text-gray-600 text-sm">Chọn tính năng cần thiết cho dự án</p>
                </div>
                <div class="text-center p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                    <div class="w-16 h-16 bg-orange-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-check-circle text-2xl text-orange-600"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-2">Installation</h3>
                    <p class="text-gray-600 text-sm">Cài đặt tự động với progress tracking</p>
                </div>
            </div>
        </div>

        <!-- Development Reset Section (Always visible in local) -->
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
