<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;

/**
 * Image Manager Page - Quản lý hình ảnh đơn giản
 * Theo nguyên tắc KISS
 */
class ImageManager extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    
    protected static ?string $navigationLabel = 'Quản lý hình ảnh';
    
    protected static ?string $title = 'Quản lý hình ảnh';
    
    protected static ?string $navigationGroup = 'Hệ thống';
    
    protected static ?int $navigationSort = 99;

    protected static string $view = 'filament.admin.image-manager';

    public $images = [];
    public $totalFiles = 0;
    public $totalSize = 0;
    public $unusedImages = [];

    public function mount()
    {
        $this->loadImages();
    }

    public function loadImages()
    {
        $this->images = [];
        $this->totalFiles = 0;
        $this->totalSize = 0;
        $this->unusedImages = [];
        
        $directories = ['courses', 'posts', 'sliders', 'testimonials', 'associations', 'albums'];
        
        foreach ($directories as $dir) {
            if (Storage::disk('public')->exists($dir)) {
                $files = Storage::disk('public')->allFiles($dir);
                
                foreach ($files as $file) {
                    $extension = pathinfo($file, PATHINFO_EXTENSION);
                    
                    if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                        $size = Storage::disk('public')->size($file);
                        $isUsed = $this->checkImageUsage($file);
                        
                        $imageData = [
                            'path' => $file,
                            'name' => basename($file),
                            'size' => $size,
                            'formatted_size' => $this->formatFileSize($size),
                            'extension' => strtolower($extension),
                            'url' => Storage::disk('public')->url($file),
                            'is_used' => $isUsed,
                            'created_at' => date('d/m/Y H:i', Storage::disk('public')->lastModified($file)),
                        ];
                        
                        $this->images[] = $imageData;
                        $this->totalFiles++;
                        $this->totalSize += $size;
                        
                        if (!$isUsed) {
                            $this->unusedImages[] = $imageData;
                        }
                    }
                }
            }
        }
        
        // Sort by created date desc
        usort($this->images, function($a, $b) {
            return Storage::disk('public')->lastModified($b['path']) - Storage::disk('public')->lastModified($a['path']);
        });
    }

    public function deleteImage($imagePath)
    {
        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
            
            Notification::make()
                ->title('Đã xóa hình ảnh thành công')
                ->success()
                ->send();
                
            $this->loadImages(); // Reload
        }
    }

    public function deleteUnusedImages()
    {
        $deleted = 0;
        
        foreach ($this->unusedImages as $image) {
            if (Storage::disk('public')->exists($image['path'])) {
                Storage::disk('public')->delete($image['path']);
                $deleted++;
            }
        }
        
        Notification::make()
            ->title("Đã xóa {$deleted} ảnh không sử dụng")
            ->success()
            ->send();
            
        $this->loadImages(); // Reload
    }

    private function checkImageUsage(string $imagePath): bool
    {
        $models = [
            \App\Models\Course::class => ['thumbnail', 'og_image_link'],
            \App\Models\Post::class => ['thumbnail', 'og_image_link'],
            \App\Models\Slider::class => ['image_link'],
            \App\Models\Testimonial::class => ['avatar'],
            \App\Models\Association::class => ['image_link'],
        ];

        // Kiểm tra các model có tồn tại không
        if (class_exists(\App\Models\PostImage::class)) {
            $models[\App\Models\PostImage::class] = ['image_link'];
        }
        if (class_exists(\App\Models\CourseImage::class)) {
            $models[\App\Models\CourseImage::class] = ['image_link'];
        }
        if (class_exists(\App\Models\Album::class)) {
            $models[\App\Models\Album::class] = ['thumbnail'];
        }
        if (class_exists(\App\Models\AlbumImage::class)) {
            $models[\App\Models\AlbumImage::class] = ['image_path'];
        }

        foreach ($models as $model => $fields) {
            foreach ($fields as $field) {
                try {
                    if ($model::where($field, $imagePath)->exists()) {
                        return true;
                    }
                } catch (\Exception $e) {
                    // Bỏ qua nếu bảng không tồn tại
                    continue;
                }
            }
        }

        return false;
    }

    private function formatFileSize(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' B';
    }

    public function getFormattedTotalSize(): string
    {
        return $this->formatFileSize($this->totalSize);
    }
}
