<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Generated\Models\MultiLanguage;
use App\Generated\Models\AutomationWorkflow;

class AdvancedFeaturesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Bắt đầu tạo Advanced Features data...');

        // Seed Multi-language translations
        $this->seedMultiLanguageTranslations();
        
        // Seed Automation workflows
        $this->seedAutomationWorkflows();

        $this->command->info('✅ Đã tạo Advanced Features data thành công!');
    }

    /**
     * Seed multi-language translations
     */
    private function seedMultiLanguageTranslations()
    {
        $this->command->info('🌐 Tạo dữ liệu đa ngôn ngữ...');

        // Clear existing translations
        MultiLanguage::truncate();

        // Vietnamese translations (default)
        $viTranslations = [
            // General
            'welcome' => 'Chào mừng',
            'home' => 'Trang chủ',
            'about' => 'Giới thiệu',
            'contact' => 'Liên hệ',
            'services' => 'Dịch vụ',
            'products' => 'Sản phẩm',
            'blog' => 'Blog',
            'news' => 'Tin tức',
            'search' => 'Tìm kiếm',
            'login' => 'Đăng nhập',
            'register' => 'Đăng ký',
            'logout' => 'Đăng xuất',
            'profile' => 'Hồ sơ',
            'settings' => 'Cài đặt',
            
            // Navigation
            'menu' => 'Menu',
            'back' => 'Quay lại',
            'next' => 'Tiếp theo',
            'previous' => 'Trước đó',
            'continue' => 'Tiếp tục',
            'cancel' => 'Hủy',
            'save' => 'Lưu',
            'edit' => 'Chỉnh sửa',
            'delete' => 'Xóa',
            'view' => 'Xem',
            'download' => 'Tải xuống',
            
            // Forms
            'name' => 'Tên',
            'email' => 'Email',
            'phone' => 'Số điện thoại',
            'address' => 'Địa chỉ',
            'message' => 'Tin nhắn',
            'subject' => 'Chủ đề',
            'description' => 'Mô tả',
            'title' => 'Tiêu đề',
            'content' => 'Nội dung',
            'category' => 'Danh mục',
            'tags' => 'Thẻ',
            'image' => 'Hình ảnh',
            'file' => 'Tệp tin',
            'date' => 'Ngày',
            'time' => 'Thời gian',
            'status' => 'Trạng thái',
            'price' => 'Giá',
            'quantity' => 'Số lượng',
            
            // Messages
            'success' => 'Thành công',
            'error' => 'Lỗi',
            'warning' => 'Cảnh báo',
            'info' => 'Thông tin',
            'loading' => 'Đang tải...',
            'no_data' => 'Không có dữ liệu',
            'no_results' => 'Không có kết quả',
            'confirm_delete' => 'Bạn có chắc chắn muốn xóa?',
            'operation_successful' => 'Thao tác thành công',
            'operation_failed' => 'Thao tác thất bại',
            
            // Validation
            'required' => 'Trường này là bắt buộc',
            'email_invalid' => 'Email không hợp lệ',
            'password_min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password_confirm' => 'Xác nhận mật khẩu không khớp',
            'file_too_large' => 'Tệp tin quá lớn',
            'invalid_format' => 'Định dạng không hợp lệ',
        ];

        // English translations
        $enTranslations = [
            // General
            'welcome' => 'Welcome',
            'home' => 'Home',
            'about' => 'About',
            'contact' => 'Contact',
            'services' => 'Services',
            'products' => 'Products',
            'blog' => 'Blog',
            'news' => 'News',
            'search' => 'Search',
            'login' => 'Login',
            'register' => 'Register',
            'logout' => 'Logout',
            'profile' => 'Profile',
            'settings' => 'Settings',
            
            // Navigation
            'menu' => 'Menu',
            'back' => 'Back',
            'next' => 'Next',
            'previous' => 'Previous',
            'continue' => 'Continue',
            'cancel' => 'Cancel',
            'save' => 'Save',
            'edit' => 'Edit',
            'delete' => 'Delete',
            'view' => 'View',
            'download' => 'Download',
            
            // Forms
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'address' => 'Address',
            'message' => 'Message',
            'subject' => 'Subject',
            'description' => 'Description',
            'title' => 'Title',
            'content' => 'Content',
            'category' => 'Category',
            'tags' => 'Tags',
            'image' => 'Image',
            'file' => 'File',
            'date' => 'Date',
            'time' => 'Time',
            'status' => 'Status',
            'price' => 'Price',
            'quantity' => 'Quantity',
            
            // Messages
            'success' => 'Success',
            'error' => 'Error',
            'warning' => 'Warning',
            'info' => 'Information',
            'loading' => 'Loading...',
            'no_data' => 'No data',
            'no_results' => 'No results',
            'confirm_delete' => 'Are you sure you want to delete?',
            'operation_successful' => 'Operation successful',
            'operation_failed' => 'Operation failed',
            
            // Validation
            'required' => 'This field is required',
            'email_invalid' => 'Invalid email',
            'password_min' => 'Password must be at least 8 characters',
            'password_confirm' => 'Password confirmation does not match',
            'file_too_large' => 'File is too large',
            'invalid_format' => 'Invalid format',
        ];

        // Import Vietnamese translations
        MultiLanguage::importTranslations($viTranslations, 'vi', 'general');
        
        // Import English translations
        MultiLanguage::importTranslations($enTranslations, 'en', 'general');

        // Set Vietnamese as default
        MultiLanguage::setTranslation('default_language', 'vi', 'vi', 'system');
        MultiLanguage::setTranslation('default_language', 'vi', 'en', 'system');

        $this->command->info('   ✅ Đã tạo ' . count($viTranslations) . ' bản dịch tiếng Việt');
        $this->command->info('   ✅ Đã tạo ' . count($enTranslations) . ' bản dịch tiếng Anh');
    }

    /**
     * Seed automation workflows
     */
    private function seedAutomationWorkflows()
    {
        $this->command->info('🤖 Tạo automation workflows...');

        // Clear existing workflows
        AutomationWorkflow::truncate();

        // Daily backup workflow
        AutomationWorkflow::create([
            'name' => 'Sao lưu dữ liệu hàng ngày',
            'description' => 'Tự động sao lưu cơ sở dữ liệu và files quan trọng mỗi ngày lúc 2:00 AM',
            'trigger_type' => 'scheduled',
            'trigger_conditions' => [
                'schedule_type' => 'daily',
                'time' => '02:00',
            ],
            'actions' => [
                [
                    'type' => 'backup_data',
                    'params' => [
                        'include_database' => true,
                        'include_files' => true,
                        'storage_path' => 'backups/daily',
                    ],
                    'stop_on_failure' => true,
                ],
                [
                    'type' => 'send_notification',
                    'params' => [
                        'type' => 'email',
                        'to' => 'admin@example.com',
                        'subject' => 'Daily Backup Completed',
                    ],
                ],
            ],
            'schedule' => [
                'type' => 'daily',
                'time' => '02:00',
            ],
            'is_active' => true,
            'is_recurring' => true,
            'priority' => 8,
            'next_execution_at' => now()->addDay()->setTime(2, 0),
            'created_by' => 1,
            'tags' => ['backup', 'maintenance', 'daily'],
        ]);

        // Cache cleanup workflow
        AutomationWorkflow::create([
            'name' => 'Dọn dẹp cache hàng tuần',
            'description' => 'Tự động xóa cache cũ và tối ưu hóa hiệu suất hệ thống mỗi tuần',
            'trigger_type' => 'scheduled',
            'trigger_conditions' => [
                'schedule_type' => 'weekly',
                'day' => 'sunday',
                'time' => '03:00',
            ],
            'actions' => [
                [
                    'type' => 'clean_cache',
                    'params' => [
                        'clear_all' => true,
                    ],
                ],
                [
                    'type' => 'optimize_images',
                    'params' => [
                        'quality' => 85,
                        'max_width' => 1920,
                    ],
                ],
                [
                    'type' => 'generate_report',
                    'params' => [
                        'type' => 'performance',
                        'period' => 'weekly',
                    ],
                ],
            ],
            'schedule' => [
                'type' => 'weekly',
                'day' => 'sunday',
                'time' => '03:00',
            ],
            'is_active' => true,
            'is_recurring' => true,
            'priority' => 6,
            'next_execution_at' => now()->next('sunday')->setTime(3, 0),
            'created_by' => 1,
            'tags' => ['cache', 'optimization', 'weekly'],
        ]);

        // User welcome email workflow
        AutomationWorkflow::create([
            'name' => 'Email chào mừng người dùng mới',
            'description' => 'Tự động gửi email chào mừng khi có người dùng mới đăng ký',
            'trigger_type' => 'event',
            'trigger_conditions' => [
                'event' => 'user.registered',
                'conditions' => [
                    'user_type' => 'customer',
                ],
            ],
            'actions' => [
                [
                    'type' => 'send_email',
                    'params' => [
                        'template' => 'welcome',
                        'to' => '{{user.email}}',
                        'subject' => 'Chào mừng bạn đến với {{site.name}}',
                        'data' => [
                            'user_name' => '{{user.name}}',
                            'site_name' => '{{site.name}}',
                        ],
                    ],
                ],
                [
                    'type' => 'update_database',
                    'params' => [
                        'table' => 'users',
                        'where' => ['id' => '{{user.id}}'],
                        'update' => ['welcome_email_sent' => true],
                    ],
                ],
            ],
            'is_active' => true,
            'is_recurring' => false,
            'priority' => 7,
            'created_by' => 1,
            'tags' => ['email', 'welcome', 'user'],
        ]);

        // Analytics report workflow
        AutomationWorkflow::create([
            'name' => 'Báo cáo analytics hàng tháng',
            'description' => 'Tự động tạo và gửi báo cáo analytics tổng hợp hàng tháng',
            'trigger_type' => 'scheduled',
            'trigger_conditions' => [
                'schedule_type' => 'monthly',
                'day' => 1,
                'time' => '09:00',
            ],
            'actions' => [
                [
                    'type' => 'generate_report',
                    'params' => [
                        'type' => 'analytics',
                        'period' => 'monthly',
                        'format' => 'pdf',
                        'include_charts' => true,
                    ],
                ],
                [
                    'type' => 'send_email',
                    'params' => [
                        'template' => 'monthly_report',
                        'to' => ['admin@example.com', 'manager@example.com'],
                        'subject' => 'Báo cáo Analytics tháng {{month}}',
                        'attachments' => ['{{report.file}}'],
                    ],
                ],
            ],
            'schedule' => [
                'type' => 'monthly',
                'day' => 1,
                'time' => '09:00',
            ],
            'is_active' => true,
            'is_recurring' => true,
            'priority' => 5,
            'next_execution_at' => now()->addMonth()->startOfMonth()->setTime(9, 0),
            'created_by' => 1,
            'tags' => ['analytics', 'report', 'monthly'],
        ]);

        // Error notification workflow
        AutomationWorkflow::create([
            'name' => 'Thông báo lỗi hệ thống',
            'description' => 'Tự động gửi thông báo khi phát hiện lỗi nghiêm trọng trong hệ thống',
            'trigger_type' => 'event',
            'trigger_conditions' => [
                'event' => 'system.error',
                'conditions' => [
                    'severity' => ['critical', 'high'],
                ],
            ],
            'actions' => [
                [
                    'type' => 'send_notification',
                    'params' => [
                        'type' => 'email',
                        'to' => 'admin@example.com',
                        'subject' => 'Cảnh báo lỗi hệ thống: {{error.type}}',
                        'priority' => 'high',
                    ],
                ],
                [
                    'type' => 'send_notification',
                    'params' => [
                        'type' => 'sms',
                        'to' => '+84123456789',
                        'message' => 'Lỗi hệ thống: {{error.message}}',
                    ],
                ],
            ],
            'is_active' => true,
            'is_recurring' => false,
            'priority' => 10,
            'created_by' => 1,
            'tags' => ['error', 'notification', 'critical'],
        ]);

        $this->command->info('   ✅ Đã tạo 5 automation workflows');
    }
}
