<?php
/**
 * ==========================================================================
 * FACILITY RESERVATION API
 * ==========================================================================
 * 
 * Endpoint: POST https://facilities.local-government-unit-1-ph.com/api/integrations/FacilityReservation.php
 * 
 * Creates a new facility reservation request.
 * 
 * ==========================================================================
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed. Use POST.'
    ]);
    exit();
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid JSON input'
    ]);
    exit();
}

// Required fields validation
$requiredFields = [
    'source_system',
    'applicant_name',
    'applicant_email',
    'applicant_phone',
    'facility_id',
    'booking_date',
    'start_time',
    'end_time',
    'purpose',
    'expected_attendees'
];

foreach ($requiredFields as $field) {
    if (empty($input[$field])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => "Field '{$field}' is required"
        ]);
        exit();
    }
}

// Validate email
if (!filter_var($input['applicant_email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid email format'
    ]);
    exit();
}

// Validate date format
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $input['booking_date'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid booking_date format. Use YYYY-MM-DD'
    ]);
    exit();
}

// Validate time format
if (!preg_match('/^\d{2}:\d{2}$/', $input['start_time']) || !preg_match('/^\d{2}:\d{2}$/', $input['end_time'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Invalid time format. Use HH:MM'
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

    // Verify facility exists
    $stmt = $pdo->prepare("SELECT f.*, c.city_name FROM facilities f LEFT JOIN lgu_cities c ON f.lgu_city_id = c.id WHERE f.facility_id = ? AND f.deleted_at IS NULL");
    $stmt->execute([$input['facility_id']]);
    $facility = $stmt->fetch();

    if (!$facility) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Facility not found or unavailable'
        ]);
        exit();
    }

    // Validate capacity
    if ($input['expected_attendees'] > ($facility['capacity'] ?? 1000)) {
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'message' => 'Expected attendees exceeds facility capacity of ' . $facility['capacity']
        ]);
        exit();
    }

    // Parse date/time
    $startDateTime = $input['booking_date'] . ' ' . $input['start_time'] . ':00';
    $endDateTime = $input['booking_date'] . ' ' . $input['end_time'] . ':00';

    // Validate operating hours (8:00 AM to 10:00 PM)
    $startHour = (int)substr($input['start_time'], 0, 2);
    $endHour = (int)substr($input['end_time'], 0, 2);
    
    if ($startHour < 8 || $endHour > 22) {
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'message' => 'Bookings must be within operating hours: 8:00 AM to 10:00 PM'
        ]);
        exit();
    }

    // Check availability (with 2-hour buffer)
    $bufferHours = 2;
    $conflictSql = "SELECT COUNT(*) FROM bookings 
                    WHERE facility_id = ? 
                    AND status IN ('pending', 'staff_verified', 'reserved', 'payment_pending', 'confirmed', 'paid')
                    AND ? < DATE_ADD(end_time, INTERVAL ? HOUR)
                    AND ? > start_time";
    $stmt = $pdo->prepare($conflictSql);
    $stmt->execute([$input['facility_id'], $startDateTime, $bufferHours, $endDateTime]);
    $conflictCount = $stmt->fetchColumn();

    if ($conflictCount > 0) {
        http_response_code(409);
        echo json_encode([
            'success' => false,
            'message' => 'Time slot is not available. Please choose a different date/time.'
        ]);
        exit();
    }

    // Calculate pricing
    $expectedAttendees = (int)$input['expected_attendees'];
    $startTime = strtotime($startDateTime);
    $endTime = strtotime($endDateTime);
    $totalHours = ($endTime - $startTime) / 3600;

    $baseRate = 0;
    $extensionRate = 0;

    if (!empty($facility['per_person_rate']) && $facility['per_person_rate'] > 0) {
        $perPersonRate = $facility['per_person_rate'];
        $baseHours = $facility['base_hours'] ?? 3;
        $extensionRatePer2Hours = $facility['per_person_extension_rate'] ?? 0;

        $baseRate = $perPersonRate * $expectedAttendees;

        $extensionHours = max(0, $totalHours - $baseHours);
        if ($extensionHours > 0 && $extensionRatePer2Hours > 0) {
            $extensionBlocks = ceil($extensionHours / 2);
            $extensionRate = $extensionBlocks * $extensionRatePer2Hours * $expectedAttendees;
        }
    } else {
        $baseRate = 7000.00;
        $extensionRatePerTwoHours = 3000.00;
        $baseHours = 3;
        $extensionHours = max(0, $totalHours - $baseHours);
        $extensionBlocks = ceil($extensionHours / 2);
        $extensionRate = $extensionBlocks * $extensionRatePerTwoHours;
    }

    // Calculate equipment total
    $equipmentTotal = 0;
    if (!empty($input['equipment']) && is_array($input['equipment'])) {
        foreach ($input['equipment'] as $equip) {
            $stmt = $pdo->prepare("SELECT price_per_unit FROM equipment_items WHERE id = ?");
            $stmt->execute([$equip['equipment_id']]);
            $item = $stmt->fetch();
            if ($item) {
                $equipmentTotal += $item['price_per_unit'] * $equip['quantity'];
            }
        }
    }

    $subtotal = $baseRate + $extensionRate + $equipmentTotal;

    // Calculate discounts
    $isResident = false;
    $residentDiscountRate = 0;
    $residentDiscountAmount = 0;

    if (!empty($input['city_of_residence']) && !empty($facility['city_name'])) {
        if (strtolower($input['city_of_residence']) === strtolower($facility['city_name'])) {
            $isResident = true;
            $residentDiscountRate = 30.00;
            $residentDiscountAmount = $subtotal * 0.30;
        }
    }

    $specialDiscountRate = 0;
    $specialDiscountAmount = 0;
    if (!empty($input['special_discount_type'])) {
        $specialDiscountRate = 20.00;
        $afterResidentDiscount = $subtotal - $residentDiscountAmount;
        $specialDiscountAmount = $afterResidentDiscount * 0.20;
    }

    $totalDiscount = $residentDiscountAmount + $specialDiscountAmount;
    $totalAmount = $subtotal - $totalDiscount;

    // Begin transaction
    $pdo->beginTransaction();

    try {
        // Create booking
        $insertSql = "INSERT INTO bookings (
            facility_id, user_id, user_name, applicant_name, applicant_email, applicant_phone,
            applicant_address, event_name, event_description, start_time, end_time, purpose,
            expected_attendees, special_requests, base_rate, extension_rate, equipment_total,
            subtotal, city_of_residence, is_resident, resident_discount_rate, resident_discount_amount,
            special_discount_type, special_discount_rate, special_discount_amount, total_discount,
            total_amount, status, staff_notes, created_at, updated_at
        ) VALUES (
            ?, NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?, NOW(), NOW()
        )";

        $staffNotes = 'Submitted via API from: ' . $input['source_system'];
        if (!empty($input['external_reference_id'])) {
            $staffNotes .= ' (Ref: ' . $input['external_reference_id'] . ')';
        }

        $stmt = $pdo->prepare($insertSql);
        $stmt->execute([
            $input['facility_id'],
            $input['applicant_name'],
            $input['applicant_name'],
            $input['applicant_email'],
            $input['applicant_phone'],
            $input['applicant_address'] ?? null,
            $input['event_name'] ?? null,
            $input['event_description'] ?? null,
            $startDateTime,
            $endDateTime,
            $input['purpose'],
            $expectedAttendees,
            $input['special_requests'] ?? null,
            $baseRate,
            $extensionRate,
            $equipmentTotal,
            $subtotal,
            $input['city_of_residence'] ?? null,
            $isResident ? 1 : 0,
            $residentDiscountRate,
            $residentDiscountAmount,
            $input['special_discount_type'] ?? null,
            $specialDiscountRate,
            $specialDiscountAmount,
            $totalDiscount,
            $totalAmount,
            $staffNotes
        ]);

        $bookingId = $pdo->lastInsertId();
        $bookingReference = 'BK' . str_pad($bookingId, 6, '0', STR_PAD_LEFT);

        // Add equipment if provided
        if (!empty($input['equipment']) && is_array($input['equipment'])) {
            foreach ($input['equipment'] as $equip) {
                $stmt = $pdo->prepare("SELECT price_per_unit FROM equipment_items WHERE id = ?");
                $stmt->execute([$equip['equipment_id']]);
                $item = $stmt->fetch();

                if ($item) {
                    $equipInsertSql = "INSERT INTO booking_equipment (booking_id, equipment_item_id, quantity, price_per_unit, subtotal, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
                    $stmt = $pdo->prepare($equipInsertSql);
                    $stmt->execute([
                        $bookingId,
                        $equip['equipment_id'],
                        $equip['quantity'],
                        $item['price_per_unit'],
                        $item['price_per_unit'] * $equip['quantity']
                    ]);
                }
            }
        }

        $pdo->commit();

        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Reservation request submitted successfully',
            'data' => [
                'booking_id' => (int)$bookingId,
                'booking_reference' => $bookingReference,
                'facility_name' => $facility['name'],
                'booking_date' => $input['booking_date'],
                'start_time' => date('h:i A', strtotime($startDateTime)),
                'end_time' => date('h:i A', strtotime($endDateTime)),
                'status' => 'pending',
                'pricing' => [
                    'base_rate' => number_format($baseRate, 2),
                    'extension_rate' => number_format($extensionRate, 2),
                    'equipment_total' => number_format($equipmentTotal, 2),
                    'subtotal' => number_format($subtotal, 2),
                    'resident_discount' => number_format($residentDiscountAmount, 2),
                    'special_discount' => number_format($specialDiscountAmount, 2),
                    'total_amount' => number_format($totalAmount, 2)
                ]
            ]
        ]);

    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred',
        'error' => $e->getMessage()
    ]);
}
