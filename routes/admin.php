// resources/routes/admin.php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Admin\ReservationReviewController;
use App\Http\Controllers\Admin\ScheduleConflictController;
use App\Http\Controllers\PaymentSlipController;
use App\Http\Controllers\Admin\MaintenanceLogController;
use App\Http\Controllers\Admin\MonthlyReportController;
use App\Http\Controllers\Admin\CitizenFeedbackController;
use App\Http\Controllers\Admin\CityEventController;

// ============================================
// ADMIN PORTAL ROUTES (Protected by 'admin.auth')
// ============================================

Route::prefix('admin')->middleware('admin.auth')->group(function () {
     // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/quick-stats', [AdminDashboardController::class, 'getQuickStats'])->name('admin.dashboard.quick-stats');

    // Facility Management (CRUD)
    Route::get('/facilities', [FacilityController::class, 'index'])->name('facility.list');
    Route::post('/facilities', [FacilityController::class, 'store'])->name('facilities.store');
    Route::put('/facilities/{facility_id}', [FacilityController::class, 'update'])->name('facilities.update');
    Route::delete('/facilities/{facility_id}', [FacilityController::class, 'destroy'])->name('facilities.destroy');

    // Booking Management
    Route::get('/bookings/approval', [FacilityController::class, 'approvalDashboard'])->name('bookings.approval');
    Route::post('/bookings', [FacilityController::class, 'storeBooking'])->name('bookings.store');
    Route::post('/bookings/{id}/approve', [FacilityController::class, 'approveBooking'])->name('bookings.approve');
    Route::post('/bookings/{id}/reject', [FacilityController::class, 'rejectBooking'])->name('bookings.reject');
    
    // Calendar and Events
    Route::get('/calendar', [FacilityController::class, 'calendar'])->name('calendar');
    Route::get('/calendar/all-events', [FacilityController::class, 'getAllEvents'])->name('calendar.all-events');
    Route::get('/facilities/{facility_id}/events', [FacilityController::class, 'getEvents'])->name('facilities.events');

    // Reports and Analytics
    Route::get('/ai-forecast', [FacilityController::class, 'forecast'])->name('forecast');
    Route::get('/api/usage-data', [AnalyticsController::class, 'getUsageData'])->name('admin.api.usage_data');
    Route::get('/reservation-status', [FacilityController::class, 'showUserBookings'])->name('reservation.status');
    Route::get('/test-ai', [FacilityController::class, 'testAISystem'])->name('admin.test.ai');

    // Announcement Management (CRUD and Status Toggles)
    Route::get('/announcements', [AnnouncementController::class, 'adminIndex'])->name('admin.announcements.index');
    Route::get('/announcements/create', [AnnouncementController::class, 'create'])->name('admin.announcements.create');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('admin.announcements.store');
    Route::get('/announcements/{id}/edit', [AnnouncementController::class, 'edit'])->name('admin.announcements.edit');
    Route::put('/announcements/{id}', [AnnouncementController::class, 'update'])->name('admin.announcements.update');
    Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy'])->name('admin.announcements.destroy');
    Route::post('/announcements/{id}/toggle-status', [AnnouncementController::class, 'toggleStatus'])->name('admin.announcements.toggle-status');
    Route::post('/announcements/{id}/toggle-pin', [AnnouncementController::class, 'togglePin'])->name('admin.announcements.toggle-pin');

    // Reservation Review Management
    Route::get('/reservations', [ReservationReviewController::class, 'index'])->name('admin.reservations.index');
    Route::get('/reservations/{id}', [ReservationReviewController::class, 'show'])->name('admin.reservations.show');
    Route::post('/reservations/{id}/approve', [ReservationReviewController::class, 'approve'])->name('admin.reservations.approve');
    Route::post('/reservations/{id}/reject', [ReservationReviewController::class, 'reject'])->name('admin.reservations.reject');
    Route::get('/reservations/{id}/document/{type}/download', [ReservationReviewController::class, 'downloadDocument'])->name('admin.reservations.download');
    Route::get('/reservations/{id}/document/{type}/preview', [ReservationReviewController::class, 'previewDocument'])->name('admin.reservations.preview');

    // Schedule Conflict Management
    Route::get('/schedule-conflicts', [ScheduleConflictController::class, 'index'])->name('admin.schedule.conflicts');

    // Maintenance Log Management (Using Route::resource for brevity)
    Route::resource('maintenance-logs', MaintenanceLogController::class)->except(['destroy']);
    Route::post('/maintenance-logs/{id}/update-status', [MaintenanceLogController::class, 'updateStatus'])->name('admin.maintenance-logs.update-status');
    Route::delete('/maintenance-logs/{id}', [MaintenanceLogController::class, 'destroy'])->name('admin.maintenance-logs.destroy');

    // Monthly Reports
    Route::get('/monthly-reports', [MonthlyReportController::class, 'index'])->name('admin.monthly-reports.index');
    Route::get('/monthly-reports/export', [MonthlyReportController::class, 'export'])->name('admin.monthly-reports.export');

    // Citizen Feedback Management
    Route::get('/feedback', [CitizenFeedbackController::class, 'index'])->name('admin.feedback.index');
    Route::get('/feedback/{id}', [CitizenFeedbackController::class, 'show'])->name('admin.feedback.show');
    Route::patch('/feedback/{id}/status', [CitizenFeedbackController::class, 'updateStatus'])->name('admin.feedback.update-status');
    Route::post('/feedback/{id}/respond', [CitizenFeedbackController::class, 'respond'])->name('admin.feedback.respond');
    Route::delete('/feedback/{id}', [CitizenFeedbackController::class, 'destroy'])->name('admin.feedback.destroy');

    // City Event Management (Mayor Authorized) - Using Route::resource
    Route::resource('city-events', CityEventController::class);

    // Payment Slip Management
    Route::get('/payment-slips', [PaymentSlipController::class, 'adminIndex'])->name('admin.payment-slips.index');
    Route::post('/payment-slips/{id}/mark-paid', [PaymentSlipController::class, 'markAsPaid'])->name('admin.payment-slips.mark-paid');
    Route::post('/payment-slips/mark-expired', [PaymentSlipController::class, 'markExpired'])->name('admin.payment-slips.mark-expired');

    // Legacy routes (kept for backward compatibility)
    Route::get('/new-reservation', [FacilityController::class, 'newReservation'])->name('new-reservation');
    Route::post('/reservations', [FacilityController::class, 'storeReservation'])->name('reservations.store');
    Route::get('/reservations/status', [FacilityController::class, 'reservationStatus'])->name('reservations.status');
});