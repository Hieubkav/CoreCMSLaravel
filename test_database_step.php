<?php

echo "=== TESTING DATABASE STEP DETAILED ===\n\n";

$baseUrl = 'http://127.0.0.1:8000';
$url = $baseUrl . '/setup?step=database';

echo "Testing Database Step: $url\n";

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
    exit;
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

echo "Status: $status\n";
echo "Response length: " . strlen($response) . " bytes\n\n";

// Check for specific content
$checks = [
    'Setup Wizard' => strpos($response, 'Setup Wizard') !== false,
    'Database' => strpos($response, 'Database') !== false || strpos($response, 'database') !== false,
    'Form elements' => strpos($response, '<form') !== false,
    'Input fields' => strpos($response, '<input') !== false,
    'Submit button' => strpos($response, 'submit') !== false || strpos($response, 'Submit') !== false,
    'Error messages' => strpos($response, 'error') !== false || strpos($response, 'Error') !== false,
    'Exception' => strpos($response, 'Exception') !== false,
    'Undefined variable' => strpos($response, 'Undefined variable') !== false,
];

echo "Content Analysis:\n";
foreach ($checks as $check => $found) {
    echo "  $check: " . ($found ? "✅ Found" : "❌ Not found") . "\n";
}

// Extract any error messages
if (strpos($response, 'Undefined variable') !== false) {
    echo "\n⚠️ Undefined Variable Errors Detected:\n";
    preg_match_all('/Undefined variable \$(\w+)/', $response, $matches);
    if (!empty($matches[1])) {
        foreach (array_unique($matches[1]) as $var) {
            echo "  - \$$var\n";
        }
    }
}

echo "\n=== DATABASE STEP TEST COMPLETED ===\n";
