<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Booking;
use App\Services\FacilityService; // Requires you to create this service class
use App\Services\BookingService; // Requires you to create this service class
use App\Services\AIRecommendationService; // Keep the existing service
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\StoreFacilityRequest; // Requires you to create this file
use App\Http\Requests\Admin\UpdateFacilityRequest; // Requires you to create this file
use App\Http\Requests\Citizen\StoreReservationRequest; // Requires you to create this file
use App\Http\Requests\Citizen\CheckAIRecommendationRequest; // Requires you to create this file

class FacilityController extends Controller
{
    protected FacilityService $facilityService;
    protected BookingService $bookingService;

    public function __construct(FacilityService $facilityService, BookingService $bookingService)
    {
        // Dependency Injection for clean code
        $this->facilityService = $facilityService;
        $this->bookingService = $bookingService;
    }
    
    // --- Admin Facility CRUD ---

    /**
     * Display a listing of the facilities (Admin view).
     */
    public function index()
    {
        // The complex session/file logic is moved to FacilityService
        $facilities = $this->facilityService->getFacilitiesWithSessionFallback();
        
        Log::info('FacilityController loaded ' . $facilities->count() . ' facilities.');
        
        return response()
            ->view('FacilityList', compact('facilities'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    /**
     * Store a newly created facility.
     */
    public function store(StoreFacilityRequest $request)
    {
        // Validation is handled by StoreFacilityRequest
        $facility = $this->facilityService->createFacility($request->validated());
        
        return redirect()->route('facility.list')->with('success', 'Facility created successfully!');
    }

    /**
     * Update the specified facility.
     */
    public function update(UpdateFacilityRequest $request, int $facility_id)
    {
        // Validation is handled by UpdateFacilityRequest
        $this->facilityService->updateFacility($facility_id, $request->validated());
        
        return redirect()->route('facility.list')->with('success', 'Facility updated successfully!');
    }

    /**
     * Remove the specified facility.
     */
    public function destroy(int $facility_id)
    {
        $this->facilityService->deleteFacility($facility_id);
        
        return redirect()->route('facility.list')->with('success', 'Facility deleted successfully!');
    }
    
    // --- Admin Booking Management ---

    /**
     * Display the approval dashboard for bookings.
     */
    public function approvalDashboard()
    {
        $bookings = $this->bookingService->getPendingBookings();
        return view('admin.booking.approval', compact('bookings'));
    }
    
    /**
     * Store a booking (usually for Admin/Staff on behalf of a citizen).
     */
    public function storeBooking(Request $request)
    {
        // NOTE: If this is an Admin function, you should use a dedicated AdminRequest
        $booking = $this->bookingService->storeAdminBooking($request->all());
        return redirect()->back()->with('success', 'Booking created successfully!');
    }

    /**
     * Approve a booking.
     */
    public function approveBooking(int $id)
    {
        $this->bookingService->approveBooking($id, Auth::id());
        return redirect()->back()->with('success', 'Booking approved!');
    }

    /**
     * Reject a booking.
     */
    public function rejectBooking(int $id, Request $request)
    {
        $request->validate(['rejection_reason' => 'required|string']);
        $this->bookingService->rejectBooking($id, $request->rejection_reason, Auth::id());
        return redirect()->back()->with('success', 'Booking rejected!');
    }
    
    // --- Citizen Reservation (with AI) ---

    /**
     * Store a new reservation request (Citizen portal) with AI check.
     * Uses StoreReservationRequest for validation.
     */
    public function storeReservationWithAI(StoreReservationRequest $request)
    {
        // Validation is handled by StoreReservationRequest
        $reservation = $this->bookingService->storeCitizenReservationWithAI($request->validated(), Auth::user());
        
        // Redirect logic moved to service, but here is the final response
        return redirect()->route('citizen.reservations')->with('success', 
            'Reservation request submitted! Pending approval. Total Fee: ' . number_format($reservation->total_fee, 2)
        );
    }
    
    // --- Calendar and Events (Kept Simple) ---

    public function calendar()
    {
        $facilities = Facility::all();
        return view('admin.calendar', compact('facilities'));
    }

    public function getAllEvents()
    {
        return response()->json($this->bookingService->getAllApprovedEvents());
    }

    public function getEvents(int $facility_id)
    {
        return response()->json($this->bookingService->getFacilityEvents($facility_id));
    }
    
    // --- AI Integration (Kept as original with Type Hinting) ---
    
    /**
     * API endpoint to get AI recommendations for reservation time/facility.
     */
    public function getAIRecommendations(CheckAIRecommendationRequest $request, AIRecommendationService $aiService)
    {
        try {
            // Validation is handled by CheckAIRecommendationRequest
            $validated = $request->validated();
            
            $recommendations = $aiService->getRecommendations(
                $validated['facility_id'],
                $validated['event_date'],
                $validated['start_time'],
                $validated['end_time'],
                $validated['expected_attendees'],
                $validated['event_type'] ?? 'general'
            );

            return response()->json($recommendations);

        } catch (\Exception $e) {
            Log::error('AI Recommendations API Error:', ['message' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => 'Unable to fetch recommendations at this time.'], 500);
        }
    }

    /**
     * Test the AI recommendation system (Admin only)
     */
    public function testAISystem(AIRecommendationService $aiService)
    {
        try {
            $testResult = $aiService->testRecommendationSystem();

            return response()->json(['status' => 'success', 'message' => 'AI System test completed', 'test_result' => $testResult]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'AI System test failed: ' . $e->getMessage()], 500);
        }
    }
    
    // --- Legacy and Dummy Routes (Kept for backward compatibility) ---
    public function newReservation() { /* ... */ }
    public function storeReservation(Request $request) { /* ... */ }
    public function reservationStatus() { /* ... */ }
    public function showUserBookings() { /* ... */ } // Placeholder
}