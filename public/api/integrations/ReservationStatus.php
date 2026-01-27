<?php
/**
 * ==========================================================================
 * RESERVATION STATUS API
 * ==========================================================================
 * 
 * Endpoint: GET https://facilities.local-government-unit-1-ph.com/api/integrations/ReservationStatus.php
 * 
 * Parameters:
 *   - booking_id (required) - The booking ID or reference number
 * 
 * Returns the status of a facility reservation.
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

// Get booking_id parameter
$bookingId = $_GET['booking_id'] ?? null;

if (empty($bookingId)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'booking_id is required'
    ]);
    exit();
}

// Extract numeric ID if reference format (BK000001 -> 1)
if (preg_match('/^BK/i', $bookingId)) {
    $bookingId = (int) preg_replace('/[^0-9]/', '', $bookingId);
} else {
    $bookingId = (int) $bookingId;
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
                b.id,
                b.applicant_name,
                b.applicant_email,
                b.applicant_phone,
                b.status,
                b.start_time,
                b.end_time,
                b.purpose,
                b.expected_attendees,
                b.total_amount,
                b.rejected_reason,
                b.staff_notes,
                b.created_at,
                b.updated_at,
                f.name as facility_name,
                f.address as facility_address
            FROM bookings b
            JOIN facilities f ON b.facility_id = f.facility_id
            WHERE b.id = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$bookingId]);
    $booking = $stmt->fetch();

    if (!$booking) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Booking not found'
        ]);
        exit();
    }

    echo json_encode([
        'success' => true,
        'message' => 'Reservation status retrieved successfully',
        'data' => [
            'booking_id' => (int)$booking['id'],
            'booking_reference' => 'BK' . str_pad($booking['id'], 6, '0', STR_PAD_LEFT),
            'applicant_name' => $booking['applicant_name'],
            'applicant_email' => $booking['applicant_email'],
            'applicant_phone' => $booking['applicant_phone'],
            'facility_name' => $booking['facility_name'],
            'facility_address' => $booking['facility_address'],
            'status' => $booking['status'],
            'start_time' => date('Y-m-d h:i A', strtotime($booking['start_time'])),
            'end_time' => date('Y-m-d h:i A', strtotime($booking['end_time'])),
            'purpose' => $booking['purpose'],
            'expected_attendees' => (int)$booking['expected_attendees'],
            'total_amount' => number_format($booking['total_amount'], 2),
            'rejected_reason' => $booking['rejected_reason'],
            'submitted_at' => date('Y-m-d h:i A', strtotime($booking['created_at'])),
            'last_updated' => date('Y-m-d h:i A', strtotime($booking['updated_at']))
        ]
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred',
        'error' => $e->getMessage()
    ]);
}
