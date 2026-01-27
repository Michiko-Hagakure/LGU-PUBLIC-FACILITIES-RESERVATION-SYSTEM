<?php
/**
 * Test Infrastructure PM API Connection
 * DELETE THIS FILE AFTER TESTING
 */

header('Content-Type: application/json');

$baseUrl = 'https://infra-pm.local-government-unit-1-ph.com';
$endpoint = $baseUrl . '/api/integrations/ProjectRequest.php';

echo "<h2>Infrastructure PM API Connection Test</h2>";

// Test 1: Check if the endpoint is reachable
echo "<h3>1. Testing connection to: $endpoint</h3>";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_VERBOSE, true);

// Capture verbose output
$verbose = fopen('php://temp', 'w+');
curl_setopt($ch, CURLOPT_STDERR, $verbose);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
$errno = curl_errno($ch);

curl_close($ch);

// Get verbose info
rewind($verbose);
$verboseLog = stream_get_contents($verbose);
fclose($verbose);

echo "<pre>";
echo "HTTP Code: " . $httpCode . "\n";
echo "cURL Error Code: " . $errno . "\n";
echo "cURL Error: " . ($error ?: 'None') . "\n";
echo "\n--- Response ---\n";
echo htmlspecialchars($response ?: 'No response');
echo "\n\n--- Debug Log ---\n";
echo htmlspecialchars($verboseLog);
echo "</pre>";

// Test 2: Try a sample POST request
echo "<h3>2. Testing POST request with sample data</h3>";

$testPayload = [
    'requesting_office' => 'TEST - Please Ignore',
    'contact_person' => 'API Test',
    'project_title' => 'API Connection Test - DELETE',
    'project_category' => 'Test',
    'problem_identified' => 'Testing API connectivity from Facility Reservation System'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($testPayload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

curl_close($ch);

echo "<pre>";
echo "HTTP Code: " . $httpCode . "\n";
echo "cURL Error: " . ($error ?: 'None') . "\n";
echo "\n--- Response ---\n";
echo htmlspecialchars($response ?: 'No response');
echo "</pre>";

echo "<hr>";
echo "<p style='color:red;'><strong>⚠️ DELETE THIS FILE AFTER TESTING: test-infra-pm.php</strong></p>";
