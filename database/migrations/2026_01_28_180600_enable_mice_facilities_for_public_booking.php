<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Enable QC M.I.C.E. facilities for public booking (ordinance approved).
     * Note: The QC M.I.C.E. Auditorium remains unavailable as it's under construction.
     */
    public function up(): void
    {
        // Enable the Convention & Exhibit Hall
        DB::table('facilities')
            ->where('facility_name', 'QC M.I.C.E. Convention & Exhibit Hall')
            ->update([
                'is_available' => true,
                'description' => 'A state-of-the-art convention and exhibit hall suitable for large-scale conferences, trade shows, exhibitions, and city-sponsored programs. Features spacious modern interiors with flexible layout options.',
                'terms_and_conditions' => 'Advance booking required with detailed event proposal. Quezon City LGU events receive priority scheduling.',
            ]);

        // Enable Breakout Room 1
        DB::table('facilities')
            ->where('facility_name', 'M.I.C.E. Breakout Room 1')
            ->update([
                'is_available' => true,
                'description' => 'A modern breakout room perfect for small to medium-sized seminars, training sessions, and workshops. Fully equipped with presentation facilities.',
                'terms_and_conditions' => 'Breakout rooms are in high demand. Booking confirmation sent within 48 hours of request. Government seminars receive priority.',
            ]);

        // Enable Breakout Room 2
        DB::table('facilities')
            ->where('facility_name', 'M.I.C.E. Breakout Room 2')
            ->update([
                'is_available' => true,
                'description' => 'Another versatile breakout room ideal for corporate meetings, team building activities, and educational seminars with smaller groups.',
                'terms_and_conditions' => 'Same terms as Breakout Room 1. Can be combined with Breakout Room 1 for larger events with prior approval.',
            ]);

        // Update the QC location config to reflect ordinance is now approved
        DB::table('locations')
            ->where('location_code', 'QC')
            ->update([
                'config' => json_encode([
                    'payment_mode' => 'per_person',
                    'base_rate' => 150,
                    'currency' => 'PHP',
                    'operating_hours' => ['start' => '07:00', 'end' => '21:00'],
                    'advance_booking_days' => 180,
                    'cancellation_deadline_hours' => 72,
                    'approval_levels' => ['staff', 'admin'],
                    'discount_tiers' => ['pwd' => 20, 'senior' => 20, 'student' => 20],
                    'requires_full_payment' => true,
                    'payment_policy' => 'Full payment required before reservation confirmation',
                    'ordinance_status' => 'approved',
                    'public_booking_status' => 'available'
                ]),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to coming soon status
        DB::table('facilities')
            ->where('facility_name', 'QC M.I.C.E. Convention & Exhibit Hall')
            ->update([
                'is_available' => false,
                'description' => 'A state-of-the-art convention and exhibit hall suitable for large-scale conferences, trade shows, exhibitions, and city-sponsored programs. Features spacious modern interiors with flexible layout options. **Currently accepting only QC-LGU events while ordinance is being finalized.**',
                'terms_and_conditions' => 'Currently prioritizing Quezon City LGU events. Private bookings pending approval of M.I.C.E. Center ordinance. Advance booking required with detailed event proposal.',
            ]);

        DB::table('facilities')
            ->where('facility_name', 'M.I.C.E. Breakout Room 1')
            ->update([
                'is_available' => false,
                'description' => 'A modern breakout room perfect for small to medium-sized seminars, training sessions, and workshops. Fully equipped with presentation facilities. **Currently accepting only QC-LGU events while ordinance is being finalized.**',
                'terms_and_conditions' => 'Breakout rooms are in high demand. Booking confirmation sent within 48 hours of request. Government seminars receive priority. Currently prioritizing QC-LGU events pending ordinance approval.',
            ]);

        DB::table('facilities')
            ->where('facility_name', 'M.I.C.E. Breakout Room 2')
            ->update([
                'is_available' => false,
                'description' => 'Another versatile breakout room ideal for corporate meetings, team building activities, and educational seminars with smaller groups. **Currently accepting only QC-LGU events while ordinance is being finalized.**',
                'terms_and_conditions' => 'Same terms as Breakout Room 1. Can be combined with Breakout Room 1 for larger events with prior approval. Currently prioritizing QC-LGU events pending ordinance approval.',
            ]);

        DB::table('locations')
            ->where('location_code', 'QC')
            ->update([
                'config' => json_encode([
                    'payment_mode' => 'per_person',
                    'base_rate' => 150,
                    'currency' => 'PHP',
                    'operating_hours' => ['start' => '07:00', 'end' => '21:00'],
                    'advance_booking_days' => 180,
                    'cancellation_deadline_hours' => 72,
                    'approval_levels' => ['staff', 'admin'],
                    'discount_tiers' => ['pwd' => 20, 'senior' => 20, 'student' => 20],
                    'requires_full_payment' => true,
                    'payment_policy' => 'Full payment required before reservation confirmation',
                    'ordinance_status' => 'pending',
                    'public_booking_status' => 'coming_soon'
                ]),
            ]);
    }
};
