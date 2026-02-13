<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Services\RoadTransportApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoadAssistanceController extends Controller
{
    protected RoadTransportApiService $roadApi;

    public function __construct(RoadTransportApiService $roadApi)
    {
        $this->roadApi = $roadApi;
    }

    /**
     * Display road assistance request form and list of user's requests
     */
    public function index()
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        // Fetch user's requests from the Road & Transportation API
        $apiResult = $this->roadApi->getRequestsByUser($userId);
        $requests = $apiResult['data'] ?? [];

        // Event types for road assistance
        $eventTypes = [
            'Road Closure' => 'Temporary Road Closure',
            'Traffic Management' => 'Traffic Management Support',
            'Escort Service' => 'Vehicle Escort Service',
            'Signage Request' => 'Traffic Signage & Cones',
            'Personnel Deployment' => 'Traffic Personnel Deployment',
            'Rerouting Plan' => 'Traffic Rerouting Assistance',
        ];

        // Get user's upcoming confirmed bookings for reference
        $upcomingBookings = DB::connection('facilities_db')
            ->table('bookings')
            ->join('facilities', 'bookings.facility_id', '=', 'facilities.facility_id')
            ->where('bookings.user_id', $userId)
            ->where('bookings.status', 'confirmed')
            ->where('bookings.start_time', '>=', Carbon::now())
            ->select('bookings.*', 'facilities.name as facility_name', 'facilities.address as facility_address')
            ->orderBy('bookings.start_time')
            ->get();

        return view('citizen.road-assistance.index', compact('requests', 'eventTypes', 'upcomingBookings'));
    }

    /**
     * Store a new road assistance request
     */
    public function store(Request $request)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        $validated = $request->validate([
            'event_type' => 'required|string|max:100',
            'start_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|string',
            'end_date' => 'required|date|after_or_equal:start_date',
            'end_time' => 'required|string',
            'location' => 'required|string|max:500',
            'landmark' => 'nullable|string|max:255',
            'description' => 'required|string|max:2000',
            'booking_id' => 'nullable|integer',
        ]);

        // Format dates with times
        $startDateTime = Carbon::parse($validated['start_date'] . ' ' . $validated['start_time'])->format('Y-m-d H:i:s');
        $endDateTime = Carbon::parse($validated['end_date'] . ' ' . $validated['end_time'])->format('Y-m-d H:i:s');

        // Send to Road & Transportation API
        $result = $this->roadApi->createRequest([
            'user_id' => $userId,
            'event_type' => $validated['event_type'],
            'start_date' => $startDateTime,
            'end_date' => $endDateTime,
            'location' => $validated['location'],
            'landmark' => $validated['landmark'],
            'description' => $validated['description'],
        ]);

        // Store local reference regardless of API result
        $externalRequestId = $result['success'] ? $result['request_id'] : null;
        $status = $result['success'] ? 'pending' : 'pending_sync';
        $remarks = $result['success'] ? null : 'Failed to sync with external system. Will retry later.';

        DB::connection('facilities_db')->table('citizen_road_requests')->insert([
            'user_id' => $userId,
            'external_request_id' => $externalRequestId,
            'event_type' => $validated['event_type'],
            'start_datetime' => $startDateTime,
            'end_datetime' => $endDateTime,
            'location' => $validated['location'],
            'landmark' => $validated['landmark'],
            'description' => $validated['description'],
            'booking_id' => $validated['booking_id'],
            'status' => $status,
            'remarks' => $remarks,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        if ($result['success']) {
            return redirect()->route('citizen.road-assistance.index')
                ->with('success', 'Road assistance request submitted successfully! Request ID: ' . $result['request_id']);
        }

        return redirect()->route('citizen.road-assistance.index')
            ->with('warning', 'Request saved locally but could not sync with Road & Transportation system. It will be synced when their system is available.');
    }

    /**
     * Get requests as JSON for AJAX
     */
    public function getRequestsJson()
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $apiResult = $this->roadApi->getRequestsByUser($userId);

        return response()->json([
            'success' => $apiResult['success'],
            'data' => $apiResult['data'] ?? []
        ]);
    }
}
