<?php

require_once 'vendor/autoload.php';

// Test a few key steps
$testSteps = [
    'database' => 1,
    'module-layout' => 12,
    'installation' => 17,
    'complete' => 18
];

echo "Testing Setup Step Numbers:\n";
echo "==========================\n\n";

foreach ($testSteps as $step => $expectedNumber) {
    $url = "http://127.0.0.1:8000/setup/step/{$step}";
    
    $context = stream_context_create([
        'http' => [
            'timeout' => 5,
            'method' => 'GET'
        ]
    ]);
    
    $content = @file_get_contents($url, false, $context);
    
    if ($content !== false) {
        if (preg_match('/Bước (\d+) \/ (\d+)/', $content, $matches)) {
            $actualNumber = (int)$matches[1];
            $total = (int)$matches[2];
            
            $status = $actualNumber === $expectedNumber ? '✅' : '❌';
            echo "{$status} {$step}: Bước {$actualNumber}/{$total} (expected {$expectedNumber})\n";
        } else {
            echo "❌ {$step}: Could not find step number\n";
        }
    } else {
        echo "❌ {$step}: Failed to fetch page\n";
    }
}

echo "\nTest completed!\n";
