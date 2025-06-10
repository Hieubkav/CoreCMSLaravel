@extends('setup.layout')

@section('title', 'Module: Staff Management - Core Framework Setup')
@section('description', 'Cài đặt hệ thống quản lý nhân viên và thông tin liên hệ')

@section('content')
<div class="text-center mb-8">
    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-users text-2xl text-orange-600"></i>
    </div>
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Staff Management Module</h2>
    <p class="text-gray-600">
        Hệ thống quản lý nhân viên, thông tin liên hệ và team members.
    </p>
</div>

<!-- Module Configuration Form -->
<form id="module-form" onsubmit="configureModule(event)">
    <div class="space-y-8">
        
        <!-- Module Enable/Disable -->
        <div class="border border-gray-200 rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-toggle-on text-orange-600 mr-2"></i>
                    Cài đặt Module
                </h3>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="enable_module" name="enable_module" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
                    <span class="ml-3 text-sm font-medium text-gray-900">Bật module này</span>
                </label>
            </div>
            
            <div id="module-content" class="space-y-6">
                <!-- Module Description -->
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                    <h4 class="font-semibold text-orange-800 mb-2">Tính năng của module:</h4>
                    <ul class="text-orange-700 text-sm space-y-1">
                        <li>• <strong>Staff Profiles:</strong> Quản lý thông tin nhân viên chi tiết</li>
                        <li>• <strong>Positions & Roles:</strong> Chức vụ và vai trò trong công ty</li>
                        <li>• <strong>Contact Info:</strong> Email, phone, social media links</li>
                        <li>• <strong>Image Management:</strong> Avatar và hình ảnh nhân viên</li>
                        <li>• <strong>Frontend Display:</strong> Trang team, staff listing</li>
                        <li>• <strong>SEO Optimization:</strong> Staff detail pages với SEO</li>
                    </ul>
                </div>

                <!-- Staff Fields Configuration -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Thông tin nhân viên sẽ bao gồm:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <div class="flex items-center p-2 bg-gray-50 rounded">
                                <i class="fas fa-user text-orange-500 mr-3"></i>
                                <span class="text-sm">Tên đầy đủ & slug</span>
                            </div>
                            <div class="flex items-center p-2 bg-gray-50 rounded">
                                <i class="fas fa-briefcase text-blue-500 mr-3"></i>
                                <span class="text-sm">Chức vụ & phòng ban</span>
                            </div>
                            <div class="flex items-center p-2 bg-gray-50 rounded">
                                <i class="fas fa-envelope text-green-500 mr-3"></i>
                                <span class="text-sm">Email & điện thoại</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center p-2 bg-gray-50 rounded">
                                <i class="fas fa-image text-purple-500 mr-3"></i>
                                <span class="text-sm">Avatar & hình ảnh</span>
                            </div>
                            <div class="flex items-center p-2 bg-gray-50 rounded">
                                <i class="fas fa-share-alt text-indigo-500 mr-3"></i>
                                <span class="text-sm">Social media links</span>
                            </div>
                            <div class="flex items-center p-2 bg-gray-50 rounded">
                                <i class="fas fa-align-left text-gray-500 mr-3"></i>
                                <span class="text-sm">Mô tả & kinh nghiệm</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Frontend Components -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Components sẽ được tạo:</h4>
                    <div class="space-y-2">
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-list text-orange-500 mr-3"></i>
                            <span class="text-sm"><strong>staff-listing.blade.php:</strong> Danh sách nhân viên với filter</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-eye text-blue-500 mr-3"></i>
                            <span class="text-sm"><strong>staff-detail.blade.php:</strong> Chi tiết nhân viên</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-users text-green-500 mr-3"></i>
                            <span class="text-sm"><strong>team-section.blade.php:</strong> Section cho homepage</span>
                        </div>
                    </div>
                </div>

                <!-- Sample Data Configuration -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-center mb-3">
                        <input type="checkbox" 
                               id="create_sample_data" 
                               name="create_sample_data" 
                               checked
                               class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                        <label for="create_sample_data" class="ml-2 block text-sm font-medium text-gray-900">
                            Tạo dữ liệu mẫu
                        </label>
                    </div>
                    
                    <div id="sample-data-options" class="ml-6 space-y-2 text-sm text-gray-600">
                        <div>• 8 nhân viên mẫu với các chức vụ khác nhau</div>
                        <div>• Avatar placeholder từ picsum.photos</div>
                        <div>• Thông tin liên hệ và social links</div>
                        <div>• Mô tả kinh nghiệm và kỹ năng</div>
                    </div>
                </div>

                <!-- Advanced Options -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Tùy chọn nâng cao:</h4>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="enable_departments" 
                                   name="enable_departments" 
                                   checked
                                   class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                            <label for="enable_departments" class="ml-2 block text-sm text-gray-900">
                                Bật quản lý phòng ban
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="enable_social_links" 
                                   name="enable_social_links" 
                                   checked
                                   class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                            <label for="enable_social_links" class="ml-2 block text-sm text-gray-900">
                                Bật social media links
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="enable_staff_pages" 
                                   name="enable_staff_pages" 
                                   checked
                                   class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                            <label for="enable_staff_pages" class="ml-2 block text-sm text-gray-900">
                                Tạo trang chi tiết cho từng nhân viên
                            </label>
                        </div>
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
                        Bạn có thể bỏ qua module này nếu website không cần hiển thị thông tin nhân viên. 
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
                    class="px-8 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                <i class="fas fa-check mr-2"></i>
                Cài đặt module
            </button>
        </div>
    </div>
</form>

<!-- Navigation -->
<div id="navigation-section" class="hidden border-t pt-6 mt-6">
    <div class="flex justify-between">
        <a href="{{ route('setup.step', 'module-blog') }}" 
           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Quay lại
        </a>
        
        <button onclick="goToNextStep('module-content')"
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
    data.enable_departments = document.getElementById('enable_departments').checked;
    data.enable_social_links = document.getElementById('enable_social_links').checked;
    data.enable_staff_pages = document.getElementById('enable_staff_pages').checked;
    data.module_key = 'staff';
    
    showLoading('Đang cấu hình Staff Management module và generate code...');

    submitStep('{{ route('setup.process', 'module-staff') }}', data, (response) => {
        document.getElementById('navigation-section').classList.remove('hidden');

        // Show additional success message for generation
        if (response.generation_results && response.generation_results.generation) {
            showAlert('✅ Staff Management module đã được cài đặt và code đã được generate thành công!', 'success');
        }

        // Auto proceed to next step after 3 seconds (longer to see results)
        setTimeout(() => {
            clearGenerationResults(); // Clear before moving to next step
            goToNextStep('module-content');
        }, 3000);
    });
}

function skipModule() {
    const data = {
        enable_module: false,
        create_sample_data: false,
        module_key: 'staff',
        skipped: true
    };
    
    showLoading('Đang bỏ qua module...');
    
    submitStep('{{ route('setup.process', 'module-staff') }}', data, (response) => {
        document.getElementById('navigation-section').classList.remove('hidden');
        
        // Auto proceed to next step after 1 second
        setTimeout(() => {
            goToNextStep('module-content');
        }, 1000);
    });
}

// Toggle module content and sample data options
document.addEventListener('DOMContentLoaded', function() {
    const enableToggle = document.getElementById('enable_module');
    const moduleContent = document.getElementById('module-content');
    const sampleDataToggle = document.getElementById('create_sample_data');
    const sampleDataOptions = document.getElementById('sample-data-options');
    
    enableToggle.addEventListener('change', function() {
        if (this.checked) {
            moduleContent.style.display = 'block';
        } else {
            moduleContent.style.display = 'none';
        }
    });
    
    sampleDataToggle.addEventListener('change', function() {
        if (this.checked) {
            sampleDataOptions.style.display = 'block';
        } else {
            sampleDataOptions.style.display = 'none';
        }
    });
    
    console.log('Staff Management module page loaded');
});
</script>
@endpush
