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
     * This route is OUTSIDE auth middleware so it works even if session expires during checkout
     */
    public function success(Request $request, $bookingId)
    {
        // Find booking by ID only (no session check - user may have lost session during PayMongo checkout)
        $booking = Booking::find($bookingId);

        if (!$booking) {
            Log::error("PayMongo success callback: booking #{$bookingId} not found");
            return redirect()->route('login')->with('error', 'Booking not found. Please login and check your reservations.');
        }

        // If already paid (e.g. user refreshed the page), redirect appropriately
        if ($booking->down_payment_paid_at) {
            return $this->redirectAfterPayment($booking, 'Booking submitted successfully!');
        }

        // Verify payment with PayMongo
        $checkoutSessionId = $booking->paymongo_checkout_id;
        if (!$checkoutSessionId) {
            return $this->redirectAfterPayment($booking, 'Booking submitted. Payment verification pending.', 'warning');
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

                    return $this->redirectAfterPayment($booking, 'Payment received via GCash! Booking submitted successfully.');
                }
            }

            // Payment not confirmed yet — could be pending
            Log::warning("PayMongo payment not yet confirmed for booking #{$bookingId}");
            return $this->redirectAfterPayment($booking, 'Booking submitted. Your GCash payment is being processed and will be confirmed shortly.', 'warning');

        } catch (\Exception $e) {
            Log::error("PayMongo verification error for booking #{$bookingId}: " . $e->getMessage());
            return $this->redirectAfterPayment($booking, 'Booking submitted. Payment verification is in progress.', 'warning');
        }
    }

    /**
     * Retry GCash payment for a booking with unpaid down payment
     */
    public function retry(Request $request, $bookingId)
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

        // Only allow retry if payment hasn't been made
        if ($booking->down_payment_paid_at) {
            return redirect()->route('citizen.booking.confirmation', $bookingId)
                ->with('info', 'Payment has already been received for this booking.');
        }

        // Only allow for GCash bookings
        if ($booking->payment_method !== 'gcash') {
            return redirect()->route('citizen.booking.confirmation', $bookingId)
                ->with('error', 'Retry is only available for GCash payments.');
        }

        try {
            $paymongo = new PaymongoService();

            // Get facility name
            $facility = DB::connection('facilities_db')
                ->table('facilities')
                ->where('facility_id', $booking->facility_id)
                ->first();

            $checkoutData = (object) [
                'id' => $booking->id,
                'booking_id' => $booking->id,
                'amount_due' => $booking->down_payment_amount,
                'slip_number' => 'BK' . str_pad($booking->id, 6, '0', STR_PAD_LEFT),
            ];

            $bookingData = (object) [
                'facility_name' => $facility->name ?? 'Facility Reservation',
            ];

            $successUrl = url("/citizen/paymongo/success/{$bookingId}");
            $cancelUrl = url("/citizen/paymongo/failed/{$bookingId}");

            $result = $paymongo->createCheckoutSession($checkoutData, $bookingData, $successUrl, $cancelUrl);

            if ($result['success']) {
                $booking->update([
                    'paymongo_checkout_id' => $result['checkout_session_id'],
                ]);

                return redirect()->away($result['checkout_url']);
            } else {
                Log::error("PayMongo retry failed for booking #{$bookingId}: " . ($result['error'] ?? 'Unknown'));
                return redirect()->route('citizen.booking.confirmation', $bookingId)
                    ->with('error', 'Unable to create GCash payment. Please try again or pay at the City Treasurer\'s Office.');
            }
        } catch (\Exception $e) {
            Log::error("PayMongo retry error for booking #{$bookingId}: " . $e->getMessage());
            return redirect()->route('citizen.booking.confirmation', $bookingId)
                ->with('error', 'Payment service error. Please try again later or pay at the City Treasurer\'s Office.');
        }
    }

    /**
     * Handle failed/cancelled payment from PayMongo checkout
     * This route is OUTSIDE auth middleware so it works even if session expires during checkout
     */
    public function failed(Request $request, $bookingId)
    {
        $booking = Booking::find($bookingId);

        if (!$booking) {
            Log::error("PayMongo failed callback: booking #{$bookingId} not found");
            return redirect()->route('login')->with('error', 'Booking not found. Please login and check your reservations.');
        }

        Log::info("PayMongo payment cancelled/failed for booking #{$bookingId}");

        return $this->redirectAfterPayment(
            $booking,
            'GCash payment was not completed. Your booking has been submitted but payment is still required. You can pay at the City Treasurer\'s Office or retry via your booking details.',
            'warning'
        );
    }

    /**
     * Redirect user after PayMongo callback.
     * If session is active, go to confirmation page. Otherwise, go to login with a message.
     */
    private function redirectAfterPayment(Booking $booking, string $message, string $type = 'success')
    {
        $userId = session('user_id');

        if ($userId && $userId == $booking->user_id) {
            return redirect()->route('citizen.booking.confirmation', $booking->id)
                ->with($type, $message);
        }

        // Session expired — redirect to login with context
        return redirect()->route('login')
            ->with($type, $message . ' Please login to view your booking #BK' . str_pad($booking->id, 6, '0', STR_PAD_LEFT) . '.');
    }
}
