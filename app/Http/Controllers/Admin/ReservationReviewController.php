<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\PaymentSlip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Http\Requests\Admin\ApproveReservationRequest; // Create this file
use App\Http\Requests\Admin\RejectReservationRequest; // Create this file

class ReservationReviewController extends Controller
{
    /**
     * Display list of reservations for review.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        
        $reservations = Booking::with(['facility', 'user'])
                              ->when($status !== 'all', function ($query) use ($status) {
                                  return $query->where('status', $status);
                              })
                              ->orderBy('created_at', 'desc')
                              ->paginate(15);

        // Pre-calculate status counts (can be moved to a dedicated service/trait if used everywhere)
        $statusCounts = [
            'pending' => Booking::where('status', 'pending')->count(),
            'approved' => Booking::where('status', 'approved')->count(),
            'rejected' => Booking::where('status', 'rejected')->count(),
            'all' => Booking::count()
        ];

        return view('admin.reservations.index', compact('reservations', 'statusCounts', 'status'));
    }

    /**
     * Display detailed view of reservation for review.
     */
    public function show($id)
    {
        $reservation = Booking::with(['facility', 'user'])->findOrFail($id);
        
        // Get uploaded files (assuming file fields are named 'application_form_path', etc.)
        $files = $this->getUploadedFiles($reservation);
        
        return view('admin.reservations.show', compact('reservation', 'files'));
    }

    /**
     * Approve the reservation and create a payment slip.
     */
    public function approve(ApproveReservationRequest $request, $id)
    {
        $reservation = Booking::findOrFail($id);
        $validated = $request->validated();
        
        // Check if already approved or rejected
        if ($reservation->status !== 'pending') {
            return redirect()->back()->with('error', 'Reservation is already reviewed.');
        }

        // 1. Update reservation status
        $reservation->status = 'approved';
        $reservation->approved_by = Auth::id();
        $reservation->approved_at = now();
        $reservation->admin_notes = $validated['admin_notes'] ?? null;
        $reservation->save();

        // 2. Generate Payment Slip (if total_fee > 0)
        $paymentSlip = null;
        if ($reservation->total_fee > 0) {
            $paymentSlip = $this->createPaymentSlip($reservation);
        }

        // 3. Send Notification
        $this->sendApprovalNotification($reservation, $paymentSlip);

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Reservation has been approved and payment slip generated.');
    }

    /**
     * Reject the reservation.
     */
    public function reject(RejectReservationRequest $request, $id)
    {
        $reservation = Booking::findOrFail($id);
        $validated = $request->validated();

        if ($reservation->status !== 'pending') {
            return redirect()->back()->with('error', 'Reservation is already reviewed.');
        }

        // 1. Update reservation status
        $reservation->status = 'rejected';
        $reservation->rejected_by = Auth::id();
        $reservation->rejected_at = now();
        $reservation->rejection_reason = $validated['rejection_reason'];
        $reservation->save();

        // 2. Send Notification
        $this->sendRejectionNotification($reservation);

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Reservation has been rejected.');
    }
    
    /**
     * Download the specified document (kept as original, needs cleanup).
     */
    public function downloadDocument($id, $type)
    {
        $reservation = Booking::findOrFail($id);
        $path = $this->getDocumentPath($reservation, $type);
        
        if ($path && Storage::disk('local')->exists($path)) {
            // Use download for real file download
            return Storage::disk('local')->download($path, basename($path)); 
        }

        return redirect()->back()->with('error', 'Document not found.');
    }

    /**
     * Preview the specified document (kept as original, needs cleanup).
     */
    public function previewDocument($id, $type)
    {
        $reservation = Booking::findOrFail($id);
        $path = $this->getDocumentPath($reservation, $type);
        
        if ($path && Storage::disk('local')->exists($path)) {
            // Use response with headers for preview in browser
            return response()->file(Storage::disk('local')->path($path));
        }

        return redirect()->back()->with('error', 'Document not found.');
    }

    /**
     * Helper to get document paths.
     */
    private function getDocumentPath(Booking $reservation, string $type): ?string
    {
        // Add more file types as needed based on your Booking model structure
        $documentMap = [
            'application_form' => $reservation->application_form_path,
            'id_scan' => $reservation->id_scan_path,
            'proof_of_address' => $reservation->proof_of_address_path,
            // Add other document paths here
        ];

        return $documentMap[$type] ?? null;
    }

    /**
     * Helper to get list of uploaded files for the view.
     */
    private function getUploadedFiles(Booking $reservation): array
    {
        $files = [];
        
        // Example: Check if the paths exist in the model
        if ($reservation->application_form_path) {
            $files['Application Form'] = 'application_form';
        }
        if ($reservation->id_scan_path) {
            $files['ID Scan'] = 'id_scan';
        }
        if ($reservation->proof_of_address_path) {
            $files['Proof of Address'] = 'proof_of_address';
        }
        
        return $files;
    }

    /**
     * Generate and store a payment slip.
     */
    private function createPaymentSlip(Booking $reservation): PaymentSlip
    {
        $paymentSlip = PaymentSlip::create([
            'reservation_id' => $reservation->id,
            'user_id' => $reservation->user_id,
            'generated_by' => Auth::id(),
            'amount' => $reservation->total_fee,
            'due_date' => Carbon::now()->addDays(7), // 7 days to pay
            'status' => 'unpaid'
        ]);

        return $paymentSlip;
    }

    /**
     * Send approval notification to citizen
     */
    private function sendApprovalNotification(Booking $reservation, ?PaymentSlip $paymentSlip = null): void
    {
        // TODO: Implement email/SMS/app notification logic here
        \Log::info('Reservation Approved:', [
            'reservation_id' => $reservation->id,
            'citizen_email' => $reservation->applicant_email,
            'event_name' => $reservation->event_name,
            'payment_slip_number' => $paymentSlip ? $paymentSlip->slip_number : null,
            'payment_amount' => $paymentSlip ? $paymentSlip->amount : null,
            'payment_due_date' => $paymentSlip ? $paymentSlip->due_date : null
        ]);
    }

    /**
     * Send rejection notification to citizen
     */
    private function sendRejectionNotification(Booking $reservation): void
    {
        // TODO: Implement email/SMS/app notification logic here
        \Log::info('Reservation Rejected:', [
            'reservation_id' => $reservation->id,
            'citizen_email' => $reservation->applicant_email,
            'event_name' => $reservation->event_name,
            'reason' => $reservation->rejection_reason,
        ]);
    }
}