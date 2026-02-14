<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EnergyFacilityRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EnergyFacilityRequestApiController extends Controller
{
    /**
     * POST /api/energy-efficiency/facility-request
     * Submit a new facility request from Energy Efficiency system
     */
    public function store(Request $request)
    {
        // Normalize time fields — accept H:i:s and trim to H:i
        $input = $request->all();
        foreach (['start_time', 'end_time', 'alternative_start_time', 'alternative_end_time'] as $tf) {
            if (!empty($input[$tf]) && preg_match('/^\d{2}:\d{2}:\d{2}$/', $input[$tf])) {
                $input[$tf] = substr($input[$tf], 0, 5);
            }
        }

        $validator = Validator::make($input, [
            // Event Information
            'event_title' => 'required|string|max:255',
            'purpose' => 'nullable|string',
            'organizer_office' => 'nullable|string|max:255',
            'point_person' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:50',
            'contact_email' => 'nullable|email|max:255',

            // Schedule Details
            'preferred_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'alternative_date' => 'nullable|date',
            'alternative_start_time' => 'nullable|date_format:H:i',
            'alternative_end_time' => 'nullable|date_format:H:i',

            // Attendance & Format
            'audience_type' => 'nullable|string|in:employees,public,students,mixed',
            'session_type' => 'nullable|string|in:orientation,training,workshop,briefing,meeting',

            // Venue Requirements
            'facility_type' => 'nullable|string|in:small,medium,large',

            // Equipment & Technical Needs
            'needs_projector' => 'nullable|boolean',
            'laptop_option' => 'nullable|string|in:yes,no,bringing_own',
            'needs_sound_system' => 'nullable|boolean',
            'needs_microphone' => 'nullable|boolean',
            'microphone_count' => 'nullable|integer|min:0|max:20',
            'microphone_type' => 'nullable|string|in:handheld,lapel,both',
            'needs_wifi' => 'nullable|boolean',
            'needs_extension_cords' => 'nullable|boolean',
            'additional_power_needs' => 'nullable|string|max:1000',
            'other_equipment' => 'nullable|string|max:1000',

            // Materials & Documents
            'needs_handouts' => 'nullable|boolean',
            'handouts_format' => 'nullable|string|in:softcopy,hardcopy,both',
            'needs_certificates' => 'nullable|boolean',
            'certificates_provider' => 'nullable|string|in:us,them,both',

            // Food & Logistics
            'needs_refreshments' => 'nullable|boolean',
            'dietary_notes' => 'nullable|string|max:1000',
            'delivery_instructions' => 'nullable|string|max:1000',

            // Special Requests
            'special_requests' => 'nullable|string|max:2000',

            // Source tracking
            'user_id' => 'nullable|integer',
            'seminar_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            Log::warning('Energy facility request validation failed', [
                'errors' => $validator->errors()->toArray(),
                'input_keys' => array_keys($input),
                'start_time' => $input['start_time'] ?? null,
                'end_time' => $input['end_time'] ?? null,
                'preferred_date' => $input['preferred_date'] ?? null,
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $data = $validator->validated();

            // Set defaults for boolean fields
            $data['needs_projector'] = $data['needs_projector'] ?? false;
            $data['needs_sound_system'] = $data['needs_sound_system'] ?? false;
            $data['needs_microphone'] = $data['needs_microphone'] ?? false;
            $data['needs_wifi'] = $data['needs_wifi'] ?? false;
            $data['needs_extension_cords'] = $data['needs_extension_cords'] ?? false;
            $data['needs_handouts'] = $data['needs_handouts'] ?? false;
            $data['needs_certificates'] = $data['needs_certificates'] ?? false;
            $data['needs_refreshments'] = $data['needs_refreshments'] ?? false;
            $data['point_person'] = $data['point_person'] ?? 'Not specified';
            $data['laptop_option'] = $data['laptop_option'] ?? 'no';
            $data['microphone_count'] = $data['microphone_count'] ?? 0;
            $data['status'] = 'pending';

            $facilityRequest = EnergyFacilityRequest::create($data);

            Log::info('Energy facility request submitted', [
                'id' => $facilityRequest->id,
                'event_title' => $facilityRequest->event_title,
                'preferred_date' => $facilityRequest->preferred_date,
                'point_person' => $facilityRequest->point_person,
                'seminar_id' => $facilityRequest->seminar_id,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Facility request submitted successfully',
                'data' => [
                    'id' => $facilityRequest->id,
                    'event_title' => $facilityRequest->event_title,
                    'status' => $facilityRequest->status,
                    'preferred_date' => $facilityRequest->preferred_date->format('Y-m-d'),
                    'start_time' => $facilityRequest->start_time,
                    'end_time' => $facilityRequest->end_time,
                    'created_at' => $facilityRequest->created_at->toDateTimeString(),
                ],
            ], 201);

        } catch (\Exception $e) {
            Log::error('Energy facility request failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to submit facility request: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * GET /api/energy-efficiency/facility-request/{id}
     * Check status of a specific facility request
     */
    public function show($id)
    {
        $facilityRequest = EnergyFacilityRequest::find($id);

        if (!$facilityRequest) {
            return response()->json([
                'status' => 'error',
                'message' => 'Facility request not found',
            ], 404);
        }

        $responseData = $facilityRequest->response_data
            ? json_decode($facilityRequest->response_data, true)
            : null;

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $facilityRequest->id,
                'event_title' => $facilityRequest->event_title,
                'purpose' => $facilityRequest->purpose,
                'organizer_office' => $facilityRequest->organizer_office,
                'point_person' => $facilityRequest->point_person,
                'contact_number' => $facilityRequest->contact_number,
                'contact_email' => $facilityRequest->contact_email,
                'preferred_date' => $facilityRequest->preferred_date ? $facilityRequest->preferred_date->format('Y-m-d') : null,
                'start_time' => $facilityRequest->start_time,
                'end_time' => $facilityRequest->end_time,
                'alternative_date' => $facilityRequest->alternative_date ? $facilityRequest->alternative_date->format('Y-m-d') : null,
                'alternative_start_time' => $facilityRequest->alternative_start_time,
                'alternative_end_time' => $facilityRequest->alternative_end_time,
                'audience_type' => $facilityRequest->audience_type,
                'session_type' => $facilityRequest->session_type,
                'facility_type' => $facilityRequest->facility_type,
                'approval_status' => $facilityRequest->status,
                'admin_feedback' => $facilityRequest->admin_feedback,
                'response_data' => $responseData,
                'seminar_id' => $facilityRequest->seminar_id,
                'created_at' => $facilityRequest->created_at->toDateTimeString(),
                'updated_at' => $facilityRequest->updated_at->toDateTimeString(),
            ],
        ]);
    }

    /**
     * GET /api/energy-efficiency/facility-request
     * List all facility requests (optionally filter by seminar_id or status)
     */
    public function index(Request $request)
    {
        $query = EnergyFacilityRequest::orderBy('created_at', 'desc');

        if ($request->has('seminar_id')) {
            $query->where('seminar_id', $request->seminar_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $requests = $query->get()->map(function ($req) {
            $responseData = $req->response_data ? json_decode($req->response_data, true) : null;
            return [
                'id' => $req->id,
                'event_title' => $req->event_title,
                'purpose' => $req->purpose,
                'organizer_office' => $req->organizer_office,
                'point_person' => $req->point_person,
                'preferred_date' => $req->preferred_date ? $req->preferred_date->format('Y-m-d') : null,
                'start_time' => $req->start_time,
                'end_time' => $req->end_time,
                'session_type' => $req->session_type,
                'facility_type' => $req->facility_type,
                'approval_status' => $req->status,
                'admin_feedback' => $req->admin_feedback,
                'response_data' => $responseData,
                'seminar_id' => $req->seminar_id,
                'created_at' => $req->created_at->toDateTimeString(),
                'updated_at' => $req->updated_at->toDateTimeString(),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $requests,
            'total' => $requests->count(),
        ]);
    }

    /**
     * GET /api/energy-efficiency/facilities
     * List available facilities for the Energy team to choose from
     */
    public function listFacilities()
    {
        try {
            $facilities = DB::connection('facilities_db')
                ->table('facilities')
                ->where('is_available', true)
                ->whereNull('deleted_at')
                ->orderBy('name')
                ->select('facility_id', 'name', 'capacity', 'location', 'description')
                ->get()
                ->map(function ($f) {
                    // Determine facility size category
                    $size = 'small';
                    if ($f->capacity >= 100) {
                        $size = 'large';
                    } elseif ($f->capacity >= 50) {
                        $size = 'medium';
                    }

                    return [
                        'id' => $f->facility_id,
                        'name' => $f->name,
                        'capacity' => $f->capacity,
                        'size_category' => $size,
                        'location' => $f->location,
                        'description' => $f->description,
                    ];
                });

            return response()->json([
                'status' => 'success',
                'data' => $facilities,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch facilities: ' . $e->getMessage(),
            ], 500);
        }
    }
}
