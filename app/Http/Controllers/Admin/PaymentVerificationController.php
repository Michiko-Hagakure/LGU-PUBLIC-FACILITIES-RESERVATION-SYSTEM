<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\RefundRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentVerificationController extends Controller
{
    /**
     * Display payment verification queue
     * Shows all staff_verified bookings awaiting payment
     */
    public function index(Request $request)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        // Auto-fix: Create payment slips for staff_verified bookings that need them
        $stuckBookings = Booking::where('status', 'staff_verified')
            ->where('total_amount', '>', 0)
            ->get();

        foreach ($stuckBookings as $stuck) {
            $hasUnpaidSlip = \App\Models\PaymentSlip::where('booking_id', $stuck->id)
                ->where('status', 'unpaid')
                ->exists();

            // Skip if there's already an unpaid slip waiting
            if ($hasUnpaidSlip) {
                continue;
            }

            // Set payment defaults if not already set
            if (!$stuck->payment_tier) {
                $stuck->update([
                    'payment_method' => 'cash',
                    'payment_tier' => 25,
                    'down_payment_amount' => $stuck->total_amount * 0.25,
                    'amount_paid' => 0,
                    'amount_remaining' => $stuck->total_amount,
                ]);
            }

            // Determine what amount is due
            $amountRemaining = $stuck->amount_remaining ?? $stuck->total_amount;
            if ($amountRemaining <= 0) {
                continue; // Fully paid, nothing to collect
            }

            $hasPaidSlip = \App\Models\PaymentSlip::where('booking_id', $stuck->id)
                ->where('status', 'paid')
                ->exists();

            $notes = $hasPaidSlip
                ? 'Remaining balance — pay at City Treasurer\'s Office. Down payment already received.'
                : 'Down payment (25%) — pay at City Treasurer\'s Office.';
            $amountDue = $hasPaidSlip
                ? $amountRemaining
                : ($stuck->down_payment_amount ?: $stuck->total_amount * 0.25);

            \App\Models\PaymentSlip::create([
                'slip_number' => \App\Models\PaymentSlip::generateSlipNumber(),
                'booking_id' => $stuck->id,
                'amount_due' => $amountDue,
                'payment_deadline' => now()->addDays(3),
                'status' => 'unpaid',
                'payment_method' => $stuck->payment_method ?? 'cash',
                'paid_at' => null,
                'notes' => $notes,
            ]);
        }

        // Build query for payment queue
        $query = Booking::with(['facility.lguCity', 'user'])
            ->where('status', 'staff_verified'); // Awaiting payment

        // Search by booking ID or user name
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter by facility
        if ($request->filled('facility_id') && $request->input('facility_id') !== 'all') {
            $query->where('facility_id', $request->input('facility_id'));
        }

        // Order by created date (oldest first - most urgent)
        $bookings = $query->orderBy('created_at', 'asc')->paginate(20);

        // Get facilities for filter
        $facilities = \App\Models\FacilityDb::select('facility_id', 'name')
            ->where('is_available', true)
            ->orderBy('name')
            ->get();

        // Calculate time remaining for each booking (48-hour deadline from staff approval)
        foreach ($bookings as $booking) {
            // Use updated_at as the approval time (when status changed to staff_verified)
            $approvedAt = Carbon::parse($booking->updated_at);
            $deadline = $approvedAt->copy()->addHours(48);
            $now = Carbon::now();
            
            $booking->hours_remaining = max(0, $now->diffInHours($deadline, false));
            $booking->deadline = $deadline;
            $booking->is_overdue = $now->greaterThan($deadline);
        }

        return view('admin.payment-queue', compact('bookings', 'facilities'));
    }

    /**
     * Confirm payment for a booking
     * Changes status from staff_verified to paid
     */
    public function confirmPayment(Request $request, $bookingId)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        $booking = Booking::findOrFail($bookingId);

        // Verify booking is in correct status
        if ($booking->status !== 'staff_verified') {
            return back()->with('error', 'Booking is not awaiting payment verification.');
        }

        // Cash bookings must go through the treasurer — admin cannot bypass
        if ($booking->payment_method === 'cash') {
            return back()->with('error', 'Cash bookings must be paid and verified by the Treasurer first. Please direct the citizen to the City Treasurer\'s Office.');
        }

        // Update booking status (only cashless/PayMongo bookings reach here)
        $booking->status = 'paid';
        
        // Add admin notes if provided
        if ($request->filled('admin_notes')) {
            $booking->admin_notes = $request->input('admin_notes');
        }

        $booking->save();

        // TODO: Send notification to citizen (Priority 4)
        
        return redirect()
            ->route('admin.bookings.review', $bookingId)
            ->with('success', 'Payment confirmed successfully! Booking is now paid and awaiting final confirmation.');
    }

    /**
     * Reject payment for a booking (staff_verified status - payment not yet received)
     * Keeps status as staff_verified but adds rejection notes
     */
    public function rejectPayment(Request $request, $bookingId)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        $booking = Booking::findOrFail($bookingId);

        // Verify booking is in correct status
        if ($booking->status !== 'staff_verified') {
            return back()->with('error', 'Booking is not awaiting payment verification.');
        }

        // Add rejection notes (keep status as staff_verified so citizen can resubmit)
        $booking->payment_rejection_reason = $request->input('rejection_reason');
        $booking->payment_rejected_at = Carbon::now();
        $booking->payment_rejected_by = $userId;
        $booking->save();

        // TODO: Send notification to citizen (Priority 4)
        
        return redirect()
            ->route('admin.bookings.review', $bookingId)
            ->with('warning', 'Payment rejected. Citizen will be notified to resubmit payment proof.');
    }

    /**
     * Reject a booking that has already been paid.
     * Sets status to 'admin_rejected' — citizen decides to reschedule or cancel (no refund).
     */
    public function rejectBooking(Request $request, $bookingId)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $userId = session('user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        $booking = Booking::with('facility')->findOrFail($bookingId);

        // Only allow rejecting paid or confirmed bookings
        if (!in_array($booking->status, ['paid', 'confirmed', 'staff_verified'])) {
            return back()->with('error', 'This booking cannot be rejected at this stage.');
        }

        $reason = $request->input('rejection_reason');

        try {
            // Update booking status to admin_rejected — citizen will decide next step
            $booking->update([
                'status' => 'admin_rejected',
                'rejected_reason' => $reason,
            ]);

            // Send notification to citizen
            try {
                $user = \App\Models\User::find($booking->user_id);
                $bookingData = DB::connection('facilities_db')
                    ->table('bookings')
                    ->join('facilities', 'bookings.facility_id', '=', 'facilities.facility_id')
                    ->where('bookings.id', $bookingId)
                    ->selectRaw('bookings.*, facilities.name as facility_name, CONCAT("BK", LPAD(bookings.id, 6, "0")) as booking_reference')
                    ->first();

                if ($user && $bookingData) {
                    $user->notify(new \App\Notifications\BookingAdminRejected($bookingData, $reason));
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send admin rejection notification: ' . $e->getMessage());
            }

            return redirect()
                ->route('admin.bookings.review', $bookingId)
                ->with('warning', 'Booking rejected. The citizen has been notified and can choose to reschedule or cancel (no refund).');

        } catch (\Exception $e) {
            \Log::error('Failed to reject booking: ' . $e->getMessage());
            return back()->with('error', 'Failed to process rejection: ' . $e->getMessage());
        }
    }
}

