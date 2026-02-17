<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UtilityBillingApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class WaterConnectionController extends Controller
{
    protected UtilityBillingApiService $utilityApi;

    public function __construct(UtilityBillingApiService $utilityApi)
    {
        $this->utilityApi = $utilityApi;
    }

    /**
     * Display the water connection request form
     */
    public function create()
    {
        $serviceTypes = $this->getServiceTypes();
        $propertyTypes = $this->getPropertyTypes();

        return view('admin.water-connection.create', compact('serviceTypes', 'propertyTypes'));
    }

    /**
     * Submit the water connection request to the Utility Billing PFRS API
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'consumer_name'        => 'required|string|max:255',
            'service_type'         => 'required|string|in:water_connection,electricity_connection,water_reconnection,meter_replacement,transfer_service,disconnection',
            'installation_address' => 'required|string|max:500',
            'property_type'        => 'required|string|in:residential,commercial,industrial,government',
            'contact_person'       => 'required|string|max:255',
            'contact_phone'        => 'required|string|max:50',
            'contact_email'        => 'required|email|max:255',
            'notes'                => 'nullable|string|max:2000',
        ]);

        try {
            $adminId = session('user_id') ?? auth()->id();
            $partnerReference = 'PFRS-' . date('Y') . '-' . str_pad(random_int(1, 99999), 5, '0', STR_PAD_LEFT);

            // Send to Utility Billing PFRS API
            $result = $this->utilityApi->createRequest(array_merge($validated, [
                'partner_reference' => $partnerReference,
            ]));

            // Store local reference regardless of API result
            $externalId = $result['success'] ? ($result['id'] ?? null) : null;
            $applicationNumber = $result['success'] ? ($result['application_number'] ?? null) : null;
            $status = $result['success'] ? 'submitted' : 'pending_sync';
            $remarks = $result['success'] ? null : 'Failed to sync with Utility Billing system. Will retry later.';

            DB::connection('facilities_db')->table('water_connection_requests')->insert([
                'user_id'                      => $adminId,
                'external_id'                  => $externalId,
                'external_application_number'  => $applicationNumber,
                'consumer_name'                => $validated['consumer_name'],
                'service_type'                 => $validated['service_type'],
                'installation_address'         => $validated['installation_address'],
                'property_type'                => $validated['property_type'],
                'contact_person'               => $validated['contact_person'],
                'contact_phone'                => $validated['contact_phone'],
                'contact_email'                => $validated['contact_email'],
                'partner_reference'            => $partnerReference,
                'notes'                        => $validated['notes'] ?? null,
                'status'                       => $status,
                'remarks'                      => $remarks,
                'created_at'                   => Carbon::now(),
                'updated_at'                   => Carbon::now(),
            ]);

            if ($result['success']) {
                Log::info('Water connection request submitted successfully', [
                    'external_id'         => $externalId,
                    'application_number'  => $applicationNumber,
                    'user_id'             => $adminId,
                ]);

                return redirect()->route('admin.water-connection.index')
                    ->with('success', 'Water connection request submitted successfully! Application Number: ' . ($applicationNumber ?? 'Pending'));
            }

            return redirect()->route('admin.water-connection.index')
                ->with('warning', 'Request saved locally but could not sync with Utility Billing system. It will be synced when their system is available.');

        } catch (\Exception $e) {
            Log::error('Water connection request failed', [
                'error'   => $e->getMessage(),
                'user_id' => session('user_id'),
            ]);

            return back()
                ->withInput()
                ->with('error', 'An error occurred while submitting the request: ' . $e->getMessage());
        }
    }

    /**
     * Display list of water connection requests
     */
    public function index()
    {
        try {
            // Silently sync statuses
            $this->syncStatusesQuietly();

            $requests = DB::connection('facilities_db')
                ->table('water_connection_requests')
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            $stats = [
                'total' => DB::connection('facilities_db')->table('water_connection_requests')->count(),
                'submitted' => DB::connection('facilities_db')->table('water_connection_requests')->where('status', 'submitted')->count(),
                'under_review' => DB::connection('facilities_db')->table('water_connection_requests')->where('status', 'under_review')->count(),
                'approved' => DB::connection('facilities_db')->table('water_connection_requests')->where('status', 'approved')->count(),
                'rejected' => DB::connection('facilities_db')->table('water_connection_requests')->where('status', 'rejected')->count(),
                'completed' => DB::connection('facilities_db')->table('water_connection_requests')->where('status', 'completed')->count(),
                'pending_sync' => DB::connection('facilities_db')->table('water_connection_requests')->where('status', 'pending_sync')->count(),
            ];
        } catch (\Exception $e) {
            $requests = collect();
            $stats = ['total' => 0, 'submitted' => 0, 'under_review' => 0, 'approved' => 0, 'rejected' => 0, 'completed' => 0, 'pending_sync' => 0];
            Log::warning('Failed to fetch water connection requests', ['error' => $e->getMessage()]);
        }

        $serviceTypes = $this->getServiceTypes();
        $propertyTypes = $this->getPropertyTypes();

        return view('admin.water-connection.index', compact('requests', 'stats', 'serviceTypes', 'propertyTypes'));
    }

    /**
     * AJAX endpoint for live data
     */
    public function getRequestsJson()
    {
        try {
            $this->syncStatusesQuietly();

            $requests = DB::connection('facilities_db')
                ->table('water_connection_requests')
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get();

            $stats = [
                'total' => DB::connection('facilities_db')->table('water_connection_requests')->count(),
                'submitted' => DB::connection('facilities_db')->table('water_connection_requests')->where('status', 'submitted')->count(),
                'under_review' => DB::connection('facilities_db')->table('water_connection_requests')->where('status', 'under_review')->count(),
                'approved' => DB::connection('facilities_db')->table('water_connection_requests')->where('status', 'approved')->count(),
                'rejected' => DB::connection('facilities_db')->table('water_connection_requests')->where('status', 'rejected')->count(),
                'completed' => DB::connection('facilities_db')->table('water_connection_requests')->where('status', 'completed')->count(),
                'pending_sync' => DB::connection('facilities_db')->table('water_connection_requests')->where('status', 'pending_sync')->count(),
            ];

            return response()->json([
                'success'   => true,
                'data'      => $requests,
                'stats'     => $stats,
                'timestamp' => now()->toDateTimeString(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Sync statuses from Utility Billing API
     */
    public function syncStatuses()
    {
        try {
            $result = $this->utilityApi->syncStatuses();

            $message = "Synced {$result['updated']} of {$result['total']} request(s).";
            return back()->with($result['updated'] > 0 ? 'success' : 'info', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to sync statuses: ' . $e->getMessage());
        }
    }

    /**
     * Retry syncing pending_sync records
     */
    public function retrySync()
    {
        try {
            $result = $this->utilityApi->retrySyncPending();

            $message = "Synced {$result['synced']} of {$result['total']} pending request(s).";
            if ($result['failed'] > 0) {
                $message .= " {$result['failed']} failed.";
            }

            return back()->with($result['synced'] > 0 ? 'success' : 'warning', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to retry sync: ' . $e->getMessage());
        }
    }

    /**
     * Silently sync statuses (for page load)
     */
    private function syncStatusesQuietly(): void
    {
        try {
            $this->utilityApi->syncStatuses();
        } catch (\Exception $e) {
            Log::warning('Silent water connection status sync failed', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get valid service types
     */
    private function getServiceTypes(): array
    {
        return [
            'water_connection'       => 'Water Connection',
            'electricity_connection' => 'Electricity Connection',
            'water_reconnection'     => 'Water Reconnection',
            'meter_replacement'      => 'Meter Replacement',
            'transfer_service'       => 'Transfer Service',
            'disconnection'          => 'Disconnection',
        ];
    }

    /**
     * Get valid property types
     */
    private function getPropertyTypes(): array
    {
        return [
            'residential' => 'Residential',
            'commercial'  => 'Commercial',
            'industrial'  => 'Industrial',
            'government'  => 'Government',
        ];
    }
}
