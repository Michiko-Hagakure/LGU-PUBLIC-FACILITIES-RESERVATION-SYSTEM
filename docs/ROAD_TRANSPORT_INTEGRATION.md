# Road & Transportation Infrastructure Monitoring - Integration

## Overview

This document describes the integration between the **Public Facility Reservation System (PFRS)** and the **Road & Transportation Infrastructure Monitoring System** operated at:

**Base URL:** `https://lucia-road-trans.local-government-unit-1-ph.com/`

The integration allows PFRS to request road assistance (traffic management, road closures, escorts, etc.) for events that may cause traffic disruptions, and receive approval/rejection notifications back.

---

## Integration Flow

```
┌─────────────────────┐                    ┌──────────────────────────────┐
│  PFRS (Our System)  │                    │  Road & Transportation System │
│                     │                    │  (lucia-road-trans)           │
│                     │  1. Submit Event   │                              │
│  Admin / Citizen    │ ──────────────────>│  /api/submit_traffic_event.php│
│  requests road      │  POST (form data)  │                              │
│  assistance         │                    │  Returns: request_id          │
│                     │                    │                              │
│                     │  2. Status Update  │                              │
│  /api/road-transport│ <─────────────────│  Their admin approves/rejects │
│  /webhook           │  POST (JSON)       │  → calls our webhook_url      │
│                     │                    │                              │
│                     │  3. We notify them │                              │
│  Admin approves     │ ──────────────────>│  /api/webhook_receiver.php    │
│  incoming requests  │  POST (JSON)       │                              │
└─────────────────────┘                    └──────────────────────────────┘
```

---

## Their Endpoints (External System)

### 1. Submit Traffic Event
- **URL:** `POST /api/submit_traffic_event.php`
- **Content-Type:** `application/x-www-form-urlencoded` (form data)
- **Required Fields:**
  | Field | Type | Description |
  |-------|------|-------------|
  | `event_type` | string | e.g., Traffic Management, Road Closure |
  | `location` | string | Event location |
  | `start_date` | datetime | Start date & time (`Y-m-d H:i:s`) |
  | `end_date` | datetime | End date & time (`Y-m-d H:i:s`) |
  | `description` | string | Event description and expected traffic impact |
  | `system_name` | string | Always `"Public Facility Reservation System"` |

- **Optional Fields:**
  | Field | Type | Description |
  |-------|------|-------------|
  | `landmark` | string | Nearby landmark |
  | `contact_person` | string | Contact person name |
  | `contact_number` | string | Contact phone number |
  | `webhook_url` | url | Our callback URL for status notifications |

- **Response (JSON):**
  ```json
  {
    "success": true,
    "message": "Request submitted successfully",
    "request_id": 42
  }
  ```

### 2. Check Status (Browser Only)
- **URL:** `GET /api/check_status.php?id={request_id}`
- **Note:** This returns an HTML page, not JSON. Use for manual status checks only.
- **Shows:** Request ID, event type, system name, location, dates, description, status (pending/approved/rejected), remarks.

### 3. Webhook Receiver (Their Incoming)
- **URL:** `POST /api/webhook_receiver.php`
- **Content-Type:** `application/json`
- **Payload:**
  ```json
  {
    "request_id": 42,
    "status": "approved",
    "event_type": "Traffic Management",
    "location": "Main Street",
    "remarks": "Approved with conditions",
    "timestamp": "2026-02-13 14:30:00"
  }
  ```
- **Response (JSON):**
  ```json
  {
    "success": true,
    "message": "Notification received"
  }
  ```

---

## Our Endpoints (PFRS)

### Webhook Receiver
- **URL:** `POST /api/road-transport/webhook`
- **Route Name:** `road-transport.webhook`
- **Content-Type:** `application/json`
- **Purpose:** Receives status update notifications from the Road & Transportation system when they approve/reject our submitted traffic event requests.
- **Expected Payload:**
  ```json
  {
    "request_id": 42,
    "status": "approved",
    "remarks": "Approved - personnel will be deployed",
    "event_type": "Traffic Management",
    "location": "Main Street",
    "timestamp": "2026-02-13 14:30:00"
  }
  ```
- **Required Fields:** `request_id`, `status`
- **Behavior:**
  - Updates the matching `citizen_road_requests` record (matched by `external_request_id`)
  - Logs the notification
  - Returns 200 even if no matching local record is found (to avoid retries from their system)

---

## Configuration

### config/services.php
```php
'road_transport' => [
    'base_url'             => env('ROAD_TRANSPORT_BASE_URL', 'https://lucia-road-trans.local-government-unit-1-ph.com'),
    'submit_url'           => env('ROAD_TRANSPORT_SUBMIT_URL', 'https://lucia-road-trans.local-government-unit-1-ph.com/api/submit_traffic_event.php'),
    'check_status_url'     => env('ROAD_TRANSPORT_CHECK_STATUS_URL', 'https://lucia-road-trans.local-government-unit-1-ph.com/api/check_status.php'),
    'webhook_receiver_url' => env('ROAD_TRANSPORT_WEBHOOK_URL', 'https://lucia-road-trans.local-government-unit-1-ph.com/api/webhook_receiver.php'),
    'timeout'              => env('ROAD_TRANSPORT_TIMEOUT', 30),
],
```

### .env (Optional Overrides)
```
ROAD_TRANSPORT_BASE_URL=https://lucia-road-trans.local-government-unit-1-ph.com
ROAD_TRANSPORT_SUBMIT_URL=https://lucia-road-trans.local-government-unit-1-ph.com/api/submit_traffic_event.php
ROAD_TRANSPORT_CHECK_STATUS_URL=https://lucia-road-trans.local-government-unit-1-ph.com/api/check_status.php
ROAD_TRANSPORT_WEBHOOK_URL=https://lucia-road-trans.local-government-unit-1-ph.com/api/webhook_receiver.php
ROAD_TRANSPORT_TIMEOUT=30
```

---

## Key Files

| File | Purpose |
|------|---------|
| `app/Services/RoadTransportApiService.php` | Service class for all Road & Transportation API calls |
| `app/Http/Controllers/Api/RoadTransportWebhookController.php` | Receives webhook notifications from their system |
| `app/Http/Controllers/Admin/RoadAssistanceController.php` | Admin management of road assistance requests |
| `app/Http/Controllers/Citizen/RoadAssistanceController.php` | Citizen-facing road assistance request form |
| `app/Models/RoadAssistanceRequest.php` | Model for incoming road assistance requests |
| `config/services.php` | API endpoint configuration |
| `routes/api.php` | Webhook route definition |
| `routes/web.php` | Admin & citizen web routes |
| `database/migrations/2026_02_03_*_create_citizen_road_requests_table.php` | Local request tracking table |

---

## Event Types

| Internal Key | External Label |
|-------------|---------------|
| `traffic_management` | Traffic Management |
| `road_closure` | Temporary Road Closure |
| `escort` | Vehicle Escort Service |
| `signage` | Traffic Signage & Cones |
| `personnel` | Traffic Personnel Deployment |
| `rerouting` | Traffic Rerouting Plan |

---

## Offline / Retry Handling

When the Road & Transportation system is unreachable:
1. The request is saved locally with `status = 'pending_sync'`
2. Admin can trigger a retry sync via the **Retry Sync** button on the admin dashboard
3. The `RoadTransportApiService::retrySyncPending()` method re-attempts submission for all `pending_sync` records
4. On success, the record is updated with the `external_request_id` and status changes to `pending`

---

## Database Table: `citizen_road_requests`

Stored on the `facilities_db` connection.

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint | Primary key |
| `user_id` | bigint | User who submitted the request |
| `external_request_id` | bigint (nullable) | Request ID from Road & Transportation system |
| `event_type` | varchar(100) | Type of road assistance needed |
| `start_datetime` | datetime | Event start |
| `end_datetime` | datetime | Event end |
| `location` | varchar(500) | Event location |
| `landmark` | varchar(255) | Nearby landmark |
| `description` | text | Event description |
| `booking_id` | bigint (nullable) | Related facility booking ID |
| `status` | varchar(50) | pending, pending_sync, approved, rejected |
| `remarks` | text (nullable) | Feedback/remarks from external system |
| `created_at` | timestamp | Record creation time |
| `updated_at` | timestamp | Last update time |
