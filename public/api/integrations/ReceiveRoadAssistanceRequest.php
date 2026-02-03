<?php
/**
 * API Endpoint for Road & Transportation System
 * =============================================
 * This file should be placed on the Road & Transportation server at:
 * /api/integrations/ReceiveRoadAssistanceRequest.php
 * 
 * It receives road assistance requests from external systems like 
 * the Public Facility Reservation System (PFRS).
 * 
 * Required database table: external_road_requests
 * See the CREATE TABLE statement at the bottom of this file.
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Database configuration - UPDATE THESE VALUES
$dbHost = 'localhost';
$dbName = 'road_rtim'; // Your database name
$dbUser = 'your_db_user';
$dbPass = 'your_db_password';

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

// Handle POST - Create new request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        $input = $_POST;
    }
    
    // Validate required fields
    $required = ['system_name', 'event_type', 'start_date', 'end_date', 'location', 'description'];
    foreach ($required as $field) {
        if (empty($input[$field])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => "Missing required field: $field"]);
            exit;
        }
    }
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO external_road_requests (
                external_system,
                external_user_id,
                system_name,
                event_type,
                start_date,
                end_date,
                location,
                landmark,
                description,
                status,
                created_at,
                updated_at
            ) VALUES (
                :external_system,
                :external_user_id,
                :system_name,
                :event_type,
                :start_date,
                :end_date,
                :location,
                :landmark,
                :description,
                'pending',
                NOW(),
                NOW()
            )
        ");
        
        $stmt->execute([
            'external_system' => $input['external_system'] ?? 'Unknown',
            'external_user_id' => $input['external_user_id'] ?? null,
            'system_name' => $input['system_name'],
            'event_type' => $input['event_type'],
            'start_date' => $input['start_date'],
            'end_date' => $input['end_date'],
            'location' => $input['location'],
            'landmark' => $input['landmark'] ?? null,
            'description' => $input['description'],
        ]);
        
        $requestId = $pdo->lastInsertId();
        
        http_response_code(201);
        echo json_encode([
            'success' => true,
            'request_id' => $requestId,
            'message' => 'Road assistance request received successfully'
        ]);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

// Handle GET - Retrieve requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $externalSystem = $_GET['external_system'] ?? null;
    $requestId = $_GET['id'] ?? null;
    
    try {
        if ($requestId) {
            $stmt = $pdo->prepare("SELECT * FROM external_road_requests WHERE id = ?");
            $stmt->execute([$requestId]);
        } elseif ($externalSystem) {
            $stmt = $pdo->prepare("SELECT * FROM external_road_requests WHERE external_system = ? ORDER BY created_at DESC");
            $stmt->execute([$externalSystem]);
        } else {
            $stmt = $pdo->query("SELECT * FROM external_road_requests ORDER BY created_at DESC LIMIT 100");
        }
        
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'error' => 'Method not allowed']);

/*
================================================================================
DATABASE TABLE - Run this SQL on the Road & Transportation database:
================================================================================

CREATE TABLE `external_road_requests` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `external_system` varchar(50) NOT NULL COMMENT 'e.g., PFRS, HRM, etc.',
  `external_user_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'User ID from external system',
  `system_name` varchar(255) NOT NULL COMMENT 'Full name of requesting system',
  `event_type` varchar(100) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `location` varchar(500) NOT NULL,
  `landmark` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `assigned_personnel` text DEFAULT NULL,
  `assigned_equipment` text DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `processed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `processed_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `external_road_requests_external_system_index` (`external_system`),
  KEY `external_road_requests_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

*/
