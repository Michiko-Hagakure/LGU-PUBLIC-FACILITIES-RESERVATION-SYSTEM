<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Booking;
use App\Models\PaymentSlip;
use Carbon\Carbon;

class AutoExpireBookings
{
    /**
     * Automatically expire overdue bookings on admin/staff page loads.
     * Throttled via cache so it only runs once every 15 minutes.
     */
    public function handle(Request $request, Closure $next)
    {
        // Only run on GET requests (page loads, not form submissions)
        if ($request->isMethod('get')) {
            // Throttle: only check once every 15 minutes
            $cacheKey = 'auto_expire_bookings_last_run';
            
            if (!Cache::has($cacheKey)) {
                try {
                    $this->expireOverdueBookings();
                } catch (\Exception $e) {
                    Log::error('AutoExpireBookings middleware error: ' . $e->getMessage());
                }
                
                // Set cache for 15 minutes
                Cache::put($cacheKey, now()->toDateTimeString(), now()->addMinutes(15));
            }
        }

        return $next($request);
    }

    /**
     * Expire bookings that are past their 48-hour payment deadline.
     */
    private function expireOverdueBookings(): void
    {
        // Method 1: Expire staff_verified bookings past 48-hour deadline
        $overdueBookings = Booking::where('status', 'staff_verified')
            ->whereNotNull('staff_verified_at')
            ->get()
            ->filter(function ($booking) {
                $deadline = $booking->staff_verified_at->copy()->addHours(48);
                return Carbon::now()->greaterThan($deadline);
            });

        foreach ($overdueBookings as $booking) {
            try {
                DB::connection('facilities_db')->beginTransaction();

                $booking->update([
                    'status' => 'expired',
                    'expired_at' => Carbon::now(),
                    'canceled_reason' => 'Payment deadline exceeded (auto-expired)',
                ]);

                // Also expire the associated payment slip
                PaymentSlip::where('booking_id', $booking->id)
                    ->where('status', 'unpaid')
                    ->update(['status' => 'expired']);

                DB::connection('facilities_db')->commit();

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
                    Log::error('AutoExpire notification failed for booking #' . $booking->id . ': ' . $e->getMessage());
                }

                Log::info("AutoExpireBookings: Expired booking #{$booking->id}");
            } catch (\Exception $e) {
                DB::connection('facilities_db')->rollBack();
                Log::error("AutoExpireBookings: Failed to expire booking #{$booking->id}: " . $e->getMessage());
            }
        }

        // Method 2: Also check payment slips with explicit deadlines
        $overdueSlips = PaymentSlip::where('status', 'unpaid')
            ->where('payment_deadline', '<', Carbon::now())
            ->get();

        foreach ($overdueSlips as $slip) {
            try {
                $booking = Booking::find($slip->booking_id);

                if ($booking && !in_array($booking->status, ['canceled', 'expired'])) {
                    DB::connection('facilities_db')->beginTransaction();

                    $booking->update([
                        'status' => 'expired',
                        'expired_at' => Carbon::now(),
                        'canceled_reason' => 'Payment deadline exceeded (auto-expired)',
                    ]);

                    $slip->update(['status' => 'expired']);

                    DB::connection('facilities_db')->commit();

                    // Send expiration notification
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
                        Log::error('AutoExpire notification failed for booking #' . $booking->id . ': ' . $e->getMessage());
                    }

                    Log::info("AutoExpireBookings: Expired booking #{$booking->id} via payment slip #{$slip->slip_number}");
                }
            } catch (\Exception $e) {
                DB::connection('facilities_db')->rollBack();
                Log::error("AutoExpireBookings: Failed to expire via slip #{$slip->id}: " . $e->getMessage());
            }
        }
    }
}
