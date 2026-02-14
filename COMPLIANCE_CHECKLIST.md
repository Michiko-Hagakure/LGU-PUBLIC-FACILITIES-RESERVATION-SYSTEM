# PFRS COMPLIANCE CHECKLIST — Panel Defense Guide

> **Public Facilities Reservation System (PFRS)**
> Local Government Unit 1 — City of Caloocan
> Generated: February 2026

This document maps every compliance checklist item to **explanations**, **exact file/folder locations**, and **live tests** the panel can perform.

---

## 1. CORE FUNCTIONALITIES (ISO/IEC 25010 & TAM — 20%)

### 1.1 Complete Booking Lifecycle

**Explanation:**
The system implements a full booking lifecycle with statuses: `pending → staff_verified → approved → paid → completed` (or `rejected`/`cancelled`/`expired` at any stage). Citizens go through a 3-step booking wizard (select datetime → select equipment → review & submit). Auto-expiration middleware automatically expires bookings that exceed payment deadlines. Notifications are sent at every status change via email.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Booking Model (statuses, relationships) | `app/Models/Booking.php` |
| Citizen Booking Controller (create, store, cancel) | `app/Http/Controllers/Citizen/BookingController.php` |
| Citizen Reservation Controller (view, reschedule) | `app/Http/Controllers/Citizen/ReservationController.php` |
| Staff Booking Verification | `app/Http/Controllers/Staff/BookingVerificationController.php` |
| Admin Booking Management (approve/reject) | `app/Http/Controllers/Admin/BookingManagementController.php` |
| Auto-Expire Middleware | `app/Http/Middleware/AutoExpireBookings.php` |
| Step 1 View: Select Date/Time | `resources/views/citizen/booking/step1-select-datetime.blade.php` |
| Step 2 View: Select Equipment | `resources/views/citizen/booking/step2-select-equipment.blade.php` |
| Step 3 View: Review & Submit | `resources/views/citizen/booking/step3-review-submit.blade.php` |
| Confirmation View | `resources/views/citizen/booking/confirmation.blade.php` |
| Booking Notifications (18 types) | `app/Notifications/` (BookingSubmitted, BookingConfirmed, BookingRejected, BookingCancelled, BookingExpired, PaymentConfirmed, PaymentRejected, PaymentReminder24Hours, PaymentReminder6Hours, etc.) |

**Tests to Perform:**
1. Login as Citizen → Browse Facilities → Click "Book Now" → Complete 3-step wizard
2. Login as Staff → Verify the booking in the verification queue
3. Login as Admin → Approve or Reject the booking
4. Show that the citizen receives email notifications at each stage
5. Demonstrate auto-expiration by showing `AutoExpireBookings.php` middleware logic
6. Show booking cancellation and reschedule flow

---

### 1.2 Multi-Level Approval Workflow

**Explanation:**
Bookings pass through a multi-level approval chain: **Citizen submits → Reservations Staff verifies (document check) → Admin approves/rejects → Treasurer verifies payment → System issues Official Receipt**. Each level has its own dashboard and action buttons. Conflict detection ensures double-bookings are flagged.

**File Locations:**

| Level | Controller | Dashboard View |
|-------|-----------|---------------|
| Citizen (Submit) | `app/Http/Controllers/Citizen/BookingController.php` | `resources/views/citizen/booking/` |
| Staff (Verify) | `app/Http/Controllers/Staff/BookingVerificationController.php` | `resources/views/staff/bookings/` |
| Admin (Approve/Reject) | `app/Http/Controllers/Admin/BookingManagementController.php` | `resources/views/admin/bookings/` |
| Treasurer (Payment Verify) | `app/Http/Controllers/Treasurer/PaymentVerificationController.php` | `resources/views/treasurer/payment-verification/` |
| Schedule Conflict Detection | `app/Http/Controllers/Admin/ScheduleConflictController.php` | `resources/views/admin/schedule-conflicts/` |
| Booking Conflict Model | `app/Models/BookingConflict.php` | — |

**Tests to Perform:**
1. Submit a booking as Citizen → show it appears in Staff queue → Staff verifies → Admin approves → Treasurer verifies payment
2. Show each role's dashboard with pending items
3. Demonstrate rejection flow with feedback message sent to citizen
4. Show conflict detection when double-booking the same facility

---

### 1.3 Role-Based Access Control

**Explanation:**
The system implements 6 distinct roles: **Super Admin, Admin, Reservations Staff, Treasurer, CBD Staff, Citizen**. Access is enforced via `CheckRole` middleware registered as `role` in the HTTP Kernel. Each role has its own layout, sidebar, dashboard, and route group. Unauthorized access redirects to the user's own dashboard with an error message.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| CheckRole Middleware | `app/Http/Middleware/CheckRole.php` |
| HTTP Kernel (middleware registration) | `app/Http/Kernel.php` (line 69: `'role' => CheckRole::class`) |
| Route definitions (role-grouped) | `routes/web.php` |
| Admin Layout | `resources/views/layouts/admin.blade.php` |
| Citizen Layout | `resources/views/layouts/citizen.blade.php` |
| Staff Layout | `resources/views/layouts/staff.blade.php` |
| Treasurer Layout | `resources/views/layouts/treasurer.blade.php` |
| Super Admin Layout | `resources/views/layouts/superadmin.blade.php` |
| CBD Staff Layout | `resources/views/layouts/cbd.blade.php` |

**Tests to Perform:**
1. Login as each role → show different dashboard/sidebar
2. Try accessing `/admin/dashboard` as Citizen → show redirect with "Unauthorized" message
3. Show `CheckRole.php` middleware code with role validation logic
4. Show route middleware groups in `web.php` (e.g., `middleware(['session.timeout', 'role:admin'])`)

---

### 1.4 Payment Processing

**Explanation:**
The system supports **multi-channel payments**: Cash, GCash, Maya, BPI, BDO, Metrobank, UnionBank, Landbank, and automated **PayMongo** checkout. Payment slips are generated with unique slip numbers. The Treasurer verifies manual payments and issues Official Receipts (OR numbers). PayMongo webhook handles automated payment confirmations.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Payment Config (all channels) | `config/payment.php` |
| PayMongo Service | `app/Services/PaymongoService.php` |
| Citizen Payment Controller | `app/Http/Controllers/Citizen/PaymentController.php` |
| Citizen PayMongo Controller | `app/Http/Controllers/Citizen/PayMongoController.php` |
| Payment Method Selection | `app/Http/Controllers/Citizen/PaymentMethodController.php` |
| Treasurer Payment Verification | `app/Http/Controllers/Treasurer/PaymentVerificationController.php` |
| Admin Payment Verification | `app/Http/Controllers/Admin/PaymentVerificationController.php` |
| PayMongo Webhook Controller | `app/Http/Controllers/Api/PayMongoWebhookController.php` |
| Payment Slip Model | `app/Models/PaymentSlip.php` |
| Refund Controller | `app/Http/Controllers/Citizen/RefundController.php` |
| Treasurer Refund Controller | `app/Http/Controllers/Treasurer/RefundController.php` |

**Tests to Perform:**
1. Complete a booking → select payment method (GCash/Maya/Bank) → show payment instructions
2. Login as Treasurer → verify a payment → issue an Official Receipt
3. Show PayMongo checkout integration (if enabled)
4. Show the `config/payment.php` with all 7 bank/e-wallet channels configured
5. Demonstrate refund request flow

---

### 1.5 Pricing Calculator

**Explanation:**
The `PricingCalculatorService` implements a **two-tier discount system**: Tier 1 is a 30% City Residency Discount for Caloocan residents; Tier 2 is a 20% Identity-Based Discount for Senior Citizens, PWDs, and Students (applied after city discount). Equipment rental is calculated separately with no discounts. The service provides full pricing breakdowns with subtotals, discount amounts, and final totals.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Pricing Calculator Service | `app/Services/PricingCalculatorService.php` |
| Admin Pricing Management | `app/Http/Controllers/Admin/PricingController.php` |
| Staff Pricing View | `app/Http/Controllers/Staff/PricingController.php` |
| Admin Pricing View | `resources/views/admin/pricing/` |

**Tests to Perform:**
1. Book a facility as a Caloocan resident → show 30% discount applied
2. Book with Senior/PWD/Student ID → show 20% additional discount (after city discount)
3. Add equipment → show equipment total added without discount
4. Show the pricing breakdown on Step 3 (Review & Submit) of the booking wizard
5. Open `PricingCalculatorService.php` → walk through the calculation logic

---

### 1.6 Facility Directory

**Explanation:**
Citizens can browse all available facilities with photos, descriptions, rates, addresses, capacity, and availability calendars. Facilities can be searched, filtered, and favorited. Each facility has a detail page with a real-time availability calendar, reviews, and a "Book Now" button.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Public Facility Controller | `app/Http/Controllers/FacilityController.php` |
| Citizen Facility Controller | `app/Http/Controllers/Citizen/FacilityController.php` |
| Admin Facility Management | `app/Http/Controllers/Admin/FacilityController.php` |
| Staff Facility Controller | `app/Http/Controllers/Staff/FacilityController.php` |
| Facility Model | `app/Models/Facility.php` |
| Browse Facilities View | `resources/views/citizen/browse-facilities.blade.php` |
| Facility Details View | `resources/views/citizen/facility-details.blade.php` |
| Facility Calendar View | `resources/views/citizen/facility-calendar.blade.php` |
| Citizen Favorites | `app/Http/Controllers/Citizen/FavoriteController.php` |
| Facility Reviews | `app/Http/Controllers/Citizen/ReviewController.php` |
| Admin Facility CRUD Views | `resources/views/admin/facilities/` |

**Tests to Perform:**
1. Visit the public facilities page → browse and filter facilities
2. Click a facility → show detail page with photos, rates, calendar, reviews
3. Login as Admin → Add/Edit/Delete a facility
4. Show facility availability calendar with color-coded time slots

---

## 2. AI / IoT INTEGRATION (15%)

### 2.1 AI Neural Network Forecasting

**Explanation:**
The system implements a **TensorFlow.js-based neural network** directly in the browser for real-time facility demand forecasting. The architecture is a 3-layer sequential model: Input Layer (20 neurons, ReLU) → Hidden Layer (10 neurons, ReLU) → Output Layer (1 neuron, Sigmoid). It trains on actual facility utilization data from the database using the **Adam optimizer** with **Mean Squared Error** loss function over **50 epochs**. After training, it predicts occupancy for the next 12 hours and provides AI-generated resource allocation advice.

**Technical Details:**
- **Framework:** TensorFlow.js (runs entirely in-browser)
- **Architecture:** 3-layer Dense Neural Network (20→10→1 neurons)
- **Training Data:** Real facility utilization rates injected from Laravel controller via `@json($facilities)`
- **Activation Functions:** ReLU (hidden layers), Sigmoid (output)
- **Optimizer:** Adam (learning rate: 0.05)
- **Loss Function:** Mean Squared Error
- **Training:** 50 epochs with real-time progress bar and loss display
- **Output:** Predicted peak time, predicted occupancy %, AI strategy advice

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Neural Network Implementation | `resources/views/admin/analytics/facility-utilization.blade.php` (lines 332–502) |
| Facility Utilization Controller (data source) | `app/Http/Controllers/Admin/AnalyticsController.php` |
| Analytics API (data for AI) | `app/Http/Controllers/Api/AnalyticsApiController.php` |

**Key Code Sections (in `facility-utilization.blade.php`):**
- **Lines 341–415:** `runAIForecasting()` — Main AI execution: data normalization, model building, training
- **Lines 376–386:** Neural network architecture definition (tf.sequential, tf.layers.dense)
- **Lines 388–407:** Model compilation and training with epoch callbacks
- **Lines 420–441:** `generateForecast()` — Prediction generation for 7 time slots
- **Lines 473–493:** `updateAISummary()` — Dynamic AI advice based on predicted load

**Tests to Perform:**
1. Login as Admin → Analytics → Facility Utilization
2. Watch the neural network train in real-time (progress bar + loss reduction)
3. See the "Neural Network Facility Forecasting" section with:
   - Predicted Peak Window (time)
   - Predicted Occupancy (%)
   - AI Strategy recommendation
   - Forecast chart (line graph)
4. Click "Re-train AI Model" to retrain on demand
5. Open browser DevTools Console → show TensorFlow.js epoch logs

---

### 2.2 AI Audit Trail

**Explanation:**
The system maintains a comprehensive audit trail that tracks all administrative actions, security events, and system changes. The AI analytics audit view provides intelligent analysis of system activity patterns. Audit logs capture: user identity, action type, system module, IP address, user agent, timestamps, and changed data (before/after).

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Activity Log Model | `app/Models/ActivityLog.php` |
| Audit Log Model | `app/Models/AuditLog.php` |
| Audit Trail Controller | `app/Http/Controllers/Admin/AuditTrailController.php` |
| Staff Activity Log Controller | `app/Http/Controllers/Staff/ActivityLogController.php` |
| Audit Trail View | `resources/views/admin/audit-trail/index.blade.php` |
| Audit Trail Detail View | `resources/views/admin/audit-trail/show.blade.php` |
| Analytics Audit View | `resources/views/admin/analytics/audit.blade.php` |
| Audit PDF Export | `resources/views/admin/analytics/exports/` |

**Tests to Perform:**
1. Login as Admin → Audit Trail → Show all logged actions
2. Filter by event type, module, user, date range
3. Click a log entry → show detailed view with IP, user agent, changes
4. Export audit trail to CSV or PDF
5. Show that every admin action (approve booking, create facility, etc.) generates a log entry

---

## 3. MICROSERVICES / API INTEGRATION (10%)

### 3.1 Facility Reservation API

**Explanation:**
RESTful API exposing all facility reservation capabilities to external LGU subsystems. Supports facility listing, equipment listing, availability checking, booking creation, payment completion, refund management, and status tracking.

**Base URL:** `https://facilities.local-government-unit-1-ph.com/api/facility-reservation`

**Endpoints:**

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/facilities` | List all facilities |
| GET | `/equipment` | List all equipment |
| GET | `/check-availability` | Check facility availability |
| GET | `/calendar-bookings` | Get calendar bookings |
| GET | `/my-bookings?email=...` | Get user's bookings |
| GET | `/status/{reference}` | Check booking status |
| GET | `/payment-history/{reference}` | Get payment history |
| POST | `/` | Create new booking |
| POST | `/payment-complete` | Mark payment as complete |
| POST | `/promote-after-payment` | Promote booking after payment |
| POST | `/submit-cashless-payment` | Submit cashless payment |
| GET | `/refunds?email=...` | Get refund requests |
| POST | `/refunds/{id}/select-method` | Select refund method |

**File Locations:**

| Component | File Path |
|-----------|-----------|
| API Routes | `routes/api.php` (lines 74–113) |
| API Controller (60K+ lines) | `app/Http/Controllers/Api/FacilityReservationApiController.php` |

**Tests to Perform:**
1. Use Postman/browser → `GET /api/facility-reservation/facilities` → show JSON response
2. `GET /api/facility-reservation/check-availability?facility_id=1&date=2026-03-01` → show availability
3. `POST /api/facility-reservation` with booking data → show booking created
4. `GET /api/facility-reservation/status/{reference}` → show booking status

---

### 3.2 Housing & Resettlement API

**Explanation:**
API for the Housing & Resettlement Management subsystem to request facilities for beneficiary orientations and resettlement-related events.

**Base URL:** `https://facilities.local-government-unit-1-ph.com/api/housing-resettlement`

**File Locations:**

| Component | File Path |
|-----------|-----------|
| API Routes | `routes/api.php` (lines 124–136) |
| API Controller | `app/Http/Controllers/Api/HousingResettlementApiController.php` |
| Admin Management View | `resources/views/admin/housing-resettlement/` |
| Admin Controller | `app/Http/Controllers/Admin/HousingResettlementController.php` |

**Tests to Perform:**
1. `GET /api/housing-resettlement/facilities` → list available facilities
2. `POST /api/housing-resettlement/request` → submit facility request
3. `GET /api/housing-resettlement/status/{reference}` → check status
4. Login as Admin → show Housing & Resettlement requests management page

---

### 3.3 Energy Efficiency API

**Explanation:**
API for the Energy Efficiency & Conservation Management subsystem to request facilities for seminars, trainings, and workshops. Includes both new facility request endpoints and legacy fund request endpoints for backward compatibility.

**Base URL:** `https://facilities.local-government-unit-1-ph.com/api/energy-efficiency`

**File Locations:**

| Component | File Path |
|-----------|-----------|
| API Routes | `routes/api.php` (lines 157–220) |
| API Controller | `app/Http/Controllers/Api/EnergyFacilityRequestApiController.php` |
| Energy Facility Request Model | `app/Models/EnergyFacilityRequest.php` |
| Admin Management | `app/Http/Controllers/Admin/EnergyFacilityRequestController.php` |
| Admin View | `resources/views/admin/energy-facility-requests/` |
| Energy Efficiency API Service | `app/Services/UtilityBillingApiService.php` |
| Fund Request Model | `app/Models/FundRequest.php` |

**Tests to Perform:**
1. `POST /api/energy-efficiency/facility-request` → submit facility request
2. `GET /api/energy-efficiency/facilities` → list facilities
3. `GET /api/energy-efficiency/facility-request/{id}` → check request status
4. Login as Admin → Energy Facility Requests → manage incoming requests

---

### 3.4 Road & Transportation API

**Explanation:**
API for the Road & Transportation Infrastructure Monitoring subsystem to submit road assistance requests for events that may cause traffic congestion. Also includes a webhook endpoint for receiving status updates from the Road & Transportation system.

**Base URL:** `https://facilities.local-government-unit-1-ph.com/api/road-assistance`

**File Locations:**

| Component | File Path |
|-----------|-----------|
| API Routes | `routes/api.php` (lines 234–309) |
| Road Transport Webhook | `routes/api.php` (lines 397–398) |
| Webhook Controller | `app/Http/Controllers/Api/RoadTransportWebhookController.php` |
| Road Transport API Service | `app/Services/RoadTransportApiService.php` |
| Road Assistance Model | `app/Models/RoadAssistanceRequest.php` |
| Admin Road Assistance | `app/Http/Controllers/Admin/RoadAssistanceController.php` |
| Admin View | `resources/views/admin/road-assistance/` |
| Service Config | `config/services.php` (lines 63–68) |

**Tests to Perform:**
1. `POST /api/road-assistance/request` → submit road assistance request
2. `GET /api/road-assistance/status/{id}` → check request status
3. Login as Admin → Road Assistance → manage requests
4. Show webhook endpoint configuration for receiving status updates

---

### 3.5 Super Admin Analytics API

**Explanation:**
Comprehensive REST API exposing all analytics data for the Super Admin dashboard and external monitoring. Supports 7 individual analytics endpoints plus a combined "all data" endpoint and date range filtering via POST.

**Base URL:** `https://facilities.local-government-unit-1-ph.com/api/super-admin/analytics`

**Endpoints:**

| Method | Endpoint | Purpose |
|--------|----------|---------|
| GET | `/overview` | Revenue, bookings, citizens, utilization |
| GET | `/booking-statistics` | Status breakdown, trends, popular facilities |
| GET | `/facility-utilization` | AI training data, utilization rates |
| GET | `/revenue` | Revenue by facility, payment method, monthly |
| GET | `/citizen` | New/repeat citizens, growth trend |
| GET | `/operational-metrics` | Processing times, staff performance |
| GET | `/payments` | Payment method breakdown, daily revenue |
| GET | `/all` | All analytics in single response |
| POST | `/filter` | Filter any type with date range |

**File Locations:**

| Component | File Path |
|-----------|-----------|
| API Routes | `routes/api.php` (lines 336–363) |
| API Controller | `app/Http/Controllers/Api/AnalyticsApiController.php` |

**Tests to Perform:**
1. `GET /api/super-admin/analytics/overview` → show overview JSON
2. `GET /api/super-admin/analytics/all` → show complete analytics data
3. `POST /api/super-admin/analytics/filter` with date range → show filtered results

---

### 3.6 PayMongo Webhook

**Explanation:**
Webhook endpoint for PayMongo to send automated payment event notifications (checkout_session.payment.paid, payment.paid). Automatically updates booking and payment slip status upon successful payment.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Webhook Route | `routes/api.php` (lines 377–378) |
| Webhook Controller | `app/Http/Controllers/Api/PayMongoWebhookController.php` |
| PayMongo Service | `app/Services/PaymongoService.php` |
| Payment Config | `config/payment.php` (lines 24–27) |

**Tests to Perform:**
1. Show webhook URL: `POST /api/paymongo/webhook`
2. Show `PayMongoWebhookController.php` — handles signature verification and payment confirmation
3. Show `config/payment.php` with PayMongo credentials configuration

---

## 4. PHYSICAL SERVER SETUP AND CONFIGURATION (15%)

### 4.1 Live Domain Deployed

**Explanation:**
The application is deployed on a live domain: `https://facilities.local-government-unit-1-ph.com`. The server is configured with Apache/Nginx virtual hosts, and the application is accessible via the internet.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Docker Config | `Dockerfile`, `docker-compose.yml` |
| App URL Config | `config/app.php` (line 55: `'url' => env('APP_URL')`) |
| Public Entry Point | `public/index.php` |

**Tests to Perform:**
1. Open browser → navigate to `https://facilities.local-government-unit-1-ph.com`
2. Show the live landing page
3. Show DNS records pointing to the server

---

### 4.2 SSL Certificate

**Explanation:**
The live domain uses HTTPS with a valid SSL certificate. Session cookies are configured for secure-only transmission. The application enforces HTTPS in production.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Session Secure Cookie | `config/session.php` (line 172: `'secure' => env('SESSION_SECURE_COOKIE')`) |
| HTTP-Only Cookies | `config/session.php` (line 185: `'http_only' => true`) |
| Same-Site Cookie Policy | `config/session.php` (line 202: `'same_site' => 'lax'`) |

**Tests to Perform:**
1. Visit live URL → show padlock icon in browser (HTTPS)
2. Click padlock → show SSL certificate details
3. Show `config/session.php` secure cookie settings

---

### 4.3 Environment-Based Configuration

**Explanation:**
All sensitive configuration (database credentials, API keys, mail credentials, payment keys) are stored in the `.env` file and accessed via `env()` helper. Different environments (local, production) use different `.env` files. The `.env` file is excluded from version control via `.gitignore`.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Environment File | `.env` (gitignored — not in repo) |
| Git Ignore | `.gitignore` |
| App Config (env-driven) | `config/app.php` (lines 29, 42, 55) |
| Database Config (env-driven) | `config/database.php` |
| Payment Config (env-driven) | `config/payment.php` |
| Services Config (env-driven) | `config/services.php` |
| Mail Config (env-driven) | `config/mail.php` |
| Session Config (env-driven) | `config/session.php` |

**Tests to Perform:**
1. Show `.gitignore` contains `.env`
2. Open `config/database.php` → show all values use `env()` function
3. Show `config/app.php` → `'env' => env('APP_ENV', 'production')`
4. Show that production server has different `.env` values than local development

---

### 4.4 Backup System

**Explanation:**
Automated backup system using **Spatie Laravel Backup** package. Backs up both databases (`auth_db` and `facilities_db`) as compressed ZIP archives. Supports manual backup creation from Admin panel, backup listing, secure download with OTP verification, and automated cleanup with retention policies (7 days all backups, 30 days daily backups).

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Backup Config | `config/backup.php` |
| Backup Controller | `app/Http/Controllers/Admin/BackupController.php` |
| Backup Admin View | `resources/views/admin/backup/index.blade.php` |
| Backup Download Model | `app/Models/BackupDownload.php` |
| Backup OTP Notification | `app/Notifications/BackupDownloadOtp.php` |
| Database Dump Config | `config/database.php` (lines 81–85, 107–111) |

**Key Config Points:**
- **Databases backed up:** `facilities_db` and `auth_db` (line 80–82 of `config/backup.php`)
- **Compression:** ZipArchive level 9 (line 144)
- **Encryption:** AES-256 (line 177: `'encryption' => 'default'` which uses `ZipArchive::EM_AES_256`)
- **Retention:** 7 days all, 30 days daily (lines 294–301)
- **Max storage:** 5000 MB (line 327)

**Tests to Perform:**
1. Login as Admin → Backup Management → Click "Create Backup"
2. Show list of existing backups with timestamps and sizes
3. Download a backup (requires OTP verification)
4. Show `config/backup.php` → databases, encryption, retention settings

---

### 4.5 Multi-Database Architecture

**Explanation:**
The system uses a **dual-database architecture**: `auth_db` (shared authentication database for all LGU subsystems) and `facilities_db` (dedicated database for the Facilities Reservation System). Models explicitly declare their database connection. This separation supports the microservices architecture and data isolation between subsystems.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Database Config (2 MySQL connections) | `config/database.php` (lines 63–112) |
| Auth DB Connection | `config/database.php` → `auth_db` (line 63) |
| Facilities DB Connection | `config/database.php` → `facilities_db` (line 89) |
| Default Connection | `config/database.php` (line 18: `'default' => 'auth_db'`) |
| Auth DB Migrations | `database/migrations/` |
| Facilities DB Migrations | `database/migrations/` (with `facilities_db` connection) |
| Energy Efficiency Migrations | `database/migrations_energy_efficiency/` |
| Database Seeders (20 files) | `database/seeders/` |

**Tests to Perform:**
1. Open `config/database.php` → show `auth_db` and `facilities_db` connections
2. Show a model using `$connection = 'facilities_db'` (e.g., `Booking.php`)
3. Show a controller using `DB::connection('auth_db')` and `DB::connection('facilities_db')` in the same method
4. Connect to MySQL → show two separate databases exist

---

### 4.6 Timezone Configured

**Explanation:**
The application timezone is set to **Asia/Manila** (Philippine Standard Time, UTC+8) in the application configuration. All timestamps, booking times, and reports use this timezone.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Timezone Config | `config/app.php` (line 68: `'timezone' => 'Asia/Manila'`) |

**Tests to Perform:**
1. Open `config/app.php` → show `'timezone' => 'Asia/Manila'`
2. Create a booking → show the timestamps are in Philippine time
3. Show backup dates displayed in Asia/Manila timezone (BackupController line 32)

---

### 4.7 AES-256-CBC Encryption

**Explanation:**
Laravel's encryption service uses **AES-256-CBC** cipher for all encrypted data (sessions, cookies, tokens, backup archives). The encryption key is derived from the `APP_KEY` environment variable (32-character random key).

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Cipher Config | `config/app.php` (line 98: `'cipher' => 'AES-256-CBC'`) |
| Encryption Key | `config/app.php` (line 100: `'key' => env('APP_KEY')`) |
| Key Rotation Support | `config/app.php` (lines 102–106: `'previous_keys'`) |
| Cookie Encryption | `app/Http/Kernel.php` (line 33: `EncryptCookies::class`) |
| Backup Encryption | `config/backup.php` (line 177: `'encryption' => 'default'` → AES-256) |

**Tests to Perform:**
1. Open `config/app.php` → show `'cipher' => 'AES-256-CBC'`
2. Open browser DevTools → Cookies → show encrypted session cookie values
3. Show `config/backup.php` → encryption enabled for backup archives

---

## 5. ADVANCED SECURITY FEATURES (Data Privacy Act & ISO 27001 — 15%)

### 5.1 Multi-Factor Authentication (OTP + 2FA + Trusted Devices)

**Explanation:**
The system implements a **3-layer authentication system**:
1. **Layer 1 — Email/Password:** Standard credential verification
2. **Layer 2 — Email OTP:** 6-digit one-time password sent to registered email, expires in 1 minute
3. **Layer 3 — 2FA PIN:** Optional 6-digit PIN for untrusted devices. Once verified, the device is fingerprinted and trusted.

Users can also login via **Google OAuth** (Socialite). Trusted devices are tracked with SHA-256 fingerprints based on User-Agent + IP subnet.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Login Flow (OTP generation) | `routes/web.php` (lines 109–221) |
| OTP Verification | `routes/web.php` (lines 224–380) |
| 2FA PIN Verification | `routes/web.php` (lines 383–540) |
| OTP Resend | `routes/web.php` (lines 543–606) |
| Security Settings (enable/disable 2FA) | `app/Http/Controllers/Citizen/SecurityController.php` |
| Trusted Device Management | `app/Http/Controllers/Citizen/SecurityController.php` (lines 181–218) |
| Google OAuth Controller | `app/Http/Controllers/Auth/GoogleController.php` |
| OTP Email Template | `app/Mail/LoginOtpMail.php` |
| Security Settings View | `resources/views/citizen/security/index.blade.php` |
| Login View (OTP UI) | `resources/views/auth/login.blade.php` |

**Tests to Perform:**
1. Login with email/password → show OTP sent to email → enter OTP → logged in
2. Enable 2FA in Security Settings → set 6-digit PIN
3. Login from a new device → after OTP, system prompts for 2FA PIN
4. Enter correct PIN → device is now "trusted" (won't ask again)
5. Show trusted devices list → remove a device
6. Show login via Google OAuth
7. Show OTP expiration (1 minute) and resend functionality

---

### 5.2 Brute-Force Protection & Session Management

**Explanation:**
- **Brute-force protection:** Login attempts are tracked per IP using Laravel Cache. After **3 failed attempts**, the IP is locked out for **3 minutes**. The lockout countdown is shown to the user.
- **API rate limiting:** `ThrottleRequests::class.':api'` middleware on all API routes.
- **Session management:** Database-driven sessions with timeout detection. Users can view active sessions, revoke individual sessions, or revoke all other sessions. Sessions expire after 120 minutes of inactivity.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Brute-Force Logic | `routes/web.php` (lines 114–220) — `$maxAttempts = 3`, `$lockoutMinutes = 3` |
| API Rate Limiting | `app/Http/Kernel.php` (line 43: `ThrottleRequests::class.':api'`) |
| Session Timeout Middleware | `app/Http/Middleware/CheckSessionTimeout.php` |
| Session Config | `config/session.php` (line 21: `'driver' => 'database'`, line 35: `'lifetime' => 120`) |
| Session Management (view/revoke) | `app/Http/Controllers/Citizen/SecurityController.php` (lines 220–274) |
| User Sessions Table | `database/migrations/` (user_sessions table) |

**Tests to Perform:**
1. Enter wrong password 3 times → show lockout message with countdown timer
2. Wait 3 minutes → show login is re-enabled
3. Login → go to Security Settings → show active sessions list
4. Click "Revoke" on another session → show it's terminated
5. Show `CheckSessionTimeout.php` — expires inactive sessions

---

### 5.3 Role-Based Access Control with Middleware Guards

**Explanation:**
See Section 1.3. The `CheckRole` middleware is applied to all protected route groups via `middleware(['session.timeout', 'role:admin'])` pattern. Each role is restricted to its own set of routes and views.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| CheckRole Middleware | `app/Http/Middleware/CheckRole.php` |
| CheckAuth Middleware | `app/Http/Middleware/CheckAuth.php` |
| Session Timeout Middleware | `app/Http/Middleware/CheckSessionTimeout.php` |
| Kernel Registration | `app/Http/Kernel.php` (lines 67–69) |

---

### 5.4 Input Validation & Injection Prevention (XSS, SQL, CSRF)

**Explanation:**
- **CSRF Protection:** `VerifyCsrfToken` middleware applied to all web routes. CSRF token refresh endpoint at `/csrf-token`.
- **SQL Injection Prevention:** All database queries use Laravel's query builder with parameter binding (no raw SQL).
- **XSS Prevention:** Blade templates use `{{ }}` (auto-escaped) syntax. Input validation on all form submissions.
- **Input Validation:** Server-side validation with Laravel's `$request->validate()` on every form endpoint.
- **Disposable Email Blocking:** Registration blocks temporary/disposable email addresses.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| CSRF Middleware | `app/Http/Kernel.php` (line 37: `VerifyCsrfToken::class`) |
| CSRF Token Refresh | `routes/web.php` (lines 51–55) |
| Input Validation Example | `routes/web.php` (lines 237–252: road assistance validation) |
| Password Validation (regex) | `app/Http/Controllers/Citizen/SecurityController.php` (line 82) |
| Disposable Email Blocker | `app/Helpers/DisposableEmailDomains.php` |
| CORS Config | `config/cors.php` |

**Tests to Perform:**
1. Try submitting a form without CSRF token → show 419 error
2. Show Blade template using `{{ }}` auto-escaping
3. Show a controller with `$request->validate([...])` rules
4. Try registering with a disposable email → show rejection message
5. Show password strength requirements (uppercase, lowercase, digit, special char)

---

### 5.5 Audit Trail & Login History Tracking

**Explanation:**
The system tracks two types of audit data:
1. **Activity Logs** (`activity_logs` table) — All admin/system actions with subject, event, description, IP, user agent, and JSON properties (before/after changes).
2. **Login History** (`login_history` table) — Every login attempt with device, IP, geolocation, success/failure status, 2FA requirement, and timestamp.

Both support filtering, searching, and export to CSV/PDF.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Activity Log Model | `app/Models/ActivityLog.php` |
| Audit Log Model | `app/Models/AuditLog.php` |
| Audit Trail Controller | `app/Http/Controllers/Admin/AuditTrailController.php` |
| Staff Activity Log | `app/Http/Controllers/Staff/ActivityLogController.php` |
| Login History (recorded at login) | `routes/web.php` (lines 318–329, 423–435, 485–496) |
| Security View (login history) | `app/Http/Controllers/Citizen/SecurityController.php` (lines 48–53) |
| Audit Trail Views | `resources/views/admin/audit-trail/` |
| Analytics Audit View | `resources/views/admin/analytics/audit.blade.php` |

**Tests to Perform:**
1. Login as Admin → Audit Trail → show all logged actions
2. Filter by event, module, date range
3. Export to CSV and PDF
4. Login as Citizen → Security Settings → show last 20 login history entries
5. Show a failed login attempt logged with IP and reason

---

### 5.6 Data Privacy Act Compliance (Privacy Policy, Consent, Data Minimization)

**Explanation:**
- **Privacy Policy:** Displayed during registration and accessible via the system.
- **Consent:** Users explicitly consent to data collection during registration.
- **Data Minimization:** Only necessary data fields are collected for booking purposes.
- **Data Export (Right to Access):** Citizens can download all their personal data as CSV from Security Settings.
- **Profile Visibility Controls:** Citizens can set their profile to public/private, control review visibility, and booking count display.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Data Export (CSV) | `app/Http/Controllers/Citizen/SecurityController.php` (lines 300–399: `requestDataDownload()`) |
| Privacy Settings | `app/Http/Controllers/Citizen/SecurityController.php` (lines 276–298: `updatePrivacySettings()`) |
| Registration (consent) | `resources/views/auth/register.blade.php` |
| Profile Management | `app/Http/Controllers/Citizen/ProfileController.php` |

**Tests to Perform:**
1. Login as Citizen → Security Settings → Privacy Settings → toggle profile visibility
2. Click "Download My Data" → receive CSV with all personal data, bookings, reviews
3. Show registration form with privacy consent checkbox
4. Show that exported data includes only relevant information (data minimization)

---

### 5.7 ISO 27001 Controls (Encryption, Signed URLs, API Key Validation)

**Explanation:**
- **Encryption:** AES-256-CBC for all application-level encryption (see Section 4.7).
- **Signed URLs:** Admin panel routes use signed URLs via `ValidateSignedAdminUrl` middleware to prevent URL tampering.
- **API Key Validation:** External API calls validated via `ValidateApiKey` middleware checking `X-API-Key` header.
- **Password Hashing:** Bcrypt via Laravel `Hash::make()`.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Signed URL Middleware | `app/Http/Middleware/ValidateSignedAdminUrl.php` |
| API Key Validation Middleware | `app/Http/Middleware/ValidateApiKey.php` |
| Kernel Registration | `app/Http/Kernel.php` (line 68: `'signed.admin'`) |
| AES-256-CBC Config | `config/app.php` (line 98) |
| Password Hashing | Used throughout: `Hash::make()`, `Hash::check()` |
| Signed Route Usage | `resources/views/admin/analytics/audit.blade.php` (line 14: `URL::signedRoute()`) |

**Tests to Perform:**
1. Show signed URL in admin panel (URL contains `signature` parameter)
2. Try modifying the URL signature → show 403 error
3. Call API without `X-API-Key` header → show 401 error
4. Call API with invalid key → show 403 error
5. Show `config/app.php` → `'cipher' => 'AES-256-CBC'`

---

## 6. ANALYTICS (10%)

### 6.1 Analytics Hub

**Explanation:**
Central analytics dashboard showing aggregated metrics: total revenue (YTD), total bookings, active citizens, and facility utilization rate. Serves as the entry point to all detailed analytics views.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Analytics Controller | `app/Http/Controllers/Admin/AnalyticsController.php` |
| Analytics Hub View | `resources/views/admin/analytics/index.blade.php` |

---

### 6.2 Booking Statistics

**Explanation:**
Detailed booking analytics: status breakdown (pending/approved/rejected/completed), booking trends over time, popular facilities, peak booking hours, and monthly comparison.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Booking Statistics View | `resources/views/admin/analytics/booking-statistics.blade.php` |
| Analytics Controller | `app/Http/Controllers/Admin/AnalyticsController.php` |
| Export (Excel) | `app/Exports/BookingStatisticsExport.php` |
| Staff Statistics | `app/Http/Controllers/Staff/StatisticsController.php` |

---

### 6.3 Citizen Analytics

**Explanation:**
Analytics on citizen engagement: new vs. repeat citizens, top citizens by bookings, citizen growth trend, and demographic insights.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Citizen Analytics View | `resources/views/admin/analytics/citizen-analytics.blade.php` |
| Export (Excel) | `app/Exports/CitizenAnalyticsExport.php` |

---

### 6.4 Facility Utilization

**Explanation:**
Facility utilization metrics with AI-powered forecasting (see Section 2.1). Shows utilization rate per facility, underutilized vs. high-performing facilities, and AI training data.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Facility Utilization View (with AI) | `resources/views/admin/analytics/facility-utilization.blade.php` |
| Export (Excel) | `app/Exports/FacilityUtilizationExport.php` |

---

### 6.5 Revenue Report

**Explanation:**
Revenue analytics: revenue by facility, by payment method, monthly revenue trend, and total collections.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Revenue Report View | `resources/views/admin/analytics/revenue-report.blade.php` |
| CBD Revenue Export | `app/Exports/CbdRevenueExport.php` |
| Treasurer Reports | `app/Http/Controllers/Treasurer/ReportController.php` |
| Treasurer Report Views | `resources/views/treasurer/reports/` |

---

### 6.6 Operational Metrics

**Explanation:**
Operational performance metrics: average processing times, staff performance, bottleneck identification, and workflow efficiency.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Operational Metrics View | `resources/views/admin/analytics/operational-metrics.blade.php` |

---

### 6.7 Payment Analytics

**Explanation:**
Payment-specific analytics: payment method breakdown, daily revenue, payment success rate, and payment processing trends.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Payment Analytics View | `resources/views/admin/analytics/payments.blade.php` |
| Payment Analytics Controller | `app/Http/Controllers/Admin/PaymentAnalyticsController.php` |

---

### 6.8 Date Range Filtering

**Explanation:**
All analytics views support date range filtering via start/end date pickers. The API also supports date range via query parameters (`?start_date=&end_date=`) and POST body filtering.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| API Filter Endpoint | `routes/api.php` (line 362: `POST /api/super-admin/analytics/filter`) |
| Controller Date Filtering | All analytics controller methods accept `$request->start_date` / `$request->end_date` |

**Tests to Perform for All Analytics:**
1. Login as Admin → Analytics Hub → show overview cards
2. Navigate to each analytics sub-page (Booking Statistics, Citizen, Facility, Revenue, Operational, Payment)
3. Use date range pickers to filter data
4. Show charts and tables updating based on filters
5. Test the API: `GET /api/super-admin/analytics/all?start_date=2026-01-01&end_date=2026-02-14`

---

## 7. IMPORT AND EXPORT FUNCTIONS / FREE REPORT FORMAT (5%)

### 7.1 Excel Export

**Explanation:**
The system uses **Maatwebsite/Laravel-Excel** package for Excel exports. Available exports: Booking Statistics, Citizen Analytics, Facility Utilization, and CBD Revenue.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Composer Dependency | `composer.json` (line 16: `"maatwebsite/excel": "^3.1"`) |
| Excel Config | `config/excel.php` |
| Booking Statistics Export | `app/Exports/BookingStatisticsExport.php` |
| Citizen Analytics Export | `app/Exports/CitizenAnalyticsExport.php` |
| Facility Utilization Export | `app/Exports/FacilityUtilizationExport.php` |
| CBD Revenue Export | `app/Exports/CbdRevenueExport.php` |

**Tests to Perform:**
1. Admin → Analytics → Booking Statistics → Click "Export Excel"
2. Admin → Analytics → Citizen Analytics → Click "Export Excel"
3. Admin → Analytics → Facility Utilization → Click "Export Excel"
4. Open downloaded `.xlsx` file → show formatted data

---

### 7.2 PDF Export

**Explanation:**
The system uses **Barryvdh/Laravel-DomPDF** package for PDF generation. PDFs are generated for: audit trails, official receipts, daily collections reports, and analytics exports.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Composer Dependency | `composer.json` (line 9: `"barryvdh/laravel-dompdf": "^3.1"`) |
| DomPDF Config | `config/dompdf.php` |
| Audit Trail PDF Export | `app/Http/Controllers/Admin/AuditTrailController.php` (lines 120–151) |
| Audit Trail PDF Template | `resources/views/admin/audit-trail/pdf` |
| Analytics Audit PDF | `resources/views/admin/analytics/exports/` |
| Treasurer Daily Collections PDF | `app/Http/Controllers/Treasurer/ReportController.php` (lines 88+) |
| Treasurer Report PDF Views | `resources/views/treasurer/reports/` |

**Tests to Perform:**
1. Admin → Audit Trail → Click "Export PDF"
2. Treasurer → Daily Collections → Click "Export PDF"
3. Open downloaded PDF → show formatted report with headers and data

---

### 7.3 Official Receipts

**Explanation:**
The Treasurer can generate official receipts (OR) for verified payments. Each receipt has a unique OR number, transaction reference, payment details, facility information, and can be downloaded as a PDF.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Official Receipt Controller | `app/Http/Controllers/Treasurer/OfficialReceiptController.php` |
| OR List View | `resources/views/treasurer/official-receipts/index.blade.php` |
| OR Detail View | `resources/views/treasurer/official-receipts/show.blade.php` |
| OR PDF Template | `resources/views/treasurer/official-receipts/pdf.blade.php` (generated in `print()` method) |

**Tests to Perform:**
1. Login as Treasurer → Official Receipts → browse receipts
2. Click a receipt → show detail page with all payment info
3. Click "Print/Download PDF" → download official receipt PDF
4. Show OR number, transaction reference, amount, facility name on the PDF

---

### 7.4 Report Filtering

**Explanation:**
All reports and exports support filtering by: date range, search keywords, payment method, status, and more. The Audit Trail supports filtering by event type, log module, user, and date range. The Treasurer reports support filtering by date.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Audit Trail Filtering | `app/Http/Controllers/Admin/AuditTrailController.php` (lines 17–41) |
| OR Filtering | `app/Http/Controllers/Treasurer/OfficialReceiptController.php` (lines 35–50) |
| Daily Collections Filtering | `app/Http/Controllers/Treasurer/ReportController.php` (line 18) |
| CSV Export with Filters | `app/Http/Controllers/Admin/AuditTrailController.php` (lines 63–117) |
| Citizen Data Export | `app/Http/Controllers/Citizen/SecurityController.php` (line 300: `requestDataDownload()`) |

**Tests to Perform:**
1. Admin → Audit Trail → filter by date range → export filtered results
2. Treasurer → Official Receipts → search by OR number or name
3. Treasurer → Daily Collections → select a date → show filtered report
4. Export filtered data to Excel/PDF → confirm filters are applied

---

## 8. USER INTERFACE (UI) LOOK AND FEEL (10%)

### 8.1 TailwindCSS

**Explanation:**
The entire UI is built with **TailwindCSS v4.1.16** with a custom Golden Ratio-based design system. Custom theme includes: LGU brand colors, Fibonacci-based spacing scale (`gr-xs` through `gr-3xl`), Golden Ratio typography scale, and Poppins font family.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Tailwind Config (144 lines) | `tailwind.config.js` |
| Package.json (Tailwind dependency) | `package.json` (line 18: `"tailwindcss": "^4.1.16"`) |
| PostCSS Config | `postcss.config.js` |
| Vite Config | `vite.config.js` |
| CSS Entry Point | `resources/css/app.css` |

**Tests to Perform:**
1. Open `tailwind.config.js` → show custom LGU colors, Golden Ratio spacing, typography scale
2. Inspect any page element → show Tailwind utility classes in action
3. Show the consistent color scheme across all pages (dark green `#00473e`, gold `#faae2b`, pink `#ffa8ba`)

---

### 8.2 Lucide Icons

**Explanation:**
The system uses **Lucide Icons v0.552.0** (open-source icon library) throughout the entire UI. Icons are rendered via `<i data-lucide="icon-name">` tags and initialized with `lucide.createIcons()`.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Package.json (Lucide dependency) | `package.json` (line 23: `"lucide": "^0.552.0"`) |
| Icon Usage (every layout) | All `resources/views/layouts/*.blade.php` files |
| Icon Initialization | Every view calls `lucide.createIcons()` |

**Tests to Perform:**
1. Open any page → show Lucide icons in sidebar, buttons, cards
2. Inspect an icon element → show `<i data-lucide="...">` pattern
3. Show icons used in: navigation, action buttons, status badges, analytics cards

---

### 8.3 Responsive Design

**Explanation:**
All pages use Tailwind's responsive prefixes (`sm:`, `md:`, `lg:`, `xl:`) for mobile-first responsive design. Sidebars collapse on mobile with hamburger menu. Tables use horizontal scroll on small screens. The booking wizard adapts to mobile layout.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Responsive Layouts | All `resources/views/layouts/*.blade.php` |
| Mobile Sidebar Scripts | `resources/views/components/sidebar/` (12 sidebar components) |
| Responsive Browse Facilities | `resources/views/citizen/browse-facilities.blade.php` |
| Offline Fallback (PWA) | `resources/views/offline.blade.php` |

**Tests to Perform:**
1. Open browser DevTools → toggle device toolbar → show mobile layout
2. Show sidebar collapsing to hamburger menu on mobile
3. Show booking wizard adapting to mobile screens
4. Show facility cards stacking vertically on mobile

---

### 8.4 SweetAlert2

**Explanation:**
**SweetAlert2** is used across **77+ blade files** (419+ instances) for user-friendly confirmation dialogs, success messages, error alerts, and interactive prompts. Used for: booking confirmations, delete confirmations, payment verifications, status changes, and form submissions.

**File Locations:**

| Component | Example Files |
|-----------|--------------|
| Login/Register Alerts | `resources/views/auth/login.blade.php`, `resources/views/auth/register.blade.php` |
| Booking Alerts | `resources/views/citizen/booking/step3-review-submit.blade.php` |
| Admin Confirmations | `resources/views/admin/energy-facility-requests/index.blade.php` |
| Security Alerts | `resources/views/citizen/security/index.blade.php` |
| Staff Verifications | `resources/views/staff/bookings/review.blade.php` |
| Treasurer Alerts | `resources/views/treasurer/payment-verification/show.blade.php` |

**Tests to Perform:**
1. Try deleting/rejecting a booking → show SweetAlert2 confirmation dialog
2. Complete a form submission → show SweetAlert2 success toast
3. Try an invalid action → show SweetAlert2 error message
4. Show the beautiful modal animations (slide-in, fade)

---

### 8.5 Component-Based UI

**Explanation:**
The UI follows a component-based architecture with reusable Blade components: layouts (6 role-specific), sidebars (12 components), notification bell, announcement banner, offline indicator, and headers.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Layouts (6 roles) | `resources/views/layouts/` (admin, citizen, staff, treasurer, superadmin, cbd) |
| Sidebar Components (12) | `resources/views/components/sidebar/` |
| Notification Bell | `resources/views/components/notification-bell.blade.php` |
| Announcement Banner | `resources/views/components/announcement-banner.blade.php` |
| Offline Indicator | `resources/views/components/offline-indicator.blade.php` |
| Header Components | `resources/views/components/header/` |
| Email Templates (21) | `resources/views/emails/` |
| Partials | `resources/views/partials/` |

**Tests to Perform:**
1. Show 6 different layout files for 6 roles
2. Show reusable notification bell component across all layouts
3. Show announcement banner appearing on all pages when active
4. Show offline indicator when internet connection is lost

---

### 8.6 Multi-Step Booking Wizard

**Explanation:**
The booking process uses a **3-step wizard interface** with progress indicators: Step 1 (Select Date/Time with interactive calendar) → Step 2 (Select Equipment with quantities) → Step 3 (Review all details with pricing breakdown & submit). Each step validates before proceeding.

**File Locations:**

| Step | File Path |
|------|-----------|
| Step 1: Date/Time Selection (85K) | `resources/views/citizen/booking/step1-select-datetime.blade.php` |
| Step 2: Equipment Selection (16K) | `resources/views/citizen/booking/step2-select-equipment.blade.php` |
| Step 3: Review & Submit (73K) | `resources/views/citizen/booking/step3-review-submit.blade.php` |
| Confirmation | `resources/views/citizen/booking/confirmation.blade.php` |

**Tests to Perform:**
1. Start a booking → show step progress indicator (1 → 2 → 3)
2. Complete Step 1 → show calendar with available/booked time slots
3. Complete Step 2 → show equipment selection with prices
4. Complete Step 3 → show full pricing breakdown with discounts → submit

---

### 8.7 Modern Dashboard

**Explanation:**
Each role has a modern, data-rich dashboard with: statistics cards, charts (Chart.js), recent activity feeds, quick action buttons, and real-time data. The admin dashboard (23K) includes booking stats, revenue, pending items, and calendar preview.

**File Locations:**

| Role | File Path |
|------|-----------|
| Admin Dashboard (23K) | `resources/views/admin/dashboard.blade.php` |
| Citizen Dashboard (12K) | `resources/views/citizen/dashboard.blade.php` |
| Treasurer Dashboard (12K) | `resources/views/treasurer/dashboard.blade.php` |
| CBD Dashboard | `resources/views/cbd/` |
| Staff Dashboard | `resources/views/staff/` |
| Super Admin Dashboard | `resources/views/superadmin/` |
| Landing Page (31K) | `resources/views/welcome.blade.php` |

**Tests to Perform:**
1. Login as each role → show their unique dashboard with stats and charts
2. Show the landing page (welcome.blade.php) with public-facing design
3. Show Chart.js visualizations (bar, line, doughnut charts)

---

### 8.8 Consistent Theming

**Explanation:**
All pages follow the LGU1 brand identity defined in `tailwind.config.js`: Dark Green primary (`#00473e`), Gold highlights (`#faae2b`), Pink accents (`#ffa8ba`), Light Mint backgrounds (`#f2f7f5`), and Poppins font throughout. Golden Ratio spacing ensures visual harmony.

**File Locations:**

| Component | File Path |
|-----------|-----------|
| Brand Colors | `tailwind.config.js` (lines 92–118) |
| Font Family | `tailwind.config.js` (lines 120–123: Poppins) |
| Golden Ratio Spacing | `tailwind.config.js` (lines 59–89) |
| Golden Ratio Typography | `tailwind.config.js` (lines 38–57) |

**Tests to Perform:**
1. Navigate through multiple pages → show consistent color scheme
2. Show `tailwind.config.js` → LGU brand colors, Poppins font, Golden Ratio system
3. Compare Admin, Citizen, and Treasurer dashboards → same visual language

---

## QUICK REFERENCE: FILE STRUCTURE SUMMARY

```
app/
├── Console/                     # Artisan commands
├── Exports/                     # Excel exports (4 files)
│   ├── BookingStatisticsExport.php
│   ├── CbdRevenueExport.php
│   ├── CitizenAnalyticsExport.php
│   └── FacilityUtilizationExport.php
├── Helpers/                     # Disposable email blocker
├── Http/
│   ├── Controllers/
│   │   ├── Admin/               # 42 admin controllers
│   │   ├── Api/                 # 7 API controllers
│   │   ├── Auth/                # Google OAuth
│   │   ├── CBD/                 # 2 CBD controllers
│   │   ├── Citizen/             # 21 citizen controllers
│   │   ├── Staff/               # 10 staff controllers
│   │   ├── SuperAdmin/          # 1 super admin controller
│   │   └── Treasurer/           # 5 treasurer controllers
│   └── Middleware/              # 6 custom middleware
│       ├── AutoExpireBookings.php
│       ├── CheckAuth.php
│       ├── CheckRole.php
│       ├── CheckSessionTimeout.php
│       ├── ValidateApiKey.php
│       └── ValidateSignedAdminUrl.php
├── Mail/                        # 8 email templates
├── Models/                      # 39 Eloquent models
├── Notifications/               # 18 notification classes
├── Services/                    # 5 service classes
│   ├── FaceVerificationService.php
│   ├── PaymongoService.php
│   ├── PricingCalculatorService.php
│   ├── RoadTransportApiService.php
│   └── UtilityBillingApiService.php
└── Traits/                      # 2 traits

config/
├── app.php                      # AES-256-CBC, timezone, env
├── auth.php                     # Authentication config
├── backup.php                   # Spatie backup (dual DB)
├── database.php                 # Multi-DB (auth_db + facilities_db)
├── dompdf.php                   # PDF generation config
├── excel.php                    # Excel export config
├── payment.php                  # 7 payment channels + PayMongo
├── services.php                 # External API configs
└── session.php                  # DB sessions, secure cookies

resources/views/
├── admin/                       # 87 admin views
│   ├── analytics/               # 10 analytics views + AI forecasting
│   ├── audit-trail/             # 3 views + PDF export
│   ├── backup/                  # Backup management
│   ├── bookings/                # Booking management
│   └── ...
├── auth/                        # Login (33K), Register (77K), Forgot Password
├── citizen/                     # 48 citizen views
│   ├── booking/                 # 3-step wizard + confirmation
│   ├── security/                # MFA, 2FA, trusted devices, login history
│   └── ...
├── components/                  # 18 reusable components
├── layouts/                     # 6 role-specific layouts + 4 others
├── staff/                       # 12 staff views
└── treasurer/                   # 13 treasurer views
    ├── official-receipts/       # OR list, detail, PDF
    └── reports/                 # Daily collections, exports

routes/
├── api.php                      # All API routes (399 lines)
└── web.php                      # All web routes (2184 lines)
```

---

## PANEL DEMO SCRIPT (Recommended Order)

1. **Start** → Show live domain with SSL certificate
2. **Landing Page** → Show public facilities directory
3. **Register** as Citizen (show email validation, OTP, privacy consent)
4. **Login** → Demonstrate OTP → 2FA → Trusted Device flow
5. **Browse Facilities** → View details, calendar, reviews
6. **Book a Facility** → Complete 3-step wizard with pricing calculator
7. **Switch to Staff** → Verify the booking
8. **Switch to Admin** → Approve the booking
9. **Show Payment** → Citizen pays via GCash/Maya → Treasurer verifies → Issue OR
10. **Analytics** → Walk through all 7 analytics pages + AI Forecasting
11. **Export** → Download Excel, PDF, Official Receipt
12. **Security** → Show audit trail, login history, session management
13. **API** → Hit 3-4 API endpoints in Postman
14. **Server** → Show `config/app.php` (AES-256, timezone), `config/database.php` (multi-DB), `config/backup.php`
