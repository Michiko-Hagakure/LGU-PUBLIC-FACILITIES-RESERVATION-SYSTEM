<?php
require_once '../config/config.php';

header('Content-Type: application/json');

try {
    $region_id = $_GET['region_id'] ?? null;
    
    if ($region_id) {
        $stmt = $conn->prepare("SELECT id, name, province FROM cities WHERE region_id = ? ORDER BY name ASC");
        $stmt->execute([$region_id]);
    } else {
        $stmt = $conn->query("SELECT id, name, province FROM cities ORDER BY name ASC");
    }
    
    $cities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'data' => $cities
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch cities: ' . $e->getMessage()
    ]);
}
?>
