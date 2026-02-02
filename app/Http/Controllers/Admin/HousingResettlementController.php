<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HousingResettlementController extends Controller
{
    /**
     * Display all facility requests from Housing and Resettlement
     */
    public function index()
    {
        $requests = DB::connection('facilities_db')
            ->table('bookings')
            ->join('facilities', 'bookings.facility_id', '=', 'facilities.facility_id')
            ->where('bookings.source_system', 'Housing_Resettlement')
            ->orderBy('bookings.created_at', 'desc')
            ->select(
                'bookings.id',
                'bookings.event_name',
                'bookings.event_description',
                'bookings.purpose',
                'bookings.start_time',
                'bookings.end_time',
                'bookings.expected_attendees',
                'bookings.applicant_name',
                'bookings.applicant_email',
                'bookings.applicant_phone',
                'bookings.special_requests',
                'bookings.status',
                'bookings.created_at',
                'bookings.updated_at',
                'facilities.name as facility_name',
                'facilities.capacity as facility_capacity'
            )
            ->get()
            ->map(function ($request) {
                $request->booking_reference = 'BK' . str_pad($request->id, 6, '0', STR_PAD_LEFT);
                return $request;
            });

        // Count stats
        $stats = [
            'total' => $requests->count(),
            'pending' => $requests->where('status', 'pending')->count(),
            'confirmed' => $requests->where('status', 'confirmed')->count(),
            'total_attendees' => $requests->sum('expected_attendees'),
        ];

        return view('admin.housing-resettlement.index', compact('requests', 'stats'));
    }

    /**
     * Approve a Housing and Resettlement request
     */
    public function approve(Request $request, $id)
    {
        try {
            $booking = DB::connection('facilities_db')
                ->table('bookings')
                ->where('id', $id)
                ->where('source_system', 'Housing_Resettlement')
                ->first();

            if (!$booking) {
                return back()->with('error', 'Request not found.');
            }

            DB::connection('facilities_db')
                ->table('bookings')
                ->where('id', $id)
                ->update([
                    'status' => 'confirmed',
                    'updated_at' => now(),
                ]);

            return back()->with('success', 'Request approved successfully. Booking reference: ' . $booking->booking_reference);

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to approve request: ' . $e->getMessage());
        }
    }

    /**
     * Reject a Housing and Resettlement request
     */
    public function reject(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'rejection_reason' => 'nullable|string|max:500',
            ]);

            $booking = DB::connection('facilities_db')
                ->table('bookings')
                ->where('id', $id)
                ->where('source_system', 'Housing_Resettlement')
                ->first();

            if (!$booking) {
                return back()->with('error', 'Request not found.');
            }

            DB::connection('facilities_db')
                ->table('bookings')
                ->where('id', $id)
                ->update([
                    'status' => 'cancelled',
                    'special_requests' => $booking->special_requests . "\n\n[REJECTED] " . ($validated['rejection_reason'] ?? 'No reason provided'),
                    'updated_at' => now(),
                ]);

            return back()->with('success', 'Request rejected.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reject request: ' . $e->getMessage());
        }
    }
}
