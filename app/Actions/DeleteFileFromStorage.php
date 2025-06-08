<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\Storage;

/**
 * Delete File From Storage Action - KISS Principle
 * 
 * Chỉ làm 1 việc: Xóa file từ storage
 * Thay thế HandlesFileObserver trait phức tạp
 */
class DeleteFileFromStorage
{
    use AsAction;

    /**
     * Xóa file từ storage
     */
    public function handle(?string $filePath): bool
    {
        if (empty($filePath)) {
            return false;
        }

        try {
            // Xóa từ public disk
            if (Storage::disk('public')->exists($filePath)) {
                return Storage::disk('public')->delete($filePath);
            }

            // Fallback: xóa từ default disk
            if (Storage::exists($filePath)) {
                return Storage::delete($filePath);
            }

            return true; // File không tồn tại, coi như đã xóa thành công
        } catch (\Exception $e) {
            // Log error nhưng không throw exception
            \Log::warning('Failed to delete file: ' . $filePath, [
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Xóa nhiều file cùng lúc
     */
    public static function multiple(array $filePaths): array
    {
        $results = [];
        
        foreach ($filePaths as $filePath) {
            $results[$filePath] = static::run($filePath);
        }
        
        return $results;
    }

    /**
     * Xóa file từ model field
     */
    public static function fromModel($model, string $field): bool
    {
        $filePath = $model->{$field} ?? null;
        return static::run($filePath);
    }

    /**
     * Xóa file cũ khi upload file mới
     */
    public static function oldFile($model, string $field, ?string $newFilePath = null): bool
    {
        $oldFilePath = $model->getOriginal($field);
        
        // Không xóa nếu file cũ giống file mới
        if ($oldFilePath === $newFilePath) {
            return true;
        }
        
        return static::run($oldFilePath);
    }
}
