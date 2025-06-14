<?php

echo "=== TESTING RESET STEP ===\n\n";

$baseUrl = 'http://127.0.0.1:8000';

// Test reset with POST method
echo "Testing Reset Step with POST: ";

$url = $baseUrl . '/setup/reset';

$postData = http_build_query([
    '_token' => 'test', // We'll try without proper CSRF for now
]);

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/x-www-form-urlencoded\r\n" .
                   "Content-Length: " . strlen($postData) . "\r\n",
        'content' => $postData,
        'timeout' => 30,
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
    
    echo "$status\n";
    
    if (strpos($status, '200') !== false) {
        echo "✅ Reset successful\n";
    } elseif (strpos($status, '302') !== false) {
        echo "✅ Reset redirect (probably successful)\n";
    } elseif (strpos($status, '419') !== false) {
        echo "⚠️ CSRF token mismatch (expected)\n";
    } elseif (strpos($status, '500') !== false) {
        echo "❌ Server error during reset\n";
    }
}

echo "\n=== RESET STEP TEST COMPLETED ===\n";
