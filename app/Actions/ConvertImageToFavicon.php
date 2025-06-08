<?php

namespace App\Actions;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Lorisleiva\Actions\Concerns\AsAction;

/**
 * Convert Image To Favicon Action - KISS Principle
 *
 * Chỉ làm 1 việc: Chuyển ảnh sang favicon.ico với kích thước 32x32
 * Không có cache, không có optimization phức tạp
 */
class ConvertImageToFavicon
{
    use AsAction;

    private ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Chuyển ảnh sang favicon.ico với kích thước 32x32
     *
     * @param UploadedFile $file File upload
     * @return bool Thành công hay không
     */
    public function handle(UploadedFile $file): bool
    {
        try {
            // Đường dẫn favicon trong public
            $faviconPath = public_path('favicon.ico');

            // Backup favicon cũ nếu tồn tại
            if (file_exists($faviconPath)) {
                $backupPath = public_path('favicon_backup_' . time() . '.ico');
                copy($faviconPath, $backupPath);
            }

            // Kiểm tra file có hợp lệ không
            if (!$file->isValid()) {
                throw new \Exception('File upload không hợp lệ');
            }

            // Kiểm tra MIME type
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp'];
            if (!in_array($file->getMimeType(), $allowedMimes)) {
                throw new \Exception('Định dạng file không được hỗ trợ: ' . $file->getMimeType());
            }

            // Đọc và convert sang favicon
            $image = $this->manager->read($file->getPathname());

            // Resize về 32x32 (kích thước chuẩn favicon)
            $image->resize(32, 32);

            // Convert sang PNG với chất lượng cao (PNG tương thích tốt hơn ICO)
            $imageData = $image->toPng();

            // Lưu trực tiếp vào public/favicon.ico
            $result = file_put_contents($faviconPath, $imageData);

            if ($result === false) {
                throw new \Exception('Không thể ghi file favicon.ico');
            }

            \Illuminate\Support\Facades\Log::info('Favicon updated successfully from: ' . $file->getClientOriginalName());
            return true;

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Favicon conversion failed: ' . $e->getMessage(), [
                'file_name' => $file->getClientOriginalName() ?? 'unknown',
                'file_size' => $file->getSize() ?? 0,
                'mime_type' => $file->getMimeType() ?? 'unknown'
            ]);
            return false;
        }
    }

    /**
     * Static method để gọi action dễ dàng hơn
     */
    public static function run(UploadedFile $file): bool
    {
        return static::make()->handle($file);
    }
}
