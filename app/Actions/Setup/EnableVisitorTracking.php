<?php

namespace App\Actions\Setup;

use App\Models\Visitor;
use App\Actions\System\GetVisitorStats;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class EnableVisitorTracking
{
    /**
     * Kích hoạt visitor tracking realtime
     */
    public static function enable(): array
    {
        try {
            $results = [];
            
            // 1. Kiểm tra bảng visitors
            $results['database'] = self::checkDatabase();
            
            // 2. Kiểm tra middleware
            $results['middleware'] = self::checkMiddleware();
            
            // 3. Tạo sample data để test
            $results['sample_data'] = self::createInitialData();
            
            // 4. Clear cache để refresh stats
            GetVisitorStats::clearCache();
            $results['stats'] = GetVisitorStats::overview();
            
            return [
                'success' => true,
                'message' => 'Visitor tracking đã được kích hoạt thành công!',
                'details' => $results
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi khi kích hoạt visitor tracking: ' . $e->getMessage()
            ];
        }
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
            // Clear tất cả visitor data
            $count = Visitor::count();
            Visitor::truncate();
            
            // Clear cache
            GetVisitorStats::clearCache();
            
            return [
                'success' => true,
                'message' => "Đã xóa $count visitor records và disable tracking"
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Lỗi disable tracking: ' . $e->getMessage()
            ];
        }
    }
}
