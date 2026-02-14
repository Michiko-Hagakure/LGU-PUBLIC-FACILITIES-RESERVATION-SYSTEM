<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\PaymentSlip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PayMongoWebhookController extends Controller
{
    /**
     * Handle incoming PayMongo webhook events.
     * 
     * Supported events:
     * - checkout_session.payment.paid
     * - payment.paid
     */
    public function handle(Request $request)
    {
        $payload = $request->all();

        // Verify webhook signature if secret is configured
        $webhookSecret = config('payment.paymongo_webhook_secret');
        if (!empty($webhookSecret)) {
            $signature = $request->header('Paymongo-Signature');
            if (!$this->verifySignature($payload, $signature, $webhookSecret)) {
                Log::warning('PayMongo webhook: invalid signature');
                return response()->json(['error' => 'Invalid signature'], 401);
            }
        }

        $eventType = $payload['data']['attributes']['type'] ?? null;
        $eventData = $payload['data']['attributes']['data'] ?? null;

        if (!$eventType || !$eventData) {
            Log::warning('PayMongo webhook: missing event type or data', ['payload' => $payload]);
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        Log::info("PayMongo webhook received: {$eventType}", [
            'event_id' => $payload['data']['id'] ?? 'unknown',
        ]);

        switch ($eventType) {
            case 'checkout_session.payment.paid':
                return $this->handleCheckoutSessionPaid($eventData);

            case 'payment.paid':
                return $this->handlePaymentPaid($eventData);

            default:
                Log::info("PayMongo webhook: unhandled event type '{$eventType}'");
                return response()->json(['status' => 'ignored']);
        }
    }

    /**
     * Handle checkout_session.payment.paid event.
     * This fires when a checkout session payment is completed.
     */
    private function handleCheckoutSessionPaid(array $checkoutSession)
    {
        $checkoutSessionId = $checkoutSession['id'] ?? null;
        $metadata = $checkoutSession['attributes']['metadata'] ?? [];
        $payments = $checkoutSession['attributes']['payments'] ?? [];

        if (!$checkoutSessionId) {
            Log::warning('PayMongo webhook: checkout session missing ID');
            return response()->json(['error' => 'Missing checkout session ID'], 400);
        }

        Log::info("PayMongo webhook: processing checkout_session.payment.paid", [
            'checkout_session_id' => $checkoutSessionId,
            'metadata' => $metadata,
        ]);

        // Get payment details from the first successful payment
        $paymentId = null;
        $paymentMethod = 'paymongo';
        $paidAmount = 0;

        if (!empty($payments)) {
            $payment = $payments[0];
            $paymentId = $payment['id'] ?? null;
            $paymentAttrs = $payment['attributes'] ?? [];
            $paidAmount = ($paymentAttrs['amount'] ?? 0) / 100;
            $paymentMethod = $paymentAttrs['source']['type'] ?? 'paymongo';
        }

        // Determine if this is a booking down payment or a payment slip payment
        $bookingId = $metadata['booking_id'] ?? null;
        $paymentSlipId = $metadata['payment_slip_id'] ?? null;

        // Try to match by payment slip first (post-approval payments)
        if ($paymentSlipId) {
            $this->processPaymentSlipWebhook($paymentSlipId, $checkoutSessionId, $paymentId, $paymentMethod, $paidAmount);
        }

        // Then try to match by booking (down payment at booking time)
        if ($bookingId) {
            $this->processBookingDownPaymentWebhook($bookingId, $checkoutSessionId, $paymentId, $paidAmount);
        }

        // Fallback: search by checkout session ID in bookings table
        if (!$bookingId && !$paymentSlipId) {
            $booking = Booking::where('paymongo_checkout_id', $checkoutSessionId)->first();
            if ($booking) {
                $this->processBookingDownPaymentWebhook($booking->id, $checkoutSessionId, $paymentId, $paidAmount);
            } else {
                // Check payment slips table
                $slip = DB::connection('facilities_db')
                    ->table('payment_slips')
                    ->where('paymongo_checkout_id', $checkoutSessionId)
                    ->first();
                if ($slip) {
                    $this->processPaymentSlipWebhook($slip->id, $checkoutSessionId, $paymentId, $paymentMethod, $paidAmount);
                } else {
                    Log::warning("PayMongo webhook: no matching booking or payment slip for checkout session {$checkoutSessionId}");
                }
            }
        }

        return response()->json(['status' => 'processed']);
    }

    /**
     * Handle payment.paid event (generic payment confirmation).
     */
    private function handlePaymentPaid(array $paymentData)
    {
        $paymentId = $paymentData['id'] ?? null;
        Log::info("PayMongo webhook: payment.paid received", ['payment_id' => $paymentId]);

        // This event is a fallback — most processing is done via checkout_session.payment.paid
        return response()->json(['status' => 'acknowledged']);
    }

    /**
     * Process webhook for a booking down payment.
     */
    private function processBookingDownPaymentWebhook(int $bookingId, string $checkoutSessionId, ?string $paymentId, float $paidAmount)
    {
        $booking = Booking::find($bookingId);

        if (!$booking) {
            Log::warning("PayMongo webhook: booking #{$bookingId} not found");
            return;
        }

        // Already paid — skip
        if ($booking->down_payment_paid_at) {
            Log::info("PayMongo webhook: booking #{$bookingId} already paid, skipping");
            return;
        }

        // Verify checkout session ID matches
        if ($booking->paymongo_checkout_id !== $checkoutSessionId) {
            Log::warning("PayMongo webhook: checkout session ID mismatch for booking #{$bookingId}", [
                'expected' => $booking->paymongo_checkout_id,
                'received' => $checkoutSessionId,
            ]);
            // Still process — the webhook is authoritative
        }

        $wasAwaitingPayment = $booking->status === 'awaiting_payment';

        $booking->update([
            'status' => $wasAwaitingPayment ? 'pending' : $booking->status,
            'amount_paid' => $booking->down_payment_amount,
            'amount_remaining' => $booking->total_amount - $booking->down_payment_amount,
            'down_payment_paid_at' => Carbon::now(),
            'paymongo_payment_id' => $paymentId,
        ]);

        // Auto-cancel overlapping unpaid bookings — whoever pays first gets the slot
        if ($wasAwaitingPayment) {
            Booking::cancelOverlappingUnpaidBookings($booking);
        }

        Log::info("PayMongo webhook: booking #{$bookingId} down payment confirmed" . ($wasAwaitingPayment ? ' (promoted to pending)' : ''), [
            'amount' => $booking->down_payment_amount,
            'payment_id' => $paymentId,
        ]);

        // Send notifications if booking was just promoted from awaiting_payment
        if ($wasAwaitingPayment) {
            $this->sendBookingNotifications($booking);
        }
    }

    /**
     * Process webhook for a payment slip (post-approval remaining balance).
     */
    private function processPaymentSlipWebhook(int $paymentSlipId, string $checkoutSessionId, ?string $paymentId, string $paymentMethod, float $paidAmount)
    {
        $paymentSlip = DB::connection('facilities_db')
            ->table('payment_slips')
            ->where('id', $paymentSlipId)
            ->first();

        if (!$paymentSlip) {
            Log::warning("PayMongo webhook: payment slip #{$paymentSlipId} not found");
            return;
        }

        // Already paid — skip
        if ($paymentSlip->status === 'paid') {
            Log::info("PayMongo webhook: payment slip #{$paymentSlipId} already paid, skipping");
            return;
        }

        // Generate OR number
        $orNumber = $this->generateOfficialReceiptNumber();

        // Update payment slip as paid
        DB::connection('facilities_db')
            ->table('payment_slips')
            ->where('id', $paymentSlipId)
            ->update([
                'status' => 'paid',
                'payment_method' => $paymentMethod,
                'payment_channel' => 'paymongo',
                'transaction_reference' => $paymentId ?? $checkoutSessionId,
                'gateway_reference_number' => $paymentId ?? $checkoutSessionId,
                'or_number' => $orNumber,
                'paid_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

        // Update booking payment tracking and status
        $booking = Booking::find($paymentSlip->booking_id);
        if ($booking) {
            $paymentAmount = $paidAmount > 0 ? $paidAmount : $paymentSlip->amount_due;
            $newAmountPaid = ($booking->amount_paid ?? 0) + $paymentAmount;
            $newAmountRemaining = max(0, ($booking->total_amount ?? 0) - $newAmountPaid);

            $updateData = [
                'amount_paid' => $newAmountPaid,
                'amount_remaining' => $newAmountRemaining,
                'updated_at' => Carbon::now(),
            ];

            // Only set 'paid' if fully paid; otherwise keep current status
            if ($newAmountRemaining <= 0) {
                $updateData['status'] = 'paid';
            }

            $booking->update($updateData);
        }

        Log::info("PayMongo webhook: payment slip #{$paymentSlipId} confirmed via webhook", [
            'booking_id' => $paymentSlip->booking_id,
            'amount' => $paymentSlip->amount_due,
            'payment_id' => $paymentId,
            'or_number' => $orNumber,
        ]);

        // Send notification
        try {
            $booking = DB::connection('facilities_db')
                ->table('bookings')
                ->where('id', $paymentSlip->booking_id)
                ->first();

            if ($booking) {
                $user = \App\Models\User::find($booking->user_id);
                $bookingWithFacility = DB::connection('facilities_db')
                    ->table('bookings')
                    ->join('facilities', 'bookings.facility_id', '=', 'facilities.facility_id')
                    ->where('bookings.id', $paymentSlip->booking_id)
                    ->selectRaw('bookings.*, facilities.name as facility_name, CONCAT("BK", LPAD(bookings.id, 6, "0")) as booking_reference')
                    ->first();

                $paymentSlipFresh = DB::connection('facilities_db')
                    ->table('payment_slips')
                    ->where('id', $paymentSlipId)
                    ->first();

                if ($user && $bookingWithFacility && $paymentSlipFresh) {
                    $user->notify(new \App\Notifications\PaymentConfirmed($bookingWithFacility, $paymentSlipFresh));
                }
            }
        } catch (\Exception $e) {
            Log::error('PayMongo webhook: failed to send payment notification: ' . $e->getMessage());
        }
    }

    /**
     * Verify PayMongo webhook signature.
     * PayMongo signs webhooks using HMAC SHA256.
     */
    private function verifySignature(array $payload, ?string $signatureHeader, string $secret): bool
    {
        if (empty($signatureHeader)) {
            return false;
        }

        // PayMongo signature format: t=<timestamp>,te=<test_signature>,li=<live_signature>
        $parts = explode(',', $signatureHeader);
        $timestamp = null;
        $signatures = [];

        foreach ($parts as $part) {
            $kv = explode('=', $part, 2);
            if (count($kv) === 2) {
                if ($kv[0] === 't') {
                    $timestamp = $kv[1];
                } elseif (in_array($kv[0], ['te', 'li'])) {
                    $signatures[] = $kv[1];
                }
            }
        }

        if (!$timestamp || empty($signatures)) {
            return false;
        }

        // Compute expected signature
        $rawPayload = json_encode($payload);
        $signedPayload = $timestamp . '.' . $rawPayload;
        $expectedSignature = hash_hmac('sha256', $signedPayload, $secret);

        foreach ($signatures as $sig) {
            if (hash_equals($expectedSignature, $sig)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generate a unique Official Receipt number.
     */
    private function generateOfficialReceiptNumber(): string
    {
        $year = Carbon::now()->year;
        $prefix = "OR-{$year}-";

        $lastOR = DB::connection('facilities_db')
            ->table('payment_slips')
            ->where('or_number', 'like', "{$prefix}%")
            ->orderBy('or_number', 'desc')
            ->value('or_number');

        if ($lastOR) {
            $lastNumber = intval(substr($lastOR, -6));
            $newNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '000001';
        }

        return $prefix . $newNumber;
    }

    /**
     * Send booking submission notifications to citizen and staff.
     * Called after cashless payment is confirmed via webhook.
     */
    private function sendBookingNotifications(Booking $booking)
    {
        try {
            $user = \App\Models\User::find($booking->user_id);
            $bookingWithFacility = DB::connection('facilities_db')
                ->table('bookings')
                ->join('facilities', 'bookings.facility_id', '=', 'facilities.facility_id')
                ->where('bookings.id', $booking->id)
                ->selectRaw('bookings.*, facilities.name as facility_name, CONCAT("BK", LPAD(bookings.id, 6, "0")) as booking_reference')
                ->first();

            if ($user && $bookingWithFacility) {
                $user->notify(new \App\Notifications\BookingSubmitted($bookingWithFacility));

                $staffMembers = \App\Models\User::where('subsystem_role_id', 3)
                    ->where('subsystem_id', 4)
                    ->get();

                foreach ($staffMembers as $staff) {
                    $staff->notify(new \App\Notifications\BookingSubmitted($bookingWithFacility));
                }
            }
        } catch (\Exception $e) {
            Log::error('PayMongo webhook: failed to send booking notification: ' . $e->getMessage());
        }
    }
}
