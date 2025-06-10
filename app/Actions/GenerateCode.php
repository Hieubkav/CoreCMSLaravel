<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

/**
 * Generate Code Action - KISS Principle
 * 
 * Thay thế CodeGeneratorService phức tạp
 * Chỉ làm 1 việc: Generate code cho modules được chọn
 */
class GenerateCode
{
    use AsAction;

    /**
     * Generate code cho modules được chọn
     */
    public function handle(array $enabledModules): array
    {
        try {
            $results = [];
            $this->ensureGeneratedDirectory();

            foreach ($enabledModules as $moduleName) {
                $results[$moduleName] = $this->generateModule($moduleName);
            }

            return [
                'success' => true,
                'message' => 'Generated code cho ' . count($enabledModules) . ' modules',
                'results' => $results
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Lỗi khi generate code: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Generate code cho một module
     */
    private function generateModule(string $moduleName): array
    {
        $moduleFiles = $this->getModuleFiles($moduleName);
        $generated = [];

        foreach ($moduleFiles as $type => $files) {
            $generated[$type] = [];
            
            foreach ($files as $fileName) {
                try {
                    $result = $this->generateFile($type, $fileName, $moduleName);
                    $generated[$type][] = $result;
                } catch (\Exception $e) {
                    $generated[$type][] = [
                        'file' => $fileName,
                        'success' => false,
                        'error' => $e->getMessage()
                    ];
                }
            }
        }

        return $generated;
    }

    /**
     * Generate một file cụ thể
     */
    private function generateFile(string $type, string $fileName, string $moduleName): array
    {
        return match ($type) {
            'models' => $this->generateModel($fileName),
            'resources' => $this->generateResource($fileName),
            'actions' => $this->generateAction($fileName, $moduleName),
            'livewire' => $this->generateLivewire($fileName),
            'views' => $this->generateView($fileName, $moduleName),
            'components' => $this->generateComponent($fileName, $moduleName),
            default => ['file' => $fileName, 'success' => false, 'error' => "Unknown type: {$type}"]
        };
    }

    /**
     * Generate Model
     */
    private function generateModel(string $modelName): array
    {
        $targetPath = app_path("Generated/Models/{$modelName}.php");
        
        Artisan::call('make:model', [
            'name' => "Generated/Models/{$modelName}",
        ]);
        
        return [
            'file' => $modelName,
            'path' => $targetPath,
            'success' => File::exists($targetPath),
            'type' => 'model'
        ];
    }

    /**
     * Generate Filament Resource
     */
    private function generateResource(string $resourceName): array
    {
        $targetPath = app_path("Generated/Filament/Resources/{$resourceName}.php");
        $modelName = str_replace('Resource', '', $resourceName);
        
        Artisan::call('make:filament-resource', [
            'name' => "Generated/Filament/Resources/{$resourceName}",
            '--model' => "App\\Generated\\Models\\{$modelName}",
            '--generate',
        ]);
        
        return [
            'file' => $resourceName,
            'path' => $targetPath,
            'success' => File::exists($targetPath),
            'type' => 'resource'
        ];
    }

    /**
     * Generate Action
     */
    private function generateAction(string $actionName, string $moduleName): array
    {
        $targetPath = app_path("Generated/Actions/{$actionName}.php");
        File::ensureDirectoryExists(dirname($targetPath));

        $content = $this->getActionTemplate($actionName, $moduleName);
        File::put($targetPath, $content);

        return [
            'file' => $actionName,
            'path' => $targetPath,
            'success' => File::exists($targetPath),
            'type' => 'action'
        ];
    }

    /**
     * Generate Livewire Component
     */
    private function generateLivewire(string $componentName): array
    {
        $targetPath = app_path("Generated/Livewire/{$componentName}.php");

        Artisan::call('make:livewire', [
            'name' => "Generated/Livewire/{$componentName}",
        ]);

        return [
            'file' => $componentName,
            'path' => $targetPath,
            'success' => File::exists($targetPath),
            'type' => 'livewire'
        ];
    }

    /**
     * Generate View
     */
    private function generateView(string $viewName, string $moduleName): array
    {
        $targetPath = resource_path("views/generated/{$viewName}.blade.php");
        File::ensureDirectoryExists(dirname($targetPath));
        
        $content = $this->getViewTemplate($viewName, $moduleName);
        File::put($targetPath, $content);
        
        return [
            'file' => $viewName,
            'path' => $targetPath,
            'success' => File::exists($targetPath),
            'type' => 'view'
        ];
    }

    /**
     * Generate Component
     */
    private function generateComponent(string $componentName, string $moduleName): array
    {
        $targetPath = resource_path("views/generated/components/{$componentName}.blade.php");
        File::ensureDirectoryExists(dirname($targetPath));
        
        $content = $this->getComponentTemplate($componentName, $moduleName);
        File::put($targetPath, $content);
        
        return [
            'file' => $componentName,
            'path' => $targetPath,
            'success' => File::exists($targetPath),
            'type' => 'component'
        ];
    }

    /**
     * Đảm bảo thư mục Generated tồn tại
     */
    private function ensureGeneratedDirectory(): void
    {
        $directories = [
            app_path('Generated'),
            app_path('Generated/Models'),
            app_path('Generated/Filament/Resources'),
            app_path('Generated/Actions'),
            app_path('Generated/Livewire'),
            resource_path('views/generated'),
            resource_path('views/generated/components'),
        ];

        foreach ($directories as $directory) {
            File::ensureDirectoryExists($directory);
        }
    }

    /**
     * Lấy danh sách files cho module
     */
    private function getModuleFiles(string $moduleName): array
    {
        // Simplified mapping - chỉ những gì thực sự cần thiết
        return match ($moduleName) {
            'blog' => [
                'models' => ['Post', 'PostCategory'],
                'resources' => ['PostResource', 'PostCategoryResource'],
                'actions' => ['CreatePost', 'UpdatePost'],
                'livewire' => ['BlogListing', 'PostDetail'],
            ],
            'staff' => [
                'models' => ['Staff'],
                'resources' => ['StaffResource'],
                'actions' => ['CreateStaff', 'UpdateStaff'],
                'livewire' => ['StaffListing'],
            ],
            'content_sections' => [
                'models' => ['Slider', 'Gallery', 'FAQ'],
                'resources' => ['SliderResource', 'GalleryResource', 'FAQResource'],
                'components' => ['slider', 'gallery', 'faq'],
            ],
            'ecommerce' => [
                'models' => ['Product', 'ProductCategory', 'Order'],
                'resources' => ['ProductResource', 'ProductCategoryResource', 'OrderResource'],
                'actions' => ['CreateProduct', 'ProcessOrder'],
                'livewire' => ['ProductFilter', 'CartSidebar'],
            ],
            default => []
        };
    }

    /**
     * Templates đơn giản
     */
    private function getActionTemplate(string $actionName, string $moduleName): string
    {
        $className = Str::studly($actionName);
        return "<?php\n\nnamespace App\\Generated\\Actions;\n\nuse Lorisleiva\\Actions\\Concerns\\AsAction;\n\nclass {$className}\n{\n    use AsAction;\n\n    public function handle()\n    {\n        // Generated action for {$moduleName} module\n        // Add your logic here\n    }\n}\n";
    }

    private function getViewTemplate(string $viewName, string $moduleName): string
    {
        return "{{-- Generated view for {$moduleName} module --}}\n@extends('layouts.app')\n\n@section('content')\n<div class=\"container\">\n    <h1>{{ ucfirst('{$viewName}') }}</h1>\n    {{-- Add your content here --}}\n</div>\n@endsection\n";
    }

    private function getComponentTemplate(string $componentName, string $moduleName): string
    {
        return "{{-- Generated component for {$moduleName} module --}}\n<div class=\"{$componentName}-component\">\n    {{-- Add your {$componentName} content here --}}\n</div>\n";
    }

    /**
     * Static method để dễ sử dụng
     */
    public static function forModules(array $modules): array
    {
        return static::run($modules);
    }
}
