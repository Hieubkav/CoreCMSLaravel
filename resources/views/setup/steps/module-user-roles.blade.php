@extends('setup.layout')

@section('title', 'Module: User Roles & Permissions - Core Framework Setup')
@section('description', 'Cài đặt hệ thống quản lý vai trò và quyền hạn người dùng')

@section('content')
<div class="text-center mb-8">
    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-users-cog text-2xl text-blue-600"></i>
    </div>
    <h2 class="text-2xl font-bold text-gray-900 mb-2">User Roles & Permissions</h2>
    <p class="text-gray-600">
        Cài đặt hệ thống quản lý vai trò và quyền hạn người dùng với Spatie Laravel Permission.
    </p>
</div>

<!-- Module Configuration Form -->
<form id="module-form" onsubmit="configureModule(event)">
    <div class="space-y-8">
        
        <!-- Module Enable/Disable -->
        <div class="border border-gray-200 rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-toggle-on text-blue-600 mr-2"></i>
                    Cài đặt Module
                </h3>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="enable_module" name="enable_module" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    <span class="ml-3 text-sm font-medium text-gray-900">Bật module này</span>
                </label>
            </div>
            
            <div id="module-content" class="space-y-6">
                <!-- Module Description -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="font-semibold text-blue-800 mb-2">Tính năng của module:</h4>
                    <ul class="text-blue-700 text-sm space-y-1">
                        <li>• <strong>Spatie Laravel Permission:</strong> Package quản lý quyền hạn mạnh mẽ</li>
                        <li>• <strong>Roles mặc định:</strong> Super Admin, Admin, Editor, Viewer</li>
                        <li>• <strong>Permissions tự động:</strong> Tạo quyền cho tất cả Filament resources</li>
                        <li>• <strong>Filament Plugin:</strong> Giao diện quản lý roles & permissions</li>
                        <li>• <strong>Middleware:</strong> Bảo vệ routes theo vai trò</li>
                    </ul>
                </div>

                <!-- Default Roles Configuration -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Roles mặc định sẽ được tạo:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center p-3 bg-red-50 border border-red-200 rounded-lg">
                            <i class="fas fa-crown text-red-600 mr-3"></i>
                            <div>
                                <div class="font-medium text-red-900">Super Admin</div>
                                <div class="text-sm text-red-700">Toàn quyền hệ thống</div>
                            </div>
                        </div>
                        <div class="flex items-center p-3 bg-orange-50 border border-orange-200 rounded-lg">
                            <i class="fas fa-user-shield text-orange-600 mr-3"></i>
                            <div>
                                <div class="font-medium text-orange-900">Admin</div>
                                <div class="text-sm text-orange-700">Quản lý nội dung</div>
                            </div>
                        </div>
                        <div class="flex items-center p-3 bg-green-50 border border-green-200 rounded-lg">
                            <i class="fas fa-edit text-green-600 mr-3"></i>
                            <div>
                                <div class="font-medium text-green-900">Editor</div>
                                <div class="text-sm text-green-700">Chỉnh sửa nội dung</div>
                            </div>
                        </div>
                        <div class="flex items-center p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <i class="fas fa-eye text-blue-600 mr-3"></i>
                            <div>
                                <div class="font-medium text-blue-900">Viewer</div>
                                <div class="text-sm text-blue-700">Chỉ xem</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Package Installation Info -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Packages sẽ được cài đặt:</h4>
                    <div class="space-y-2">
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fab fa-laravel text-red-500 mr-3"></i>
                            <code class="text-sm">spatie/laravel-permission</code>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-puzzle-piece text-orange-500 mr-3"></i>
                            <code class="text-sm">filament/spatie-laravel-permissions-plugin</code>
                        </div>
                    </div>
                </div>

                <!-- Sample Data Option -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="create_sample_data" 
                               name="create_sample_data" 
                               checked
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="create_sample_data" class="ml-2 block text-sm text-gray-900">
                            Tạo dữ liệu mẫu (roles, permissions, user assignments)
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Skip Option -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-yellow-600 mt-1 mr-3"></i>
                <div>
                    <h4 class="font-semibold text-yellow-800">Module tùy chọn</h4>
                    <p class="text-yellow-700 text-sm mt-1">
                        Bạn có thể bỏ qua module này nếu không cần hệ thống phân quyền phức tạp. 
                        Module có thể được cài đặt sau thông qua admin panel.
                    </p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center">
            <button type="button" 
                    onclick="skipModule()"
                    class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fas fa-skip-forward mr-2"></i>
                Bỏ qua module này
            </button>
            
            <button type="submit" 
                    class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-check mr-2"></i>
                Cài đặt module
            </button>
        </div>
    </div>
</form>

<!-- Navigation -->
<div id="navigation-section" class="hidden border-t pt-6 mt-6">
    <div class="flex justify-between">
        <a href="{{ route('setup.step', 'admin-config') }}" 
           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Quay lại
        </a>
        
        <button onclick="goToNextStep('module-blog')"
                class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
            Tiếp theo
            <i class="fas fa-arrow-right ml-2"></i>
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
function configureModule(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData.entries());
    
    // Convert checkboxes to boolean
    data.enable_module = document.getElementById('enable_module').checked;
    data.create_sample_data = document.getElementById('create_sample_data').checked;
    data.module_key = 'user_roles_permissions';
    
    showLoading('Đang cấu hình User Roles & Permissions module...');
    
    submitStep('{{ route('setup.process', 'module-user-roles') }}', data, (response) => {
        document.getElementById('navigation-section').classList.remove('hidden');
        
        // Auto proceed to next step after 2 seconds
        setTimeout(() => {
            goToNextStep('module-blog');
        }, 2000);
    });
}

function skipModule() {
    const data = {
        enable_module: false,
        create_sample_data: false,
        module_key: 'user_roles_permissions',
        skipped: true
    };
    
    showLoading('Đang bỏ qua module...');
    
    submitStep('{{ route('setup.process', 'module-user-roles') }}', data, (response) => {
        document.getElementById('navigation-section').classList.remove('hidden');
        
        // Auto proceed to next step after 1 second
        setTimeout(() => {
            goToNextStep('module-blog');
        }, 1000);
    });
}

// Toggle module content based on enable/disable
document.addEventListener('DOMContentLoaded', function() {
    const enableToggle = document.getElementById('enable_module');
    const moduleContent = document.getElementById('module-content');
    
    enableToggle.addEventListener('change', function() {
        if (this.checked) {
            moduleContent.style.display = 'block';
        } else {
            moduleContent.style.display = 'none';
        }
    });
    
    console.log('User Roles & Permissions module page loaded');
});
</script>
@endpush
