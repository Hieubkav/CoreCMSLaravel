<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CourseMaterial;
use Illuminate\Support\Facades\DB;

class UpdateCourseMaterialsAccessTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔄 Cập nhật access_type cho tài liệu khóa học...');

        // Cập nhật tài liệu preview thành public
        $previewCount = CourseMaterial::where('is_preview', true)
            ->update(['access_type' => 'public']);

        $this->command->info("✅ Đã cập nhật {$previewCount} tài liệu preview thành 'public'");

        // Cập nhật một số tài liệu thành enrolled (tài liệu khóa)
        $materials = CourseMaterial::where('is_preview', false)
            ->where('access_type', 'public')
            ->get();

        $enrolledCount = 0;
        foreach ($materials as $index => $material) {
            // Mỗi khóa học sẽ có 50% tài liệu là enrolled
            if ($index % 2 === 1) {
                $material->update(['access_type' => 'enrolled']);
                $enrolledCount++;
            }
        }

        $this->command->info("✅ Đã cập nhật {$enrolledCount} tài liệu thành 'enrolled'");

        // Thống kê kết quả
        $publicCount = CourseMaterial::where('access_type', 'public')->count();
        $enrolledOnlyCount = CourseMaterial::where('access_type', 'enrolled')->count();

        $this->command->info("📊 Thống kê tài liệu:");
        $this->command->info("   - Tài liệu mở (public): {$publicCount}");
        $this->command->info("   - Tài liệu khóa (enrolled): {$enrolledOnlyCount}");
        $this->command->info("   - Tổng cộng: " . ($publicCount + $enrolledOnlyCount));

        $this->command->info('✨ Hoàn thành cập nhật access_type!');
    }
}
