<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// IMPORTANT: This line allows the API to talk to your Database model
use App\Models\FundRequest;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Facility Reservation API (Existing Routes - DO NOT REMOVE)
|--------------------------------------------------------------------------
*/
Route::prefix('facility-reservation')->group(function () {
    Route::get('/facilities', [\App\Http\Controllers\Api\FacilityReservationApiController::class, 'listFacilities']);
    Route::get('/equipment', [\App\Http\Controllers\Api\FacilityReservationApiController::class, 'listEquipment']);
    Route::get('/check-availability', [\App\Http\Controllers\Api\FacilityReservationApiController::class, 'checkAvailability']);
    Route::get('/status/{reference}', [\App\Http\Controllers\Api\FacilityReservationApiController::class, 'checkStatus']);
    Route::post('/', [\App\Http\Controllers\Api\FacilityReservationApiController::class, 'store']);
    Route::post('/payment-complete', [\App\Http\Controllers\Api\FacilityReservationApiController::class, 'paymentComplete']);
});

/*
|--------------------------------------------------------------------------
| Energy Fund Bridge API (New Fixed Routes)
|--------------------------------------------------------------------------
*/

// This RECEIVES the fund request from the Energy Portal
Route::post('/receive-funds', function (Request $request) {
    // Laravel requires 'user_id', 'logistics', and 'status' to be fillable in FundRequest.php
    $newRequest = FundRequest::create([
        'requester_name' => $request->requester_name, 
        'user_id'        => $request->user_id,
        'amount'         => $request->amount,
        'purpose'        => $request->purpose,
        'logistics'      => $request->logistics ?? 'None',
        'status'         => 'pending'
    ]);

    if($newRequest) {
        return response()->json(['status' => 'success', 'id' => $newRequest->id]);
    }
    return response()->json(['status' => 'error'], 500);
});

// This allows the Energy Portal to CHECK if the Admin clicked Approved or Rejected
Route::get('/check-status/{id}', function ($id) {
    $fund = FundRequest::find($id);
    if ($fund) {
        return response()->json([
            'status' => $fund->status,
            'feedback' => $fund->feedback,
            'requester_name' => $fund->requester_name
        ]);
    }
    return response()->json(['status' => 'not_found'], 404);
});