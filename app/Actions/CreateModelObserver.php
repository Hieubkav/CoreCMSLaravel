<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;

/**
 * Create Model Observer Action - KISS Principle
 * 
 * Chỉ làm 1 việc: Tạo Observer template đơn giản sử dụng Actions
 * Thay thế các Observer phức tạp với traits
 */
class CreateModelObserver
{
    use AsAction;

    /**
     * Tạo Observer content cho model
     */
    public function handle(string $modelName, array $fileFields = []): string
    {
        $className = $modelName . 'Observer';
        $modelClass = "App\\Models\\{$modelName}";
        
        $fileHandling = '';
        if (!empty($fileFields)) {
            $fileHandling = $this->generateFileHandling($fileFields);
        }

        return <<<PHP
<?php

namespace App\Observers;

use {$modelClass};
use App\Actions\ClearViewCache;
use App\Actions\DeleteFileFromStorage;

class {$className}
{
    /**
     * Handle the {$modelName} "creating" event.
     */
    public function creating({$modelName} \$model): void
    {
        // Tự động tạo slug nếu chưa có
        if (empty(\$model->slug) && method_exists(\$model, 'generateSlug')) {
            \$model->slug = \$model->generateSlug();
        }
    }

    /**
     * Handle the {$modelName} "created" event.
     */
    public function created({$modelName} \$model): void
    {
        ClearViewCache::forModel(\$model);
    }

    /**
     * Handle the {$modelName} "updating" event.
     */
    public function updating({$modelName} \$model): void
    {
        // Cập nhật slug nếu title thay đổi
        if (\$model->isDirty('title') && empty(\$model->slug) && method_exists(\$model, 'generateSlug')) {
            \$model->slug = \$model->generateSlug();
        }
    }

    /**
     * Handle the {$modelName} "updated" event.
     */
    public function updated({$modelName} \$model): void
    {{$fileHandling}
        ClearViewCache::forModel(\$model);
    }

    /**
     * Handle the {$modelName} "deleted" event.
     */
    public function deleted({$modelName} \$model): void
    {
{$this->generateFileCleanup($fileFields)}
        ClearViewCache::forModel(\$model);
    }

    /**
     * Handle the {$modelName} "restored" event.
     */
    public function restored({$modelName} \$model): void
    {
        ClearViewCache::forModel(\$model);
    }

    /**
     * Handle the {$modelName} "force deleted" event.
     */
    public function forceDeleted({$modelName} \$model): void
    {
{$this->generateFileCleanup($fileFields)}
        ClearViewCache::forModel(\$model);
    }
}
PHP;
    }

    /**
     * Tạo code xử lý file trong updated event
     */
    private function generateFileHandling(array $fileFields): string
    {
        if (empty($fileFields)) {
            return '';
        }

        $code = "\n        // Xóa file cũ nếu có thay đổi\n";
        
        foreach ($fileFields as $field) {
            $code .= "        if (\$model->wasChanged('{$field}')) {\n";
            $code .= "            DeleteFileFromStorage::oldFile(\$model, '{$field}');\n";
            $code .= "        }\n        \n";
        }

        return $code;
    }

    /**
     * Tạo code cleanup file trong deleted events
     */
    private function generateFileCleanup(array $fileFields): string
    {
        if (empty($fileFields)) {
            return "        // No files to cleanup\n";
        }

        $code = "        // Xóa tất cả files liên quan\n";
        
        foreach ($fileFields as $field) {
            $code .= "        DeleteFileFromStorage::fromModel(\$model, '{$field}');\n";
        }

        return $code;
    }

    /**
     * Tạo Observer cho Course
     */
    public static function forCourse(): string
    {
        return static::run('Course', ['thumbnail_link', 'og_image_link']);
    }

    /**
     * Tạo Observer cho Post
     */
    public static function forPost(): string
    {
        return static::run('Post', ['thumbnail_link', 'og_image_link']);
    }

    /**
     * Tạo Observer cho Instructor
     */
    public static function forInstructor(): string
    {
        return static::run('Instructor', ['avatar_link']);
    }

    /**
     * Tạo Observer cho Student
     */
    public static function forStudent(): string
    {
        return static::run('Student', ['avatar_link']);
    }

    /**
     * Tạo Observer cho Slider
     */
    public static function forSlider(): string
    {
        return static::run('Slider', ['image_link']);
    }

    /**
     * Tạo Observer cho Partner
     */
    public static function forPartner(): string
    {
        return static::run('Partner', ['logo_link']);
    }

    /**
     * Tạo Observer cho Testimonial
     */
    public static function forTestimonial(): string
    {
        return static::run('Testimonial', ['avatar_link']);
    }

    /**
     * Tạo Observer đơn giản không có file
     */
    public static function simple(string $modelName): string
    {
        return static::run($modelName, []);
    }
}
