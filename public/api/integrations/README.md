# Facility Reservation API

## Base URL
https://facilities.local-government-unit-1-ph.com

---

# Facility List API

## Endpoint
GET https://facilities.local-government-unit-1-ph.com/api/integrations/FacilityList.php

## cURL Example
```bash
curl -X GET https://facilities.local-government-unit-1-ph.com/api/integrations/FacilityList.php
```

## Response
```json
{
  "success": true,
  "message": "Facilities retrieved successfully",
  "data": [
    {
      "facility_id": 1,
      "name": "Municipal Covered Court",
      "description": "Indoor covered court suitable for sports events",
      "address": "123 Main Street",
      "capacity": 500,
      "min_capacity": 50,
      "per_person_rate": 50.00,
      "per_person_extension_rate": 25.00,
      "base_hours": 3,
      "city_name": "City Name"
    }
  ]
}
```

---

# Equipment List API

## Endpoint
GET https://facilities.local-government-unit-1-ph.com/api/integrations/EquipmentList.php

## cURL Example
```bash
curl -X GET https://facilities.local-government-unit-1-ph.com/api/integrations/EquipmentList.php
```

## Response
```json
{
  "success": true,
  "message": "Equipment retrieved successfully",
  "data": [
    {
      "id": 1,
      "name": "Plastic Chairs",
      "description": "Standard plastic chairs",
      "category": "Seating",
      "price_per_unit": 10.00,
      "quantity_available": 200
    }
  ]
}
```

---

# Facility Reservation API

## Endpoint
POST https://facilities.local-government-unit-1-ph.com/api/integrations/FacilityReservation.php

## cURL Example
```bash
curl -X POST https://facilities.local-government-unit-1-ph.com/api/integrations/FacilityReservation.php \
  -H "Content-Type: application/json" \
  -d '{
    "source_system": "Energy Efficiency Portal",
    "applicant_name": "John Doe",
    "applicant_email": "john@example.com",
    "applicant_phone": "09123456789",
    "facility_id": 1,
    "booking_date": "2026-02-01",
    "start_time": "09:00",
    "end_time": "12:00",
    "purpose": "Community Meeting",
    "expected_attendees": 50
  }'
```

## Required Fields
- source_system
- applicant_name
- applicant_email
- applicant_phone
- facility_id
- booking_date
- start_time
- end_time
- purpose
- expected_attendees

## Request Body
```json
{
  "source_system": "Energy Efficiency Portal",
  "external_reference_id": "EE-2026-001",
  "applicant_name": "John Doe",
  "applicant_email": "john@example.com",
  "applicant_phone": "09123456789",
  "applicant_address": "123 Sample Street, Barangay Example",
  "facility_id": 1,
  "booking_date": "2026-02-01",
  "start_time": "09:00",
  "end_time": "12:00",
  "purpose": "Community Meeting for Energy Awareness",
  "event_name": "Energy Awareness Seminar",
  "event_description": "A seminar to educate residents about energy efficiency",
  "expected_attendees": 50,
  "city_of_residence": "City Name",
  "special_discount_type": "senior",
  "special_requests": "Need projector setup",
  "equipment": [
    {
      "equipment_id": 1,
      "quantity": 50
    }
  ]
}
```

## Response
```json
{
  "success": true,
  "message": "Reservation request submitted successfully",
  "data": {
    "booking_id": 123,
    "booking_reference": "BK000123",
    "facility_name": "Municipal Covered Court",
    "booking_date": "2026-02-01",
    "start_time": "09:00 AM",
    "end_time": "12:00 PM",
    "status": "pending",
    "pricing": {
      "base_rate": "2,500.00",
      "extension_rate": "0.00",
      "equipment_total": "500.00",
      "subtotal": "3,000.00",
      "resident_discount": "900.00",
      "special_discount": "420.00",
      "total_amount": "1,680.00"
    }
  }
}
```

## Error Response
```json
{
  "success": false,
  "message": "Field 'applicant_name' is required"
}
```

---

# Reservation Status API

## Endpoint
GET https://facilities.local-government-unit-1-ph.com/api/integrations/ReservationStatus.php

## Parameters
- booking_id (required) - The booking ID or reference number (e.g., 123 or BK000123)

## cURL Example
```bash
curl -X GET "https://facilities.local-government-unit-1-ph.com/api/integrations/ReservationStatus.php?booking_id=123"
```

Or using booking reference:
```bash
curl -X GET "https://facilities.local-government-unit-1-ph.com/api/integrations/ReservationStatus.php?booking_id=BK000123"
```

## Response
```json
{
  "success": true,
  "message": "Reservation status retrieved successfully",
  "data": {
    "booking_id": 123,
    "booking_reference": "BK000123",
    "applicant_name": "John Doe",
    "applicant_email": "john@example.com",
    "applicant_phone": "09123456789",
    "facility_name": "Municipal Covered Court",
    "facility_address": "123 Main Street",
    "status": "pending",
    "start_time": "2026-02-01 09:00 AM",
    "end_time": "2026-02-01 12:00 PM",
    "purpose": "Community Meeting",
    "expected_attendees": 50,
    "total_amount": "1,680.00",
    "rejected_reason": null,
    "submitted_at": "2026-01-25 07:30 PM",
    "last_updated": "2026-01-25 07:30 PM"
  }
}
```

## Error Response
```json
{
  "success": false,
  "message": "booking_id is required"
}
```

---

# Status Values

| Status | Description |
|--------|-------------|
| pending | Awaiting staff review |
| staff_verified | Verified by staff, awaiting payment |
| payment_pending | Payment submitted, awaiting verification |
| confirmed | Reservation confirmed |
| paid | Payment received |
| reserved | Fully reserved |
| rejected | Reservation rejected |
| cancelled | Reservation cancelled |

---

# Operating Hours
All facility bookings must be within operating hours: **8:00 AM to 10:00 PM**

# Buffer Time
There is a **2-hour buffer** between reservations for setup and cleanup.

# Discounts
- **Resident Discount**: 30% off for residents of the facility's city
- **Special Discount**: 20% off for senior citizens, PWDs, or students (applied after resident discount)
