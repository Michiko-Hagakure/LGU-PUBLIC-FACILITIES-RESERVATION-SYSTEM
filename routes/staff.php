// resources/routes/staff.php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SsoController;
use App\Http\Controllers\Staff\RequirementVerificationController;
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Staff\HelpSupportController;

// ============================================
// STAFF PORTAL ROUTES
// ============================================

Route::prefix('staff')->middleware('web')->group(function () {
    // Staff Dashboard (Main Overview) - Handles SSO authentication
    Route::get('/dashboard', [SsoController::class, 'handleStaffDashboard'])->name('staff.dashboard');
    
    // Booking Requirement Verification
    Route::get('/verification', [RequirementVerificationController::class, 'index'])->name('staff.verification.index');
    Route::get('/verification/{booking}', [RequirementVerificationController::class, 'show'])->name('staff.verification.show');
    Route::post('/verification/{booking}/approve', [RequirementVerificationController::class, 'approve'])->name('staff.verification.approve');
    Route::post('/verification/{booking}/reject', [RequirementVerificationController::class, 'reject'])->name('staff.verification.reject');
    
    // Staff Statistics and Reports
    Route::get('/my-stats', [StaffDashboardController::class, 'myStats'])->name('staff.stats');
    
    // Staff Help & Support
    Route::get('/help-support', [HelpSupportController::class, 'index'])->name('staff.help-support');
    Route::post('/help-support/submit-issue', [HelpSupportController::class, 'submitIssue'])->name('staff.help-support.submit-issue');
});