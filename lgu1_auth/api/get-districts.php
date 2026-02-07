<?php
require_once '../config/config.php';

header('Content-Type: application/json');

try {
    $city_id = $_GET['city_id'] ?? null;
    
    if ($city_id) {
        $stmt = $conn->prepare("SELECT id, name FROM districts WHERE city_id = ? ORDER BY name ASC");
        $stmt->execute([$city_id]);
    } else {
        $stmt = $conn->query("SELECT id, name FROM districts ORDER BY name ASC");
    }
    
    $districts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'data' => $districts
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch districts: ' . $e->getMessage()
    ]);
}
?>