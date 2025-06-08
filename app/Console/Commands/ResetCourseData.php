<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Providers\ViewServiceProvider;

class ResetCourseData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'course:reset {--fresh : Drop and recreate all tables} {--seed : Run seeders after reset}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset course data and optionally reseed the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Bắt đầu reset dữ liệu khóa học...');

        if ($this->option('fresh')) {
            $this->freshMigrate();
        } else {
            $this->truncateTables();
        }

        if ($this->option('seed')) {
            $this->seedData();
        }

        // Clear cache
        $this->clearCache();

        $this->info('✅ Hoàn thành reset dữ liệu khóa học!');
        return 0;
    }

    /**
     * Fresh migrate - drop all tables and recreate
     */
    private function freshMigrate()
    {
        $this->info('🔄 Đang fresh migrate database...');

        if ($this->confirm('⚠️  Điều này sẽ xóa TẤT CẢ dữ liệu. Bạn có chắc chắn?')) {
            $this->call('migrate:fresh');
            $this->info('✅ Fresh migrate hoàn thành');
        } else {
            $this->error('❌ Hủy bỏ fresh migrate');
            return;
        }
    }

    /**
     * Truncate specific tables
     */
    private function truncateTables()
    {
        $this->info('🗑️  Đang xóa dữ liệu khóa học...');

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $tables = [
            'course_student',
            'course_materials',
            'course_images',
            'courses',
            'students',
            'menu_items'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
                $this->line("  ✓ Đã xóa bảng: {$table}");
            }
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('✅ Xóa dữ liệu hoàn thành');
    }

    /**
     * Seed data
     */
    private function seedData()
    {
        $this->info('🌱 Đang seed dữ liệu mẫu...');

        $seeders = [
            'CourseSeeder',
            'StudentSeeder',
            'CourseMaterialSeeder',
            'CourseImageSeeder',
            'MenuItemSeeder'
        ];

        foreach ($seeders as $seeder) {
            $this->call('db:seed', ['--class' => $seeder]);
            $this->line("  ✓ Đã chạy: {$seeder}");
        }

        $this->info('✅ Seed dữ liệu hoàn thành');
    }

    /**
     * Clear application cache
     */
    private function clearCache()
    {
        $this->info('🧹 Đang clear cache...');

        // Clear Laravel caches
        $this->call('cache:clear');
        $this->call('config:clear');
        $this->call('route:clear');
        $this->call('view:clear');

        // Clear ViewServiceProvider cache
        ViewServiceProvider::clearCache();

        $this->info('✅ Clear cache hoàn thành');
    }
}
