@extends('setup.layout')

@section('title', 'Module: Settings Expansion - Core Framework Setup')
@section('description', 'Mở rộng hệ thống cấu hình: Email, SMS, API, Cache, Security')

@section('content')
<div class="text-center mb-8">
    <div class="w-16 h-16 bg-cyan-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-cogs text-2xl text-cyan-600"></i>
    </div>
    <h2 class="text-2xl font-bold text-gray-900 mb-2">Settings Expansion Module</h2>
    <p class="text-gray-600">
        Mở rộng hệ thống cấu hình với Email, SMS, API management, Cache và Security settings.
    </p>
</div>

<!-- Module Configuration Form -->
<form id="module-form" onsubmit="configureModule(event)">
    <div class="space-y-8">
        
        <!-- Module Enable/Disable -->
        <div class="border border-gray-200 rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-toggle-on text-cyan-600 mr-2"></i>
                    Cài đặt Module
                </h3>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="enable_module" name="enable_module" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-cyan-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-cyan-600"></div>
                    <span class="ml-3 text-sm font-medium text-gray-900">Bật module này</span>
                </label>
            </div>
            
            <div id="module-content" class="space-y-6">
                <!-- Module Description -->
                <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4">
                    <h4 class="font-semibold text-cyan-800 mb-2">Tính năng của module:</h4>
                    <ul class="text-cyan-700 text-sm space-y-1">
                        <li>• <strong>Email Settings:</strong> SMTP, templates, queue management</li>
                        <li>• <strong>SMS Integration:</strong> Twilio, Nexmo, local providers</li>
                        <li>• <strong>API Management:</strong> Rate limiting, authentication</li>
                        <li>• <strong>Cache Settings:</strong> Redis, Memcached configuration</li>
                        <li>• <strong>Security Settings:</strong> 2FA, password policies</li>
                        <li>• <strong>Backup & Maintenance:</strong> Automated backups</li>
                    </ul>
                </div>

                <!-- Settings Categories Selection -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Chọn nhóm cấu hình cần cài đặt:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_email_settings" 
                                       name="enable_email_settings" 
                                       checked
                                       class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded">
                                <label for="enable_email_settings" class="ml-2 block text-sm text-gray-900">
                                    <strong>Email Settings</strong> - SMTP, templates
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_sms_settings" 
                                       name="enable_sms_settings" 
                                       class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded">
                                <label for="enable_sms_settings" class="ml-2 block text-sm text-gray-900">
                                    <strong>SMS Integration</strong> - SMS providers
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_api_settings" 
                                       name="enable_api_settings" 
                                       checked
                                       class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded">
                                <label for="enable_api_settings" class="ml-2 block text-sm text-gray-900">
                                    <strong>API Management</strong> - Rate limiting, auth
                                </label>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_cache_settings" 
                                       name="enable_cache_settings" 
                                       checked
                                       class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded">
                                <label for="enable_cache_settings" class="ml-2 block text-sm text-gray-900">
                                    <strong>Cache Settings</strong> - Redis, Memcached
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_security_settings" 
                                       name="enable_security_settings" 
                                       checked
                                       class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded">
                                <label for="enable_security_settings" class="ml-2 block text-sm text-gray-900">
                                    <strong>Security Settings</strong> - 2FA, policies
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="enable_backup_settings" 
                                       name="enable_backup_settings" 
                                       class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded">
                                <label for="enable_backup_settings" class="ml-2 block text-sm text-gray-900">
                                    <strong>Backup & Maintenance</strong> - Auto backup
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Email Configuration -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Cấu hình Email:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="email_driver" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Driver
                            </label>
                            <select id="email_driver" name="email_driver" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <option value="smtp" selected>SMTP</option>
                                <option value="mailgun">Mailgun</option>
                                <option value="ses">Amazon SES</option>
                                <option value="sendmail">Sendmail</option>
                            </select>
                        </div>
                        <div>
                            <label for="email_queue" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Queue
                            </label>
                            <select id="email_queue" name="email_queue" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <option value="sync">Sync - Gửi ngay</option>
                                <option value="database" selected>Database Queue</option>
                                <option value="redis">Redis Queue</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Security Configuration -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Cấu hình Security:</h4>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="enable_2fa" 
                                   name="enable_2fa" 
                                   class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded">
                            <label for="enable_2fa" class="ml-2 block text-sm text-gray-900">
                                Bật Two-Factor Authentication (2FA)
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="enable_password_policy" 
                                   name="enable_password_policy" 
                                   checked
                                   class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded">
                            <label for="enable_password_policy" class="ml-2 block text-sm text-gray-900">
                                Áp dụng password policy mạnh
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="enable_login_throttle" 
                                   name="enable_login_throttle" 
                                   checked
                                   class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded">
                            <label for="enable_login_throttle" class="ml-2 block text-sm text-gray-900">
                                Rate limiting cho login attempts
                            </label>
                        </div>
                    </div>
                </div>

                <!-- API Configuration -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Cấu hình API:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="api_rate_limit" class="block text-sm font-medium text-gray-700 mb-2">
                                Rate Limit (requests/minute)
                            </label>
                            <input type="number" 
                                   id="api_rate_limit" 
                                   name="api_rate_limit" 
                                   min="10" 
                                   max="1000" 
                                   value="60"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label for="api_auth_method" class="block text-sm font-medium text-gray-700 mb-2">
                                Authentication Method
                            </label>
                            <select id="api_auth_method" name="api_auth_method" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <option value="token" selected>API Token</option>
                                <option value="oauth">OAuth 2.0</option>
                                <option value="jwt">JWT</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Admin Resources -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Filament Resources sẽ được tạo:</h4>
                    <div class="space-y-2">
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-envelope text-cyan-500 mr-3"></i>
                            <span class="text-sm"><strong>EmailSettingsResource:</strong> Cấu hình SMTP và templates</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-shield-alt text-blue-500 mr-3"></i>
                            <span class="text-sm"><strong>SecuritySettingsResource:</strong> 2FA và password policies</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-code text-green-500 mr-3"></i>
                            <span class="text-sm"><strong>ApiSettingsResource:</strong> API keys và rate limiting</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-tachometer-alt text-purple-500 mr-3"></i>
                            <span class="text-sm"><strong>CacheSettingsResource:</strong> Cache configuration</span>
                        </div>
                        <div class="flex items-center p-2 bg-gray-50 rounded">
                            <i class="fas fa-save text-orange-500 mr-3"></i>
                            <span class="text-sm"><strong>BackupSettingsResource:</strong> Backup scheduling</span>
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
                               class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded">
                        <label for="create_sample_data" class="ml-2 block text-sm font-medium text-gray-900">
                            Tạo cấu hình mẫu
                        </label>
                    </div>
                    
                    <div id="sample-data-options" class="ml-6 space-y-2 text-sm text-gray-600">
                        <div>• Email templates mẫu (welcome, reset password)</div>
                        <div>• Security policies mặc định</div>
                        <div>• API rate limiting rules</div>
                        <div>• Cache configuration tối ưu</div>
                        <div>• Backup schedule hàng ngày</div>
                    </div>
                </div>

                <!-- Advanced Options -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 mb-3">Tùy chọn nâng cao:</h4>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="enable_logging" 
                                   name="enable_logging" 
                                   checked
                                   class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded">
                            <label for="enable_logging" class="ml-2 block text-sm text-gray-900">
                                Bật advanced logging và monitoring
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="enable_notifications" 
                                   name="enable_notifications" 
                                   checked
                                   class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded">
                            <label for="enable_notifications" class="ml-2 block text-sm text-gray-900">
                                System notifications và alerts
                            </label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="checkbox" 
                                   id="enable_maintenance_mode" 
                                   name="enable_maintenance_mode" 
                                   class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded">
                            <label for="enable_maintenance_mode" class="ml-2 block text-sm text-gray-900">
                                Maintenance mode management
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
                    <h4 class="font-semibold text-yellow-800">Module bắt buộc</h4>
                    <p class="text-yellow-700 text-sm mt-1">
                        Settings Expansion cung cấp các cấu hình cần thiết cho production. 
                        Khuyến nghị cài đặt để có hệ thống hoàn chỉnh.
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
                    class="px-8 py-3 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors">
                <i class="fas fa-check mr-2"></i>
                Cài đặt module
            </button>
        </div>
    </div>
</form>

<!-- Navigation -->
<div id="navigation-section" class="hidden border-t pt-6 mt-6">
    <div class="flex justify-between">
        <a href="{{ route('setup.step', 'module-layout') }}" 
           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Quay lại
        </a>
        
        <button onclick="goToNextStep('module-webdesign')"
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
    data.enable_email_settings = document.getElementById('enable_email_settings').checked;
    data.enable_sms_settings = document.getElementById('enable_sms_settings').checked;
    data.enable_api_settings = document.getElementById('enable_api_settings').checked;
    data.enable_cache_settings = document.getElementById('enable_cache_settings').checked;
    data.enable_security_settings = document.getElementById('enable_security_settings').checked;
    data.enable_backup_settings = document.getElementById('enable_backup_settings').checked;
    data.enable_2fa = document.getElementById('enable_2fa').checked;
    data.enable_password_policy = document.getElementById('enable_password_policy').checked;
    data.enable_login_throttle = document.getElementById('enable_login_throttle').checked;
    data.enable_logging = document.getElementById('enable_logging').checked;
    data.enable_notifications = document.getElementById('enable_notifications').checked;
    data.enable_maintenance_mode = document.getElementById('enable_maintenance_mode').checked;
    data.module_key = 'settings_expansion';
    
    showLoading('Đang cấu hình Settings Expansion module...');
    
    submitStep('{{ route('setup.process', 'module-settings') }}', data, (response) => {
        document.getElementById('navigation-section').classList.remove('hidden');
        
        // Auto proceed to next step after 2 seconds
        setTimeout(() => {
            goToNextStep('module-webdesign');
        }, 2000);
    });
}

function skipModule() {
    const data = {
        enable_module: false,
        create_sample_data: false,
        module_key: 'settings_expansion',
        skipped: true
    };
    
    showLoading('Đang bỏ qua module...');
    
    submitStep('{{ route('setup.process', 'module-settings') }}', data, (response) => {
        document.getElementById('navigation-section').classList.remove('hidden');
        
        // Auto proceed to next step after 1 second
        setTimeout(() => {
            goToNextStep('module-webdesign');
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
    
    console.log('Settings Expansion module page loaded');
});
</script>
@endpush
