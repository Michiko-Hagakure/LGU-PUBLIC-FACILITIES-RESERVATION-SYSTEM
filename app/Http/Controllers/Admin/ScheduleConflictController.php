<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Booking;
use Illuminate\Support\Collection;

class ScheduleConflictController extends Controller
{
    /**
     * Display a list of all schedule conflicts.
     */
    public function index() 
    {
        // Retrieve the conflicts and the bookings involved, grouped by facility and date
        $conflictGroups = $this->findConflicts();

        // Return the view with the conflict data
        return view('schedule-conflicts', [
            'conflictGroups' => $conflictGroups // Changed variable name to conflictGroups for clarity
        ]);
    }

    /**
     * Logic to find overlapping bookings.
     *
     * @return \Illuminate\Support\Collection - Grouped by facility_id-event_date
     */
    private function findConflicts(): Collection
    {
        // Step 1: Get the IDs of bookings that have an overlap on the same date/facility
        $conflictingIds = DB::table('bookings as a') 
            ->select('a.id')
            ->join('bookings as b', function ($join) {
                $join->on('a.facility_id', '=', 'b.facility_id')
                     ->on('a.event_date', '=', 'b.event_date')
                     ->whereRaw('a.id != b.id') // Ensure we don't compare a booking with itself
                     ->whereRaw('(a.start_time < b.end_time AND a.end_time > b.start_time)') // The overlap condition
                     ->whereIn('a.status', ['approved', 'pending'])
                     ->whereIn('b.status', ['approved', 'pending']);
            })
            // Prevent duplicates and only select the ID once
            ->distinct()
            ->pluck('a.id');

        // Check if any conflicts were found
        if ($conflictingIds->isEmpty()) {
            return collect(); // Return an empty collection if no conflicts
        }

        // Step 2: Retrieve the full booking details for the conflicting IDs
        $conflictingBookings = Booking::whereIn('id', $conflictingIds)
            ->with(['facility', 'user']) 
            ->orderBy('facility_id')
            ->orderBy('event_date')
            ->orderBy('start_time')
            ->get();
            
        // Step 3: Group the bookings to be easily displayed in the view.
        $conflictGroups = $conflictingBookings->groupBy(function (Booking $item) {
             return $item->facility_id . '-' . $item->event_date->toDateString();
        });

        return $conflictGroups;
    }
}