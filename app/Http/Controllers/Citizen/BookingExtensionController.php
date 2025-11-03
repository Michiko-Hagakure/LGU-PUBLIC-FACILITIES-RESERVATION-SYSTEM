<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Citizen\CheckBookingConflictRequest; // Requires you to create this file
use App\Http\Requests\Citizen\ExtendBookingRequest; // Requires you to create this file
use App\Services\BookingConflictService; // Requires you to create this service

class BookingExtensionController extends Controller
{
    protected BookingConflictService $conflictService;
    
    public function __construct(BookingConflictService $conflictService)
    {
        $this->conflictService = $conflictService;
    }

    /**
     * Check if booking extension would cause conflicts.
     * Uses CheckBookingConflictRequest for validation.
     *
     * @param CheckBookingConflictRequest $request
     * @param int $bookingId
     * @return JsonResponse
     */
    public function checkConflict(CheckBookingConflictRequest $request, int $bookingId): JsonResponse
    {
        // Validation (new_end_time) is handled by CheckBookingConflictRequest
        $validated = $request->validated();
        $booking = $request->get('booking'); // Booking object retrieved in the Request class

        // OLAC is enforced in CheckBookingConflictRequest's authorize() method.

        // Validate the booking status
        if (!in_array($booking->status, ['approved', 'pending'])) {
            return response()->json([
                'success' => false,
                'message' => 'Only approved or pending bookings can be extended.'
            ], 400); 
        }

        // Check conflict using the dedicated service
        $newEndTime = $validated['new_end_time'];
        
        // This method will check for overlaps with other bookings (excluding itself)
        $conflicts = $this->conflictService->findConflictsForExtension(
            $booking->facility_id,
            $booking->event_date,
            $booking->start_time,
            $newEndTime,
            $booking->id 
        );

        if ($conflicts->isNotEmpty()) {
            // Get conflicting users/event names
            $conflictNames = $conflicts->pluck('event_name')->unique()->implode(', ');
            
            return response()->json([
                'success' => false,
                'message' => 'Schedule conflict detected with existing events: ' . $conflictNames,
                'conflicts' => $conflicts->toArray()
            ], 409); // 409 Conflict
        }

        return response()->json([
            'success' => true,
            'message' => 'No schedule conflicts found for the proposed extension.'
        ]);
    }

    /**
     * Process and apply the booking extension.
     * Uses ExtendBookingRequest for validation and security checks.
     *
     * @param ExtendBookingRequest $request
     * @param int $bookingId
     * @return RedirectResponse
     */
    public function extend(ExtendBookingRequest $request, int $bookingId): RedirectResponse
    {
        // Validation (new_end_time, extension_reason) is handled by ExtendBookingRequest
        $validated = $request->validated();
        $booking = $request->get('booking'); // Booking object retrieved in the Request class

        // OLAC and Conflict Check should already be done in the ExtendBookingRequest.
        
        $newEndTime = $validated['new_end_time'];
        $extensionReason = $validated['extension_reason'] ?? 'Not specified';
        $userId = Auth::id(); // Guaranteed to be authenticated

        // Perform the final conflict check again before saving (defense in depth)
        $conflicts = $this->conflictService->findConflictsForExtension(
            $booking->facility_id,
            $booking->event_date,
            $booking->start_time,
            $newEndTime,
            $booking->id 
        );

        if ($conflicts->isNotEmpty()) {
            $conflictNames = $conflicts->pluck('event_name')->unique()->implode(', ');
            return redirect()->back()->with('error', 
                'Cannot extend booking: Schedule conflict detected with existing events by ' . $conflictNames);
        }
        
        // Store old end time for logging
        $oldEndTime = $booking->end_time;
        
        // Update the booking
        $booking->end_time = $newEndTime;
        
        // Add extension note to admin notes (for audit trail)
        $extensionNote = "\n[" . now()->format('Y-m-d H:i:s') . "] Booking extended by Citizen (ID: {$userId}) from " . 
                        Carbon::parse($oldEndTime)->format('h:i A') . " to " . 
                        Carbon::parse($newEndTime)->format('h:i A');
        
        if ($extensionReason) {
            $extensionNote .= " - Reason: " . $extensionReason;
        }
        
        $booking->admin_notes = ($booking->admin_notes ?? '') . $extensionNote;
        $booking->save();
        
        Log::info('Booking extended by citizen', [
            'booking_id' => $booking->id,
            'user_id' => $userId,
            'old_end_time' => $oldEndTime,
            'new_end_time' => $newEndTime,
        ]);
        
        return redirect()->route('citizen.reservations') // Assuming this is the correct redirect
            ->with('success', 'Booking extended successfully from ' . 
                   Carbon::parse($oldEndTime)->format('h:i A') . ' to ' . 
                   Carbon::parse($newEndTime)->format('h:i A') . '.');
    }
}