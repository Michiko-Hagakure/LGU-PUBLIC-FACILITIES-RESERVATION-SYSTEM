<?php
/**
 * CIM API Diagnostic Tool
 * Run from CLI: php public/cim-diagnostic.php
 * Run from web: https://your-domain/cim-diagnostic.php
 */

header('Content-Type: application/json');

$results = [
    'context' => php_sapi_name(),
    'user' => get_current_user(),
    'whoami' => trim(shell_exec('whoami 2>&1') ?? 'unknown'),
    'timestamp' => date('Y-m-d H:i:s'),
];

// Test DNS resolution
$host = 'community.local-government-unit-1-ph.com';
$results['dns'] = [
    'host' => $host,
    'ip' => gethostbyname($host),
    'resolves' => gethostbyname($host) !== $host,
];

// Test payload
$payload = [
    'resident_name' => 'Diagnostic Test ' . time(),
    'contact_info' => '09999999999',
    'subject' => 'Diagnostic Test',
    'description' => 'Testing from ' . php_sapi_name(),
    'unit_number' => 'Test Unit',
    'report_type' => 'maintenance',
    'priority' => 'low',
];

$url = 'https://community.local-government-unit-1-ph.com/api/integration/RequestFacilityMaintenance.php';
$jsonPayload = json_encode($payload);

// Test 1: PHP curl
$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $jsonPayload,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_SSL_VERIFYPEER => false,
]);
$phpCurlResponse = curl_exec($ch);
$phpCurlInfo = curl_getinfo($ch);
$phpCurlError = curl_error($ch);
curl_close($ch);

$results['php_curl'] = [
    'response' => $phpCurlResponse,
    'http_code' => $phpCurlInfo['http_code'],
    'error' => $phpCurlError,
    'local_ip' => $phpCurlInfo['local_ip'] ?? null,
    'primary_ip' => $phpCurlInfo['primary_ip'] ?? null,
];

// Test 2: shell_exec curl
$escapedPayload = escapeshellarg($jsonPayload);
$curlCmd = "curl -s -X POST '{$url}' -H 'Content-Type: application/json' -d {$escapedPayload} 2>&1";
$shellCurlResponse = shell_exec($curlCmd);

$results['shell_curl'] = [
    'command' => $curlCmd,
    'response' => $shellCurlResponse,
];

// Test 3: Check outbound IP
$results['outbound_ip'] = trim(shell_exec('curl -s ifconfig.me 2>&1') ?? 'unknown');

// Test 4: Check if curl binary is accessible
$results['curl_version'] = trim(shell_exec('curl --version 2>&1 | head -1') ?? 'unknown');

// Output results
echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
