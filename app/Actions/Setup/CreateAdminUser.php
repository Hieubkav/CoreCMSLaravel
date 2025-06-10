<?php

namespace App\Actions\Setup;

use Lorisleiva\Actions\Concerns\AsAction;
use App\Models\User;
use Exception;

/**
 * Create Admin User Action - KISS Principle
 * 
 * Chỉ làm 1 việc: Tạo tài khoản admin đầu tiên
 * Thay thế logic phức tạp trong SetupController
 */
class CreateAdminUser
{
    use AsAction;

    /**
     * Tạo tài khoản admin
     */
    public function handle(array $data): array
    {
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'email_verified_at' => now()
            ]);

            // Tạo role Super Admin nếu chưa có và gán cho user
            $this->ensureSuperAdminRole($user);

            return [
                'success' => true,
                'message' => 'Tạo tài khoản admin thành công! User có thể login vào admin panel.',
                'user_id' => $user->id,
                'user_email' => $user->email,
                'next_step' => 'website'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => 'Không thể tạo admin: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Validate dữ liệu đầu vào
     */
    public static function validate(array $data): array
    {
        $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = 'Vui lòng nhập họ và tên';
        }

        if (empty($data['email'])) {
            $errors['email'] = 'Vui lòng nhập email';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ';
        } elseif (User::where('email', $data['email'])->exists()) {
            $errors['email'] = 'Email đã tồn tại';
        }

        if (empty($data['password'])) {
            $errors['password'] = 'Vui lòng nhập mật khẩu';
        } elseif (strlen($data['password']) < 8) {
            $errors['password'] = 'Mật khẩu phải có ít nhất 8 ký tự';
        }

        if (empty($data['password_confirmation'])) {
            $errors['password_confirmation'] = 'Vui lòng xác nhận mật khẩu';
        } elseif ($data['password'] !== $data['password_confirmation']) {
            $errors['password_confirmation'] = 'Mật khẩu xác nhận không khớp';
        }

        return $errors;
    }

    /**
     * Tạo admin với validation
     */
    public static function createWithValidation(array $data): array
    {
        $errors = static::validate($data);
        
        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors
            ];
        }

        return static::run($data);
    }

    /**
     * Đảm bảo role Super Admin tồn tại và gán cho user
     */
    private function ensureSuperAdminRole(User $user): void
    {
        try {
            // Kiểm tra xem có bảng roles không (có thể chưa chạy migration)
            if (!\Illuminate\Support\Facades\Schema::hasTable('roles')) {
                return; // Bỏ qua nếu chưa có bảng roles
            }

            // Tạo role Super Admin nếu chưa có
            $role = \Spatie\Permission\Models\Role::firstOrCreate([
                'name' => 'Super Admin',
                'guard_name' => 'web'
            ]);

            // Gán role cho user
            if (!$user->hasRole('Super Admin')) {
                $user->assignRole($role);
            }

        } catch (Exception $e) {
            // Nếu có lỗi với roles, không làm gì (user vẫn có thể login bằng fallback)
            // Log lỗi để debug
            \Illuminate\Support\Facades\Log::info('Could not assign Super Admin role: ' . $e->getMessage());
        }
    }
}
