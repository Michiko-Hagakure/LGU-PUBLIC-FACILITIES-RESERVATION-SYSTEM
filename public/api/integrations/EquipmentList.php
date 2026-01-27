<?php
/**
 * ==========================================================================
 * EQUIPMENT LIST API
 * ==========================================================================
 * 
 * Endpoint: GET https://facilities.local-government-unit-1-ph.com/api/integrations/EquipmentList.php
 * 
 * Returns a list of all available equipment for facility reservations.
 * 
 * ==========================================================================
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed. Use GET.'
    ]);
    exit();
}

// Database configuration
$dbHost = '127.0.0.1';
$dbPort = '3306';
$dbName = 'faci_facilities';
$dbUser = 'faci_facilities';
$dbPass = 'cristian123';

try {
    $pdo = new PDO(
        "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    $sql = "SELECT 
                id,
                name,
                description,
                category,
                price_per_unit,
                quantity_available
            FROM equipment_items
            WHERE is_available = 1
            ORDER BY category, name";

    $stmt = $pdo->query($sql);
    $equipment = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'message' => 'Equipment retrieved successfully',
        'data' => $equipment
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred',
        'error' => $e->getMessage()
    ]);
}
