<?php

namespace App\Actions\System;

use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\File;

class CreateModelObserver
{
    use AsAction;

    /**
     * Tạo Observer cho model với file handling và cache clearing
     */
    public function handle(string $modelName, array $fileFields = []): string
    {
        $observerName = $modelName . 'Observer';
        $observerPath = app_path("Observers/{$observerName}.php");
        
        // Tạo nội dung observer
        $content = $this->generateObserverContent($modelName, $observerName, $fileFields);
        
        // Tạo thư mục nếu chưa có
        File::ensureDirectoryExists(dirname($observerPath));
        
        // Ghi file
        File::put($observerPath, $content);
        
        return $observerPath;
    }

    /**
     * Generate nội dung observer
     */
    private function generateObserverContent(string $modelName, string $observerName, array $fileFields): string
    {
        $fileHandling = $this->generateFileHandling($fileFields);
        
        return "<?php

namespace App\Observers;

use App\Models\\{$modelName};
use App\Actions\Cache\ClearViewCache;
use App\Actions\File\DeleteFileFromStorage;

class {$observerName}
{
    /**
     * Handle the {$modelName} \"created\" event.
     */
    public function created({$modelName} \$model): void
    {
        ClearViewCache::forModel(\$model);
    }

    /**
     * Handle the {$modelName} \"updating\" event.
     */
    public function updating({$modelName} \$model): void
    {{$fileHandling}
    }

    /**
     * Handle the {$modelName} \"updated\" event.
     */
    public function updated({$modelName} \$model): void
    {
        ClearViewCache::forModel(\$model);
    }

    /**
     * Handle the {$modelName} \"deleted\" event.
     */
    public function deleted({$modelName} \$model): void
    {
{$this->generateFileCleanup($fileFields)}
        \App\Actions\Cache\ClearViewCache::forModel(\$model);
    }

    /**
     * Handle the {$modelName} \"restored\" event.
     */
    public function restored({$modelName} \$model): void
    {
        ClearViewCache::forModel(\$model);
    }
}
";
    }

    /**
     * Generate file handling code cho updating event
     */
    private function generateFileHandling(array $fileFields): string
    {
        if (empty($fileFields)) {
            return '';
        }

        $code = "\n        // Xóa file cũ khi upload file mới";
        
        foreach ($fileFields as $field) {
            $code .= "\n        if (\$model->isDirty('{$field}') && \$model->getOriginal('{$field}')) {";
            $code .= "\n            \\App\\Actions\\File\\DeleteFileFromStorage::run(\$model->getOriginal('{$field}'));";
            $code .= "\n        }";
        }
        
        return $code;
    }

    /**
     * Generate file cleanup code cho deleted event
     */
    private function generateFileCleanup(array $fileFields): string
    {
        if (empty($fileFields)) {
            return '';
        }

        $code = "        // Xóa files khi xóa record\n";
        
        foreach ($fileFields as $field) {
            $code .= "        if (\$model->{$field}) {\n";
            $code .= "            \\App\\Actions\\File\\DeleteFileFromStorage::run(\$model->{$field});\n";
            $code .= "        }\n";
        }
        
        return $code;
    }

    /**
     * Tạo observer cho model có file uploads
     */
    public static function withFileHandling(string $modelName, array $fileFields): string
    {
        return static::run($modelName, $fileFields);
    }

    /**
     * Tạo observer đơn giản chỉ có cache clearing
     */
    public static function simple(string $modelName): string
    {
        return static::run($modelName, []);
    }
}
