<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TaxSetting;
use App\Models\EmailTemplate;
use App\Models\NotificationSetting;
use App\Models\BackupSetting;

class SettingsExpansionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('⚙️ Bắt đầu tạo dữ liệu mẫu cho Settings Expansion Module...');

        $this->createTaxSettings();
        $this->createEmailTemplates();
        $this->createNotificationSettings();
        $this->createBackupSettings();

        $this->command->info('🎉 Hoàn thành tạo dữ liệu mẫu Settings Expansion!');
    }

    /**
     * Tạo tax settings
     */
    private function createTaxSettings()
    {
        $taxSettings = [
            [
                'name' => 'VAT Việt Nam',
                'slug' => 'vat-viet-nam',
                'description' => 'Thuế giá trị gia tăng áp dụng tại Việt Nam',
                'type' => 'percentage',
                'rate' => 10.0000,
                'is_inclusive' => false,
                'applicable_countries' => ['VN'],
                'customer_type' => 'all',
                'priority' => 10,
                'status' => 'active',
                'order' => 1
            ],
            [
                'name' => 'Thuế nhập khẩu',
                'slug' => 'thue-nhap-khau',
                'description' => 'Thuế nhập khẩu cho hàng hóa từ nước ngoài',
                'type' => 'percentage',
                'rate' => 5.0000,
                'is_inclusive' => false,
                'applicable_countries' => ['VN'],
                'customer_type' => 'business',
                'priority' => 5,
                'status' => 'active',
                'order' => 2
            ],
            [
                'name' => 'Phí dịch vụ',
                'slug' => 'phi-dich-vu',
                'description' => 'Phí dịch vụ cố định cho đơn hàng',
                'type' => 'fixed',
                'rate' => 100.0000,
                'is_inclusive' => false,
                'applicable_countries' => ['VN'],
                'customer_type' => 'all',
                'priority' => 1,
                'status' => 'inactive',
                'order' => 3
            ]
        ];

        foreach ($taxSettings as $taxData) {
            TaxSetting::firstOrCreate(
                ['slug' => $taxData['slug']],
                $taxData
            );
        }

        $this->command->info('✅ Đã tạo ' . count($taxSettings) . ' tax settings');
    }

    /**
     * Tạo email templates
     */
    private function createEmailTemplates()
    {
        $emailTemplates = [
            [
                'name' => 'Xác nhận đơn hàng',
                'slug' => 'xac-nhan-don-hang',
                'description' => 'Email gửi khi khách hàng đặt hàng thành công',
                'subject' => 'Xác nhận đơn hàng #{{order_number}}',
                'html_content' => $this->getOrderConfirmationTemplate(),
                'type' => 'order_confirmation',
                'category' => 'order',
                'available_variables' => ['order_number', 'customer_name', 'order_total', 'order_items'],
                'required_variables' => ['order_number', 'customer_name'],
                'is_automated' => true,
                'language' => 'vi',
                'status' => 'active',
                'is_default' => true,
                'order' => 1
            ],
            [
                'name' => 'Chào mừng người dùng mới',
                'slug' => 'chao-mung-nguoi-dung-moi',
                'description' => 'Email chào mừng khi người dùng đăng ký tài khoản',
                'subject' => 'Chào mừng {{user_name}} đến với {{site_name}}!',
                'html_content' => $this->getWelcomeTemplate(),
                'type' => 'user_welcome',
                'category' => 'user',
                'available_variables' => ['user_name', 'site_name', 'login_url'],
                'required_variables' => ['user_name', 'site_name'],
                'is_automated' => true,
                'language' => 'vi',
                'status' => 'active',
                'is_default' => true,
                'order' => 2
            ],
            [
                'name' => 'Đặt lại mật khẩu',
                'slug' => 'dat-lai-mat-khau',
                'description' => 'Email gửi link đặt lại mật khẩu',
                'subject' => 'Yêu cầu đặt lại mật khẩu',
                'html_content' => $this->getPasswordResetTemplate(),
                'type' => 'password_reset',
                'category' => 'user',
                'available_variables' => ['user_name', 'reset_url', 'expires_at'],
                'required_variables' => ['user_name', 'reset_url'],
                'is_automated' => true,
                'language' => 'vi',
                'status' => 'active',
                'is_default' => true,
                'order' => 3
            ]
        ];

        foreach ($emailTemplates as $templateData) {
            EmailTemplate::firstOrCreate(
                ['slug' => $templateData['slug']],
                $templateData
            );
        }

        $this->command->info('✅ Đã tạo ' . count($emailTemplates) . ' email templates');
    }

    /**
     * Tạo notification settings
     */
    private function createNotificationSettings()
    {
        // Tạo email templates trước
        $orderTemplate = EmailTemplate::where('slug', 'xac-nhan-don-hang')->first();

        $notificationSettings = [
            [
                'name' => 'Thông báo đơn hàng mới',
                'slug' => 'thong-bao-don-hang-moi',
                'description' => 'Thông báo khi có đơn hàng mới',
                'type' => 'order_status',
                'category' => 'order',
                'email_enabled' => true,
                'database_enabled' => true,
                'email_template_id' => $orderTemplate?->id,
                'email_to_admin' => true,
                'email_to_user' => true,
                'frequency' => 'immediate',
                'priority' => 'high',
                'status' => 'active',
                'order' => 1
            ],
            [
                'name' => 'Cảnh báo tồn kho thấp',
                'slug' => 'canh-bao-ton-kho-thap',
                'description' => 'Thông báo khi sản phẩm sắp hết hàng',
                'type' => 'inventory_alert',
                'category' => 'inventory',
                'email_enabled' => true,
                'slack_enabled' => true,
                'email_to_admin' => true,
                'frequency' => 'daily',
                'priority' => 'normal',
                'status' => 'active',
                'order' => 2
            ],
            [
                'name' => 'Đánh giá sản phẩm mới',
                'slug' => 'danh-gia-san-pham-moi',
                'description' => 'Thông báo khi có đánh giá sản phẩm mới',
                'type' => 'review_submitted',
                'category' => 'product',
                'email_enabled' => true,
                'database_enabled' => true,
                'email_to_admin' => true,
                'frequency' => 'immediate',
                'priority' => 'low',
                'status' => 'active',
                'order' => 3
            ]
        ];

        foreach ($notificationSettings as $notificationData) {
            NotificationSetting::firstOrCreate(
                ['slug' => $notificationData['slug']],
                $notificationData
            );
        }

        $this->command->info('✅ Đã tạo ' . count($notificationSettings) . ' notification settings');
    }

    /**
     * Tạo backup settings
     */
    private function createBackupSettings()
    {
        $backupSettings = [
            [
                'name' => 'Backup hàng ngày',
                'slug' => 'backup-hang-ngay',
                'description' => 'Backup toàn bộ hệ thống mỗi ngày',
                'type' => 'full',
                'storage_type' => 'local',
                'storage_path' => 'backups/daily',
                'is_scheduled' => true,
                'frequency' => 'daily',
                'preferred_time' => '02:00:00',
                'keep_daily' => 7,
                'keep_weekly' => 4,
                'keep_monthly' => 6,
                'auto_cleanup' => true,
                'compression' => 'gzip',
                'notify_on_failure' => true,
                'verify_backup' => true,
                'status' => 'active',
                'order' => 1
            ],
            [
                'name' => 'Backup cơ sở dữ liệu',
                'slug' => 'backup-co-so-du-lieu',
                'description' => 'Backup chỉ cơ sở dữ liệu mỗi 6 giờ',
                'type' => 'database_only',
                'storage_type' => 'local',
                'storage_path' => 'backups/database',
                'is_scheduled' => true,
                'frequency' => 'hourly',
                'keep_daily' => 14,
                'auto_cleanup' => true,
                'compression' => 'gzip',
                'use_single_transaction' => true,
                'verify_backup' => true,
                'status' => 'active',
                'order' => 2
            ],
            [
                'name' => 'Backup files hàng tuần',
                'slug' => 'backup-files-hang-tuan',
                'description' => 'Backup files và media hàng tuần',
                'type' => 'files_only',
                'storage_type' => 'local',
                'storage_path' => 'backups/files',
                'included_directories' => ['storage/app/public', 'public/uploads'],
                'excluded_directories' => ['storage/logs', 'storage/cache'],
                'is_scheduled' => true,
                'frequency' => 'weekly',
                'preferred_time' => '01:00:00',
                'preferred_days' => [0], // Sunday
                'keep_weekly' => 8,
                'keep_monthly' => 12,
                'auto_cleanup' => true,
                'compression' => 'zip',
                'status' => 'active',
                'order' => 3
            ]
        ];

        foreach ($backupSettings as $backupData) {
            $backup = BackupSetting::firstOrCreate(
                ['slug' => $backupData['slug']],
                $backupData
            );

            // Update next run time
            $backup->updateNextRun();
        }

        $this->command->info('✅ Đã tạo ' . count($backupSettings) . ' backup settings');
    }

    /**
     * Get order confirmation email template
     */
    private function getOrderConfirmationTemplate(): string
    {
        return '
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
            <h2 style="color: #dc2626;">Xác nhận đơn hàng #{{order_number}}</h2>
            <p>Xin chào {{customer_name}},</p>
            <p>Cảm ơn bạn đã đặt hàng tại cửa hàng của chúng tôi. Đơn hàng của bạn đã được xác nhận và đang được xử lý.</p>

            <div style="background: #f9fafb; padding: 20px; margin: 20px 0; border-radius: 8px;">
                <h3>Thông tin đơn hàng:</h3>
                <p><strong>Mã đơn hàng:</strong> {{order_number}}</p>
                <p><strong>Tổng tiền:</strong> {{order_total}}</p>
                <p><strong>Ngày đặt:</strong> {{order_date}}</p>
            </div>

            {{#if order_items}}
            <h3>Sản phẩm đã đặt:</h3>
            <div style="border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden;">
                {{order_items}}
            </div>
            {{/if}}

            <p>Chúng tôi sẽ thông báo cho bạn khi đơn hàng được giao.</p>
            <p>Trân trọng,<br>Đội ngũ hỗ trợ</p>
        </div>';
    }

    /**
     * Get welcome email template
     */
    private function getWelcomeTemplate(): string
    {
        return '
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
            <h2 style="color: #dc2626;">Chào mừng đến với {{site_name}}!</h2>
            <p>Xin chào {{user_name}},</p>
            <p>Cảm ơn bạn đã đăng ký tài khoản tại {{site_name}}. Chúng tôi rất vui mừng được chào đón bạn!</p>

            <div style="background: #f9fafb; padding: 20px; margin: 20px 0; border-radius: 8px;">
                <h3>Bạn có thể:</h3>
                <ul>
                    <li>Duyệt và mua sắm các sản phẩm</li>
                    <li>Theo dõi đơn hàng của mình</li>
                    <li>Quản lý thông tin cá nhân</li>
                    <li>Nhận thông báo về khuyến mãi</li>
                </ul>
            </div>

            {{#if login_url}}
            <p style="text-align: center;">
                <a href="{{login_url}}" style="background: #dc2626; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block;">
                    Đăng nhập ngay
                </a>
            </p>
            {{/if}}

            <p>Nếu bạn có bất kỳ câu hỏi nào, đừng ngần ngại liên hệ với chúng tôi.</p>
            <p>Trân trọng,<br>Đội ngũ {{site_name}}</p>
        </div>';
    }

    /**
     * Get password reset email template
     */
    private function getPasswordResetTemplate(): string
    {
        return '
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
            <h2 style="color: #dc2626;">Đặt lại mật khẩu</h2>
            <p>Xin chào {{user_name}},</p>
            <p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.</p>

            <div style="background: #fef3c7; padding: 20px; margin: 20px 0; border-radius: 8px; border-left: 4px solid #f59e0b;">
                <p><strong>Lưu ý:</strong> Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.</p>
            </div>

            <p style="text-align: center;">
                <a href="{{reset_url}}" style="background: #dc2626; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; display: inline-block;">
                    Đặt lại mật khẩu
                </a>
            </p>

            {{#if expires_at}}
            <p style="text-align: center; color: #6b7280; font-size: 14px;">
                Link này sẽ hết hạn vào {{expires_at}}
            </p>
            {{/if}}

            <p>Nếu nút không hoạt động, bạn có thể copy và paste link sau vào trình duyệt:</p>
            <p style="word-break: break-all; color: #6b7280;">{{reset_url}}</p>

            <p>Trân trọng,<br>Đội ngũ hỗ trợ</p>
        </div>';
    }
}
