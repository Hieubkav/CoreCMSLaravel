<?php

/**
 * Script sửa lỗi duplicate methods trong Resources
 */

echo "🔧 Sửa lỗi duplicate methods trong Resources...\n\n";

$resources = [
    'CourseMaterialResource.php',
    'FaqResource.php',
    'MenuItemResource.php',
    'PostCategoryResource.php',
    'UserResource.php',
];

$resourcesPath = 'app/Filament/Admin/Resources/';
$fixedCount = 0;
$errorCount = 0;

foreach ($resources as $resourceFile) {
    $filePath = $resourcesPath . $resourceFile;
    
    echo "🔧 Kiểm tra: {$resourceFile}...\n";
    
    if (!file_exists($filePath)) {
        echo "   ⚠️ File không tồn tại\n";
        continue;
    }
    
    // Kiểm tra syntax trước
    $output = [];
    $returnCode = 0;
    exec("php -l {$filePath} 2>&1", $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "   ✅ Syntax OK\n";
        continue;
    }
    
    // Có lỗi syntax, kiểm tra duplicate methods
    $content = file_get_contents($filePath);
    
    // Tìm và xóa duplicate methods
    $methods = ['getTableColumns', 'getFormRelationships', 'getSearchableColumns'];
    $hasChanges = false;
    
    foreach ($methods as $method) {
        $pattern = '/protected static function ' . $method . '\(\): array\s*\{[^}]*\}/s';
        preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE);
        
        if (count($matches[0]) > 1) {
            echo "   🔧 Tìm thấy " . count($matches[0]) . " duplicate {$method}, đang sửa...\n";
            
            // Giữ lại method đầu tiên, xóa các method duplicate
            for ($i = 1; $i < count($matches[0]); $i++) {
                $duplicateMethod = $matches[0][$i][0];
                $content = str_replace($duplicateMethod, '', $content);
                $hasChanges = true;
            }
        }
    }
    
    if ($hasChanges) {
        // Clean up extra whitespace
        $content = preg_replace('/\n\s*\n\s*\n/', "\n\n", $content);
        $content = preg_replace('/\}\s*\n\s*\n\s*\}/', "}\n}", $content);
        
        file_put_contents($filePath, $content);
        
        // Kiểm tra syntax sau khi sửa
        exec("php -l {$filePath} 2>&1", $output, $returnCode);
        
        if ($returnCode === 0) {
            echo "   ✅ Đã sửa thành công\n";
            $fixedCount++;
        } else {
            echo "   ❌ Vẫn còn lỗi: " . implode(' ', $output) . "\n";
            $errorCount++;
        }
    } else {
        echo "   ⚠️ Có lỗi nhưng không phải duplicate methods\n";
        echo "   Lỗi: " . implode(' ', $output) . "\n";
        $errorCount++;
    }
}

echo "\n📊 Kết quả:\n";
echo "✅ Đã sửa: {$fixedCount} resources\n";
echo "❌ Lỗi: {$errorCount} resources\n";

// Kiểm tra syntax tất cả Resources
echo "\n🔍 Kiểm tra syntax tất cả Resources...\n";
$allResources = glob($resourcesPath . '*.php');
$allOk = true;

foreach ($allResources as $file) {
    $fileName = basename($file);
    
    exec("php -l {$file} 2>&1", $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "   ✅ {$fileName}\n";
    } else {
        echo "   ❌ {$fileName}: " . implode(' ', $output) . "\n";
        $allOk = false;
    }
}

if ($allOk) {
    echo "\n🎉 Tất cả Resources đều OK!\n";
} else {
    echo "\n⚠️ Vẫn còn một số Resources có lỗi\n";
}

echo "\n✨ Script hoàn thành!\n";
