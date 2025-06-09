@extends('setup.layout')

@section('title', 'Tổng quan Modules - Core Framework Setup')
@section('description', 'Xem lại các module đã chọn và cấu hình trước khi cài đặt')

@section('content')
<div class="text-center mb-8">
    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-clipboard-list text-2xl text-indigo-600"></i>
    </div>
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Tổng quan Modules</h2>
    <p class="text-gray-600">
        Xem lại các module đã chọn và cấu hình trước khi tiến hành cài đặt.
    </p>
</div>

<div class="space-y-8">
    
    <!-- Core Configuration Summary -->
    <div class="border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-cog text-gray-600 mr-2"></i>
            Cấu hình cơ bản
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Database:</span>
                    <span class="font-medium text-green-600">✓ Đã cấu hình</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Admin Account:</span>
                    <span class="font-medium text-green-600">✓ Đã tạo</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Website Info:</span>
                    <span class="font-medium text-green-600">✓ Đã cấu hình</span>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Frontend Theme:</span>
                    <span class="font-medium text-green-600">✓ Đã cấu hình</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Admin Dashboard:</span>
                    <span class="font-medium text-green-600">✓ Đã cấu hình</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Sample Data:</span>
                    <span class="font-medium text-blue-600" id="sample-data-status">Đang kiểm tra...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Selected Modules Summary -->
    <div class="border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-puzzle-piece text-indigo-600 mr-2"></i>
            Modules đã chọn
        </h3>
        
        <div id="selected-modules" class="space-y-4">
            <!-- Modules will be loaded here via JavaScript -->
        </div>
        
        <div id="no-modules" class="hidden text-center py-8 text-gray-500">
            <i class="fas fa-info-circle text-4xl mb-4"></i>
            <p>Không có module nào được chọn. Chỉ cài đặt core framework.</p>
        </div>
    </div>

    <!-- Installation Estimate -->
    <div class="border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-clock text-orange-600 mr-2"></i>
            Ước tính thời gian cài đặt
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <div class="text-2xl font-bold text-blue-600" id="estimated-time">~2-5 phút</div>
                <div class="text-sm text-blue-700">Thời gian cài đặt</div>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <div class="text-2xl font-bold text-green-600" id="files-count">~50+ files</div>
                <div class="text-sm text-green-700">Files sẽ được tạo</div>
            </div>
            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <div class="text-2xl font-bold text-purple-600" id="database-tables">~15+ tables</div>
                <div class="text-sm text-purple-700">Database tables</div>
            </div>
        </div>
    </div>

    <!-- Installation Steps Preview -->
    <div class="border border-gray-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-list-ol text-green-600 mr-2"></i>
            Các bước cài đặt
        </h3>
        
        <div class="space-y-3">
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-medium mr-3">1</div>
                <span class="text-gray-700">Cài đặt packages và dependencies</span>
            </div>
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-medium mr-3">2</div>
                <span class="text-gray-700">Tạo migrations và chạy database</span>
            </div>
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-medium mr-3">3</div>
                <span class="text-gray-700">Tạo models và relationships</span>
            </div>
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-medium mr-3">4</div>
                <span class="text-gray-700">Tạo Filament resources</span>
            </div>
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-medium mr-3">5</div>
                <span class="text-gray-700">Tạo frontend components</span>
            </div>
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <div class="w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-medium mr-3">6</div>
                <span class="text-gray-700">Import dữ liệu mẫu (nếu được chọn)</span>
            </div>
        </div>
    </div>

    <!-- Warning Notice -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <div class="flex items-start">
            <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
            <div>
                <h4 class="font-semibold text-yellow-800">Lưu ý quan trọng</h4>
                <ul class="text-yellow-700 text-sm mt-2 space-y-1">
                    <li>• Quá trình cài đặt có thể mất vài phút, vui lòng không đóng trình duyệt</li>
                    <li>• Đảm bảo kết nối internet ổn định để tải packages</li>
                    <li>• Backup database nếu đây không phải lần cài đặt đầu tiên</li>
                    <li>• Sau khi hoàn thành, bạn có thể thay đổi cấu hình trong admin panel</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-between items-center">
        <button type="button" 
                onclick="goBack()"
                class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Quay lại chỉnh sửa
        </button>
        
        <button type="button" 
                onclick="startInstallation()"
                class="px-8 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
            <i class="fas fa-rocket mr-2"></i>
            Bắt đầu cài đặt
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
function startInstallation() {
    // Confirm before starting
    if (confirm('Bạn có chắc chắn muốn bắt đầu cài đặt? Quá trình này không thể hoàn tác.')) {
        showLoading('Đang chuẩn bị cài đặt...');
        
        // Proceed to installation step
        setTimeout(() => {
            window.location.href = '{{ route('setup.step', 'installation') }}';
        }, 1000);
    }
}

function goBack() {
    // Go back to the last module step
    window.history.back();
}

// Load selected modules summary from server session
function loadModulesSummary() {
    // Get modules data from server-side session
    const moduleConfigs = @json(session('module_configs', []));
    const modulesContainer = document.getElementById('selected-modules');
    const noModulesDiv = document.getElementById('no-modules');

    // Filter enabled modules
    const enabledModules = [];
    const moduleInfo = {
        'user_roles_permissions': {
            title: 'User Roles & Permissions',
            description: 'Quản lý vai trò và quyền hạn người dùng',
            icon: 'fas fa-users-cog'
        },
        'blog_posts': {
            title: 'Blog & Posts',
            description: 'Hệ thống bài viết, tin tức và blog',
            icon: 'fas fa-blog'
        },
        'staff': {
            title: 'Staff Management',
            description: 'Quản lý nhân viên và thông tin liên hệ',
            icon: 'fas fa-user-tie'
        },
        'content_sections': {
            title: 'Content Sections',
            description: 'Slider, Gallery, FAQ, Testimonials, v.v.',
            icon: 'fas fa-th-large'
        },
        'ecommerce': {
            title: 'E-commerce',
            description: 'Sản phẩm, đơn hàng, thanh toán',
            icon: 'fas fa-shopping-cart'
        },
        'layout_components': {
            title: 'Layout Components',
            description: 'Header, Footer, Navigation, Sidebar',
            icon: 'fas fa-th-large'
        },
        'settings_expansion': {
            title: 'Settings Expansion',
            description: 'Email, SMS, API, Cache, Security',
            icon: 'fas fa-sliders-h'
        },
        'web_design_management': {
            title: 'Web Design Management',
            description: 'Theme editor, CSS customization',
            icon: 'fas fa-paint-brush'
        },
        'advanced_features': {
            title: 'Advanced Features',
            description: 'Multi-language, AI, Analytics',
            icon: 'fas fa-rocket'
        }
    };

    // Check which modules are enabled
    for (const [moduleKey, config] of Object.entries(moduleConfigs)) {
        if (config.enable_module && moduleInfo[moduleKey]) {
            enabledModules.push({
                key: moduleKey,
                ...moduleInfo[moduleKey]
            });
        }
    }

    if (enabledModules.length === 0) {
        modulesContainer.style.display = 'none';
        noModulesDiv.classList.remove('hidden');
        return;
    }

    // Hide no modules message and show modules container
    noModulesDiv.classList.add('hidden');
    modulesContainer.style.display = 'block';
    modulesContainer.innerHTML = '';

    enabledModules.forEach(module => {
        const moduleDiv = document.createElement('div');
        moduleDiv.className = 'flex items-center justify-between p-4 bg-green-50 border border-green-200 rounded-lg';
        moduleDiv.innerHTML = `
            <div class="flex items-center">
                <i class="${module.icon} text-green-600 mr-3"></i>
                <div>
                    <div class="font-medium text-green-900">${module.title}</div>
                    <div class="text-sm text-green-700">${module.description}</div>
                </div>
            </div>
            <div class="text-green-600">
                <i class="fas fa-check-circle"></i>
            </div>
        `;
        modulesContainer.appendChild(moduleDiv);
    });

    // Update estimates based on selected modules
    updateEstimates(enabledModules.length);
}

function updateEstimates(moduleCount) {
    const baseTime = 2;
    const timePerModule = 0.5;
    const estimatedTime = baseTime + (moduleCount * timePerModule);
    
    const baseFiles = 50;
    const filesPerModule = 15;
    const estimatedFiles = baseFiles + (moduleCount * filesPerModule);
    
    const baseTables = 15;
    const tablesPerModule = 3;
    const estimatedTables = baseTables + (moduleCount * tablesPerModule);
    
    document.getElementById('estimated-time').textContent = `~${estimatedTime}-${estimatedTime + 2} phút`;
    document.getElementById('files-count').textContent = `~${estimatedFiles}+ files`;
    document.getElementById('database-tables').textContent = `~${estimatedTables}+ tables`;
}

// Check sample data status from session
function checkSampleDataStatus() {
    // Check if any module has sample data enabled
    const moduleConfigs = @json(session('module_configs', []));
    let sampleDataEnabled = false;

    for (const [moduleKey, config] of Object.entries(moduleConfigs)) {
        if (config.enable_module && config.create_sample_data) {
            sampleDataEnabled = true;
            break;
        }
    }

    const statusElement = document.getElementById('sample-data-status');

    if (sampleDataEnabled) {
        statusElement.textContent = '✓ Sẽ được tạo';
        statusElement.className = 'font-medium text-green-600';
    } else {
        statusElement.textContent = '✗ Không tạo';
        statusElement.className = 'font-medium text-gray-600';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    loadModulesSummary();
    checkSampleDataStatus();
    console.log('Modules summary page loaded');
});
</script>
@endpush
