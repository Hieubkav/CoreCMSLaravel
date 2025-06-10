<?php

namespace App\Traits;

use App\Actions\File\HandleFileObserver;

/**
 * Handles File Observer Trait - Backward Compatibility
 * 
 * Trait đơn giản để tương thích ngược với các Observer cũ
 * Bên trong sử dụng HandleFileObserver Action (KISS principle)
 */
trait HandlesFileObserver
{
    /**
     * Xóa file cũ khi upload file mới
     */
    public function handleFileUpdate($model, string $attribute, $newValue, $oldValue = null)
    {
        return HandleFileObserver::updateFile($model, $attribute, $newValue, $oldValue);
    }

    /**
     * Xóa file khi xóa record
     */
    public function handleFileDelete($model, array $fileAttributes = [])
    {
        return HandleFileObserver::deleteFiles($model, $fileAttributes);
    }

    /**
     * Xử lý multiple files (array)
     */
    protected function handleMultipleFiles($model, array $newFiles = [], array $oldFiles = [])
    {
        return HandleFileObserver::handleMultiple($model, $newFiles, $oldFiles);
    }

    /**
     * Kiểm tra file có tồn tại không
     */
    protected function fileExists(string $filePath): bool
    {
        return HandleFileObserver::fileExists($filePath);
    }

    /**
     * Legacy methods để tương thích với Observer cũ
     */
    
    /**
     * Store old file info (legacy method - simplified)
     */
    protected function storeOldFile(string $modelClass, $modelId, string $attribute, $oldValue)
    {
        // Simplified - chỉ lưu vào session tạm thời
        $key = "{$modelClass}_{$modelId}_{$attribute}";
        session()->put("old_file_{$key}", $oldValue);
    }

    /**
     * Get and delete old file (legacy method - simplified)
     */
    protected function getAndDeleteOldFile(string $modelClass, $modelId, string $attribute)
    {
        $key = "{$modelClass}_{$modelId}_{$attribute}";
        $oldFile = session()->pull("old_file_{$key}");
        
        if ($oldFile) {
            HandleFileObserver::run('delete', null, [
                'file_attributes' => [$attribute => $oldFile]
            ]);
        }
        
        return $oldFile;
    }

    /**
     * Delete file if exists (legacy method)
     */
    protected function deleteFileIfExists(?string $filePath): void
    {
        if ($filePath) {
            HandleFileObserver::run('delete', null, [
                'file_attributes' => ['file' => $filePath]
            ]);
        }
    }

    /**
     * Clear cache (legacy method - simplified)
     */
    protected function clearCache(): void
    {
        // Simplified cache clearing
        \Illuminate\Support\Facades\Cache::flush();
    }

    /**
     * Clear related cache (legacy method)
     */
    protected function clearRelatedCache(): void
    {
        $this->clearCache();
    }
}
