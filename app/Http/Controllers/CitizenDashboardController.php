<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\PaymentSlip;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CitizenDashboardController extends Controller
{
    /**
     * Display the main citizen dashboard.
     */
    public function index(Request $request)
    {
        // User is guaranteed to be authenticated by middleware
        $user = Auth::user();
        
        $bookings = $user->bookings()->with('facility')
                         ->orderBy('event_date', 'desc')
                         ->take(5)
                         ->get();
                         
        $facilities = Facility::where('status', 'active')->get();

        return view('citizen.dashboard.index', compact('user', 'bookings', 'facilities'));
    }

    /**
     * Display all reservations for the authenticated citizen.
     */
    public function reservations()
    {
        $user = Auth::user();
        $reservations = $user->bookings()->with('facility')
                             ->orderBy('created_at', 'desc')
                             ->paginate(15);
                             
        return view('citizen.dashboard.reservations', compact('reservations'));
    }

    /**
     * Display historical reservation data (e.g., past events).
     */
    public function reservationHistory()
    {
        $user = Auth::user();
        $history = $user->bookings()->with('facility')
                        ->where('event_date', '<', now()->toDateString())
                        ->orderBy('event_date', 'desc')
                        ->paginate(15);
                        
        return view('citizen.dashboard.reservation-history', compact('history'));
    }

    /**
     * Display facility availability calendar.
     */
    public function viewAvailability()
    {
        $facilities = Facility::where('status', 'active')->get();
        return view('citizen.dashboard.availability', compact('facilities'));
    }

    /**
     * Display user profile.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('citizen.dashboard.profile', compact('user'));
    }

    /**
     * Update user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            // Add other updatable fields here
        ]);
        
        $user->update($validated);
        
        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    /**
     * API: Get bookings for a specific facility for the calendar.
     */
    public function getFacilityBookings(int $facility_id)
    {
        $bookings = Booking::where('facility_id', $facility_id)
                           ->whereIn('status', ['approved', 'pending'])
                           ->get();
                           
        return response()->json($this->formatBookingsForCalendar($bookings));
    }

    /**
     * API: Get all facility bookings for the calendar.
     */
    public function getAllFacilityBookings()
    {
        try {
            $bookings = Booking::with('facility')
                               ->whereIn('status', ['approved', 'pending'])
                               ->get();
            
            return response()->json($this->formatBookingsForCalendar($bookings));
            
        } catch (\Exception $e) {
            Log::error('Error fetching all facility bookings:', ['error' => $e->getMessage()]);
            return response()->json([], 500);
        }
    }
    
    /**
     * Helper to format booking objects for FullCalendar (or similar).
     *
     * @param \Illuminate\Support\Collection $bookings
     * @return array
     */
    private function formatBookingsForCalendar($bookings): array
    {
        $events = [];

        foreach ($bookings as $booking) {
            
            // Determine colors based on status
            $backgroundColor = ($booking->status === 'approved') ? '#48bb78' : '#f6ad55'; // Green for approved, Orange for pending
            $borderColor = $backgroundColor; 
            
            // Format times for ISO 8601 (FullCalendar standard)
            $startTime = Str::endsWith($booking->start_time, ':00') ? $booking->start_time : $booking->start_time . ':00';
            $endTime = Str::endsWith($booking->end_time, ':00') ? $booking->end_time : $booking->end_time . ':00';
            $eventDate = Carbon::parse($booking->event_date)->toDateString(); // Ensure Y-m-d format

            $events[] = [
                'id' => $booking->id,
                'title' => $booking->event_name . ' - ' . ($booking->facility->name ?? 'N/A'),
                'start' => $eventDate . 'T' . $startTime,
                'end' => $eventDate . 'T' . $endTime,
                'backgroundColor' => $backgroundColor,
                'borderColor' => $borderColor,
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'facility_id' => $booking->facility_id,
                    'applicant' => $booking->applicant_name,
                    'attendees' => $booking->expected_attendees,
                    'status' => $booking->status,
                    'description' => $booking->event_description
                ]
            ];
        }

        return $events;
    }
}