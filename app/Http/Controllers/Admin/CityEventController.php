<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Facility;
use Illuminate\Http\Request; // Still used for type hinting the general Request
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreCityEventRequest; // Requires you to create this file
use App\Http\Requests\UpdateCityEventRequest; // Requires you to create this file

class CityEventController extends Controller
{
    /**
     * Display a listing of city events.
     * Note: City events are also visible in the main facility calendar.
     */
    public function index()
    {
        // Use a dedicated scope on the Booking model if this query grows complex.
        $cityEvents = Booking::where(function($query) {
                $query->where('user_name', 'City Government')
                      ->orWhere('event_name', 'LIKE', '%City Event%')
                      ->orWhere('event_name', 'LIKE', '%CITY EVENT%')
                      ->orWhere('applicant_name', 'City Mayor Office');
            })
            ->orderBy('event_date', 'desc')
            ->paginate(15);

        return view('admin.city-events.index', compact('cityEvents'));
    }

    /**
     * Show the form for creating a new city event.
     */
    public function create()
    {
        $facilities = Facility::where('status', 'active')->get();
        return view('admin.city-events.create', compact('facilities'));
    }

    /**
     * Store a newly created city event in storage.
     * Uses StoreCityEventRequest for clean validation.
     */
    public function store(StoreCityEventRequest $request)
    {
        $validated = $request->validated();

        $cityEvent = Booking::create([
            'facility_id' => $validated['facility_id'],
            'user_id' => Auth::id() ?? 1, // Fallback to admin ID 1
            'user_name' => 'City Government',
            'applicant_name' => 'City Mayor Office',
            'applicant_email' => 'citymayor@lgu.gov.ph',
            'event_name' => 'CITY EVENT: ' . $validated['event_name'],
            'event_description' => $validated['event_description'],
            'event_date' => $validated['event_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'expected_attendees' => $validated['expected_attendees'],
            'status' => 'approved', // City events are automatically approved
            'total_fee' => 0, // No fee for internal events
            'type' => 'City Event',
            'created_by_admin' => true,
        ]);

        return redirect()->route('admin.city-events.index')
            ->with('success', 'City event created and automatically approved!');
    }

    /**
     * Display the specified city event.
     */
    public function show($id)
    {
        $cityEvent = Booking::findOrFail($id);
        return view('admin.city-events.show', compact('cityEvent'));
    }

    /**
     * Show the form for editing the specified city event.
     */
    public function edit($id)
    {
        $cityEvent = Booking::findOrFail($id);
        $facilities = Facility::where('status', 'active')->get();
        
        // Remove 'CITY EVENT: ' prefix for editing
        $eventNameForEdit = str_replace('CITY EVENT: ', '', $cityEvent->event_name);

        return view('admin.city-events.edit', compact('cityEvent', 'facilities', 'eventNameForEdit'));
    }

    /**
     * Update the specified city event in storage.
     * Uses UpdateCityEventRequest for clean validation.
     */
    public function update(UpdateCityEventRequest $request, $id)
    {
        $cityEvent = Booking::findOrFail($id);
        $validated = $request->validated();

        $cityEvent->update([
            'facility_id' => $validated['facility_id'],
            'event_name' => 'CITY EVENT: ' . $validated['event_name'],
            'event_description' => $validated['event_description'],
            'event_date' => $validated['event_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'expected_attendees' => $validated['expected_attendees'],
        ]);

        return redirect()->route('admin.city-events.index')
            ->with('success', 'City event updated successfully!');
    }

    /**
     * Remove the specified city event from storage.
     */
    public function destroy($id)
    {
        $cityEvent = Booking::findOrFail($id);
        $cityEvent->delete();

        return redirect()->route('admin.city-events.index')
            ->with('success', 'City event deleted successfully!');
    }
}