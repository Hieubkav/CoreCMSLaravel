<?php

echo "=== PHASE 3: TEST AFTER CACHE CLEAR ===\n\n";

echo "Testing basic route after cache clear...\n";

$url = 'http://127.0.0.1:8000/';
$context = stream_context_create([
    'http' => [
        'timeout' => 10,
        'method' => 'GET',
        'ignore_errors' => true
    ]
]);

$response = @file_get_contents($url, false, $context);

if ($response === false) {
    echo "❌ FAILED (no response)\n";
} else {
    $status = '';
    if (isset($http_response_header)) {
        foreach ($http_response_header as $header) {
            if (strpos($header, 'HTTP/') === 0) {
                $status = $header;
                break;
            }
        }
    }
    
    echo "Status: $status\n";
    
    if (strpos($status, '200') !== false) {
        echo "✅ SUCCESS - Application working!\n";
    } elseif (strpos($status, '500') !== false) {
        echo "❌ STILL FAILED - 500 Error persists\n";
    } else {
        echo "⚠️ UNKNOWN STATUS\n";
    }
}

echo "\n=== CACHE CLEAR TEST COMPLETED ===\n";
