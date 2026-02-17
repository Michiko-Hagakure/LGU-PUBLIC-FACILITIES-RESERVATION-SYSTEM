<?php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentSlip;
use App\Models\Booking;
use Carbon\Carbon;

class PaymentVerificationController extends Controller
{
    /**
     * Display the payment verification queue.
     */
    public function index(Request $request)
    {
        // Auto-expire overdue bookings before loading the queue
        $this->autoExpireOverdueBookings();

        // Auto-fix: Create payment slips for staff_verified bookings that need them
        try {
            $stuckBookings = Booking::where('status', 'staff_verified')
                ->where('total_amount', '>', 0)
                ->get();

            foreach ($stuckBookings as $stuck) {
                $hasUnpaidSlip = PaymentSlip::where('booking_id', $stuck->id)
                    ->where('status', 'unpaid')
                    ->exists();

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

                $amountRemaining = $stuck->amount_remaining ?? $stuck->total_amount;
                if ($amountRemaining <= 0) {
                    continue;
                }

                $hasPaidSlip = PaymentSlip::where('booking_id', $stuck->id)
                    ->where('status', 'paid')
                    ->exists();

                $notes = $hasPaidSlip
                    ? 'Remaining balance — pay at City Treasurer\'s Office. Down payment already received.'
                    : 'Down payment (25%) — pay at City Treasurer\'s Office.';
                $amountDue = $hasPaidSlip
                    ? $amountRemaining
                    : ($stuck->down_payment_amount ?: $stuck->total_amount * 0.25);

                PaymentSlip::create([
                    'slip_number' => PaymentSlip::generateSlipNumber(),
                    'booking_id' => $stuck->id,
                    'amount_due' => $amountDue,
                    'payment_deadline' => now()->addDays(3),
                    'status' => 'unpaid',
                    'payment_method' => $stuck->payment_method ?? 'cash',
                    'paid_at' => null,
                    'notes' => $notes,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Auto-fix payment slips failed: ' . $e->getMessage());
        }

        try {
            $query = DB::connection('facilities_db')
                ->table('payment_slips')
                ->join('bookings', 'payment_slips.booking_id', '=', 'bookings.id')
                ->join('facilities', 'bookings.facility_id', '=', 'facilities.facility_id')
                ->select(
                    'payment_slips.*',
                    'bookings.applicant_name',
                    'bookings.applicant_email',
                    'bookings.applicant_phone',
                    'bookings.user_id',
                    'bookings.start_time',
                    'bookings.total_amount as booking_total',
                    'bookings.amount_remaining as booking_remaining',
                    'bookings.payment_tier',
                    'bookings.amount_paid as booking_amount_paid',
                    'bookings.down_payment_amount',
                    'facilities.name as facility_name'
                );
            
            // Filter by status (default: unpaid)
            $status = $request->get('status', 'unpaid');
            if ($status !== 'all') {
                $query->where('payment_slips.status', $status);
            }
            
            // Exclude payment slips for bookings that are already fully paid, confirmed, expired, or cancelled
            if ($status === 'unpaid') {
                $query->whereNotIn('bookings.status', ['paid', 'confirmed', 'completed', 'expired', 'cancelled']);
            }
            
            // Search functionality
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('payment_slips.slip_number', 'like', "%{$search}%")
                      ->orWhere('bookings.applicant_name', 'like', "%{$search}%")
                      ->orWhere('bookings.applicant_email', 'like', "%{$search}%");
                });
            }
            
            // Sort by payment deadline (urgent first)
            $query->orderBy('payment_slips.payment_deadline', 'asc');
            
            $paymentSlips = $query->paginate(20);
            
            // Fetch user names from auth_db for bookings with user_id but no applicant_name
            $paymentSlips->getCollection()->transform(function ($slip) {
                if (empty($slip->applicant_name) && $slip->user_id) {
                    $user = DB::connection('auth_db')->table('users')
                        ->where('id', $slip->user_id)
                        ->first(['full_name', 'email']);
                    if ($user) {
                        $slip->applicant_name = $user->full_name;
                        if (empty($slip->applicant_email)) {
                            $slip->applicant_email = $user->email;
                        }
                    }
                }
                return $slip;
            });
            
        } catch (\Exception $e) {
            // If query fails, return empty collection
            $paymentSlips = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
            $status = $request->get('status', 'unpaid');
        }
        
        return view('treasurer.payment-verification.index', [
            'paymentSlips' => $paymentSlips,
            'status' => $status ?? 'unpaid',
        ]);
    }

    /**
     * Return payment slips as JSON for AJAX polling
     */
    public function getPaymentsJson(Request $request)
    {
        $query = DB::connection('facilities_db')
            ->table('payment_slips')
            ->join('bookings', 'payment_slips.booking_id', '=', 'bookings.id')
            ->join('facilities', 'bookings.facility_id', '=', 'facilities.facility_id')
            ->select(
                'payment_slips.*',
                'bookings.applicant_name',
                'bookings.applicant_email',
                'bookings.user_id',
                'bookings.total_amount as booking_total',
                'bookings.amount_remaining as booking_remaining',
                'bookings.payment_tier',
                'bookings.amount_paid as booking_amount_paid',
                'bookings.down_payment_amount',
                'facilities.name as facility_name'
            );

        $status = $request->get('status', 'unpaid');
        if ($status !== 'all') {
            $query->where('payment_slips.status', $status);
        }

        // Exclude payment slips for bookings that are already fully paid, confirmed, expired, or cancelled
        if ($status === 'unpaid') {
            $query->whereNotIn('bookings.status', ['paid', 'confirmed', 'completed', 'expired', 'cancelled']);
        }

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('payment_slips.slip_number', 'like', "%{$search}%")
                  ->orWhere('bookings.applicant_name', 'like', "%{$search}%")
                  ->orWhere('bookings.applicant_email', 'like', "%{$search}%");
            });
        }

        $paymentSlips = $query->orderBy('payment_slips.payment_deadline', 'asc')->limit(50)->get();

        foreach ($paymentSlips as $slip) {
            if (empty($slip->applicant_name) && $slip->user_id) {
                $user = DB::connection('auth_db')->table('users')->where('id', $slip->user_id)->first(['full_name', 'email']);
                if ($user) {
                    $slip->applicant_name = $user->full_name ?? 'Unknown';
                    if (empty($slip->applicant_email)) {
                        $slip->applicant_email = $user->email;
                    }
                }
            }
        }

        $stats = [
            'unpaid' => DB::connection('facilities_db')->table('payment_slips')
                ->join('bookings', 'payment_slips.booking_id', '=', 'bookings.id')
                ->where('payment_slips.status', 'unpaid')
                ->whereNotIn('bookings.status', ['paid', 'confirmed', 'completed', 'expired', 'cancelled'])
                ->count(),
            'paid' => DB::connection('facilities_db')->table('payment_slips')->where('status', 'paid')->count(),
            'expired' => DB::connection('facilities_db')->table('payment_slips')->where('status', 'expired')->count(),
        ];

        return response()->json(['data' => $paymentSlips, 'stats' => $stats]);
    }
    
    /**
     * Show individual payment slip details.
     */
    public function show($id)
    {
        $paymentSlip = DB::connection('facilities_db')
            ->table('payment_slips')
            ->join('bookings', 'payment_slips.booking_id', '=', 'bookings.id')
            ->join('facilities', 'bookings.facility_id', '=', 'facilities.facility_id')
            ->where('payment_slips.id', $id)
            ->select(
                'payment_slips.*',
                'bookings.applicant_name',
                'bookings.applicant_email',
                'bookings.applicant_phone',
                'bookings.user_id',
                'bookings.start_time',
                'bookings.end_time',
                'bookings.purpose',
                'bookings.expected_attendees as attendees',
                'facilities.name as facility_name',
                'facilities.address as facility_address'
            )
            ->first();
        
        if (!$paymentSlip) {
            return redirect()->route('treasurer.payment-verification')
                ->with('error', 'Payment slip not found.');
        }
        
        // Fetch user details from auth_db if applicant_name is empty
        if (empty($paymentSlip->applicant_name) && $paymentSlip->user_id) {
            $user = DB::connection('auth_db')->table('users')
                ->where('id', $paymentSlip->user_id)
                ->first(['full_name', 'email', 'mobile_number']);
            if ($user) {
                $paymentSlip->applicant_name = $user->full_name;
                if (empty($paymentSlip->applicant_email)) {
                    $paymentSlip->applicant_email = $user->email;
                }
                if (empty($paymentSlip->applicant_phone)) {
                    $paymentSlip->applicant_phone = $user->mobile_number;
                }
            }
        }
        
        return view('treasurer.payment-verification.show', [
            'paymentSlip' => $paymentSlip,
        ]);
    }
    
    /**
     * Verify cash payment and generate Official Receipt.
     * Handles both initial full payments and balance payments for partial down payments.
     */
    public function verifyPayment(Request $request, $id)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,gcash,paymaya,bank_transfer,credit_card',
            'notes' => 'nullable|string|max:1000',
            'payment_amount' => 'nullable|numeric|min:0',
        ]);
        
        $paymentSlip = PaymentSlip::find($id);
        
        if (!$paymentSlip) {
            return response()->json([
                'success' => false,
                'message' => 'Payment slip not found.'
            ], 404);
        }
        
        if ($paymentSlip->status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'This payment has already been verified.'
            ], 400);
        }
        
        try {
            DB::connection('facilities_db')->beginTransaction();
            
            // Auto-generate Official Receipt number
            $orNumber = $this->generateOfficialReceiptNumber();
            
            // Update payment slip status
            $paymentSlip->status = 'paid';
            $paymentSlip->payment_method = $request->payment_method;
            $paymentSlip->or_number = $orNumber;
            $paymentSlip->notes = $request->notes;
            $paymentSlip->paid_at = now();
            $paymentSlip->verified_by = session('user_id');
            $paymentSlip->save();
            
            // Update booking payment tracking and status
            $booking = Booking::find($paymentSlip->booking_id);
            $wasAwaitingPayment = false;
            if ($booking) {
                $wasAwaitingPayment = $booking->status === 'awaiting_payment';
                
                // Calculate the payment amount (balance payment or full remaining)
                $paymentAmount = $request->payment_amount ?? $paymentSlip->amount_due;
                
                // Update booking payment fields
                $newAmountPaid = $booking->amount_paid + $paymentAmount;
                $newAmountRemaining = max(0, $booking->total_amount - $newAmountPaid);
                
                $booking->amount_paid = $newAmountPaid;
                $booking->amount_remaining = $newAmountRemaining;
                $booking->payment_recorded_by = session('user_id');
                
                // If booking was awaiting_payment (down payment just confirmed), promote to 'pending' for staff review
                if ($wasAwaitingPayment) {
                    $booking->status = 'pending';
                    $booking->down_payment_paid_at = now();
                }
                
                // If fully paid now, update status to 'paid' for admin confirmation
                if ($newAmountRemaining <= 0) {
                    $booking->status = 'paid';
                }
                
                $booking->save();

                // Auto-cancel overlapping unpaid bookings — whoever pays first gets the slot
                if ($wasAwaitingPayment) {
                    Booking::cancelOverlappingUnpaidBookings($booking);
                }
            }
            
            // Send notifications
            try {
                $user = User::find($booking->user_id);
                $bookingWithFacility = DB::connection('facilities_db')
                    ->table('bookings')
                    ->join('facilities', 'bookings.facility_id', '=', 'facilities.facility_id')
                    ->where('bookings.id', $paymentSlip->booking_id)
                    ->selectRaw('bookings.*, facilities.name as facility_name, CONCAT("BK", LPAD(bookings.id, 6, "0")) as booking_reference')
                    ->first();
                
                $paymentSlipFresh = DB::connection('facilities_db')
                    ->table('payment_slips')
                    ->where('id', $id)
                    ->first();
                
                if ($user && $bookingWithFacility && $paymentSlipFresh) {
                    // Notify citizen about payment verification
                    $user->notify(new \App\Notifications\PaymentVerified($bookingWithFacility, $paymentSlipFresh));
                    
                    // If booking was just promoted from awaiting_payment, also send booking submission notifications
                    if ($wasAwaitingPayment) {
                        $user->notify(new \App\Notifications\BookingSubmitted($bookingWithFacility));
                        
                        // Notify staff members about the new booking in their verification queue
                        $staffMembers = User::where('subsystem_role_id', 3)
                            ->where('subsystem_id', 4)
                            ->get();
                        
                        foreach ($staffMembers as $staff) {
                            $staff->notify(new \App\Notifications\BookingSubmitted($bookingWithFacility));
                        }
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send payment verification notification: ' . $e->getMessage());
            }
            
            // Official Receipt PDF is available via the Official Receipts menu
            
            DB::connection('facilities_db')->commit();
            
            return response()->json([
                'success' => true,
                'message' => "Payment verified successfully! Official Receipt #{$orNumber} has been generated.",
                'or_number' => $orNumber,
                'redirect' => route('treasurer.payment-verification')
            ]);
            
        } catch (\Exception $e) {
            DB::connection('facilities_db')->rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify payment: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Display payment history (all verified payments).
     */
    public function history(Request $request)
    {
        try {
            $query = DB::connection('facilities_db')
                ->table('payment_slips')
                ->join('bookings', 'payment_slips.booking_id', '=', 'bookings.id')
                ->join('facilities', 'bookings.facility_id', '=', 'facilities.facility_id')
                ->select(
                    'payment_slips.*',
                    'bookings.applicant_name',
                    'bookings.applicant_email',
                    'bookings.applicant_phone',
                    'bookings.user_id',
                    'bookings.start_time',
                    'bookings.end_time',
                    'facilities.name as facility_name'
                )
                ->whereIn('payment_slips.status', ['paid', 'expired']);
            
            // Filter by slip status (paid or expired)
            if ($request->filled('slip_status') && $request->slip_status !== 'all') {
                $query->where('payment_slips.status', $request->slip_status);
            }
            
            // Search functionality
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('payment_slips.slip_number', 'like', "%{$search}%")
                      ->orWhere('payment_slips.transaction_reference', 'like', "%{$search}%")
                      ->orWhere('bookings.applicant_name', 'like', "%{$search}%")
                      ->orWhere('bookings.applicant_email', 'like', "%{$search}%");
                });
            }
            
            // Filter by payment method
            if ($request->filled('payment_method') && $request->payment_method !== 'all') {
                $query->where('payment_slips.payment_method', $request->payment_method);
            }
            
            // Filter by date range
            if ($request->filled('date_from')) {
                $query->whereDate('payment_slips.paid_at', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('payment_slips.paid_at', '<=', $request->date_to);
            }
            
            // Sort by payment date (most recent first), with fallback for expired slips that have no paid_at
            $paymentSlips = $query->orderByRaw('COALESCE(payment_slips.paid_at, payment_slips.updated_at, payment_slips.created_at) DESC')->paginate(15);
            
            // Fetch user data for bookings without applicant_name
            $userIds = $paymentSlips->filter(function($slip) {
                return empty($slip->applicant_name) && !empty($slip->user_id);
            })->pluck('user_id')->unique();
            
            if ($userIds->isNotEmpty()) {
                $users = DB::connection('auth_db')
                    ->table('users')
                    ->whereIn('id', $userIds)
                    ->get()
                    ->keyBy('id');
                
                // Merge user data into payment slips
                $paymentSlips->transform(function($slip) use ($users) {
                    if (empty($slip->applicant_name) && !empty($slip->user_id) && isset($users[$slip->user_id])) {
                        $user = $users[$slip->user_id];
                        $slip->applicant_name = $user->full_name ?? 'N/A';
                        $slip->applicant_email = $user->email ?? 'N/A';
                        $slip->applicant_phone = $user->mobile_number ?? 'N/A';
                    }
                    return $slip;
                });
            }
            
            // Get statistics
            $stats = [
                'total_verified' => DB::connection('facilities_db')
                    ->table('payment_slips')
                    ->where('status', 'paid')
                    ->count(),
                'total_amount' => DB::connection('facilities_db')
                    ->table('payment_slips')
                    ->where('status', 'paid')
                    ->sum('amount_due'),
                'today_verified' => DB::connection('facilities_db')
                    ->table('payment_slips')
                    ->where('status', 'paid')
                    ->whereDate('paid_at', today())
                    ->count(),
                'today_amount' => DB::connection('facilities_db')
                    ->table('payment_slips')
                    ->where('status', 'paid')
                    ->whereDate('paid_at', today())
                    ->sum('amount_due'),
                'total_expired' => DB::connection('facilities_db')
                    ->table('payment_slips')
                    ->where('status', 'expired')
                    ->count(),
                'expired_amount' => DB::connection('facilities_db')
                    ->table('payment_slips')
                    ->where('status', 'expired')
                    ->sum('amount_due'),
            ];
            
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Payment History Query Error: ' . $e->getMessage());
            
            // Return empty collection with proper pagination
            $paymentSlips = new \Illuminate\Pagination\LengthAwarePaginator(
                [],
                0,
                15,
                1,
                ['path' => request()->url(), 'query' => request()->query()]
            );
            $stats = [
                'total_verified' => 0,
                'total_amount' => 0,
                'today_verified' => 0,
                'today_amount' => 0,
                'total_expired' => 0,
                'expired_amount' => 0,
            ];
        }
        
        return view('treasurer.payment-history.index', compact('paymentSlips', 'stats'));
    }
    
    /**
     * Auto-expire overdue bookings as a safety net.
     * Handles: pending with no payment after 24h, staff_verified with remaining balance after 7 days.
     */
    private function autoExpireOverdueBookings(): void
    {
        try {
            $now = Carbon::now();

            // 1. Pending bookings with no payment after 24 hours
            $overdueBookings = Booking::where('status', 'pending')
                ->where(function ($q) {
                    $q->where('amount_paid', '<=', 0)->orWhereNull('amount_paid');
                })
                ->where('created_at', '<', $now->copy()->subHours(24))
                ->get();

            foreach ($overdueBookings as $booking) {
                $booking->update([
                    'status' => 'expired',
                    'expired_at' => $now,
                    'canceled_reason' => 'No down payment made within 24 hours (auto-expired)',
                ]);

                PaymentSlip::where('booking_id', $booking->id)
                    ->where('status', 'unpaid')
                    ->update(['status' => 'expired']);
            }

            // 2. Staff_verified bookings with remaining balance after 7 days
            $overdueVerified = Booking::where('status', 'staff_verified')
                ->where('amount_remaining', '>', 0)
                ->whereNotNull('staff_verified_at')
                ->where('staff_verified_at', '<', $now->copy()->subDays(7))
                ->get();

            foreach ($overdueVerified as $booking) {
                $booking->update([
                    'status' => 'expired',
                    'expired_at' => $now,
                    'canceled_reason' => 'Remaining balance not settled within 7 days of staff verification (auto-expired)',
                ]);

                PaymentSlip::where('booking_id', $booking->id)
                    ->where('status', 'unpaid')
                    ->update(['status' => 'expired']);
            }
            // 3. Clean up orphaned payment slips — booking already expired/cancelled but slip still unpaid
            DB::connection('facilities_db')
                ->table('payment_slips')
                ->join('bookings', 'payment_slips.booking_id', '=', 'bookings.id')
                ->where('payment_slips.status', 'unpaid')
                ->whereIn('bookings.status', ['expired', 'cancelled'])
                ->update(['payment_slips.status' => 'expired', 'payment_slips.updated_at' => $now]);
        } catch (\Exception $e) {
            \Log::error('Treasurer auto-expire bookings failed: ' . $e->getMessage());
        }
    }

    /**
     * Auto-generate Official Receipt number.
     * Format: OR-YYYY-NNNN (e.g., OR-2025-0001)
     */
    private function generateOfficialReceiptNumber()
    {
        $year = now()->year;
        
        // Find the last OR number for this year (look in or_number column, not transaction_reference)
        $lastPayment = PaymentSlip::where('or_number', 'like', "OR-{$year}-%")
            ->orderBy('or_number', 'desc')
            ->first();
        
        if ($lastPayment && $lastPayment->or_number) {
            // Extract the sequence number from the last OR
            $lastNumber = intval(substr($lastPayment->or_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // First OR of the year
            $newNumber = '0001';
        }
        
        return "OR-{$year}-{$newNumber}";
    }
}

