<?php

/**
 * Script revert tất cả Livewire Components về trạng thái ban đầu
 * Xóa bỏ OptimizedLivewireComponent trait và các method liên quan
 */

echo "🔄 Bắt đầu revert tất cả Livewire Components...\n\n";

// Danh sách các Components cần revert
$components = [
    'CourseCard.php',
    'CoursesOverview.php', 
    'EnrollmentForm.php',
    'ProductsFilter.php',
    'Public/CartIcon.php',
    'Public/DynamicMenu.php',
    'Public/UserAccount.php',
];

$componentsPath = 'app/Livewire/';
$revertedCount = 0;
$errorCount = 0;

foreach ($components as $componentFile) {
    $filePath = $componentsPath . $componentFile;
    
    echo "📝 Đang revert: {$componentFile}...\n";
    
    if (!file_exists($filePath)) {
        echo "   ⚠️ File không tồn tại: {$filePath}\n";
        continue;
    }
    
    try {
        $content = file_get_contents($filePath);
        
        // Kiểm tra xem có OptimizedLivewireComponent trait không
        if (strpos($content, 'OptimizedLivewireComponent') === false) {
            echo "   ✅ Đã ở trạng thái gốc\n";
            continue;
        }
        
        // 1. Xóa use statement
        $content = str_replace(
            "use App\Traits\OptimizedLivewireComponent;\n",
            "",
            $content
        );
        
        // 2. Xóa trait usage trong class
        $content = str_replace(
            "    use OptimizedLivewireComponent;\n",
            "",
            $content
        );
        
        // 3. Thay đổi renderComponent thành render
        $content = preg_replace(
            '/protected function renderComponent\(\)/',
            'public function render()',
            $content
        );
        
        // 4. Xóa các method optimization (từ comment đến cuối file)
        $content = preg_replace(
            '/\s*\/\*\*\s*\* Override methods từ OptimizedLivewireComponent trait.*$/s',
            '',
            $content
        );
        
        // 5. Đảm bảo file kết thúc đúng cách
        $content = rtrim($content) . "\n";
        
        // Ghi lại file
        file_put_contents($filePath, $content);
        
        echo "   ✅ Đã revert thành công\n";
        $revertedCount++;
        
    } catch (Exception $e) {
        echo "   ❌ Lỗi: " . $e->getMessage() . "\n";
        $errorCount++;
    }
}

echo "\n📊 Kết quả:\n";
echo "✅ Đã revert: {$revertedCount} components\n";
echo "❌ Lỗi: {$errorCount} components\n";

// Clear cache để áp dụng thay đổi
echo "\n🧹 Đang clear cache...\n";
clearCache();

echo "\n🎉 Hoàn thành revert tất cả Livewire Components!\n";
echo "\n📖 Các bước tiếp theo:\n";
echo "1. Test website: http://127.0.0.1:8000\n";
echo "2. Kiểm tra tất cả trang có hoạt động không\n";
echo "3. Kiểm tra admin panel: http://127.0.0.1:8000/admin\n";

/**
 * Clear cache
 */
function clearCache(): void
{
    $commands = [
        'php artisan config:clear',
        'php artisan view:clear',
        'php artisan route:clear',
    ];
    
    foreach ($commands as $command) {
        echo "   Chạy: {$command}\n";
        exec($command, $output, $returnCode);
        
        if ($returnCode === 0) {
            echo "   ✅ Thành công\n";
        } else {
            echo "   ❌ Lỗi: " . implode("\n", $output) . "\n";
        }
    }
}

/**
 * Kiểm tra trạng thái các components
 */
function checkComponentsStatus(): void
{
    echo "\n🔍 Kiểm tra trạng thái components...\n";
    
    $componentsPath = 'app/Livewire/';
    $components = [
        'CourseCard.php',
        'CoursesOverview.php', 
        'EnrollmentForm.php',
        'ProductsFilter.php',
        'Public/CartIcon.php',
        'Public/DynamicMenu.php',
        'Public/UserAccount.php',
        'Public/SearchBar.php',
    ];
    
    foreach ($components as $componentFile) {
        $filePath = $componentsPath . $componentFile;
        
        if (file_exists($filePath)) {
            $content = file_get_contents($filePath);
            
            if (strpos($content, 'OptimizedLivewireComponent') !== false) {
                echo "   ❌ {$componentFile} vẫn có OptimizedLivewireComponent\n";
            } else {
                echo "   ✅ {$componentFile} đã clean\n";
            }
        } else {
            echo "   ⚠️ {$componentFile} không tồn tại\n";
        }
    }
}

// Kiểm tra trạng thái sau khi revert
checkComponentsStatus();
