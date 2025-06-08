<?php

/**
 * Script test truy cập admin panel sau khi áp dụng optimization
 * 
 * Kiểm tra xem admin panel có load được không và có lỗi gì
 */

echo "🧪 Testing admin panel access...\n\n";

// Test basic admin routes
$adminRoutes = [
    '/admin',
    '/admin/login',
];

foreach ($adminRoutes as $route) {
    echo "📡 Testing route: {$route}\n";
    
    $startTime = microtime(true);
    
    try {
        // Use curl to test the route
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:8000{$route}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HEADER, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $totalTime = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
        
        curl_close($ch);
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000;
        
        if ($httpCode >= 200 && $httpCode < 400) {
            echo "   ✅ Status: {$httpCode} (OK)\n";
            echo "   ⏱️ Response time: " . round($totalTime * 1000, 2) . "ms\n";
            
            // Check for common errors in response
            if (strpos($response, 'Call to undefined method') !== false) {
                echo "   ❌ Found 'Call to undefined method' error\n";
            } elseif (strpos($response, 'Class not found') !== false) {
                echo "   ❌ Found 'Class not found' error\n";
            } elseif (strpos($response, 'Fatal error') !== false) {
                echo "   ❌ Found 'Fatal error'\n";
            } else {
                echo "   ✅ No obvious errors detected\n";
            }
        } else {
            echo "   ❌ Status: {$httpCode} (Error)\n";
        }
        
    } catch (Exception $e) {
        echo "   ❌ Exception: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
}

// Test optimization service
echo "🔧 Testing FilamentOptimizationService...\n";

try {
    // Test if we can create the service
    require_once __DIR__ . '/../vendor/autoload.php';
    
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    
    $optimizationService = $app->make(\App\Services\FilamentOptimizationService::class);
    
    if ($optimizationService) {
        echo "   ✅ FilamentOptimizationService created successfully\n";
        
        // Test performance stats
        $stats = $optimizationService->getPerformanceStats();
        echo "   📊 Performance stats available: " . count($stats) . " metrics\n";
        
        // Test cache functionality
        $testResult = $optimizationService->cacheQuery(
            'test_cache_key',
            function() {
                return 'test_value';
            },
            60
        );
        
        if ($testResult === 'test_value') {
            echo "   ✅ Cache functionality working\n";
        } else {
            echo "   ❌ Cache functionality not working\n";
        }
        
    } else {
        echo "   ❌ Failed to create FilamentOptimizationService\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Error testing optimization service: " . $e->getMessage() . "\n";
}

echo "\n";

// Test if optimization trait is working
echo "🎯 Testing OptimizedFilamentResource trait...\n";

try {
    $resourceClass = \App\Filament\Admin\Resources\PostResource::class;
    
    if (class_exists($resourceClass)) {
        echo "   ✅ PostResource class exists\n";
        
        // Test if trait methods exist
        if (method_exists($resourceClass, 'getTableColumns')) {
            $columns = $resourceClass::getTableColumns();
            echo "   ✅ getTableColumns() method works: " . count($columns) . " columns\n";
        } else {
            echo "   ❌ getTableColumns() method not found\n";
        }
        
        if (method_exists($resourceClass, 'getFormRelationships')) {
            $relationships = $resourceClass::getFormRelationships();
            echo "   ✅ getFormRelationships() method works: " . count($relationships) . " relationships\n";
        } else {
            echo "   ❌ getFormRelationships() method not found\n";
        }
        
        if (method_exists($resourceClass, 'getSearchableColumns')) {
            $searchable = $resourceClass::getSearchableColumns();
            echo "   ✅ getSearchableColumns() method works: " . count($searchable) . " columns\n";
        } else {
            echo "   ❌ getSearchableColumns() method not found\n";
        }
        
    } else {
        echo "   ❌ PostResource class not found\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Error testing trait: " . $e->getMessage() . "\n";
}

echo "\n";

// Final recommendations
echo "💡 Recommendations:\n";
echo "1. Truy cập http://127.0.0.1:8000/admin để test trực tiếp\n";
echo "2. Chạy 'php artisan filament:optimize --stats' để xem thống kê\n";
echo "3. Kiểm tra logs nếu có lỗi: storage/logs/laravel.log\n";
echo "4. Nếu có lỗi, chạy 'php artisan config:clear' và 'php artisan cache:clear'\n";

echo "\n✅ Test completed!\n";
