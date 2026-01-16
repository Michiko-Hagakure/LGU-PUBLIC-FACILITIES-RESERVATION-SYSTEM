<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ExternalIntegrationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| External Integration API Routes
| For Energy Efficiency System Communication
|--------------------------------------------------------------------------
*/

Route::prefix('v1/external')->middleware('api.key')->group(function () {
    
    // Inbound: Energy Efficiency sends facility booking requests to us
    Route::post('/government-programs/request', [ExternalIntegrationController::class, 'receiveBookingRequest'])
        ->name('api.external.receive-booking-request');
    
    // Outbound: Energy Efficiency queries our available facilities
    Route::get('/facilities/available', [ExternalIntegrationController::class, 'getAvailableFacilities'])
        ->name('api.external.available-facilities');
    
    // Outbound: Energy Efficiency checks booking status
    Route::get('/government-programs/{bookingId}/status', [ExternalIntegrationController::class, 'getBookingStatus'])
        ->name('api.external.booking-status');
    
    // Outbound: We send confirmation back to Energy Efficiency
    Route::post('/government-programs/{bookingId}/confirm', [ExternalIntegrationController::class, 'confirmBooking'])
        ->name('api.external.confirm-booking');
    
    // Health check endpoint
    Route::get('/health', function () {
        return response()->json([
            'status' => 'online',
            'system' => 'LGU1 Public Facilities',
            'timestamp' => now()->toIso8601String()
        ]);
    })->name('api.external.health');
});
