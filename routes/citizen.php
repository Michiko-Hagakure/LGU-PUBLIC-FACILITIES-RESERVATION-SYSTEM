// resources/routes/citizen.php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitizenDashboardController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\PaymentSlipController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\Auth\CitizenAuthController;
use App\Http\Controllers\Citizen\HelpFaqController;
use App\Http\Controllers\Citizen\BookingExtensionController;


// ============================================
// CITIZEN PORTAL ROUTES (Protected by 'sso' and 'citizen' middleware)
// ============================================

Route::middleware(['web', 'sso', 'citizen'])->prefix('citizen')->name('citizen.')->group(function () {
    
    Route::get('/dashboard', [CitizenDashboardController::class, 'index'])->name('dashboard');
    
    // Routes requiring the Laravel 'auth' check (auth:web)
    Route::middleware('auth:web')->group(function () {
        
        // General Citizen Dashboard Routes
        Route::get('/reservations', [CitizenDashboardController::class, 'reservations'])->name('reservations');
        Route::get('/reservation-history', [CitizenDashboardController::class, 'reservationHistory'])->name('reservation.history');
        Route::get('/availability', [CitizenDashboardController::class, 'viewAvailability'])->name('availability');
        
        // API Endpoints
        Route::get('/api/facility/{facility_id}/bookings', [CitizenDashboardController::class, 'getFacilityBookings'])->name('api.facility.bookings');
        Route::get('/api/all-facility-bookings', [CitizenDashboardController::class, 'getAllFacilityBookings'])->name('api.all.facility.bookings');
        Route::post('/api/recommendations', [FacilityController::class, 'getAIRecommendations'])->name('api.recommendations');
        
        // Bulletin/Announcement
        Route::get('/bulletin-board', [AnnouncementController::class, 'citizenIndex'])->name('bulletin.board');
        Route::get('/announcements/{id}/download', [AnnouncementController::class, 'downloadAttachment'])->name('announcements.download');
        
        // Payment Slips
        Route::get('/payment-slips', [PaymentSlipController::class, 'citizenIndex'])->name('payment-slips.index');
        Route::get('/payment-slips/{id}', [PaymentSlipController::class, 'citizenShow'])->name('payment-slips.show');
        Route::get('/payment-slips/{id}/download', [PaymentSlipController::class, 'citizenDownloadPdf'])->name('payment-slips.download');
        
        // Profile Management
        Route::get('/profile', [CitizenDashboardController::class, 'profile'])->name('profile');
        Route::put('/profile', [CitizenDashboardController::class, 'updateProfile'])->name('profile.update');
        
        // Help & FAQ
        Route::get('/help-faq', [HelpFaqController::class, 'index'])->name('help-faq');
        Route::post('/help-faq/submit', [HelpFaqController::class, 'submitQuestion'])->name('help-faq.submit');
        
        // Two-Factor Authentication Routes
        Route::get('/security/setup-2fa', [CitizenAuthController::class, 'showTwoFactorSetup'])->name('security.setup-2fa');
        Route::post('/security/enable-2fa', [CitizenAuthController::class, 'enableTwoFactor'])->name('security.enable-2fa');
        
        // AI-Enhanced reservation store route
        Route::post('/reservations/store', [FacilityController::class, 'storeReservationWithAI'])->name('reservations.store');
        
        // Booking Extension Routes
        Route::post('/bookings/{booking}/check-extension-conflict', [BookingExtensionController::class, 'checkConflict'])->name('bookings.check-extension');
        Route::post('/bookings/{booking}/extend', [BookingExtensionController::class, 'extend'])->name('bookings.extend');
    
    });
    
    // Citizen Logout (does not require prior authentication check, only web middleware)
    Route::post('/logout', [CitizenAuthController::class, 'logout'])->name('logout');
});