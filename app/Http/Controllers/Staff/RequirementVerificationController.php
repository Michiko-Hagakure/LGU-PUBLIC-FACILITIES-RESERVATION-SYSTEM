<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking; // Still needed for model references/type hinting
use App\Services\StaffBookingService; // Requires you to create this service
use App\Http\Requests\Staff\RejectBookingRequest; // Requires you to create this file
use App\Http\Requests\Staff\ApproveBookingRequest; // Requires you to create this file
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RequirementVerificationController extends Controller
{
    protected StaffBookingService $bookingService;

    /**
     * Inject StaffBookingService into the controller.
     */
    public function __construct(StaffBookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }
    
    /**
     * Display list of bookings pending staff verification (from service/DB).
     */
    public function index(Request $request)
    {
        // Logic to retrieve and filter bookings is moved to the service
        $bookings = $this->bookingService->getPendingVerifications($request->all());

        // Note: The view still needs to handle the array/collection structure returned by the service
        return view('staff.verification.index', compact('bookings'));
    }

    /**
     * Display booking details for verification.
     */
    public function show(int $id)
    {
        // Use the service to find the booking from the persistent storage
        $booking = $this->bookingService->findVerificationBooking($id);
        
        if (!$booking) {
            return redirect()->route('staff.verification.index')
                ->with('error', 'Booking not found or already processed.');
        }

        // The booking object structure should match the view's expectation
        return view('staff.verification.show', compact('booking'));
    }
    
    /**
     * Process staff approval for booking requirements.
     * Uses ApproveBookingRequest for validation.
     */
    public function approve(ApproveBookingRequest $request, int $id)
    {
        // Validation handled by ApproveBookingRequest
        $staffId = Auth::id() ?? 1; // Use authenticated ID or fallback
        
        try {
            // Business logic moved to service
            $this->bookingService->approveBookingRequirements($id, $staffId, $request->staff_notes);
            
            // TODO: In a real app, trigger the creation of a Payment Slip here.
            // Eg: $this->bookingService->createPaymentSlipForBooking($id);

            return redirect()->route('staff.verification.index')
                ->with('success', "Booking #{$id} requirements approved. Citizen can now proceed to payment.");
        } catch (\Exception $e) {
            Log::error("Staff Approval Failed for Booking {$id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to approve booking. ' . $e->getMessage());
        }
    }

    /**
     * Process staff rejection for booking requirements.
     * Uses RejectBookingRequest for validation.
     */
    public function reject(RejectBookingRequest $request, int $id)
    {
        // Validation handled by RejectBookingRequest
        $staffId = Auth::id() ?? 1; // Use authenticated ID or fallback
        
        try {
            // Business logic moved to service
            $this->bookingService->rejectBookingRequirements($id, $staffId, $request->rejection_reason);

            return redirect()->route('staff.verification.index')
                ->with('success', "Booking #{$id} requirements rejected. Citizen has been notified.");
        } catch (\Exception $e) {
            Log::error("Staff Rejection Failed for Booking {$id}: " . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to reject booking. ' . $e->getMessage());
        }
    }
}