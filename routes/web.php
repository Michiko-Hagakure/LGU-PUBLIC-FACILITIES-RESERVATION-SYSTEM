// resources/routes/web.php

<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\CitizenAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider within a group
| which contains the "web" middleware group.
|
*/

// --- Load Feature-Specific Route Files ---
require __DIR__ . '/general.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/staff.php';
require __DIR__ . '/citizen.php';

// --- General Access Routes ---

// Logout Route (for all authenticated users: Admin, Staff, Citizen)
Route::post('/logout', [CitizenAuthController::class, 'logout'])->name('logout');


// --- Alternative/Legacy Access Routes (Redirects) ---
// These routes redirect to the protected admin paths if the user is authenticated as an admin.

Route::get('/facilities', function() {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('facility.list');
    }
    return redirect()->route('admin.dashboard');
});

Route::get('/facilities/{id}', function($id) {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('facility.list')->with('edit_facility', $id);
    }
    return redirect()->route('admin.dashboard');
})->name('facilities.show');

// Note: This closure manually calls the Controller, which is kept to maintain original functionality.
Route::put('/facilities/{id}', function(Request $request, $id) {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return app(\App\Http\Controllers\FacilityController::class)->update($request, $id);
    }
    return redirect()->route('admin.dashboard');
});

Route::get('/calendar', function() {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('calendar');
    }
    return redirect()->route('admin.dashboard');
});

// --- Home Route (Main Entry Point) ---
Route::get('/', function () {
    if (Auth::check() && Auth::user()->role === 'citizen') {
        return redirect()->route('citizen.dashboard');
    }
    // Redirect unauthenticated users or other roles to the main login page/redirect
    return redirect()->route('login');
})->name('home');

// (Commented out the old duplicated home route)
// Route::get('/', function () {
//    return redirect()->route('citizen.dashboard');
// })->name('home');