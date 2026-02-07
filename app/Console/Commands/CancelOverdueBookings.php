<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\PaymentSlip;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CancelOverdueBookings extends Command
{
    protected $signature = 'bookings:cancel-overdue';
    protected $description = 'Cancel bookings with overdue payment deadlines';

    public function handle()
    {
        $this->info('Checking for overdue payment slips...');

        $overdueSlips = PaymentSlip::where('status', 'unpaid')
            ->where('payment_deadline', '<', Carbon::now())
            ->get();

        if ($overdueSlips->isEmpty()) {
            $this->info('No overdue payment slips found.');
            return 0;
        }

        $canceledCount = 0;

        foreach ($overdueSlips as $slip) {
            try {
                DB::connection('facilities_db')->beginTransaction();

                $booking = Booking::find($slip->booking_id);

                if ($booking && !in_array($booking->status, ['canceled', 'expired'])) {
                    $booking->update([
                        'status' => 'expired',
                        'expired_at' => Carbon::now(),
                        'canceled_reason' => 'Payment deadline exceeded (auto-expired)',
                    ]);

                    $slip->update([
                        'status' => 'expired',
                    ]);

                    // Send expiration notification to citizen
                    try {
                        $user = \App\Models\User::find($booking->user_id);
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

                    $canceledCount++;
                    $this->info("Expired booking #{$booking->id} - Payment slip {$slip->slip_number}");
                }

                DB::connection('facilities_db')->commit();
            } catch (\Exception $e) {
                DB::connection('facilities_db')->rollBack();
                $this->error("Failed to expire booking #{$slip->booking_id}: " . $e->getMessage());
            }
        }

        $this->info("Total bookings expired: {$canceledCount}");
        return 0;
    }
}
