<?php

namespace App\Actions\File;

use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

/**
 * Handle File Observer Action - KISS Principle
 * 
 * Thay thế HandlesFileObserver trait
 * Chỉ làm 1 việc: Quản lý file khi model thay đổi
 */
class HandleFileObserver
{
    use AsAction;

    /**
     * Xóa file cũ khi upload file mới
     */
    public function handle(string $action, $model, array $params = []): bool
    {
        return match ($action) {
            'update' => $this->handleFileUpdate($model, $params),
            'delete' => $this->handleFileDelete($model, $params),
            'multiple' => $this->handleMultipleFiles($model, $params),
            default => false
        };
    }

    /**
     * Xử lý file update
     */
    private function handleFileUpdate($model, array $params): bool
    {
        $attribute = $params['attribute'] ?? '';
        $newValue = $params['new_value'] ?? null;
        $oldValue = $params['old_value'] ?? $model->getOriginal($attribute);

        // Nếu có file cũ và file mới khác file cũ
        if ($oldValue && $oldValue !== $newValue) {
            return $this->deleteFile($oldValue);
        }

        return true;
    }

    /**
     * Xóa file khi xóa record
     */
    private function handleFileDelete($model, array $params): bool
    {
        $fileAttributes = $params['file_attributes'] ?? $this->getFileAttributes($model);
        $success = true;

        foreach ($fileAttributes as $attribute) {
            $filePath = $model->{$attribute};
            if ($filePath) {
                $success = $this->deleteFile($filePath) && $success;
            }
        }

        return $success;
    }

    /**
     * Xử lý multiple files (array)
     */
    private function handleMultipleFiles($model, array $params): bool
    {
        $newFiles = $params['new_files'] ?? [];
        $oldFiles = $params['old_files'] ?? [];

        // Tìm files bị xóa
        $deletedFiles = array_diff($oldFiles, $newFiles);
        $success = true;
        
        foreach ($deletedFiles as $file) {
            $success = $this->deleteFile($file) && $success;
        }

        return $success;
    }

    /**
     * Xóa file từ storage - sử dụng DeleteFileFromStorage action
     */
    private function deleteFile(string $filePath): bool
    {
        return DeleteFileFromStorage::run($filePath);
    }

    /**
     * Lấy danh sách file attributes từ model
     */
    private function getFileAttributes($model): array
    {
        // Mặc định tìm các attributes có chứa 'image', 'file', 'photo', 'avatar'
        $fileKeywords = ['image', 'file', 'photo', 'avatar', 'thumbnail', 'logo', 'icon'];
        $attributes = [];

        foreach ($model->getAttributes() as $key => $value) {
            foreach ($fileKeywords as $keyword) {
                if (str_contains(strtolower($key), $keyword) && $value) {
                    $attributes[] = $key;
                    break;
                }
            }
        }

        return $attributes;
    }

    /**
     * Kiểm tra file có tồn tại không
     */
    public static function fileExists(string $filePath): bool
    {
        try {
            // Clean path trước khi check
            $cleanPath = preg_replace('/^\/storage\//', '', $filePath);
            return Storage::disk('public')->exists($cleanPath);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Static methods để dễ sử dụng
     */
    public static function updateFile($model, string $attribute, $newValue, $oldValue = null): bool
    {
        return static::run('update', $model, [
            'attribute' => $attribute,
            'new_value' => $newValue,
            'old_value' => $oldValue
        ]);
    }

    public static function deleteFiles($model, array $fileAttributes = []): bool
    {
        return static::run('delete', $model, [
            'file_attributes' => $fileAttributes
        ]);
    }

    public static function handleMultiple($model, array $newFiles, array $oldFiles): bool
    {
        return static::run('multiple', $model, [
            'new_files' => $newFiles,
            'old_files' => $oldFiles
        ]);
    }
}
