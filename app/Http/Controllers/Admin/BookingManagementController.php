<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\FacilityDb;
use App\Models\PaymentSlip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingManagementController extends Controller
{
    /**
     * Display all bookings with advanced filters
     */
    public function index(Request $request)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        // Auto-fix: correct bookings wrongly marked as 'paid' when they still have outstanding balance
        // This was caused by the PayMongo webhook blindly setting status to 'paid' on partial payments
        try {
            $miscategorized = Booking::where('status', 'paid')
                ->where('amount_remaining', '>', 0)
                ->where('total_amount', '>', 0)
                ->get();

            foreach ($miscategorized as $fix) {
                // Revert to staff_verified so remaining balance can be collected
                $fix->update(['status' => 'staff_verified']);

                // Create a payment slip for the treasurer if none exists
                $hasUnpaidSlip = \App\Models\PaymentSlip::where('booking_id', $fix->id)
                    ->where('status', 'unpaid')
                    ->exists();

                if (!$hasUnpaidSlip && $fix->amount_remaining > 0) {
                    \App\Models\PaymentSlip::create([
                        'slip_number' => \App\Models\PaymentSlip::generateSlipNumber(),
                        'booking_id' => $fix->id,
                        'amount_due' => $fix->amount_remaining,
                        'payment_deadline' => now()->addDays(7),
                        'status' => 'unpaid',
                        'payment_method' => $fix->payment_method ?? 'cash',
                        'paid_at' => null,
                        'notes' => 'Remaining balance (' . (100 - ($fix->payment_tier ?? 0)) . '%) to collect. Down payment of ₱' . number_format($fix->down_payment_amount ?? 0, 2) . ' already received.',
                    ]);
                }
            }
        } catch (\Exception $e) {
            \Log::error('Auto-fix paid bookings with balance failed: ' . $e->getMessage());
        }

        // Build query — exclude awaiting_payment (not yet submitted)
        $query = Booking::with(['facility.lguCity', 'user'])
            ->where('status', '!=', 'awaiting_payment');

        // Filter by status
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        // Filter by facility
        if ($request->filled('facility_id') && $request->input('facility_id') !== 'all') {
            $query->where('facility_id', $request->input('facility_id'));
        }

        // Filter by date range (event date)
        if ($request->filled('date_from')) {
            $query->whereDate('start_time', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('start_time', '<=', $request->input('date_to'));
        }

        // Search by booking ID or applicant name
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhere('applicant_name', 'LIKE', "%{$search}%");
            });
        }

        // Order by most recent first
        $bookings = $query->orderBy('created_at', 'desc')->paginate(20);

        // Fetch user names from auth_db for each booking
        foreach ($bookings as $booking) {
            if ($booking->user_id) {
                $user = \DB::connection('auth_db')->table('users')
                    ->where('id', $booking->user_id)
                    ->first();
                
                if ($user) {
                    $booking->user_name = $user->full_name;
                } else {
                    $booking->user_name = $booking->user_name ?? $booking->applicant_name;
                }
            } else {
                // For API bookings (no user_id), preserve existing user_name or fall back to applicant_name
                $booking->user_name = $booking->user_name ?? $booking->applicant_name;
            }
        }

        // Get facilities for filter
        $facilities = FacilityDb::select('facility_id', 'name')
            ->where('is_available', true)
            ->orderBy('name')
            ->get();

        return view('admin.bookings.index', compact('bookings', 'facilities'));
    }

    /**
     * Return bookings as JSON for AJAX polling
     */
    public function getBookingsJson(Request $request)
    {
        $query = Booking::with(['facility.lguCity', 'user']);

        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('facility_id') && $request->input('facility_id') !== 'all') {
            $query->where('facility_id', $request->input('facility_id'));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('start_time', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('start_time', '<=', $request->input('date_to'));
        }
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhere('applicant_name', 'LIKE', "%{$search}%");
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')->limit(50)->get();

        foreach ($bookings as $booking) {
            if ($booking->user_id) {
                $user = \DB::connection('auth_db')->table('users')->where('id', $booking->user_id)->first();
                $booking->user_name = $user ? $user->full_name : ($booking->user_name ?? $booking->applicant_name);
            } else {
                // For API bookings, preserve existing user_name or fall back to applicant_name
                $booking->user_name = $booking->user_name ?? $booking->applicant_name;
            }
            $booking->booking_reference = 'BK' . str_pad($booking->id, 6, '0', STR_PAD_LEFT);
            $booking->facility_name = $booking->facility->name ?? 'N/A';
            $booking->start_formatted = Carbon::parse($booking->start_time)->format('M d, Y');
            $booking->time_range = Carbon::parse($booking->start_time)->format('h:iA') . '-' . Carbon::parse($booking->end_time)->format('h:iA');
        }

        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'staff_verified' => Booking::where('status', 'staff_verified')->count(),
        ];

        return response()->json(['data' => $bookings, 'stats' => $stats]);
    }

    // For Data
    public function show($id)
    {
        $booking = Booking::with(['facility', 'user'])->find($id);
        if (!$booking) {
            abort(404, 'Booking record not found.');
        }
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Display booking review page for admin
     * Similar to staff review but with payment verification and final confirmation
     */
    public function review($bookingId)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        // Get booking with all relationships
        $booking = Booking::with([
            'facility.lguCity',
            'user',
            'equipmentItems'
        ])->findOrFail($bookingId);

        // Auto-fix: correct booking wrongly marked as 'paid' when it still has outstanding balance
        if ($booking->status === 'paid' && $booking->amount_remaining > 0 && $booking->total_amount > 0) {
            $booking->update(['status' => 'staff_verified']);

            $hasUnpaidSlip = \App\Models\PaymentSlip::where('booking_id', $booking->id)
                ->where('status', 'unpaid')
                ->exists();

            if (!$hasUnpaidSlip) {
                \App\Models\PaymentSlip::create([
                    'slip_number' => \App\Models\PaymentSlip::generateSlipNumber(),
                    'booking_id' => $booking->id,
                    'amount_due' => $booking->amount_remaining,
                    'payment_deadline' => now()->addDays(7),
                    'status' => 'unpaid',
                    'payment_method' => $booking->payment_method ?? 'cash',
                    'paid_at' => null,
                    'notes' => 'Remaining balance (' . (100 - ($booking->payment_tier ?? 0)) . '%) to collect. Down payment of ₱' . number_format($booking->down_payment_amount ?? 0, 2) . ' already received.',
                ]);
            }

            $booking->refresh();
        }

        // Get user details from auth_db
        $userFromDb = \DB::connection('auth_db')->table('users')
            ->where('id', $booking->user_id)
            ->first();

        // Get barangay and city names
        $barangay = null;
        $city = null;
        if ($userFromDb) {
            if ($userFromDb->barangay_id) {
                $barangay = \DB::connection('auth_db')->table('barangays')->where('id', $userFromDb->barangay_id)->value('name');
            }
            if ($userFromDb->city_id) {
                $city = \DB::connection('auth_db')->table('cities')->where('id', $userFromDb->city_id)->value('name');
            }
        }

        // Build full address (handle null $userFromDb for API bookings)
        $fullAddress = '';
        if ($userFromDb) {
            $fullAddress = collect([
                $userFromDb->current_address ?? $userFromDb->address ?? null,
                $barangay,
                $city
            ])->filter()->implode(', ');
        }

        // Create standardized user object with fallbacks for API bookings (PF folder, Housing & Resettlement, etc.)
        $user = (object) [
            'name' => ($userFromDb ? ($userFromDb->full_name ?? $userFromDb->name ?? null) : null) ?? $booking->applicant_name ?? $booking->user_name ?? 'N/A',
            'email' => ($userFromDb ? ($userFromDb->email ?? null) : null) ?? $booking->applicant_email ?? 'N/A',
            'phone' => ($userFromDb ? ($userFromDb->mobile_number ?? $userFromDb->phone ?? null) : null) ?? $booking->applicant_phone ?? 'N/A',
            'address' => $fullAddress ?: ($booking->applicant_address ?? 'N/A')
        ];

        // Get uploaded documents - handle both local storage paths and external URLs (from PF folder API)
        $documents = [];
        $docFields = [
            'valid_id_front_path' => 'id_front',
            'valid_id_back_path' => 'id_back',
            'valid_id_selfie_path' => 'selfie_with_id',
        ];
        
        foreach ($docFields as $field => $key) {
            if ($booking->$field) {
                $path = $booking->$field;
                $isExternal = str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '/uploads/');
                $documents[$key] = (object)[
                    'path' => $path,
                    'is_external' => $isExternal,
                ];
            }
        }

        // Get equipment with pricing
        $equipment = $booking->equipmentItems;

        // Calculate payment deadline (48 hours from staff verification)
        $paymentDeadline = null;
        $hoursRemaining = null;
        if ($booking->status === 'staff_verified' && $booking->staff_verified_at) {
            $verifiedAt = Carbon::parse($booking->staff_verified_at);
            $paymentDeadline = $verifiedAt->copy()->addHours(48);
            $hoursRemaining = max(0, Carbon::now()->diffInHours($paymentDeadline, false));
        }

        return view('admin.bookings.review', compact(
            'booking',
            'equipment',
            'documents',
            'user',
            'paymentDeadline',
            'hoursRemaining'
        ));
    }

    /**
     * Verify documents for a pending booking (admin acts as reviewer)
     * Same logic as staff verification — moves to staff_verified or paid
     */
    public function verifyDocuments(Request $request, $bookingId)
    {
        $userId = session('user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        try {
            $booking = Booking::findOrFail($bookingId);

            if ($booking->status !== 'pending') {
                return back()->with('error', 'Only pending bookings can be verified.');
            }

            // Auto-fix: ensure amount_remaining is properly calculated
            $amountPaid = $booking->amount_paid ?? 0;
            $totalAmount = $booking->total_amount ?? 0;
            if (is_null($booking->amount_remaining) && $totalAmount > 0) {
                $booking->update([
                    'amount_remaining' => max(0, $totalAmount - $amountPaid),
                ]);
                $booking->refresh();
            }

            // If fully paid (100% tier), go straight to 'paid' for final confirmation
            // Otherwise go to 'staff_verified' for treasurer to collect remaining balance
            $newStatus = $booking->isFullyPaid() ? 'paid' : 'staff_verified';

            $booking->update([
                'status' => $newStatus,
                'staff_verified_by' => $userId,
                'staff_verified_at' => now(),
                'staff_notes' => $validated['admin_notes'] ?? null,
            ]);

            // Retrieve existing paid slip for notification
            $paymentSlip = PaymentSlip::where('booking_id', $bookingId)
                ->where('status', 'paid')
                ->orderBy('paid_at', 'desc')
                ->first();

            // Check if an unpaid slip already exists
            $hasUnpaidSlip = PaymentSlip::where('booking_id', $bookingId)
                ->where('status', 'unpaid')
                ->exists();

            // If booking has remaining balance, create a remaining balance slip for the treasurer
            if (!$hasUnpaidSlip && $booking->hasRemainingBalance()) {
                PaymentSlip::create([
                    'slip_number' => PaymentSlip::generateSlipNumber(),
                    'booking_id' => $bookingId,
                    'amount_due' => $booking->amount_remaining,
                    'payment_deadline' => now()->addDays(7),
                    'status' => 'unpaid',
                    'payment_method' => $booking->payment_method ?? 'cash',
                    'paid_at' => null,
                    'notes' => 'Remaining balance (' . (100 - ($booking->payment_tier ?? 0)) . '%) to collect.' . ($booking->down_payment_amount > 0 ? ' Down payment of ₱' . number_format($booking->down_payment_amount, 2) . ' already received.' : ''),
                ]);
            }
            // PF/API bookings: no payment was made yet, create a payment slip for the full amount
            elseif (!$hasUnpaidSlip && !$booking->payment_tier && ($booking->amount_paid ?? 0) == 0 && $booking->total_amount > 0) {
                $booking->update([
                    'payment_method' => 'cash',
                    'payment_tier' => 25,
                    'down_payment_amount' => $booking->total_amount * 0.25,
                    'amount_paid' => 0,
                    'amount_remaining' => $booking->total_amount,
                ]);

                PaymentSlip::create([
                    'slip_number' => PaymentSlip::generateSlipNumber(),
                    'booking_id' => $bookingId,
                    'amount_due' => $booking->total_amount * 0.25,
                    'payment_deadline' => now()->addDays(3),
                    'status' => 'unpaid',
                    'payment_method' => 'cash',
                    'paid_at' => null,
                    'notes' => "Down payment (25%) — pay at City Treasurer's Office. Verified by Admin.",
                ]);
            }

            // Send notification to citizen
            try {
                $user = User::find($booking->user_id);
                $bookingWithFacility = DB::connection('facilities_db')
                    ->table('bookings')
                    ->join('facilities', 'bookings.facility_id', '=', 'facilities.facility_id')
                    ->where('bookings.id', $bookingId)
                    ->selectRaw('bookings.*, facilities.name as facility_name, CONCAT("BK", LPAD(bookings.id, 6, "0")) as booking_reference')
                    ->first();

                if ($user && $bookingWithFacility) {
                    $user->notify(new \App\Notifications\StaffVerified($bookingWithFacility, $paymentSlip));
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send admin verification notification: ' . $e->getMessage());
            }

            $successMsg = $booking->isFullyPaid()
                ? 'Documents verified & booking fully paid. Ready for final confirmation.'
                : 'Documents verified. Remaining balance of ₱' . number_format($booking->amount_remaining, 2) . ' to be collected by the Treasurer.';

            return redirect()
                ->route('admin.bookings.review', $bookingId)
                ->with('success', $successMsg);

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to verify documents: ' . $e->getMessage());
        }
    }

    /**
     * Reject a pending booking (admin acts as reviewer)
     * Supports partial rejection with specific field issues
     */
    public function rejectDocuments(Request $request, $bookingId)
    {
        $userId = session('user_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
            'rejection_type' => 'nullable|in:id_issue,facility_issue,document_issue,info_issue',
            'rejection_fields' => 'nullable|array',
            'rejection_fields.*' => 'string',
        ]);

        try {
            $booking = Booking::findOrFail($bookingId);

            if ($booking->status !== 'pending') {
                return back()->with('error', 'Only pending bookings can be rejected.');
            }

            $booking->update([
                'status' => 'rejected',
                'staff_verified_by' => $userId,
                'staff_verified_at' => now(),
                'rejected_reason' => $validated['rejection_reason'],
                'rejection_type' => $validated['rejection_type'] ?? null,
                'rejection_fields' => $validated['rejection_fields'] ?? null,
            ]);

            // Send rejection notification to citizen
            try {
                $user = User::find($booking->user_id);
                $booking->booking_reference = 'BK' . str_pad($booking->id, 6, '0', STR_PAD_LEFT);

                if ($user && $booking) {
                    $user->notify(new \App\Notifications\BookingRejected($booking, $validated['rejection_reason']));
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send admin rejection notification: ' . $e->getMessage());
            }

            return redirect()
                ->route('admin.bookings.index')
                ->with('success', 'Booking rejected. Citizen has been notified.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reject booking: ' . $e->getMessage());
        }
    }

    /**
     * Final confirmation of booking
     * Changes status from paid to confirmed
     */
    public function finalConfirm(Request $request, $bookingId)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        $booking = Booking::findOrFail($bookingId);

        // Enforce payment flow: admin can only confirm bookings with 'paid' status
        // Cash bookings must go through treasurer first (staff_verified → treasurer verifies → paid)
        // Cashless bookings go through PayMongo (staff_verified → PayMongo confirms → paid)
        if ($booking->status !== 'paid') {
            if ($booking->status === 'staff_verified') {
                return back()->with('error', 'Payment has not been verified yet. Cash bookings must be paid at the Treasurer\'s Office first. Cashless bookings must complete payment via PayMongo.');
            }
            return back()->with('error', 'Booking must be paid and verified before final confirmation.');
        }
        
        // Ensure booking is fully paid before admin can confirm
        if ($booking->amount_remaining > 0) {
            return back()->with('error', 'Booking has an outstanding balance of ₱' . number_format($booking->amount_remaining, 2) . '. Balance must be settled before confirmation.');
        }

        // Update booking status to confirmed (final state)
        $booking->status = 'confirmed';
        $booking->admin_approved_at = Carbon::now();
        $booking->admin_approved_by = $userId;
        
        // Add admin notes if provided
        if ($request->filled('admin_notes')) {
            $existingNotes = $booking->admin_approval_notes ?? '';
            $booking->admin_approval_notes = $existingNotes . "\n[" . Carbon::now()->format('Y-m-d H:i') . "] " . $request->input('admin_notes');
        }

        $booking->save();

        // Send confirmation notification to citizen
        try {
            $user = User::find($booking->user_id);
            $bookingWithFacility = DB::connection('facilities_db')
                ->table('bookings')
                ->join('facilities', 'bookings.facility_id', '=', 'facilities.facility_id')
                ->where('bookings.id', $bookingId)
                ->selectRaw('bookings.*, facilities.name as facility_name, CONCAT("BK", LPAD(bookings.id, 6, "0")) as booking_reference')
                ->first();
            
            if ($user && $bookingWithFacility) {
                $user->notify(new \App\Notifications\BookingConfirmed($bookingWithFacility));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send booking confirmation notification: ' . $e->getMessage());
        }
        
        return redirect()
            ->route('admin.bookings.review', $bookingId)
            ->with('success', 'Booking confirmed! Citizen will be notified.');
    }
}
