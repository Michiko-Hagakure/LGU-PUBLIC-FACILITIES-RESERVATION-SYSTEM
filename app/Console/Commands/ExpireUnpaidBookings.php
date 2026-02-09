<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExpireUnpaidBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:expire-unpaid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically expire bookings: pending without payment after 24h, staff_verified with remaining balance after 7 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for bookings to expire...');

        $expiredCount = 0;
        $now = Carbon::now();

        // 1. Expire pending bookings with NO payment (amount_paid = 0) after 24 hours
        $unpaidPendingBookings = Booking::where('status', 'pending')
            ->where('amount_paid', '<=', 0)
            ->get();

        foreach ($unpaidPendingBookings as $booking) {
            $createdAt = $booking->created_at;
            if (!$createdAt) continue;
            
            $deadline = $createdAt->copy()->addHours(24);
            if ($now->greaterThan($deadline)) {
                $this->expireBooking($booking, $now, 'No down payment made within 24 hours (auto-expired)');
                $expiredCount++;
            }
        }

        // 2. Expire staff_verified bookings with remaining balance after 7 days
        $partialPaymentBookings = Booking::where('status', 'staff_verified')
            ->where('amount_remaining', '>', 0)
            ->get();

        foreach ($partialPaymentBookings as $booking) {
            $verifiedAt = $booking->staff_verified_at;
            if (!$verifiedAt) continue;
            
            $deadline = $verifiedAt->copy()->addDays(7);
            if ($now->greaterThan($deadline)) {
                $this->expireBooking($booking, $now, 'Remaining balance not settled within 7 days of staff verification (auto-expired)');
                $expiredCount++;
            }
        }

        if ($expiredCount > 0) {
            $this->info("SUCCESS: Expired {$expiredCount} booking(s).");
        } else {
            $this->info("SUCCESS: No bookings to expire. All good!");
        }

        return Command::SUCCESS;
    }

    /**
     * Expire a single booking and notify the citizen.
     */
    private function expireBooking(Booking $booking, Carbon $now, string $reason): void
    {
        try {
            DB::connection('facilities_db')->beginTransaction();

            $booking->update([
                'status' => 'expired',
                'expired_at' => $now,
                'canceled_reason' => $reason,
            ]);

            // Also expire associated payment slips
            \App\Models\PaymentSlip::where('booking_id', $booking->id)
                ->whereIn('status', ['unpaid', 'partial'])
                ->update(['status' => 'expired']);

            DB::connection('facilities_db')->commit();

            // Send expiration notification to citizen
            try {
                $user = User::find($booking->user_id);
                $bookingWithFacility = DB::connection('facilities_db')
                    ->table('bookings')
                    ->join('facilities', 'bookings.facility_id', '=', 'facilities.facility_id')
                    ->where('bookings.id', $booking->id)
                    ->selectRaw('bookings.*, facilities.name as facility_name, CONCAT("BK", LPAD(bookings.id, 6, "0")) as booking_reference')
                    ->first();
                
                if ($user && $bookingWithFacility) {
                    $user->notify(new \App\Notifications\BookingExpired($bookingWithFacility));
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send booking expiration notification: ' . $e->getMessage());
            }

            $citizenName = $booking->applicant_name ?? $booking->user_name ?? 'N/A';
            $this->warn("EXPIRED: Booking #{$booking->id} - Citizen: {$citizenName}");
            $this->line("   Reason: {$reason}");
            $this->newLine();
        } catch (\Exception $e) {
            DB::connection('facilities_db')->rollBack();
            $this->error("Failed to expire booking #{$booking->id}: " . $e->getMessage());
        }
    }
}
