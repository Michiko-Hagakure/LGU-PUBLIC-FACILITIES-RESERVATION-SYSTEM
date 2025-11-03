// resources/routes/general.php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SsoController;

// ============================================
// SSO AUTHENTICATION ROUTES
// ============================================

Route::middleware(['web'])->group(function () {
     Route::get('/sso/login', [SsoController::class, 'login'])->name('sso.login');
 });

// Helpful redirect for users who access the system directly via /login
 Route::get('/login', function() {
     return redirect()->away('https://local-government-unit-1-ph.com/public/login.php');
 })->name('login');