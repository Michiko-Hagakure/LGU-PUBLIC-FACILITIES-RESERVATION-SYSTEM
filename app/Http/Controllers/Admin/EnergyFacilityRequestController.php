<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\EnergyFacilityRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class EnergyFacilityRequestController extends Controller
{
    /**
     * Display all energy facility requests
     */
    public function index()
    {
        $requests = EnergyFacilityRequest::orderBy('id', 'desc')->get();

        $stats = [
            'total' => $requests->count(),
            'pending' => $requests->where('status', 'pending')->count(),
            'approved' => $requests->where('status', 'approved')->count(),
            'rejected' => $requests->where('status', 'rejected')->count(),
        ];

        // Fetch available facilities from database
        $facilities = DB::connection('facilities_db')
            ->table('facilities')
            ->where('is_available', true)
            ->whereNull('deleted_at')
            ->orderBy('name')
            ->select('facility_id', 'name', 'capacity')
            ->get();

        // Fetch available equipment from database
        $equipment = DB::connection('facilities_db')
            ->table('equipment_items')
            ->where('is_available', true)
            ->whereNull('deleted_at')
            ->orderBy('name')
            ->select('id', 'name', 'category', 'quantity_available')
            ->get();

        return view('admin.energy-facility-requests.index', compact('requests', 'stats', 'facilities', 'equipment'));
    }

    /**
     * Return requests as JSON for AJAX polling
     */
    public function getRequestsJson()
    {
        $requests = EnergyFacilityRequest::orderBy('id', 'desc')->get();

        $stats = [
            'total' => $requests->count(),
            'pending' => $requests->where('status', 'pending')->count(),
            'approved' => $requests->where('status', 'approved')->count(),
            'rejected' => $requests->where('status', 'rejected')->count(),
        ];

        return response()->json(['data' => $requests, 'stats' => $stats]);
    }

    /**
     * Update the status of a facility request (approve/reject)
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'admin_feedback' => 'nullable|string|max:2000',
            'assigned_facility' => 'nullable|integer',
            'scheduled_date' => 'nullable|date',
            'scheduled_start_time' => 'nullable|string|max:10',
            'scheduled_end_time' => 'nullable|string|max:10',
            'assigned_equipment' => 'nullable|string|max:2000',
            'approved_budget' => 'nullable|numeric|min:0',
            'budget_breakdown' => 'nullable|string|max:10000',
            'admin_notes' => 'nullable|string|max:2000',
        ]);

        $facilityRequest = EnergyFacilityRequest::findOrFail($id);
        $facilityRequest->status = $validated['status'];
        $facilityRequest->admin_feedback = $validated['admin_feedback'] ?? null;

        if ($validated['status'] === 'approved') {
            // Get facility details
            $facilityData = [];
            if (!empty($validated['assigned_facility'])) {
                $facility = DB::connection('facilities_db')
                    ->table('facilities')
                    ->where('facility_id', $validated['assigned_facility'])
                    ->first();

                if ($facility) {
                    $facilityData = [
                        'facility_id' => $facility->facility_id,
                        'facility_name' => $facility->name,
                        'facility_capacity' => $facility->capacity ?? null,
                    ];
                }
            }

            // Parse budget breakdown JSON
            $budgetBreakdown = null;
            if (!empty($validated['budget_breakdown'])) {
                $budgetBreakdown = json_decode($validated['budget_breakdown'], true);
            }

            $responseData = [
                'facility' => $facilityData,
                'scheduled_date' => $validated['scheduled_date'] ?? $facilityRequest->preferred_date->format('Y-m-d'),
                'scheduled_start_time' => $validated['scheduled_start_time'] ?? $facilityRequest->start_time,
                'scheduled_end_time' => $validated['scheduled_end_time'] ?? $facilityRequest->end_time,
                'assigned_equipment' => $validated['assigned_equipment'] ?? null,
                'approved_budget' => $validated['approved_budget'] ?? null,
                'budget_breakdown' => $budgetBreakdown,
                'admin_notes' => $validated['admin_notes'] ?? null,
                'approved_at' => now()->toDateTimeString(),
                'approved_by' => session('full_name') ?? session('username') ?? 'Admin',
            ];
            $facilityRequest->response_data = json_encode($responseData);

            // Create a booking record for visibility in All Bookings
            if (!empty($validated['assigned_facility']) && !empty($validated['scheduled_date'])) {
                $this->createBookingFromRequest($facilityRequest, $validated, $facilityData);
            }
        }

        $facilityRequest->save();

        // Send approval/rejection data back to Energy Efficiency system
        $this->notifyEnergyEfficiency($facilityRequest, $validated);

        Log::info('Energy facility request status updated', [
            'id' => $facilityRequest->id,
            'status' => $validated['status'],
            'event_title' => $facilityRequest->event_title,
        ]);

        return redirect()->to(URL::signedRoute('admin.energy-facility-requests.index'))
            ->with('success', 'Facility request ' . $validated['status'] . ' successfully.');
    }

    /**
     * Send approval/rejection notification back to Energy Efficiency system
     */
    private function notifyEnergyEfficiency(EnergyFacilityRequest $facilityRequest, array $validated): void
    {
        try {
            $apiUrl = config('services.energy_efficiency.base_url', 'https://energy.local-government-unit-1-ph.com');
            $timeout = config('services.energy_efficiency.timeout', 30);

            $responseData = $facilityRequest->response_data ? json_decode($facilityRequest->response_data, true) : [];

            $payload = [
                'receive_facility_approval' => '1',
                'facility_request_id' => $facilityRequest->id,
                'event_title' => $facilityRequest->event_title,
                'status' => $validated['status'],
                'admin_feedback' => $validated['admin_feedback'] ?? null,
            ];

            // Include seminar_id and user_id for linking back
            if ($facilityRequest->seminar_id) {
                $payload['seminar_id'] = $facilityRequest->seminar_id;
            }
            if ($facilityRequest->user_id) {
                $payload['user_id'] = $facilityRequest->user_id;
            }

            if ($validated['status'] === 'approved') {
                // Facility details
                $payload['facility_id'] = $responseData['facility']['facility_id'] ?? null;
                $payload['facility_name'] = $responseData['facility']['facility_name'] ?? null;
                $payload['facility_capacity'] = $responseData['facility']['facility_capacity'] ?? null;

                // Schedule
                $payload['scheduled_date'] = $responseData['scheduled_date'] ?? null;
                $payload['scheduled_start_time'] = $responseData['scheduled_start_time'] ?? null;
                $payload['scheduled_end_time'] = $responseData['scheduled_end_time'] ?? null;

                // Equipment
                $payload['assigned_equipment'] = $responseData['assigned_equipment'] ?? null;

                // Budget & breakdown
                $payload['approved_budget'] = $responseData['approved_budget'] ?? null;
                if (!empty($responseData['budget_breakdown'])) {
                    $payload['budget_breakdown'] = json_encode($responseData['budget_breakdown']);
                }

                // Admin info
                $payload['admin_notes'] = $responseData['admin_notes'] ?? null;
                $payload['approved_at'] = $responseData['approved_at'] ?? null;
                $payload['approved_by'] = $responseData['approved_by'] ?? null;
            }

            $response = Http::timeout($timeout)
                ->asForm()
                ->post("{$apiUrl}/facility_request_response.php", $payload);

            if ($response->successful()) {
                Log::info('Energy Efficiency facility request notification sent', [
                    'facility_request_id' => $facilityRequest->id,
                    'status' => $validated['status'],
                    'response' => $response->json(),
                ]);
            } else {
                Log::warning('Energy Efficiency facility request notification failed', [
                    'facility_request_id' => $facilityRequest->id,
                    'status_code' => $response->status(),
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            // Log error but don't fail the approval process
            Log::error('Energy Efficiency facility request notification error', [
                'facility_request_id' => $facilityRequest->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Create a booking record from an approved facility request
     */
    private function createBookingFromRequest(EnergyFacilityRequest $facilityRequest, array $validated, array $facilityData): void
    {
        try {
            $startTime = Carbon::parse($validated['scheduled_date'] . ' ' . ($validated['scheduled_start_time'] ?? $facilityRequest->start_time));
            $endTime = Carbon::parse($validated['scheduled_date'] . ' ' . ($validated['scheduled_end_time'] ?? $facilityRequest->end_time));

            $booking = Booking::create([
                'facility_id' => $validated['assigned_facility'],
                'user_id' => null,
                'user_name' => $facilityRequest->point_person,
                'applicant_name' => $facilityRequest->point_person,
                'applicant_email' => $facilityRequest->contact_email,
                'applicant_phone' => $facilityRequest->contact_number,
                'event_name' => $facilityRequest->event_title,
                'event_description' => $facilityRequest->purpose,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'purpose' => $facilityRequest->purpose,
                'expected_attendees' => null,
                'special_requests' => $facilityRequest->special_requests,
                'base_rate' => 0,
                'extension_rate' => 0,
                'equipment_total' => 0,
                'subtotal' => 0,
                'total_discount' => 0,
                'total_amount' => 0,
                'status' => 'confirmed',
                'source_system' => 'Energy_Efficiency_FacilityRequest',
                'external_reference_id' => 'EE-FR-' . $facilityRequest->id,
                'staff_notes' => 'Energy Efficiency Facility Request #' . $facilityRequest->id
                    . '. Session: ' . ucfirst($facilityRequest->session_type ?? 'N/A')
                    . '. Organizer: ' . ($facilityRequest->organizer_office ?? 'N/A'),
            ]);

            $facilityRequest->booking_id = $booking->id;
            $facilityRequest->save();

            Log::info('Booking created from energy facility request', [
                'facility_request_id' => $facilityRequest->id,
                'booking_id' => $booking->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create booking from energy facility request', [
                'facility_request_id' => $facilityRequest->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
