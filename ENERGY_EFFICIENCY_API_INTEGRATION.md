# Facility Request API — Energy Efficiency and Conservation Management

Base URL: https://facilities.local-government-unit-1-ph.com/api/energy-efficiency/

## Endpoint: facility-request

### Submit Facility Request

*Method:* POST

*Content-Type:* application/json

*Request (JSON):*
POST facility-request

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

*Required Fields:*
- event_title (string)
- point_person (string)
- preferred_date (YYYY-MM-DD, must be today or future)
- start_time (HH:MM)
- end_time (HH:MM, must be after start_time)

*Response:*
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

### Get Facility Requests

*Method:* GET

*Query Parameters:*
- seminar_id (optional): Filter by your seminar/event ID
- status (optional): Filter by status
- user_id (optional): Filter by your user ID

*Examples:*
GET facility-request
GET facility-request?seminar_id=42
GET facility-request?status=approved
GET facility-request?user_id=5&status=pending

*Response:*
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

### Get Specific Request Status

*Method:* GET

*Example:*
GET facility-request/1

*Response:*
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

## Endpoint: facilities

### Get Available Facilities

*Method:* GET

*Example:*
GET facilities

*Response:*
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
        }
    ]
}
```

## Field Options

### Session Types
- orientation
- training
- workshop
- briefing
- meeting

### Audience Types
- employees
- public
- students
- mixed

### Facility Types
- small (capacity below 50)
- medium (capacity 50–99)
- large (capacity 100+)

### Laptop Options
- yes (we provide)
- no
- bringing_own

### Microphone Types
- handheld
- lapel
- both

### Handouts Format
- softcopy
- hardcopy
- both

### Certificates Provider
- us (facility team provides)
- them (energy team provides)
- both

## Status Flow
1. pending — Request submitted, awaiting admin review
2. approved — Facility and schedule assigned, equipment confirmed
3. rejected — Request rejected (reason in admin_feedback)
4. completed — Event completed

## Response Data (When Approved)

When a request is approved, the `response_data` field will contain:
- facility (facility_id, facility_name, facility_capacity)
- scheduled_date
- scheduled_start_time
- scheduled_end_time
- assigned_equipment
- approved_budget
- admin_notes
- approved_at
- approved_by

## Files in This Integration

### On Our Side (Facility Reservation System — Laravel)

- **API Controller** — `app/Http/Controllers/Api/EnergyFacilityRequestApiController.php`
  Handles POST and GET API endpoints. This is where the data you send is received and stored.
- **Admin Controller** — `app/Http/Controllers/Admin/EnergyFacilityRequestController.php`
  Admin panel for reviewing, approving, and rejecting facility requests.
- **Model** — `app/Models/EnergyFacilityRequest.php`
  Eloquent model for the energy_facility_requests table.
- **Migration** — `database/migrations/2025_02_12_200000_create_energy_facility_requests_table.php`
  Database migration that creates the energy_facility_requests table.
- **Admin View** — `resources/views/admin/energy-facility-requests/index.blade.php`
  Admin panel UI for viewing requests, details, and approve/reject modals.
- **API Routes** — `routes/api.php`
  Defines API endpoints under /api/energy-efficiency/ prefix.
- **Web Routes** — `routes/web.php`
  Defines admin panel routes under /admin/energy-facility-requests/.
