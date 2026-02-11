<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

return new class extends Migration
{
    /**
     * Fix existing bookings that were created with old code which skipped payment for cash bookings.
     * These bookings have status='pending' with fake payment data (amount_paid > 0, down_payment_paid_at set)
     * but no actual paid payment slip from the treasurer or PayMongo.
     */
    public function up(): void
    {
        $db = DB::connection('facilities_db');

        // Find all 'pending' bookings that have NO paid payment slip
        // (meaning the payment was faked by the old code, not actually confirmed by treasurer/PayMongo)
        $fakePaidBookings = $db->table('bookings')
            ->where('status', 'pending')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('payment_slips')
                    ->whereColumn('payment_slips.booking_id', 'bookings.id')
                    ->where('payment_slips.status', 'paid');
            })
            ->get();

        foreach ($fakePaidBookings as $booking) {
            Log::info("Migration: demoting booking #{$booking->id} from 'pending' to 'awaiting_payment' (no real payment found)");

            // Reset booking to awaiting_payment with correct payment fields
            $db->table('bookings')
                ->where('id', $booking->id)
                ->update([
                    'status' => 'awaiting_payment',
                    'amount_paid' => 0,
                    'amount_remaining' => $booking->total_amount,
                    'down_payment_paid_at' => null,
                    'updated_at' => Carbon::now(),
                ]);

            // Check if an unpaid payment slip already exists for this booking
            $hasUnpaidSlip = $db->table('payment_slips')
                ->where('booking_id', $booking->id)
                ->where('status', 'unpaid')
                ->exists();

            // Create an unpaid down payment slip if none exists (for cash bookings)
            if (!$hasUnpaidSlip && $booking->down_payment_amount > 0) {
                $year = Carbon::now()->year;
                $lastSlip = $db->table('payment_slips')
                    ->where('slip_number', 'like', "PS-{$year}-%")
                    ->orderBy('slip_number', 'desc')
                    ->first();

                if ($lastSlip) {
                    $lastNumber = intval(substr($lastSlip->slip_number, -6));
                    $newNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
                } else {
                    $newNumber = '000001';
                }
                $slipNumber = "PS-{$year}-{$newNumber}";

                $db->table('payment_slips')->insert([
                    'slip_number' => $slipNumber,
                    'booking_id' => $booking->id,
                    'amount_due' => $booking->down_payment_amount,
                    'payment_deadline' => Carbon::now()->addDays(3),
                    'status' => 'unpaid',
                    'payment_method' => $booking->payment_method ?? 'cash',
                    'notes' => 'Down payment (' . $booking->payment_tier . '%) — pay at City Treasurer\'s Office to submit booking for staff review.',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                Log::info("Migration: created unpaid payment slip {$slipNumber} for booking #{$booking->id}");
            }
        }

        Log::info("Migration: fixed " . count($fakePaidBookings) . " bookings with fake payment data");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot reliably reverse data fixes
    }
};
