<?php

namespace App\Http\Controllers;

use App\Models\PaymentSlip;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Admin\MarkPaymentPaidRequest; // Requires you to create this file

class PaymentSlipController extends Controller
{
    /**
     * Display citizen's payment slips (Citizen Portal).
     */
    public function citizenIndex()
    {
        $user = Auth::user();
        
        // Guardrail: Authentication should be handled by 'auth:web' middleware on the route
        if (!$user) {
             return redirect('/login')->with('error', 'Authentication required to view payment slips.');
        }
        
        $paymentSlips = PaymentSlip::where('user_id', $user->id)
                                  ->with(['booking.facility', 'generatedBy'])
                                  ->orderBy('created_at', 'desc')
                                  ->get();

        return view('citizen.payment-slips.index', compact('paymentSlips'));
    }

    /**
     * Display specific payment slip for citizen.
     */
    public function citizenShow(int $id)
    {
        $user = Auth::user();
        
        // SECURITY FIX: Ensure the user owns the slip
        $paymentSlip = PaymentSlip::where('id', $id)
                                  ->where('user_id', $user->id) 
                                  ->with(['booking.facility', 'generatedBy'])
                                  ->firstOrFail();

        return view('citizen.payment-slips.show', compact('paymentSlip'));
    }
    
    /**
     * Download payment slip PDF for citizen.
     */
    public function citizenDownloadPdf(int $id)
    {
        $user = Auth::user();
        
        // SECURITY FIX: Ensure the user owns the slip
        $paymentSlip = PaymentSlip::where('id', $id)
                                  ->where('user_id', $user->id)
                                  ->with(['booking.facility', 'generatedBy'])
                                  ->firstOrFail();
                                  
        // TODO: Implement PDF generation logic (using DomPDF or similar)
        // For now, redirect or return a simple response
        return response()->json([
            'status' => 'error', 
            'message' => 'PDF generation not yet implemented.', 
            'slip_id' => $id
        ]);
    }

    /**
     * Admin/Cashier function to mark a payment slip as paid.
     */
    public function markAsPaid(MarkPaymentPaidRequest $request, int $id)
    {
        // Validation is handled by MarkPaymentPaidRequest
        $paymentSlip = PaymentSlip::findOrFail($id);
        
        if ($paymentSlip->status !== 'unpaid') {
            return response()->json(['status' => 'error', 'message' => 'Payment slip is not in unpaid status']);
        }

        $paymentSlip->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_method' => $request->payment_method,
            'cashier_notes' => $request->cashier_notes,
            'paid_by_cashier' => Auth::id() // Record the cashier/admin ID
        ]);

        Log::info('Payment Slip Marked as Paid:', ['slip_number' => $paymentSlip->slip_number]);

        return response()->json(['status' => 'success', 'message' => 'Payment recorded successfully!']);
    }

    /**
     * Mark expired payment slips (Admin/System function).
     */
    public function markExpired()
    {
        $expiredCount = PaymentSlip::where('status', 'unpaid')
                                  ->where('due_date', '<', now())
                                  ->update(['status' => 'expired']);

        return response()->json(['status' => 'success', 'message' => "Marked {$expiredCount} payment slips as expired"]);
    }
}