<?php
/**
 * ==========================================================================
 * FACILITY LIST API
 * ==========================================================================
 * 
 * Endpoint: GET https://facilities.local-government-unit-1-ph.com/api/integrations/FacilityList.php
 * 
 * Returns a list of all available facilities for reservation.
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
                f.facility_id,
                f.name,
                f.description,
                f.address,
                f.capacity,
                f.min_capacity,
                f.per_person_rate,
                f.per_person_extension_rate,
                f.base_hours,
                c.city_name
            FROM facilities f
            LEFT JOIN lgu_cities c ON f.lgu_city_id = c.id
            WHERE f.deleted_at IS NULL
            ORDER BY f.name";

    $stmt = $pdo->query($sql);
    $facilities = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'message' => 'Facilities retrieved successfully',
        'data' => $facilities
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred',
        'error' => $e->getMessage()
    ]);
}
