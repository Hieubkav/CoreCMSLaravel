<?php

namespace App\Actions\Setup;

use App\Models\Visitor;
use App\Models\AdminConfiguration;
use App\Actions\System\GetVisitorStats;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Lorisleiva\Actions\Concerns\AsAction;

class EnableVisitorTracking
{
    use AsAction;

    /**
     * Kích hoạt visitor tracking realtime - KISS principle
     * Logic đơn giản: Ai vào thì track, reset = truncate table
     */
    public function handle(): array
    {
        try {
            $results = [];

            // 1. Kiểm tra và tạo migration nếu cần
            $results['migration'] = $this->ensureMigration();

            // 2. Kiểm tra bảng visitors
            $results['database'] = $this->checkDatabase();

            // 3. Kiểm tra middleware
            $results['middleware'] = $this->checkMiddleware();

            // 4. Cập nhật admin configuration
            $results['admin_config'] = $this->updateAdminConfig();

            // 5. Cập nhật .env với tracking interval
            $results['env_config'] = $this->updateEnvConfig();

            // 6. Tạo sample data để test
            $results['sample_data'] = $this->createInitialData();

            // 7. Clear cache để refresh stats
            GetVisitorStats::clearCache();
            $results['stats'] = GetVisitorStats::overview();

            return [
                'success' => true,
                'message' => 'Visitor tracking đã được kích hoạt thành công với KISS principle!',
                'details' => $results,
                'instructions' => [
                    'F5 trang chủ để test realtime tracking',
                    'Sử dụng ResetVisitorStats::reset() để reset về 0',
                    'Điều chỉnh VISITOR_TRACKING_INTERVAL trong .env',
                    'Widget sẽ hiển thị stats realtime trong admin panel'
                ]
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi kích hoạt visitor tracking: ' . $e->getMessage(),
                'error' => $e->getTraceAsString()
            ];
        }
    }

    /**
     * Static method để gọi từ setup wizard
     */
    public static function enable(): array
    {
        return static::run();
    }
    
    /**
     * Kiểm tra database table
     */
    private static function checkDatabase(): array
    {
        try {
            if (!Schema::hasTable('visitors')) {
                return [
                    'status' => 'failed',
                    'message' => 'Bảng visitors chưa tồn tại - cần chạy migration'
                ];
            }
            
            $count = DB::table('visitors')->count();
            
            return [
                'status' => 'success',
                'message' => "Bảng visitors OK - có $count records",
                'record_count' => $count
            ];
            
        } catch (\Exception $e) {
            return [
                'status' => 'failed',
                'message' => 'Lỗi database: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Kiểm tra middleware
     */
    private static function checkMiddleware(): array
    {
        try {
            $middlewareExists = class_exists('App\\Http\\Middleware\\TrackVisitor');
            
            if (!$middlewareExists) {
                return [
                    'status' => 'failed',
                    'message' => 'TrackVisitor middleware chưa tồn tại'
                ];
            }
            
            return [
                'status' => 'success',
                'message' => 'TrackVisitor middleware đã sẵn sàng'
            ];
            
        } catch (\Exception $e) {
            return [
                'status' => 'failed',
                'message' => 'Lỗi middleware: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Tạo dữ liệu khởi tạo
     */
    private static function createInitialData(): array
    {
        try {
            // Tạo một vài visitor records để demo
            $visitors = [];
            
            for ($i = 1; $i <= 3; $i++) {
                $visitor = Visitor::create([
                    'ip_address' => '192.168.1.' . $i,
                    'user_agent' => 'Setup Browser ' . $i,
                    'url' => 'http://127.0.0.1:8000/setup-demo-' . $i,
                    'content_id' => null,
                    'session_id' => 'setup_session_' . uniqid(),
                    'visited_at' => now()->subMinutes($i * 5)
                ]);
                
                $visitors[] = $visitor->id;
            }
            
            return [
                'status' => 'success',
                'message' => 'Đã tạo ' . count($visitors) . ' visitor demo records',
                'visitor_ids' => $visitors
            ];
            
        } catch (\Exception $e) {
            return [
                'status' => 'failed',
                'message' => 'Lỗi tạo demo data: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Test visitor tracking
     */
    public static function test(): array
    {
        try {
            // Tạo test visitor
            $visitor = Visitor::create([
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Test Agent',
                'url' => 'http://127.0.0.1:8000/test',
                'content_id' => null,
                'session_id' => 'test_' . uniqid(),
                'visited_at' => now()
            ]);
            
            // Clear cache và get stats
            GetVisitorStats::clearCache();
            $stats = GetVisitorStats::overview();
            
            return [
                'success' => true,
                'message' => 'Test visitor tracking thành công',
                'visitor_id' => $visitor->id,
                'stats' => $stats
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Test failed: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Disable visitor tracking
     */
    public static function disable(): array
    {
        try {
            // Clear tất cả visitor data với TRUNCATE
            $count = Visitor::count();
            DB::statement('TRUNCATE TABLE visitors');

            // Clear cache
            GetVisitorStats::clearCache();

            return [
                'success' => true,
                'message' => "Đã truncate bảng visitors ($count records) và disable tracking"
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi disable tracking: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Đảm bảo migration visitors table tồn tại
     */
    private function ensureMigration(): array
    {
        try {
            $migrationPath = database_path('migrations');
            $migrationFile = null;

            // Tìm migration file visitors
            $files = File::glob($migrationPath . '/*_create_visitors_table.php');

            if (empty($files)) {
                // Tạo migration mới
                $timestamp = date('Y_m_d_His');
                $migrationFile = $migrationPath . "/{$timestamp}_create_visitors_table.php";

                $migrationContent = $this->getVisitorsMigrationContent();
                File::put($migrationFile, $migrationContent);

                return [
                    'status' => 'created',
                    'message' => 'Đã tạo migration visitors table',
                    'file' => basename($migrationFile)
                ];
            }

            return [
                'status' => 'exists',
                'message' => 'Migration visitors table đã tồn tại',
                'file' => basename($files[0])
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'failed',
                'message' => 'Lỗi migration: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cập nhật admin configuration
     */
    private function updateAdminConfig(): array
    {
        try {
            $config = AdminConfiguration::current();

            if ($config) {
                $config->update([
                    'enable_visitor_tracking' => true,
                    'visitor_analytics_enabled' => true,
                    'enable_analytics_dashboard' => true
                ]);

                return [
                    'status' => 'updated',
                    'message' => 'Đã bật visitor tracking trong admin config'
                ];
            }

            return [
                'status' => 'skipped',
                'message' => 'Admin configuration chưa tồn tại'
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'failed',
                'message' => 'Lỗi admin config: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Cập nhật .env với tracking interval
     */
    private function updateEnvConfig(): array
    {
        try {
            $envPath = base_path('.env');

            if (!File::exists($envPath)) {
                return [
                    'status' => 'failed',
                    'message' => 'File .env không tồn tại'
                ];
            }

            $envContent = File::get($envPath);

            // Kiểm tra xem đã có VISITOR_TRACKING_INTERVAL chưa
            if (strpos($envContent, 'VISITOR_TRACKING_INTERVAL') === false) {
                // Thêm vào cuối file
                $envContent .= "\n# Visitor Tracking Configuration\nVISITOR_TRACKING_INTERVAL=5\n";
                File::put($envPath, $envContent);

                return [
                    'status' => 'added',
                    'message' => 'Đã thêm VISITOR_TRACKING_INTERVAL=5 vào .env'
                ];
            }

            return [
                'status' => 'exists',
                'message' => 'VISITOR_TRACKING_INTERVAL đã tồn tại trong .env'
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'failed',
                'message' => 'Lỗi .env config: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lấy nội dung migration visitors table
     */
    private function getVisitorsMigrationContent(): string
    {
        return <<<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->index(); // Hỗ trợ IPv6
            $table->text('user_agent')->nullable();
            $table->string('url', 500)->index();
            $table->unsignedBigInteger('content_id')->nullable(); // Generic content ID
            $table->string('session_id', 100)->index();
            $table->timestamp('visited_at')->index();
            $table->timestamps();

            // Index để tối ưu query
            $table->index(['ip_address', 'visited_at']);
            $table->index(['content_id', 'visited_at']);
            $table->index(['session_id', 'visited_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};
PHP;
    }
}
