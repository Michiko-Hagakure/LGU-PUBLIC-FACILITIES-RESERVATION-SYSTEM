<?php
require_once '../config/config.php';

header('Content-Type: application/json');

try {
    $stmt = $conn->query("SELECT id, code, name, long_name FROM regions ORDER BY name ASC");
    $regions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'data' => $regions
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch regions: ' . $e->getMessage()
    ]);
}
?>
