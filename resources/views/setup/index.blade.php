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
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Các bước cài đặt</h2>
            <div class="space-y-4">
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-4">
                        <span class="text-red-600 font-semibold">1</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Cấu hình Database</h3>
                        <p class="text-gray-600">Kiểm tra kết nối và tạo bảng database</p>
                    </div>
                </div>
                
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-4">
                        <span class="text-red-600 font-semibold">2</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Tạo tài khoản Admin</h3>
                        <p class="text-gray-600">Tạo tài khoản quản trị viên đầu tiên</p>
                    </div>
                </div>
                
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-4">
                        <span class="text-red-600 font-semibold">3</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Cấu hình Website</h3>
                        <p class="text-gray-600">Thiết lập thông tin cơ bản của website</p>
                    </div>
                </div>
                
                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-4">
                        <span class="text-red-600 font-semibold">4</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Cấu hình nâng cao</h3>
                        <p class="text-gray-600">Tùy chỉnh hiệu suất và tính năng nâng cao</p>
                    </div>
                </div>

                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-4">
                        <span class="text-red-600 font-semibold">5</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Dữ liệu mẫu</h3>
                        <p class="text-gray-600">Import dữ liệu mẫu để bắt đầu nhanh (tùy chọn)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Start Button -->
        <div class="text-center">
            <a href="{{ route('setup.step', 'database') }}" 
               class="inline-flex items-center px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors">
                <i class="fas fa-play mr-2"></i>
                Bắt đầu cài đặt
            </a>
        </div>

        <!-- Footer -->
        <div class="text-center mt-12 pt-8 border-t border-gray-200">
            <p class="text-gray-500">
                Core Framework v1.0 - Được xây dựng với ❤️ bằng Laravel
            </p>
        </div>
    </div>
</body>
</html>
