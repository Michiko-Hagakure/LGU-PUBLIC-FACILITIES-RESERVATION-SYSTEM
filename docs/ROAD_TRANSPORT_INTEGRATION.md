# Road & Transportation Infrastructure Monitoring - Integration

## Overview

This document describes the integration between the **Public Facility Reservation System (PFRS)** and the **Road & Transportation Infrastructure Monitoring System** operated at:

**Base URL:** `https://lucia-road-trans.local-government-unit-1-ph.com/`

The integration allows PFRS admins to request road assistance (traffic management, road closures, escorts, etc.) for events that may cause traffic disruptions, and poll for approval/rejection status updates.

**Only admins** can request road assistance (e.g., when an event organized by a citizen may cause traffic).

---

## Integration Flow

```
┌─────────────────────┐                    ┌──────────────────────────────┐
│  PFRS (Our System)  │                    │  Road & Transportation System │
│                     │                    │  (lucia-road-trans)           │
│                     │  1. Submit Event   │                              │
│  Admin requests     │ ──────────────────>│  POST /api/integrations/     │
│  road assistance    │  JSON body         │       EventRequest.php       │
│                     │                    │  Returns: { id: 8 }          │
│                     │                    │                              │
│                     │  2. Poll Status    │                              │
│  Admin clicks       │ ──────────────────>│  GET /api/integrations/      │
│  "Sync Statuses"    │  ?id=8             │      EventRequest.php?id=8   │
│                     │                    │  Returns: { status, remarks } │
└─────────────────────┘                    └──────────────────────────────┘
```

---

## Their API Endpoint

**Single endpoint:** `POST/GET /api/integrations/EventRequest.php`

### POST - Create Event Request
- **URL:** `POST /api/integrations/EventRequest.php`
- **Content-Type:** `application/json`
- **Required Fields:**
  | Field | Type | Description |
  |-------|------|-------------|
  | `user_id` | integer | ID of the requesting user |
  | `system_name` | string | Always `"Public Facility Reservation System"` |
  | `event_type` | string | e.g., Traffic Management, Road Closure |
  | `start_date` | datetime | Start date & time (`Y-m-d H:i:s`) |
  | `end_date` | datetime | End date & time (`Y-m-d H:i:s`) |
  | `location` | string | Event location |
  | `description` | string | Event description |

- **Optional Fields:**
  | Field | Type | Description |
  |-------|------|-------------|
  | `landmark` | string | Nearby landmark |

- **Success Response (201):**
  ```json
  {
    "success": true,
    "message": "Event request created successfully",
    "id": 8
  }
  ```

### GET - Retrieve Event Requests
- **URL:** `GET /api/integrations/EventRequest.php`
- **Query Parameters:**
  | Param | Description |
  |-------|-------------|
  | `id` | Get specific event request |
  | `user_id` | Filter by user |
  | `status` | Filter by status (pending, approved, rejected) |

- **Success Response (200):**
  ```json
  {
    "success": true,
    "data": [
      {
        "id": 8,
        "user_id": 35,
        "system_name": "Public Facility Reservation System",
        "event_type": "Road Closure",
        "start_date": "2026-02-15 08:00:00",
        "end_date": "2026-02-15 18:00:00",
        "location": "Chico Street, Quezon City",
        "landmark": "Near City Hall",
        "description": "Road closure for maintenance work",
        "status": "pending",
        "remarks": null,
        "created_at": "2026-02-13 22:33:59",
        "updated_at": "2026-02-13 22:33:59"
      }
    ]
  }
  ```

---

## Configuration

### config/services.php
```php
'road_transport' => [
    'base_url' => env('ROAD_TRANSPORT_BASE_URL', 'https://lucia-road-trans.local-government-unit-1-ph.com'),
    'api_url'  => env('ROAD_TRANSPORT_API_URL', 'https://lucia-road-trans.local-government-unit-1-ph.com/api/integrations/EventRequest.php'),
    'timeout'  => env('ROAD_TRANSPORT_TIMEOUT', 30),
],
```

### .env (Optional Overrides)
```
ROAD_TRANSPORT_BASE_URL=https://lucia-road-trans.local-government-unit-1-ph.com
ROAD_TRANSPORT_API_URL=https://lucia-road-trans.local-government-unit-1-ph.com/api/integrations/EventRequest.php
ROAD_TRANSPORT_TIMEOUT=30
```

---

## Key Files

| File | Purpose |
|------|---------|
| `app/Services/RoadTransportApiService.php` | Service class for all Road & Transportation API calls |
| `app/Http/Controllers/Admin/RoadAssistanceController.php` | Admin management of road assistance requests |
| `app/Models/RoadAssistanceRequest.php` | Model for incoming road assistance requests |
| `config/services.php` | API endpoint configuration |
| `routes/web.php` | Admin web routes |
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

## Status Sync

Since their API uses polling (no webhooks), we check for status updates by:

1. **Sync Statuses** button on admin dashboard — polls `GET EventRequest.php?id={id}` for each pending outgoing request
2. **Retry Sync** button — re-submits any `pending_sync` requests that failed to reach their system

### Flow:
1. Admin submits a road assistance request → saved locally + POSTed to their API
2. If POST fails → saved with `status = 'pending_sync'`, admin can **Retry Sync** later
3. If POST succeeds → saved with `status = 'pending'` and `external_request_id` from their response
4. Admin clicks **Sync Statuses** → polls their API for each pending request, updates local status to `approved`/`rejected`

---

## Database Table: `citizen_road_requests`

Stored on the `facilities_db` connection.

| Column | Type | Description |
|--------|------|-------------|
| `id` | bigint | Primary key |
| `user_id` | bigint | Admin who submitted the request |
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
