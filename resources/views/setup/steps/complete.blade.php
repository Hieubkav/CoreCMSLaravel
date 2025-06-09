@extends('setup.layout')

@section('title', 'Hoàn thành - Core Framework Setup')
@section('description', 'Cài đặt hoàn tất! Xem lại toàn bộ cấu hình và bắt đầu sử dụng')

@section('content')
<div class="text-center mb-8">
    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-check-circle text-3xl text-green-600"></i>
    </div>
    <h2 class="text-3xl font-bold text-gray-900 mb-2">🎉 Cài đặt hoàn tất!</h2>
    <p class="text-gray-600 text-lg">
        Core Framework đã được cài đặt thành công và sẵn sàng sử dụng.
    </p>
</div>

<div class="space-y-8">
    
    <!-- Installation Summary -->
    <div class="border border-green-200 bg-green-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-green-900 mb-4 flex items-center">
            <i class="fas fa-clipboard-check text-green-600 mr-2"></i>
            Tổng kết cài đặt
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center p-4 bg-white rounded-lg border border-green-200">
                <div class="text-2xl font-bold text-green-600" id="modules-installed">0</div>
                <div class="text-sm text-green-700">Modules đã cài</div>
            </div>
            <div class="text-center p-4 bg-white rounded-lg border border-green-200">
                <div class="text-2xl font-bold text-blue-600" id="files-created">0+</div>
                <div class="text-sm text-blue-700">Files đã tạo</div>
            </div>
            <div class="text-center p-4 bg-white rounded-lg border border-green-200">
                <div class="text-2xl font-bold text-purple-600" id="tables-created">0+</div>
                <div class="text-sm text-purple-700">Database tables</div>
            </div>
        </div>
    </div>

    <!-- Installed Modules -->
    <div class="border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-puzzle-piece text-indigo-600 mr-2"></i>
            Modules đã cài đặt
        </h3>
        
        <div id="installed-modules-list" class="space-y-3">
            <!-- Will be populated by JavaScript -->
        </div>
        
        <div id="no-modules-installed" class="hidden text-center py-4 text-gray-500">
            <i class="fas fa-info-circle text-2xl mb-2"></i>
            <p>Chỉ cài đặt Core Framework cơ bản</p>
        </div>
    </div>

    <!-- Quick Access Links -->
    <div class="border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-external-link-alt text-blue-600 mr-2"></i>
            Truy cập nhanh
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('storeFront') }}" 
               class="flex items-center p-4 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                <i class="fas fa-home text-blue-600 mr-3"></i>
                <div>
                    <div class="font-medium text-blue-900">Trang chủ Website</div>
                    <div class="text-sm text-blue-700">Xem giao diện người dùng</div>
                </div>
            </a>
            
            <a href="{{ route('filament.admin.pages.dashboard') }}"
               class="flex items-center p-4 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 transition-colors">
                <i class="fas fa-user-shield text-indigo-600 mr-3"></i>
                <div>
                    <div class="font-medium text-indigo-900">Admin Panel</div>
                    <div class="text-sm text-indigo-700">Quản trị hệ thống</div>
                </div>
            </a>
        </div>
    </div>

    <!-- Configuration Summary -->
    <div class="border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-cog text-gray-600 mr-2"></i>
            Cấu hình đã áp dụng
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium text-gray-900 mb-2">Frontend Configuration</h4>
                <div class="space-y-1 text-sm text-gray-600" id="frontend-config-summary">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>
            <div>
                <h4 class="font-medium text-gray-900 mb-2">Admin Configuration</h4>
                <div class="space-y-1 text-sm text-gray-600" id="admin-config-summary">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- Next Steps -->
    <div class="border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-lightbulb text-yellow-600 mr-2"></i>
            Bước tiếp theo
        </h3>
        
        <div class="space-y-3">
            <div class="flex items-start p-3 bg-gray-50 rounded-lg">
                <i class="fas fa-edit text-blue-600 mt-1 mr-3"></i>
                <div>
                    <div class="font-medium text-gray-900">Tùy chỉnh nội dung</div>
                    <div class="text-sm text-gray-600">Truy cập admin panel để thêm nội dung, hình ảnh và cấu hình website</div>
                </div>
            </div>
            
            <div class="flex items-start p-3 bg-gray-50 rounded-lg">
                <i class="fas fa-palette text-purple-600 mt-1 mr-3"></i>
                <div>
                    <div class="font-medium text-gray-900">Tùy chỉnh giao diện</div>
                    <div class="text-sm text-gray-600">Điều chỉnh màu sắc, font chữ và layout trong phần Web Design Management</div>
                </div>
            </div>
            
            <div class="flex items-start p-3 bg-gray-50 rounded-lg">
                <i class="fas fa-users text-green-600 mt-1 mr-3"></i>
                <div>
                    <div class="font-medium text-gray-900">Quản lý người dùng</div>
                    <div class="text-sm text-gray-600">Tạo thêm tài khoản admin và phân quyền cho team</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
        <div class="flex items-start">
            <i class="fas fa-rocket text-green-600 mt-1 mr-3"></i>
            <div>
                <h4 class="font-semibold text-green-800">Chúc mừng!</h4>
                <p class="text-green-700 text-sm mt-1">
                    Bạn đã cài đặt thành công Core Framework. Hệ thống đã sẵn sàng để bạn xây dựng 
                    website hoặc ứng dụng web tuyệt vời. Hãy bắt đầu khám phá các tính năng!
                </p>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-center space-x-4">
        <a href="{{ route('storeFront') }}" 
           class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-home mr-2"></i>
            Xem Website
        </a>
        
        <a href="{{ route('filament.admin.pages.dashboard') }}"
           class="px-8 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
            <i class="fas fa-user-shield mr-2"></i>
            Vào Admin Panel
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Sample data for demonstration
const installationData = {
    modules: [
        { key: 'user_roles_permissions', title: 'User Roles & Permissions', icon: 'fas fa-users-cog', enabled: true },
        { key: 'blog_posts', title: 'Blog & Posts', icon: 'fas fa-blog', enabled: true },
        { key: 'staff', title: 'Staff Management', icon: 'fas fa-users', enabled: false },
        { key: 'content_sections', title: 'Content Sections', icon: 'fas fa-th-large', enabled: true },
        { key: 'ecommerce', title: 'E-commerce', icon: 'fas fa-shopping-cart', enabled: false }
    ],
    frontend_config: {
        theme_mode: 'light_only',
        primary_color: '#dc2626',
        design_style: 'minimalism',
        icon_system: 'fontawesome'
    },
    admin_config: {
        visitor_analytics_enabled: true,
        webp_quality: 95,
        pagination_size: 25
    }
};

function loadInstallationSummary() {
    // Count installed modules
    const installedModules = installationData.modules.filter(m => m.enabled);
    document.getElementById('modules-installed').textContent = installedModules.length;
    
    // Estimate files and tables
    const baseFiles = 50;
    const filesPerModule = 15;
    const estimatedFiles = baseFiles + (installedModules.length * filesPerModule);
    document.getElementById('files-created').textContent = `${estimatedFiles}+`;
    
    const baseTables = 15;
    const tablesPerModule = 3;
    const estimatedTables = baseTables + (installedModules.length * tablesPerModule);
    document.getElementById('tables-created').textContent = `${estimatedTables}+`;
    
    // Load installed modules list
    const modulesList = document.getElementById('installed-modules-list');
    const noModulesDiv = document.getElementById('no-modules-installed');
    
    if (installedModules.length === 0) {
        modulesList.style.display = 'none';
        noModulesDiv.style.display = 'block';
    } else {
        installedModules.forEach(module => {
            const moduleDiv = document.createElement('div');
            moduleDiv.className = 'flex items-center p-3 bg-green-50 border border-green-200 rounded-lg';
            moduleDiv.innerHTML = `
                <i class="${module.icon} text-green-600 mr-3"></i>
                <div class="flex-1">
                    <div class="font-medium text-green-900">${module.title}</div>
                    <div class="text-sm text-green-700">Đã cài đặt và cấu hình thành công</div>
                </div>
                <i class="fas fa-check-circle text-green-600"></i>
            `;
            modulesList.appendChild(moduleDiv);
        });
    }
    
    // Load configuration summaries
    loadConfigSummary();
}

function loadConfigSummary() {
    // Frontend config summary
    const frontendSummary = document.getElementById('frontend-config-summary');
    frontendSummary.innerHTML = `
        <div>• Theme: ${installationData.frontend_config.theme_mode}</div>
        <div>• Primary Color: ${installationData.frontend_config.primary_color}</div>
        <div>• Design Style: ${installationData.frontend_config.design_style}</div>
        <div>• Icon System: ${installationData.frontend_config.icon_system}</div>
    `;
    
    // Admin config summary
    const adminSummary = document.getElementById('admin-config-summary');
    adminSummary.innerHTML = `
        <div>• Analytics: ${installationData.admin_config.visitor_analytics_enabled ? 'Enabled' : 'Disabled'}</div>
        <div>• WebP Quality: ${installationData.admin_config.webp_quality}%</div>
        <div>• Pagination: ${installationData.admin_config.pagination_size} items/page</div>
        <div>• Cache: Enabled</div>
    `;
}

// Animate numbers on page load
function animateNumbers() {
    const numbers = ['modules-installed', 'files-created', 'tables-created'];
    
    numbers.forEach(id => {
        const element = document.getElementById(id);
        const finalValue = parseInt(element.textContent);
        let currentValue = 0;
        const increment = Math.ceil(finalValue / 20);
        
        const timer = setInterval(() => {
            currentValue += increment;
            if (currentValue >= finalValue) {
                currentValue = finalValue;
                clearInterval(timer);
            }
            element.textContent = currentValue + (element.textContent.includes('+') ? '+' : '');
        }, 50);
    });
}

// Confetti effect
function showConfetti() {
    // Simple confetti effect using CSS animations
    for (let i = 0; i < 50; i++) {
        setTimeout(() => {
            const confetti = document.createElement('div');
            confetti.style.cssText = `
                position: fixed;
                top: -10px;
                left: ${Math.random() * 100}%;
                width: 10px;
                height: 10px;
                background: ${['#ff6b6b', '#4ecdc4', '#45b7d1', '#96ceb4', '#feca57'][Math.floor(Math.random() * 5)]};
                z-index: 1000;
                animation: confetti-fall 3s linear forwards;
                pointer-events: none;
            `;
            document.body.appendChild(confetti);
            
            setTimeout(() => {
                confetti.remove();
            }, 3000);
        }, i * 100);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('Setup complete page loaded');
    loadInstallationSummary();
    
    // Add some celebration effects
    setTimeout(() => {
        animateNumbers();
        showConfetti();
    }, 500);
});
</script>

<style>
@keyframes confetti-fall {
    0% {
        transform: translateY(-100vh) rotate(0deg);
        opacity: 1;
    }
    100% {
        transform: translateY(100vh) rotate(720deg);
        opacity: 0;
    }
}
</style>
@endpush
