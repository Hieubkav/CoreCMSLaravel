<?php

/**
 * Script nhanh để sửa lỗi syntax trong các Livewire Components
 */

echo "🚀 Quick fix syntax errors...\n\n";

$files = [
    'app/Livewire/EnrollmentForm.php',
    'app/Livewire/ProductsFilter.php',
    'app/Livewire/Public/DynamicMenu.php',
    'app/Livewire/Public/UserAccount.php',
];

foreach ($files as $file) {
    echo "🔧 Fixing: {$file}...\n";
    
    if (!file_exists($file)) {
        echo "   ⚠️ File not found\n";
        continue;
    }
    
    $content = file_get_contents($file);
    
    // Fix common issues
    $fixes = [
        // Fix duplicate class declarations
        '/class\s+class\s+(\w+)\s+extends\s+Component/' => 'class $1 extends Component',
        '/class\s+(\w+)\s+extends\s+Component\s*\{\s*\n\s*extends\s+Component\s*\{/' => 'class $1 extends Component' . "\n{",
        
        // Fix missing closing brace
        '/(\s+public function render\(\)\s*\{\s*return view\([^}]+\);\s*\})\s*$/' => '$1' . "\n}",
        
        // Clean up extra whitespace
        '/\{\s*\n\s*\n/' => "{\n",
        '/\}\s*\n\s*\n\s*$/' => "}\n",
    ];
    
    foreach ($fixes as $pattern => $replacement) {
        $content = preg_replace($pattern, $replacement, $content);
    }
    
    // Ensure file ends with closing brace
    $content = rtrim($content);
    if (!str_ends_with($content, '}')) {
        $content .= "\n}";
    }
    $content .= "\n";
    
    file_put_contents($file, $content);
    
    // Check syntax
    $output = [];
    $returnCode = 0;
    exec("php -l {$file} 2>&1", $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "   ✅ Fixed successfully\n";
    } else {
        echo "   ❌ Still has errors: " . implode(' ', $output) . "\n";
        
        // Manual fix for specific patterns
        $content = file_get_contents($file);
        
        // If it's a class declaration issue, try to fix manually
        if (strpos(implode(' ', $output), 'unexpected token "class"') !== false) {
            // Find and fix the problematic line
            $lines = explode("\n", $content);
            foreach ($lines as $i => $line) {
                if (preg_match('/class\s+class\s+/', $line)) {
                    $lines[$i] = preg_replace('/class\s+class\s+/', 'class ', $line);
                }
                if (preg_match('/extends\s+Component.*extends\s+Component/', $line)) {
                    $lines[$i] = preg_replace('/extends\s+Component.*extends\s+Component/', 'extends Component', $line);
                }
            }
            $content = implode("\n", $lines);
            file_put_contents($file, $content);
            
            // Check again
            exec("php -l {$file} 2>&1", $output, $returnCode);
            if ($returnCode === 0) {
                echo "   ✅ Fixed after manual correction\n";
            }
        }
    }
}

echo "\n🎉 Quick fix completed!\n";

// Final check
echo "\n🔍 Final syntax check...\n";
$allFiles = [
    'app/Livewire/CourseCard.php',
    'app/Livewire/CourseList.php',
    'app/Livewire/CoursesOverview.php',
    'app/Livewire/EnrollmentForm.php',
    'app/Livewire/PostsFilter.php',
    'app/Livewire/ProductsFilter.php',
    'app/Livewire/Public/CartIcon.php',
    'app/Livewire/Public/DynamicMenu.php',
    'app/Livewire/Public/SearchBar.php',
    'app/Livewire/Public/UserAccount.php',
];

$allOk = true;
foreach ($allFiles as $file) {
    if (file_exists($file)) {
        exec("php -l {$file} 2>&1", $output, $returnCode);
        if ($returnCode === 0) {
            echo "   ✅ " . basename($file) . "\n";
        } else {
            echo "   ❌ " . basename($file) . "\n";
            $allOk = false;
        }
    }
}

if ($allOk) {
    echo "\n🎉 All files are OK! Ready to test website.\n";
} else {
    echo "\n⚠️ Some files still have issues.\n";
}
