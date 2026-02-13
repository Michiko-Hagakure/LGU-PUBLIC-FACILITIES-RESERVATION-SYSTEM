# Super Admin Analytics API

Base URL: https://facilities.local-government-unit-1-ph.com/api/super-admin/analytics/

Controller File Location: `app/Http/Controllers/Api/AnalyticsApiController.php`

Routes File: `routes/api.php`

All endpoints are public — no API key required.
All GET endpoints accept optional query parameters: `?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD`

---

## Endpoint 1: Overview

### Get Analytics Hub Overview

*Method:* GET

*URL:*
GET https://facilities.local-government-unit-1-ph.com/api/super-admin/analytics/overview

*Description:*
Returns the main analytics dashboard summary — total revenue (year to date), total bookings (all time), active citizens, and facility utilization rate (last 30 days).

*Database Tables Used:*
- `payment_slips` (facilities_db)
- `bookings` (facilities_db)
- `facilities` (facilities_db)

*Response:*
```json
{
  "status": "success",
  "data": {
    "total_revenue": 125000.50,
    "total_bookings": 342,
    "active_citizens": 87,
    "facility_utilization": 65.5,
    "total_facilities": 12,
    "booked_facilities_last_30_days": 8
  }
}
```

---

## Endpoint 2: Booking Statistics

### Get Booking Statistics

*Method:* GET

*URL:*
GET https://facilities.local-government-unit-1-ph.com/api/super-admin/analytics/booking-statistics

*Query Parameters:*
- start_date (optional): Filter start date, default: 30 days ago
- end_date (optional): Filter end date, default: today

*Examples:*
GET https://facilities.local-government-unit-1-ph.com/api/super-admin/analytics/booking-statistics
GET https://facilities.local-government-unit-1-ph.com/api/super-admin/analytics/booking-statistics?start_date=2026-01-01&end_date=2026-02-13

*Database Tables Used:*
- `bookings` (facilities_db)
- `facilities` (facilities_db)
- `lgu_cities` (facilities_db)

*Data Returned:*
- Total bookings count
- Bookings by status (pending, approved, paid, confirmed, completed, cancelled, rejected, expired)
- Daily booking trend (last 30 days, with zero-filled missing dates)
- Top 10 popular facilities (name, city, booking count, total revenue)
- Average booking value
- Paid bookings count
- Conversion rate (%)
- Cancelled bookings count
- Cancellation rate (%)
- Peak booking hours (top 5)
- Peak booking days of week

*Response:*
```json
{
  "status": "success",
  "data": {
    "total_bookings": 150,
    "bookings_by_status": [
      { "status": "approved", "count": 45 },
      { "status": "paid", "count": 30 },
      { "status": "completed", "count": 25 },
      { "status": "pending", "count": 20 },
      { "status": "cancelled", "count": 15 },
      { "status": "rejected", "count": 10 },
      { "status": "expired", "count": 5 }
    ],
    "daily_trend": [
      { "date": "Jan 14", "count": 3 },
      { "date": "Jan 15", "count": 5 },
      { "date": "Jan 16", "count": 0 }
    ],
    "popular_facilities": [
      {
        "facility_name": "Covered Court A",
        "city_name": "Noveleta",
        "booking_count": 25,
        "total_revenue": 50000.00
      }
    ],
    "avg_booking_value": 1250.75,
    "paid_bookings": 55,
    "conversion_rate": 36.67,
    "cancelled_bookings": 15,
    "cancellation_rate": 10.0,
    "peak_hours": [
      { "hour": 9, "count": 30 },
      { "hour": 14, "count": 25 },
      { "hour": 10, "count": 20 }
    ],
    "peak_days": [
      { "day_name": "Saturday", "count": 40 },
      { "day_name": "Sunday", "count": 35 },
      { "day_name": "Monday", "count": 25 }
    ]
  },
  "filters": {
    "start_date": "2026-01-14",
    "end_date": "2026-02-13"
  }
}
```

---

## Endpoint 3: Facility Utilization

### Get Facility Utilization Report

*Method:* GET

*URL:*
GET https://facilities.local-government-unit-1-ph.com/api/super-admin/analytics/facility-utilization

*Query Parameters:*
- start_date (optional): Filter start date, default: 6 months ago
- end_date (optional): Filter end date, default: today

*Examples:*
GET https://facilities.local-government-unit-1-ph.com/api/super-admin/analytics/facility-utilization
GET https://facilities.local-government-unit-1-ph.com/api/super-admin/analytics/facility-utilization?start_date=2025-08-01&end_date=2026-02-13

*Database Tables Used:*
- `facilities` (facilities_db)
- `bookings` (facilities_db)
- `lgu_cities` (facilities_db)
- `users` (auth_db)

*Data Returned:*
- All facilities with: name, city, capacity, total bookings, confirmed bookings, cancelled bookings, total revenue, utilization rate (%)
- Underutilized facilities (utilization rate < 30%)
- High performing facilities (utilization rate >= 70%)
- AI training data (facility_id, user_id, user_name, month_index, day_index, hour_index, status)
- Mayor's schedule conflict rules

*Response:*
```json
{
  "status": "success",
  "data": {
    "facilities": [
      {
        "facility_id": 1,
        "name": "Covered Court A",
        "city_name": "Noveleta",
        "capacity": 500,
        "total_bookings": 45,
        "confirmed_bookings": 30,
        "cancelled_bookings": 5,
        "total_revenue": 75000.00,
        "utilization_rate": 16.48
      }
    ],
    "underutilized": [
      {
        "facility_id": 3,
        "name": "Conference Room B",
        "city_name": "Rosario",
        "capacity": 50,
        "total_bookings": 5,
        "confirmed_bookings": 3,
        "cancelled_bookings": 1,
        "total_revenue": 5000.00,
        "utilization_rate": 1.65
      }
    ],
    "high_performing": [],
    "ai_training_data": [
      {
        "facility_id": 1,
        "user_id": 5,
        "month_index": 1,
        "day_index": 3,
        "hour_index": 9,
        "status": "completed",
        "user_name": "Juan Dela Cruz"
      }
    ],
    "mayor_conflict": {
      "facility_id": 1,
      "day_index": 2,
      "hour_start": 8,
      "hour_end": 12
    }
  },
  "filters": {
    "start_date": "2025-08-13",
    "end_date": "2026-02-13"
  }
}
```

---

## Endpoint 4: Revenue Report

### Get Revenue Report

*Method:* GET

*URL:*
GET https://facilities.local-government-unit-1-ph.com/api/super-admin/analytics/revenue

*Query Parameters:*
- start_date (optional): Filter start date, default: start of current month
- end_date (optional): Filter end date, default: end of current month

*Examples:*
GET https://facilities.local-government-unit-1-ph.com/api/super-admin/analytics/revenue
GET https://facilities.local-government-unit-1-ph.com/api/super-admin/analytics/revenue?start_date=2026-01-01&end_date=2026-02-13

*Database Tables Used:*
- `payment_slips` (facilities_db)
- `bookings` (facilities_db)
- `facilities` (facilities_db)
- `lgu_cities` (facilities_db)

*Data Returned:*
- Total revenue (paid payments in date range)
- Revenue by facility (name, city, total bookings, total revenue)
- Revenue by payment method (method name, transaction count, total amount)
- Monthly revenue trend (last 6 months)

*Response:*
```json
{
  "status": "success",
  "data": {
    "total_revenue": 85000.00,
    "revenue_by_facility": [
      {
        "facility_name": "Covered Court A",
        "city_name": "Noveleta",
        "total_bookings": 20,
        "total_revenue": 40000.00
      },
      {
        "facility_name": "Gym B",
        "city_name": "Rosario",
        "total_bookings": 15,
        "total_revenue": 30000.00
      }
    ],
    "revenue_by_payment_method": [
      {
        "payment_method": "cash",
        "transaction_count": 25,
        "total_amount": 50000.00
      },
      {
        "payment_method": "gcash",
        "transaction_count": 10,
        "total_amount": 35000.00
      }
    ],
    "monthly_revenue": [
      { "month": "Sep 2025", "revenue": 12000.00 },
      { "month": "Oct 2025", "revenue": 15000.00 },
      { "month": "Nov 2025", "revenue": 18000.00 },
      { "month": "Dec 2025", "revenue": 20000.00 },
      { "month": "Jan 2026", "revenue": 22000.00 },
      { "month": "Feb 2026", "revenue": 13000.00 }
    ]
  },
  "filters": {
    "start_date": "2026-02-01",
    "end_date": "2026-02-28"
  }
}
```

---

## Endpoint 5: Citizen Analytics

### Get Citizen Analytics

*Method:* GET

*URL:*
GET https://facilities.local-government-unit-1-ph.com/api/super-admin/analytics/citizen

*Query Parameters:*
- start_date (optional): Filter start date, default: start of current year
- end_date (optional): Filter end date, default: today

*Examples:*
GET https://facilities.local-government-unit-1-ph.com/api/super-admin/analytics/citizen
GET https://facilities.local-government-unit-1-ph.com/api/super-admin/analytics/citizen?start_date=2026-01-01&end_date=2026-02-13

*Database Tables Used:*
- `bookings` (facilities_db)
- `users` (auth_db)

*Data Returned:*
- Total unique citizens (registered + external/API bookers)
- Registered citizens count
- External citizens count (booked via API like PF Folder)
- New citizens (first-time bookers in date range)
- Repeat customers (more than 1 booking)
- Top 10 citizens by booking count (name, email, total bookings, total spent)
- Average bookings per citizen
- Monthly citizen growth trend (last 12 months)

*Response:*
```json
{
  "status": "success",
  "data": {
    "total_citizens": 95,
    "registered_citizens": 70,
    "external_citizens": 25,
    "new_citizens": 15,
    "repeat_customers": 30,
    "top_citizens": [
      {
        "full_name": "Juan Dela Cruz",
        "email": "juan@email.com",
        "total_bookings": 12,
        "total_spent": 24000.00
      },
      {
        "full_name": "External Booker",
        "email": "maria@pffolder.com",
        "total_bookings": 8,
        "total_spent": 16000.00
      }
    ],
    "avg_bookings_per_citizen": 3.58,
    "monthly_growth": [
      { "month": "2025-03", "citizen_count": 10 },
      { "month": "2025-04", "citizen_count": 12 },
      { "month": "2025-05", "citizen_count": 15 }
    ]
  },
  "filters": {
    "start_date": "2026-01-01",
    "end_date": "2026-02-13"
  }
}
```

---

## Endpoint 6: Operational Metrics

### Get Operational Metrics

*Method:* GET

*URL:*
GET https://facilities.local-government-unit-1-ph.com/api/super-admin/analytics/operational-metrics

*Query Parameters:*
- start_date (optional): Filter start date, default: 3 months ago
- end_date (optional): Filter end date, default: today

*Examples:*
GET https://facilities.local-government-unit-1-ph.com/api/super-admin/analytics/operational-metrics
GET https://facilities.local-government-unit-1-ph.com/api/super-admin/analytics/operational-metrics?start_date=2025-11-01&end_date=2026-02-13

*Database Tables Used:*
- `bookings` (facilities_db)
- `payment_slips` (facilities_db)
- `users` (auth_db)

*Data Returned:*
- Average staff verification time (hours)
- Average payment processing time (hours)
- Average total processing time (hours — from booking creation to payment)
- Staff performance (per staff: name, total verified, approved count, rejected count, avg verification hours)
- Rejection reasons breakdown
- Total bookings, expired bookings, cancelled bookings, completed bookings
- Expiration rate (%), cancellation rate (%), completion rate (%)
- Workflow bottlenecks (auto-detected: stage, avg hours, severity, recommendation)

*Response:*
```json
{
  "status": "success",
  "data": {
    "avg_staff_verification_time": 12.5,
    "avg_payment_time": 8.3,
    "avg_total_processing_time": 20.8,
    "staff_performance": [
      {
        "staff_verified_by": 3,
        "total_verified": 45,
        "approved_count": 40,
        "rejected_count": 5,
        "avg_verification_hours": 10.2,
        "staff_name": "Admin Staff 1"
      }
    ],
    "rejection_reasons": [
      { "rejected_reason": "Incomplete documents", "count": 8 },
      { "rejected_reason": "Schedule conflict", "count": 5 },
      { "rejected_reason": "Facility under maintenance", "count": 3 }
    ],
    "total_bookings": 200,
    "expired_bookings": 10,
    "cancelled_bookings": 20,
    "completed_bookings": 120,
    "expiration_rate": 5.0,
    "cancellation_rate": 10.0,
    "completion_rate": 60.0,
    "bottlenecks": [
      {
        "stage": "Staff Verification",
        "avg_hours": 52.3,
        "severity": "high",
        "recommendation": "Consider hiring additional staff or streamlining verification process"
      }
    ]
  },
  "filters": {
    "start_date": "2025-11-13",
    "end_date": "2026-02-13"
  }
}
```

---

## Endpoint 7: Payment Analytics

### Get Payment Analytics

*Method:* GET

*URL:*
GET https://facilities.local-government-unit-1-ph.com/api/super-admin/analytics/payments

*Query Parameters:*
- start_date (optional): Filter start date, default: 30 days ago
- end_date (optional): Filter end date, default: today

*Examples:*
GET https://facilities.local-government-unit-1-ph.com/api/super-admin/analytics/payments
GET https://facilities.local-government-unit-1-ph.com/api/super-admin/analytics/payments?start_date=2026-01-01&end_date=2026-02-13

*Database Tables Used:*
- `payment_slips` (facilities_db)
- `bookings` (facilities_db)
- `facilities` (facilities_db)

*Data Returned:*
- Total revenue (paid payments in date range)
- Total transactions count
- Payment method breakdown (method, count, total amount)
- Payment status breakdown (status, count)
- Daily revenue trend (date, total)
- Top 5 revenue generating facilities
- Average payment processing time (hours)
- Payment success rate (%)
- Pending payments count
- Pending payments total amount

*Response:*
```json
{
  "status": "success",
  "data": {
    "total_revenue": 95000.00,
    "total_transactions": 120,
    "payment_method_breakdown": [
      { "payment_method": "cash", "count": 60, "total": 50000.00 },
      { "payment_method": "gcash", "count": 35, "total": 30000.00 },
      { "payment_method": "paymongo", "count": 25, "total": 15000.00 }
    ],
    "status_breakdown": [
      { "status": "paid", "count": 95 },
      { "status": "pending", "count": 20 },
      { "status": "expired", "count": 5 }
    ],
    "daily_revenue": [
      { "date": "2026-01-01", "total": 5000.00 },
      { "date": "2026-01-02", "total": 3500.00 }
    ],
    "top_facilities": [
      {
        "facility_name": "Covered Court A",
        "total_revenue": 40000.00,
        "booking_count": 20
      }
    ],
    "avg_processing_time": 6.5,
    "success_rate": 79.17,
    "pending_payments": 20,
    "pending_amount": 25000.00
  },
  "filters": {
    "start_date": "2026-01-14",
    "end_date": "2026-02-13"
  }
}
```

---

## Endpoint 8: All Analytics (Combined)

### Get All Analytics Data

*Method:* GET

*URL:*
GET https://facilities.local-government-unit-1-ph.com/api/super-admin/analytics/all

*Query Parameters:*
- start_date (optional): Applied to all sub-endpoints
- end_date (optional): Applied to all sub-endpoints

*Examples:*
GET https://facilities.local-government-unit-1-ph.com/api/super-admin/analytics/all
GET https://facilities.local-government-unit-1-ph.com/api/super-admin/analytics/all?start_date=2026-01-01&end_date=2026-02-13

*Description:*
Returns ALL analytics data from all 7 endpoints combined into a single JSON response. Useful if you want everything in one API call.

*Response:*
```json
{
  "status": "success",
  "data": {
    "overview": { "...same as Endpoint 1 data..." },
    "booking_statistics": { "...same as Endpoint 2 data..." },
    "facility_utilization": { "...same as Endpoint 3 data..." },
    "revenue": { "...same as Endpoint 4 data..." },
    "citizen": { "...same as Endpoint 5 data..." },
    "operational_metrics": { "...same as Endpoint 6 data..." },
    "payments": { "...same as Endpoint 7 data..." }
  }
}
```

---

## Endpoint 9: Filter Analytics (POST)

### Filter Analytics by Type

*Method:* POST

*URL:*
POST https://facilities.local-government-unit-1-ph.com/api/super-admin/analytics/filter

*Description:*
Accepts a POST body with the analytics type and optional date range filters. Returns the corresponding analytics data. This is useful for form submissions or when you need to send filter parameters via POST body instead of query strings.

*Request (JSON Body):*
```
POST https://facilities.local-government-unit-1-ph.com/api/super-admin/analytics/filter
Content-Type: application/json

{
  "type": "booking-statistics",
  "start_date": "2026-01-01",
  "end_date": "2026-02-13"
}
```

*Available Types:*
- overview
- booking-statistics
- facility-utilization
- revenue
- citizen
- operational-metrics
- payments

*Examples:*

**Get Overview:**
```json
{
  "type": "overview"
}
```

**Get Booking Statistics with Date Filter:**
```json
{
  "type": "booking-statistics",
  "start_date": "2026-01-01",
  "end_date": "2026-02-13"
}
```

**Get Revenue Report for Current Month:**
```json
{
  "type": "revenue",
  "start_date": "2026-02-01",
  "end_date": "2026-02-28"
}
```

**Get Citizen Analytics for Whole Year:**
```json
{
  "type": "citizen",
  "start_date": "2026-01-01",
  "end_date": "2026-12-31"
}
```

**Get Facility Utilization for Last 6 Months:**
```json
{
  "type": "facility-utilization",
  "start_date": "2025-08-01",
  "end_date": "2026-02-13"
}
```

**Get Operational Metrics:**
```json
{
  "type": "operational-metrics",
  "start_date": "2025-11-01",
  "end_date": "2026-02-13"
}
```

**Get Payment Analytics:**
```json
{
  "type": "payments",
  "start_date": "2026-01-01",
  "end_date": "2026-02-13"
}
```

*Response:*
Returns the same JSON response as the corresponding GET endpoint (see Endpoints 1-7 above).

*Validation Error Response (422):*
```json
{
  "message": "The type field is required.",
  "errors": {
    "type": ["The type field is required."]
  }
}
```

*Invalid Type Error Response (422):*
```json
{
  "message": "The selected type is invalid.",
  "errors": {
    "type": ["The selected type is invalid."]
  }
}
```

---

## Database Tables Referenced

### facilities_db (Facility Reservation Database)
- **bookings** — All facility booking records (status, dates, amounts, user info)
- **facilities** — Facility master data (name, capacity, availability, city)
- **lgu_cities** — LGU city reference table
- **payment_slips** — Payment records (amount, method, status, paid_at)

### auth_db (Authentication Database)
- **users** — User accounts (full_name, email)

---

## Booking Status Values
- Pending
- Approved
- Paid
- Confirmed
- Completed
- Cancelled
- Rejected
- Expired

## Payment Status Values
- Pending
- Paid
- Expired

## Payment Methods
- cash
- gcash
- paymongo

---

## Quick Reference: All Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/super-admin/analytics/overview` | Dashboard overview (revenue, bookings, citizens, utilization) |
| GET | `/api/super-admin/analytics/booking-statistics` | Booking stats, trends, popular facilities, peak hours |
| GET | `/api/super-admin/analytics/facility-utilization` | Facility utilization, AI training data, underutilized/high-performing |
| GET | `/api/super-admin/analytics/revenue` | Revenue by facility, payment method, monthly trend |
| GET | `/api/super-admin/analytics/citizen` | Citizen counts, top bookers, growth trend |
| GET | `/api/super-admin/analytics/operational-metrics` | Processing times, staff performance, bottlenecks |
| GET | `/api/super-admin/analytics/payments` | Payment breakdown, daily revenue, success rate |
| GET | `/api/super-admin/analytics/all` | All analytics combined in one response |
| POST | `/api/super-admin/analytics/filter` | Filter any analytics type via POST body |
