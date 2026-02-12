# Energy Efficiency and Conservation Management — Facility Request API Integration Guide

## Overview

This document describes the API integration between the **Energy Efficiency and Conservation Management** system and the **Public Facilities Reservation System (Laravel)** at `https://facilities.local-government-unit-1-ph.com`.

The Energy Efficiency team can use these API endpoints to **request facilities** for their seminars, trainings, workshops, orientations, and other events. The Facility Reservation admin reviews, approves or rejects the request, assigns a facility and equipment, and optionally sets a budget.

## Base URL

```
https://facilities.local-government-unit-1-ph.com/api/energy-efficiency
```

## Authentication

**No API key required.** All endpoints are public and accessible without authentication.

---

## Endpoints

### 1. Submit Facility Request

Submit a new facility request for an energy efficiency event.

**Request:**
```
POST /api/energy-efficiency/facility-request
Content-Type: application/json
```

**Body:**
```json
{
    "event_title": "Energy Conservation Orientation Seminar",
    "purpose": "To educate barangay officials on energy-saving practices",
    "organizer_office": "Energy Efficiency and Conservation Division",
    "point_person": "Juan Dela Cruz",
    "contact_number": "09171234567",
    "contact_email": "juan@energy.local-government-unit-1-ph.com",

    "preferred_date": "2026-03-15",
    "start_time": "09:00",
    "end_time": "12:00",
    "alternative_date": "2026-03-20",
    "alternative_start_time": "13:00",
    "alternative_end_time": "16:00",

    "audience_type": "employees",
    "session_type": "orientation",

    "facility_type": "medium",

    "needs_projector": true,
    "laptop_option": "bringing_own",
    "needs_sound_system": true,
    "needs_microphone": true,
    "microphone_count": 2,
    "microphone_type": "handheld",
    "needs_wifi": true,
    "needs_extension_cords": false,
    "additional_power_needs": null,
    "other_equipment": null,

    "needs_handouts": true,
    "handouts_format": "hardcopy",
    "needs_certificates": true,
    "certificates_provider": "us",

    "needs_refreshments": true,
    "dietary_notes": "No pork",
    "delivery_instructions": "Deliver to venue 30 mins before start",

    "special_requests": "Need a registration table at the entrance",

    "user_id": 5,
    "seminar_id": 42
}
```

**Required Fields:**

| Field | Type | Description |
|-------|------|-------------|
| `event_title` | string (max 255) | Title of the event |
| `point_person` | string (max 255) | Contact person for coordination |
| `preferred_date` | date (YYYY-MM-DD) | Preferred event date, must be today or future |
| `start_time` | time (HH:MM) | Event start time |
| `end_time` | time (HH:MM) | Event end time, must be after start_time |

**Optional Fields — Event Information:**

| Field | Type | Description |
|-------|------|-------------|
| `purpose` | string | Purpose / description of the event |
| `organizer_office` | string (max 255) | Name of the organizing office or division |
| `contact_number` | string (max 50) | Contact phone number |
| `contact_email` | email (max 255) | Contact email address |

**Optional Fields — Schedule:**

| Field | Type | Description |
|-------|------|-------------|
| `alternative_date` | date (YYYY-MM-DD) | Alternative date if preferred is unavailable |
| `alternative_start_time` | time (HH:MM) | Alternative start time |
| `alternative_end_time` | time (HH:MM) | Alternative end time |

**Optional Fields — Attendance & Format:**

| Field | Type | Accepted Values |
|-------|------|-----------------|
| `audience_type` | string | `employees`, `public`, `students`, `mixed` |
| `session_type` | string | `orientation`, `training`, `workshop`, `briefing`, `meeting` |

**Optional Fields — Venue Requirements:**

| Field | Type | Accepted Values |
|-------|------|-----------------|
| `facility_type` | string | `small`, `medium`, `large` |

**Optional Fields — Equipment & Technical Needs:**

| Field | Type | Description |
|-------|------|-------------|
| `needs_projector` | boolean | Whether a projector is needed |
| `laptop_option` | string | `yes` (provide laptop), `no`, `bringing_own` |
| `needs_sound_system` | boolean | Whether a sound system is needed |
| `needs_microphone` | boolean | Whether microphones are needed |
| `microphone_count` | integer (0–20) | Number of microphones needed |
| `microphone_type` | string | `handheld`, `lapel`, `both` |
| `needs_wifi` | boolean | Whether Wi-Fi access is needed |
| `needs_extension_cords` | boolean | Whether extension cords are needed |
| `additional_power_needs` | string (max 1000) | Any additional power requirements |
| `other_equipment` | string (max 1000) | Any other equipment not listed above |

**Optional Fields — Materials & Documents:**

| Field | Type | Description |
|-------|------|-------------|
| `needs_handouts` | boolean | Whether handouts/materials are needed |
| `handouts_format` | string | `softcopy`, `hardcopy`, `both` |
| `needs_certificates` | boolean | Whether certificates of attendance are needed |
| `certificates_provider` | string | `us` (facility team provides), `them` (energy team provides), `both` |

**Optional Fields — Food & Logistics:**

| Field | Type | Description |
|-------|------|-------------|
| `needs_refreshments` | boolean | Whether refreshments/meals are needed |
| `dietary_notes` | string (max 1000) | Dietary restrictions or food preferences |
| `delivery_instructions` | string (max 1000) | Instructions for food/materials delivery |

**Optional Fields — Special Requests & Tracking:**

| Field | Type | Description |
|-------|------|-------------|
| `special_requests` | string (max 2000) | Any other special requests or notes |
| `user_id` | integer | Your system's user ID (for linking back) |
| `seminar_id` | integer | Your system's seminar/event ID (for linking back) |

**Success Response (201):**
```json
{
    "status": "success",
    "message": "Facility request submitted successfully",
    "data": {
        "id": 1,
        "event_title": "Energy Conservation Orientation Seminar",
        "status": "pending",
        "preferred_date": "2026-03-15",
        "start_time": "09:00:00",
        "end_time": "12:00:00",
        "created_at": "2026-02-12 22:30:00"
    }
}
```

**Validation Error Response (422):**
```json
{
    "status": "error",
    "message": "Validation failed",
    "errors": {
        "event_title": ["The event title field is required."],
        "preferred_date": ["The preferred date must be a date after or equal to today."]
    }
}
```

---

### 2. List All Facility Requests

Retrieve all submitted facility requests. Supports optional filters.

**Request:**
```
GET /api/energy-efficiency/facility-request
```

**Optional Query Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `seminar_id` | integer | Filter by your seminar/event ID |
| `status` | string | Filter by status: `pending`, `approved`, `rejected`, `completed` |
| `user_id` | integer | Filter by your user ID |

**Example:**
```
GET /api/energy-efficiency/facility-request?seminar_id=42
GET /api/energy-efficiency/facility-request?status=approved
GET /api/energy-efficiency/facility-request?user_id=5&status=pending
```

**Response:**
```json
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "event_title": "Energy Conservation Orientation Seminar",
            "purpose": "To educate barangay officials on energy-saving practices",
            "organizer_office": "Energy Efficiency and Conservation Division",
            "point_person": "Juan Dela Cruz",
            "preferred_date": "2026-03-15",
            "start_time": "09:00:00",
            "end_time": "12:00:00",
            "session_type": "orientation",
            "facility_type": "medium",
            "approval_status": "approved",
            "admin_feedback": "Approved. Conference Room A assigned.",
            "response_data": {
                "facility": {
                    "facility_id": 3,
                    "facility_name": "Conference Room A",
                    "facility_capacity": 80
                },
                "scheduled_date": "2026-03-15",
                "scheduled_start_time": "09:00",
                "scheduled_end_time": "12:00",
                "assigned_equipment": "LCD Projector, Sound System",
                "approved_budget": 5000.00,
                "admin_notes": "Room confirmed. Equipment ready.",
                "approved_at": "2026-02-13 10:00:00",
                "approved_by": "Admin Name"
            },
            "seminar_id": 42,
            "created_at": "2026-02-12 22:30:00",
            "updated_at": "2026-02-13 10:00:00"
        }
    ],
    "total": 1
}
```

---

### 3. Check Specific Request Status

Get the full details and current status of a specific facility request.

**Request:**
```
GET /api/energy-efficiency/facility-request/{id}
```

**Example:**
```
GET /api/energy-efficiency/facility-request/1
```

**Response:**
```json
{
    "status": "success",
    "data": {
        "id": 1,
        "event_title": "Energy Conservation Orientation Seminar",
        "purpose": "To educate barangay officials on energy-saving practices",
        "organizer_office": "Energy Efficiency and Conservation Division",
        "point_person": "Juan Dela Cruz",
        "contact_number": "09171234567",
        "contact_email": "juan@energy.local-government-unit-1-ph.com",
        "preferred_date": "2026-03-15",
        "start_time": "09:00:00",
        "end_time": "12:00:00",
        "alternative_date": "2026-03-20",
        "alternative_start_time": "13:00:00",
        "alternative_end_time": "16:00:00",
        "audience_type": "employees",
        "session_type": "orientation",
        "facility_type": "medium",
        "approval_status": "pending",
        "admin_feedback": null,
        "response_data": null,
        "seminar_id": 42,
        "created_at": "2026-02-12 22:30:00",
        "updated_at": "2026-02-12 22:30:00"
    }
}
```

**Not Found Response (404):**
```json
{
    "status": "error",
    "message": "Facility request not found"
}
```

---

### 4. List Available Facilities

Get all available facilities so the Energy team can see what's available before submitting a request.

**Request:**
```
GET /api/energy-efficiency/facilities
```

**Response:**
```json
{
    "status": "success",
    "data": [
        {
            "id": 1,
            "name": "City Hall Conference Room",
            "capacity": 100,
            "size_category": "large",
            "location": "City Hall, 2nd Floor",
            "description": "Large conference room with air conditioning"
        },
        {
            "id": 2,
            "name": "Barangay Hall Meeting Room",
            "capacity": 30,
            "size_category": "small",
            "location": "Barangay Hall",
            "description": "Small meeting room"
        }
    ]
}
```

**Size categories** are derived from facility capacity:
- **small** — capacity below 50
- **medium** — capacity 50–99
- **large** — capacity 100 and above

---

## Status Values

| Status | Description |
|--------|-------------|
| `pending` | Request submitted, awaiting admin review |
| `approved` | Request approved, facility and schedule assigned |
| `rejected` | Request rejected (reason provided in `admin_feedback`) |
| `completed` | Event completed |

---

## Response Data (When Approved)

When a request is approved, the `response_data` field contains the admin's assignment details:

| Field | Type | Description |
|-------|------|-------------|
| `facility.facility_id` | integer | Assigned facility ID |
| `facility.facility_name` | string | Assigned facility name |
| `facility.facility_capacity` | integer | Facility capacity |
| `scheduled_date` | date | Confirmed event date |
| `scheduled_start_time` | time | Confirmed start time |
| `scheduled_end_time` | time | Confirmed end time |
| `assigned_equipment` | string | Comma-separated list of assigned equipment |
| `approved_budget` | decimal | Approved budget amount (if applicable) |
| `admin_notes` | string | Additional notes from admin |
| `approved_at` | datetime | When the request was approved |
| `approved_by` | string | Name of the admin who approved |

---

## PHP Integration Example

```php
<?php
define('FACILITY_API_BASE', 'https://facilities.local-government-unit-1-ph.com/api/energy-efficiency');

/**
 * Submit a facility request
 */
function submitFacilityRequest($data) {
    $ch = curl_init(FACILITY_API_BASE . '/facility-request');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return [
        'code' => $httpCode,
        'response' => json_decode($response, true)
    ];
}

/**
 * Check status of a specific request
 */
function checkRequestStatus($requestId) {
    $ch = curl_init(FACILITY_API_BASE . '/facility-request/' . $requestId);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

/**
 * List all requests (with optional filters)
 */
function listRequests($filters = []) {
    $url = FACILITY_API_BASE . '/facility-request';
    if (!empty($filters)) {
        $url .= '?' . http_build_query($filters);
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

/**
 * List available facilities
 */
function listFacilities() {
    $ch = curl_init(FACILITY_API_BASE . '/facilities');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// =============================================================
// Example Usage
// =============================================================

// 1. Submit a facility request
$result = submitFacilityRequest([
    'event_title'      => 'Energy Conservation Orientation',
    'purpose'          => 'Educate barangay officials on energy saving',
    'organizer_office' => 'Energy Efficiency and Conservation Division',
    'point_person'     => 'Juan Dela Cruz',
    'contact_number'   => '09171234567',
    'contact_email'    => 'juan@energy.local-government-unit-1-ph.com',
    'preferred_date'   => '2026-03-15',
    'start_time'       => '09:00',
    'end_time'         => '12:00',
    'session_type'     => 'orientation',
    'facility_type'    => 'medium',
    'needs_projector'  => true,
    'needs_sound_system' => true,
    'needs_microphone' => true,
    'microphone_count' => 2,
    'microphone_type'  => 'handheld',
    'needs_wifi'       => true,
    'seminar_id'       => 42,
    'user_id'          => 5,
]);

if ($result['code'] === 201) {
    $requestId = $result['response']['data']['id'];
    echo "Request submitted! ID: " . $requestId;
}

// 2. Check status later
$status = checkRequestStatus($requestId);
echo "Status: " . $status['data']['approval_status'];

// 3. List all pending requests for a specific seminar
$requests = listRequests(['seminar_id' => 42, 'status' => 'pending']);
echo "Found " . $requests['total'] . " pending requests";

// 4. List available facilities
$facilities = listFacilities();
foreach ($facilities['data'] as $f) {
    echo $f['name'] . " (Capacity: " . $f['capacity'] . ", Size: " . $f['size_category'] . ")\n";
}
?>
```

---

## Files in This Integration

### On Our Side (Facility Reservation System — Laravel)

| File | Location | Description |
|------|----------|-------------|
| **API Controller** | `app/Http/Controllers/Api/EnergyFacilityRequestApiController.php` | Handles POST and GET API endpoints — this is where data from Energy Efficiency is received and stored |
| **Admin Controller** | `app/Http/Controllers/Admin/EnergyFacilityRequestController.php` | Admin panel for reviewing, approving, and rejecting facility requests |
| **Model** | `app/Models/EnergyFacilityRequest.php` | Eloquent model for the `energy_facility_requests` table |
| **Migration** | `database/migrations/2025_02_12_200000_create_energy_facility_requests_table.php` | Database migration that creates the `energy_facility_requests` table |
| **Admin View** | `resources/views/admin/energy-facility-requests/index.blade.php` | Admin panel UI — shows all requests, details, and approve/reject modals |
| **API Routes** | `routes/api.php` | Defines API endpoints under `/api/energy-efficiency/` prefix |
| **Web Routes** | `routes/web.php` | Defines admin panel routes under `/admin/energy-facility-requests/` |

### Data Flow

```
Energy Efficiency System                    Facility Reservation System
========================                    ===========================

POST /api/energy-efficiency/facility-request
    ──────────────────────────────────────►  EnergyFacilityRequestApiController@store
                                                │
                                                ▼
                                            Validates data
                                                │
                                                ▼
                                            Stores in energy_facility_requests table
                                                │
                                                ▼
                                            Returns { id, status: "pending" }
    ◄──────────────────────────────────────

                                            Admin reviews in admin panel
                                            Admin approves/rejects
                                                │
                                                ▼
                                            Updates status + response_data
                                            Creates booking if approved

GET /api/energy-efficiency/facility-request/{id}
    ──────────────────────────────────────►  EnergyFacilityRequestApiController@show
                                                │
                                                ▼
                                            Returns full details + approval_status
                                            + response_data (facility, schedule, etc.)
    ◄──────────────────────────────────────
```

---

## Database Table: `energy_facility_requests`

This table is created in the **auth database** (`auth_db` connection).

```sql
CREATE TABLE `energy_facility_requests` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `event_title` varchar(255) NOT NULL,
  `purpose` text DEFAULT NULL,
  `organizer_office` varchar(255) DEFAULT NULL,
  `point_person` varchar(255) NOT NULL,
  `contact_number` varchar(50) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `preferred_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `alternative_date` date DEFAULT NULL,
  `alternative_start_time` time DEFAULT NULL,
  `alternative_end_time` time DEFAULT NULL,
  `audience_type` varchar(100) DEFAULT NULL,
  `session_type` varchar(100) DEFAULT NULL,
  `facility_type` varchar(50) DEFAULT NULL,
  `needs_projector` tinyint(1) NOT NULL DEFAULT 0,
  `laptop_option` varchar(50) NOT NULL DEFAULT 'no',
  `needs_sound_system` tinyint(1) NOT NULL DEFAULT 0,
  `needs_microphone` tinyint(1) NOT NULL DEFAULT 0,
  `microphone_count` int(11) NOT NULL DEFAULT 0,
  `microphone_type` varchar(50) DEFAULT NULL,
  `needs_wifi` tinyint(1) NOT NULL DEFAULT 0,
  `needs_extension_cords` tinyint(1) NOT NULL DEFAULT 0,
  `additional_power_needs` text DEFAULT NULL,
  `other_equipment` text DEFAULT NULL,
  `needs_handouts` tinyint(1) NOT NULL DEFAULT 0,
  `handouts_format` varchar(50) DEFAULT NULL,
  `needs_certificates` tinyint(1) NOT NULL DEFAULT 0,
  `certificates_provider` varchar(100) DEFAULT NULL,
  `needs_refreshments` tinyint(1) NOT NULL DEFAULT 0,
  `dietary_notes` text DEFAULT NULL,
  `delivery_instructions` text DEFAULT NULL,
  `special_requests` text DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `admin_feedback` text DEFAULT NULL,
  `response_data` text DEFAULT NULL,
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `seminar_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `preferred_date` (`preferred_date`),
  KEY `seminar_id` (`seminar_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## Contact

For API issues or questions, contact the **Public Facilities Reservation System** team.
