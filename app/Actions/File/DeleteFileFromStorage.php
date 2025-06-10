<?php

namespace App\Actions\File;

use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DeleteFileFromStorage
{
    use AsAction;

    /**
     * Xóa file từ storage
     */
    public function handle(string $filePath, string $disk = 'public'): bool
    {
        if (empty($filePath)) {
            return true; // Không có file để xóa
        }

        try {
            // Xóa file từ storage
            if (Storage::disk($disk)->exists($filePath)) {
                Storage::disk($disk)->delete($filePath);
                return true;
            }
            
            return true; // File không tồn tại, coi như đã xóa thành công
        } catch (\Exception $e) {
            // Log error nhưng không throw exception
            Log::warning('Failed to delete file: ' . $filePath, [
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Xóa nhiều files cùng lúc
     */
    public static function multiple(array $filePaths, string $disk = 'public'): array
    {
        $results = [];
        
        foreach ($filePaths as $filePath) {
            $results[$filePath] = static::run($filePath, $disk);
        }
        
        return $results;
    }

    /**
     * Xóa file từ public disk
     */
    public static function fromPublic(string $filePath): bool
    {
        return static::run($filePath, 'public');
    }

    /**
     * Xóa file từ local disk
     */
    public static function fromLocal(string $filePath): bool
    {
        return static::run($filePath, 'local');
    }

    /**
     * Xóa tất cả files trong thư mục
     */
    public static function directory(string $directory, string $disk = 'public'): bool
    {
        try {
            if (Storage::disk($disk)->exists($directory)) {
                // Lấy tất cả files trong thư mục
                $files = Storage::disk($disk)->allFiles($directory);
                
                // Xóa từng file
                foreach ($files as $file) {
                    Storage::disk($disk)->delete($file);
                }
                
                // Xóa thư mục rỗng
                Storage::disk($disk)->deleteDirectory($directory);
                
                return true;
            }
            
            return true; // Thư mục không tồn tại
        } catch (\Exception $e) {
            Log::warning('Failed to delete directory: ' . $directory, [
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Xóa files cũ hơn số ngày chỉ định
     */
    public static function olderThan(string $directory, int $days, string $disk = 'public'): int
    {
        try {
            $deletedCount = 0;
            $cutoffTime = now()->subDays($days)->timestamp;
            
            $files = Storage::disk($disk)->allFiles($directory);
            
            foreach ($files as $file) {
                $lastModified = Storage::disk($disk)->lastModified($file);
                
                if ($lastModified < $cutoffTime) {
                    if (Storage::disk($disk)->delete($file)) {
                        $deletedCount++;
                    }
                }
            }
            
            return $deletedCount;
        } catch (\Exception $e) {
            Log::warning('Failed to delete old files in directory: ' . $directory, [
                'error' => $e->getMessage()
            ]);
            
            return 0;
        }
    }
}
