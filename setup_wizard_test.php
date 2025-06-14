<?php

echo "=== SETUP WIZARD COMPLETE TESTING ===\n\n";

// Test each step of setup wizard
$baseUrl = 'http://127.0.0.1:8000';

$steps = [
    'Initial Setup Page' => '/setup',
    'Reset Step' => '/setup/reset',
    'Database Step' => '/setup?step=database', 
    'Admin Step' => '/setup?step=admin',
    'Website Step' => '/setup?step=website',
    'Frontend Config Step' => '/setup?step=frontend-config',
    'Admin Config Step' => '/setup?step=admin-config',
    'Blog Step' => '/setup?step=blog',
];

foreach ($steps as $stepName => $path) {
    echo "Testing $stepName ($path): ";
    
    $url = $baseUrl . $path;
    
    $context = stream_context_create([
        'http' => [
            'timeout' => 15,
            'method' => 'GET',
            'ignore_errors' => true
        ]
    ]);
    
    $response = @file_get_contents($url, false, $context);
    
    if ($response === false) {
        echo "❌ FAILED (no response)\n";
        continue;
    }
    
    $status = '';
    if (isset($http_response_header)) {
        foreach ($http_response_header as $header) {
            if (strpos($header, 'HTTP/') === 0) {
                $status = $header;
                break;
            }
        }
    }
    
    if (strpos($status, '200') !== false) {
        echo "✅ SUCCESS (200 OK)";
        
        // Check for specific content
        if (strpos($response, 'Setup Wizard') !== false) {
            echo " - Setup Wizard detected";
        }
        if (strpos($response, 'error') !== false || strpos($response, 'Error') !== false) {
            echo " - ⚠️ Errors present";
        }
        echo "\n";
        
    } elseif (strpos($status, '302') !== false || strpos($status, '301') !== false) {
        echo "⚠️ REDIRECT ($status)\n";
        
    } elseif (strpos($status, '500') !== false) {
        echo "❌ FAILED (500 Error)\n";
        
    } else {
        echo "⚠️ UNKNOWN ($status)\n";
    }
    
    // Small delay between requests
    usleep(500000); // 0.5 second
}

echo "\n=== SETUP WIZARD TESTING COMPLETED ===\n";
