<?php

namespace App\Actions\Setup;

use App\Actions\Setup\CodeGenerator;
use Exception;

class CreateTemplates
{
    /**
     * Tạo templates từ existing files
     */
    public static function handle(): array
    {
        try {
            // Kiểm tra xem đã có templates chưa
            if (CodeGenerator::templatesExist()) {
                return [
                    'success' => false,
                    'message' => 'Templates đã tồn tại. Vui lòng xóa thư mục storage/setup-templates trước khi tạo mới.'
                ];
            }

            // Tạo templates từ existing files
            $result = CodeGenerator::createTemplatesFromExisting();

            if ($result['success']) {
                return [
                    'success' => true,
                    'message' => 'Đã tạo templates thành công từ existing files!',
                    'details' => $result['details']
                ];
            } else {
                return $result;
            }

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi tạo templates: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Force tạo templates (xóa cũ và tạo mới)
     */
    public static function forceCreate(): array
    {
        try {
            // Xóa templates cũ nếu có
            $templatesPath = base_path('storage/setup-templates');
            if (file_exists($templatesPath)) {
                self::deleteDirectory($templatesPath);
            }

            // Tạo templates mới
            return self::handle();

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi force create templates: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Helper method để xóa thư mục
     */
    private static function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            $filePath = $dir . DIRECTORY_SEPARATOR . $file;

            if (is_dir($filePath)) {
                self::deleteDirectory($filePath);
            } else {
                unlink($filePath);
            }
        }

        rmdir($dir);
    }
}
