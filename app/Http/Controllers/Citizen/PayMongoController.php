<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\PaymongoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PayMongoController extends Controller
{
    /**
     * Handle successful payment from PayMongo checkout
     */
    public function success(Request $request, $bookingId)
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        $booking = Booking::where('id', $bookingId)
            ->where('user_id', $userId)
            ->first();

        if (!$booking) {
            return redirect()->route('citizen.reservations')
                ->with('error', 'Booking not found.');
        }

        // If already paid (e.g. user refreshed the page), just redirect to confirmation
        if ($booking->down_payment_paid_at) {
            return redirect()->route('citizen.booking.confirmation', $bookingId)
                ->with('success', 'Booking submitted successfully!');
        }

        // Verify payment with PayMongo
        $checkoutSessionId = $booking->paymongo_checkout_id;
        if (!$checkoutSessionId) {
            return redirect()->route('citizen.booking.confirmation', $bookingId)
                ->with('warning', 'Booking submitted. Payment verification pending.');
        }

        try {
            $paymongo = new PaymongoService();
            $session = $paymongo->getCheckoutSession($checkoutSessionId);

            if ($session['success']) {
                $paymentDetails = $paymongo->getPaymentDetails($checkoutSessionId);
                $isPaid = $paymongo->isPaymentSuccessful($checkoutSessionId);

                if ($isPaid) {
                    // Update booking with payment confirmation
                    $booking->update([
                        'amount_paid' => $booking->down_payment_amount,
                        'amount_remaining' => $booking->total_amount - $booking->down_payment_amount,
                        'down_payment_paid_at' => Carbon::now(),
                        'paymongo_payment_id' => $paymentDetails['payment_id'] ?? null,
                    ]);

                    Log::info("PayMongo payment confirmed for booking #{$bookingId}", [
                        'amount' => $booking->down_payment_amount,
                        'payment_id' => $paymentDetails['payment_id'] ?? 'N/A',
                    ]);

                    return redirect()->route('citizen.booking.confirmation', $bookingId)
                        ->with('success', 'Payment received via GCash! Booking submitted successfully.');
                }
            }

            // Payment not confirmed yet — could be pending
            Log::warning("PayMongo payment not yet confirmed for booking #{$bookingId}");
            return redirect()->route('citizen.booking.confirmation', $bookingId)
                ->with('warning', 'Booking submitted. Your GCash payment is being processed and will be confirmed shortly.');

        } catch (\Exception $e) {
            Log::error("PayMongo verification error for booking #{$bookingId}: " . $e->getMessage());
            return redirect()->route('citizen.booking.confirmation', $bookingId)
                ->with('warning', 'Booking submitted. Payment verification is in progress.');
        }
    }

    /**
     * Handle failed/cancelled payment from PayMongo checkout
     */
    public function failed(Request $request, $bookingId)
    {
        $userId = session('user_id');
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        $booking = Booking::where('id', $bookingId)
            ->where('user_id', $userId)
            ->first();

        if (!$booking) {
            return redirect()->route('citizen.reservations')
                ->with('error', 'Booking not found.');
        }

        Log::info("PayMongo payment cancelled/failed for booking #{$bookingId}");

        // Booking was created but payment was not completed
        // Redirect to confirmation page with a warning
        return redirect()->route('citizen.booking.confirmation', $bookingId)
            ->with('warning', 'GCash payment was not completed. Your booking has been submitted but payment is still required. You can pay at the City Treasurer\'s Office.');
    }
}
