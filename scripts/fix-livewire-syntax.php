<?php

/**
 * Script sửa lỗi syntax trong các Livewire Components
 * Xóa bỏ dòng trống thừa sau dấu { trong class declaration
 */

echo "🔧 Bắt đầu sửa lỗi syntax trong Livewire Components...\n\n";

// Danh sách các Components cần sửa
$components = [
    'CourseCard.php',
    'CoursesOverview.php', 
    'EnrollmentForm.php',
    'ProductsFilter.php',
    'Public/DynamicMenu.php',
    'Public/UserAccount.php',
];

$componentsPath = 'app/Livewire/';
$fixedCount = 0;
$errorCount = 0;

foreach ($components as $componentFile) {
    $filePath = $componentsPath . $componentFile;
    
    echo "🔧 Đang sửa: {$componentFile}...\n";
    
    if (!file_exists($filePath)) {
        echo "   ⚠️ File không tồn tại: {$filePath}\n";
        continue;
    }
    
    try {
        $content = file_get_contents($filePath);
        
        // Kiểm tra syntax trước khi sửa
        $tempFile = tempnam(sys_get_temp_dir(), 'php_check');
        file_put_contents($tempFile, $content);
        
        $output = [];
        $returnCode = 0;
        exec("php -l {$tempFile} 2>&1", $output, $returnCode);
        unlink($tempFile);
        
        if ($returnCode === 0) {
            echo "   ✅ Syntax đã OK\n";
            continue;
        }
        
        // Sửa lỗi dòng trống thừa sau class declaration
        $content = preg_replace(
            '/class\s+\w+\s+extends\s+Component\s*\{\s*\n\s*\n/',
            'class $0 extends Component' . "\n{\n",
            $content
        );
        
        // Sửa pattern cụ thể cho từng loại lỗi
        $patterns = [
            // Xóa dòng trống thừa sau {
            '/\{\s*\n\s*\n\s*public/' => "{\n    public",
            '/\{\s*\n\s*\n\s*protected/' => "{\n    protected",
            '/\{\s*\n\s*\n\s*private/' => "{\n    private",
            
            // Đảm bảo file kết thúc đúng cách
            '/\}\s*$/' => "}\n",
        ];
        
        foreach ($patterns as $pattern => $replacement) {
            $content = preg_replace($pattern, $replacement, $content);
        }
        
        // Ghi lại file
        file_put_contents($filePath, $content);
        
        // Kiểm tra syntax sau khi sửa
        $tempFile = tempnam(sys_get_temp_dir(), 'php_check');
        file_put_contents($tempFile, $content);
        
        $output = [];
        $returnCode = 0;
        exec("php -l {$tempFile} 2>&1", $output, $returnCode);
        unlink($tempFile);
        
        if ($returnCode === 0) {
            echo "   ✅ Đã sửa thành công\n";
            $fixedCount++;
        } else {
            echo "   ❌ Vẫn còn lỗi syntax: " . implode(' ', $output) . "\n";
            $errorCount++;
        }
        
    } catch (Exception $e) {
        echo "   ❌ Lỗi: " . $e->getMessage() . "\n";
        $errorCount++;
    }
}

echo "\n📊 Kết quả:\n";
echo "✅ Đã sửa: {$fixedCount} components\n";
echo "❌ Lỗi: {$errorCount} components\n";

// Kiểm tra tất cả syntax
echo "\n🔍 Kiểm tra syntax tất cả components...\n";
checkAllSyntax();

echo "\n🎉 Hoàn thành sửa lỗi syntax!\n";

/**
 * Kiểm tra syntax tất cả components
 */
function checkAllSyntax(): void
{
    $componentsPath = 'app/Livewire/';
    $components = [
        'CourseCard.php',
        'CourseList.php',
        'CoursesOverview.php', 
        'EnrollmentForm.php',
        'PostsFilter.php',
        'ProductsFilter.php',
        'Public/CartIcon.php',
        'Public/DynamicMenu.php',
        'Public/SearchBar.php',
        'Public/UserAccount.php',
    ];
    
    $allOk = true;
    
    foreach ($components as $componentFile) {
        $filePath = $componentsPath . $componentFile;
        
        if (file_exists($filePath)) {
            $output = [];
            $returnCode = 0;
            exec("php -l {$filePath} 2>&1", $output, $returnCode);
            
            if ($returnCode === 0) {
                echo "   ✅ {$componentFile}\n";
            } else {
                echo "   ❌ {$componentFile}: " . implode(' ', $output) . "\n";
                $allOk = false;
            }
        } else {
            echo "   ⚠️ {$componentFile} không tồn tại\n";
        }
    }
    
    if ($allOk) {
        echo "\n🎉 Tất cả components đều OK!\n";
    } else {
        echo "\n⚠️ Vẫn còn một số components có lỗi syntax\n";
    }
}

/**
 * Backup files trước khi sửa
 */
function backupFiles(): void
{
    $backupDir = 'backup/livewire-' . date('Y-m-d-H-i-s');
    
    if (!is_dir('backup')) {
        mkdir('backup', 0755, true);
    }
    
    if (!is_dir($backupDir)) {
        mkdir($backupDir, 0755, true);
    }
    
    $componentsPath = 'app/Livewire/';
    $components = [
        'CourseCard.php',
        'CoursesOverview.php', 
        'EnrollmentForm.php',
        'ProductsFilter.php',
        'Public/DynamicMenu.php',
        'Public/UserAccount.php',
    ];
    
    foreach ($components as $componentFile) {
        $filePath = $componentsPath . $componentFile;
        
        if (file_exists($filePath)) {
            $backupPath = $backupDir . '/' . str_replace('/', '_', $componentFile);
            copy($filePath, $backupPath);
            echo "   📁 Backup: {$componentFile} -> {$backupPath}\n";
        }
    }
}

// Tạo backup trước khi sửa
echo "📁 Tạo backup files...\n";
backupFiles();
