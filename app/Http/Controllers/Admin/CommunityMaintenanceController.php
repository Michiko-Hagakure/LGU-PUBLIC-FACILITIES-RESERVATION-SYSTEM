<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CommunityMaintenanceController extends Controller
{
    /**
     * Display the facility maintenance request form
     */
    public function create()
    {
        // Get facilities for dropdown
        $facilities = $this->getFacilities();
        
        // Get categories and issue types for the General Request API
        $categories = $this->getCategories();
        $issueTypesByCategory = $this->getIssueTypesByCategory();
        
        // Get priority levels
        $priorityLevels = $this->getPriorityLevels();
        
        return view('admin.community-maintenance.create', compact(
            'facilities',
            'categories',
            'issueTypesByCategory',
            'priorityLevels'
        ));
    }

    /**
     * Submit the facility maintenance request to the Community General Request API
     */
    public function store(Request $request)
    {
        // Validate required fields matching General Request API
        $validated = $request->validate([
            'facility_id' => 'required|integer',
            'category' => 'required|string|in:Facilities,Housing,Community,Urban Planning,Land Management,Road Infrastructure,Energy,Utilities',
            'issue_type' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:500',
            'priority' => 'required|string|in:Low,Medium,High,Urgent',
            'reporter_name' => 'required|string|max:255',
            'reporter_contact' => 'required|string|max:255',
            'photo' => 'nullable|image|max:5120',
        ]);

        try {
            // Get facility name for local record
            $facility = DB::connection('facilities_db')
                ->table('facilities')
                ->where('facility_id', $validated['facility_id'])
                ->first();

            $facilityName = $facility ? $facility->name : 'Unknown Facility';

            // Prepare form-data payload for General Request API
            // Note: priority is omitted from API payload - remote server uses its own default
            // Priority is still stored locally for our tracking purposes
            $payload = [
                'category' => $validated['category'],
                'issue_type' => $validated['issue_type'],
                'description' => $validated['description'],
                'location' => $validated['location'],
                'reporter_name' => $validated['reporter_name'],
                'reporter_contact' => $validated['reporter_contact'],
            ];

            // Handle photo upload
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->getRealPath();
                $photoName = $request->file('photo')->getClientOriginalName();
            }

            // Send request to Community General Request API
            $response = $this->sendToGeneralRequestApi($payload, $photoPath ?? null, $photoName ?? null);

            if ($response['success']) {
                // Log successful submission
                Log::info('Community general maintenance request submitted successfully', [
                    'request_id' => $response['request_id'] ?? null,
                    'user_id' => session('user_id'),
                    'facility_id' => $validated['facility_id'],
                    'category' => $validated['category'],
                ]);

                // Store local record for tracking
                $this->storeLocalRecord($validated, $response['request_id'] ?? null, $facilityName, $request->file('photo'));

                return redirect()
                    ->route('admin.community-maintenance.create')
                    ->with('success', 'Maintenance request submitted successfully! Request ID: ' . ($response['request_id'] ?? 'Pending'));
            }

            return back()
                ->withInput()
                ->with('error', $response['message'] ?? 'Failed to submit maintenance request. Please try again.');

        } catch (\Exception $e) {
            Log::error('Community general maintenance request failed', [
                'error' => $e->getMessage(),
                'user_id' => session('user_id'),
            ]);

            return back()
                ->withInput()
                ->with('error', 'An error occurred while submitting the maintenance request: ' . $e->getMessage());
        }
    }

    /**
     * Display list of submitted maintenance requests
     */
    public function index(Request $request)
    {
        try {
            $query = DB::connection('facilities_db')
                ->table('community_maintenance_requests')
                ->orderBy('created_at', 'desc');

            // Optional filters
            if ($request->filled('category')) {
                $query->where('category', $request->input('category'));
            }
            if ($request->filled('status')) {
                $query->where('status', $request->input('status'));
            }

            $requests = $query->paginate(15);
        } catch (\Exception $e) {
            $requests = collect();
            Log::warning('Failed to fetch community maintenance requests', [
                'error' => $e->getMessage(),
            ]);
        }

        $categories = $this->getCategories();

        return view('admin.community-maintenance.index', compact('requests', 'categories'));
    }

    /**
     * Get maintenance requests as JSON for AJAX polling
     */
    public function getRequestsJson()
    {
        try {
            // First, sync statuses from Community API
            $this->syncStatusesFromApi();
            
            $requests = DB::connection('facilities_db')
                ->table('community_maintenance_requests')
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get();

            $stats = [
                'pending' => DB::connection('facilities_db')
                    ->table('community_maintenance_requests')
                    ->where('status', 'Pending')
                    ->count(),
                'in_progress' => DB::connection('facilities_db')
                    ->table('community_maintenance_requests')
                    ->where('status', 'In Progress')
                    ->count(),
                'completed' => DB::connection('facilities_db')
                    ->table('community_maintenance_requests')
                    ->where('status', 'Completed')
                    ->count(),
                'closed' => DB::connection('facilities_db')
                    ->table('community_maintenance_requests')
                    ->where('status', 'Closed')
                    ->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $requests,
                'stats' => $stats,
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
     * Sync statuses from Community API silently
     */
    private function syncStatusesFromApi(): void
    {
        try {
            // Fetch requests from the General Request API filtered by category=Facilities
            // to sync statuses for our facility-related requests
            $categories = DB::connection('facilities_db')
                ->table('community_maintenance_requests')
                ->whereNotIn('status', ['Completed', 'Closed'])
                ->distinct()
                ->pluck('category');

            foreach ($categories as $category) {
                try {
                    $statusData = $this->fetchGeneralRequests($category);
                    if ($statusData['success'] && !empty($statusData['data'])) {
                        $this->updateLocalStatuses($statusData['data']);
                    }
                } catch (\Exception $e) {
                    // Continue with next category
                }
            }
        } catch (\Exception $e) {
            Log::warning('Failed to sync statuses from API', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Check status of requests from the Community General Request API
     */
    public function checkStatus($category = null, $status = null)
    {
        try {
            $statusData = $this->fetchGeneralRequests($category, $status);

            if ($statusData['success']) {
                // Update local records with latest status
                $this->updateLocalStatuses($statusData['data']);

                return response()->json($statusData);
            }

            return response()->json($statusData, 400);

        } catch (\Exception $e) {
            Log::error('Failed to fetch request status', [
                'category' => $category,
                'status' => $status,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch request status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Refresh status for all requests and return updated view
     */
    public function refreshStatuses()
    {
        try {
            // Get unique categories from local records that are not yet completed/closed
            $categories = DB::connection('facilities_db')
                ->table('community_maintenance_requests')
                ->whereNotIn('status', ['Completed', 'Closed'])
                ->distinct()
                ->pluck('category');

            $synced = 0;
            $failed = 0;

            foreach ($categories as $category) {
                try {
                    $statusData = $this->fetchGeneralRequests($category);
                    
                    if ($statusData['success'] && !empty($statusData['data'])) {
                        $this->updateLocalStatuses($statusData['data']);
                        $synced++;
                    }
                } catch (\Exception $e) {
                    $failed++;
                }
            }

            $message = "Synced {$synced} category(ies).";
            if ($failed > 0) {
                $message .= " {$failed} failed.";
            }

            return back()->with($synced > 0 ? 'success' : 'info', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to sync statuses: ' . $e->getMessage());
        }
    }

    /**
     * Send request to Community General Request API (POST with form-data)
     */
    private function sendToGeneralRequestApi(array $payload, ?string $photoPath = null, ?string $photoName = null): array
    {
        $baseUrl = config('services.community_cim.base_url');
        $timeout = config('services.community_cim.timeout', 30);

        $url = rtrim($baseUrl, '/') . '/api/integration/CITIZEN/GeneralRequest.php';

        Log::info('Community General Request API - POST starting', [
            'url' => $url,
            'category' => $payload['category'],
            'issue_type' => $payload['issue_type'],
            'priority' => $payload['priority'],
        ]);

        // Build form-data fields
        $postFields = $payload;

        // Attach photo if provided
        if ($photoPath && file_exists($photoPath)) {
            $postFields['photo'] = new \CURLFile($photoPath, mime_content_type($photoPath), $photoName ?? 'photo.jpg');
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        
        $responseBody = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        Log::info('Community General Request API - POST response', [
            'http_code' => $httpCode,
            'response' => $responseBody,
        ]);
        
        if ($curlError) {
            Log::error('Community General Request API curl error', ['error' => $curlError]);
            return ['success' => false, 'message' => 'Connection error: ' . $curlError];
        }

        $responseData = json_decode($responseBody, true) ?? [];

        if ($responseData['success'] ?? false) {
            return $responseData;
        }

        Log::warning('Community General Request API POST failed', [
            'body' => $responseBody,
            'payload' => [
                'category' => $payload['category'] ?? null,
                'issue_type' => $payload['issue_type'] ?? null,
                'priority' => $payload['priority'] ?? null,
                'location' => $payload['location'] ?? null,
                'reporter_name' => $payload['reporter_name'] ?? null,
                'reporter_contact' => empty($payload['reporter_contact']) ? null : '[redacted]',
                'description' => empty($payload['description']) ? null : '[redacted]',
            ],
        ]);

        return [
            'success' => false,
            'message' => $responseData['message'] ?? 'Request failed',
        ];
    }

    /**
     * Fetch general requests from Community General Request API (GET)
     */
    private function fetchGeneralRequests(?string $category = null, ?string $status = null): array
    {
        $baseUrl = config('services.community_cim.base_url');
        $timeout = config('services.community_cim.timeout', 30);

        $url = rtrim($baseUrl, '/') . '/api/integration/CITIZEN/GeneralRequest.php';

        $params = [];
        if ($category) {
            $params['category'] = $category;
        }
        if ($status) {
            $params['status'] = $status;
        }

        $response = Http::timeout($timeout)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->withOptions(['verify' => false])
            ->get($url, $params);

        if ($response->successful()) {
            return $response->json();
        }

        $errorData = $response->json();
        
        return [
            'success' => false,
            'message' => $errorData['message'] ?? 'Failed to fetch requests: ' . $response->status(),
        ];
    }

    /**
     * Store a local record of the submission for tracking
     */
    private function storeLocalRecord(array $validated, ?int $externalRequestId, string $facilityName, $photoFile = null): void
    {
        try {
            // Store photo locally if provided
            $localPhotoPath = null;
            if ($photoFile) {
                $localPhotoPath = $photoFile->store('community_maintenance_photos', 'public');
            }

            DB::connection('facilities_db')->table('community_maintenance_requests')->insert([
                'external_request_id' => $externalRequestId,
                'external_report_id' => $externalRequestId,
                'category' => $validated['category'],
                'issue_type' => $validated['issue_type'],
                'facility_id' => $validated['facility_id'],
                'facility_name' => $facilityName,
                'resident_name' => $validated['reporter_name'],
                'contact_info' => $validated['reporter_contact'],
                'reporter_name' => $validated['reporter_name'],
                'reporter_contact' => $validated['reporter_contact'],
                'subject' => $validated['issue_type'] . ' - ' . $validated['category'],
                'description' => $validated['description'],
                'location' => $validated['location'],
                'unit_number' => $validated['location'],
                'report_type' => 'maintenance',
                'priority' => strtolower($validated['priority']),
                'photo_path' => $localPhotoPath,
                'status' => 'Pending',
                'submitted_by_user_id' => session('user_id'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Log but don't fail the request if local storage fails
            Log::warning('Failed to store local community maintenance record', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update local records with statuses from API
     */
    private function updateLocalStatuses(array $requests): void
    {
        try {
            foreach ($requests as $request) {
                $requestId = $request['request_id'] ?? null;
                if (!$requestId) continue;

                DB::connection('facilities_db')
                    ->table('community_maintenance_requests')
                    ->where('external_request_id', $requestId)
                    ->update([
                        'status' => $request['status'] ?? 'Pending',
                        'updated_at' => now(),
                    ]);
            }
        } catch (\Exception $e) {
            Log::warning('Failed to update local maintenance statuses', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get facilities from database
     */
    private function getFacilities(): array
    {
        try {
            $facilities = DB::connection('facilities_db')
                ->table('facilities')
                ->where('is_available', true)
                ->whereNull('deleted_at')
                ->orderBy('name')
                ->get(['facility_id', 'name', 'address', 'full_address']);

            return $facilities->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get available categories for the General Request API
     */
    private function getCategories(): array
    {
        return [
            'Facilities',
            'Housing',
            'Community',
            'Urban Planning',
            'Land Management',
            'Road Infrastructure',
            'Energy',
            'Utilities',
        ];
    }

    /**
     * Get issue types grouped by category
     */
    private function getIssueTypesByCategory(): array
    {
        return [
            'Facilities' => [
                'Broken Equipment',
                'Electrical Issues',
                'Plumbing Problems',
                'HVAC Issues',
                'Structural Damage',
            ],
            'Housing' => [
                'Building Repairs',
                'Water Supply Issues',
                'Electrical Problems',
                'Sanitation Issues',
                'Safety Concerns',
            ],
            'Community' => [
                'Street Lighting',
                'Road Damage',
                'Drainage Issues',
                'Public Facilities',
                'Waste Management',
            ],
            'Urban Planning' => [
                'Zoning Issues',
                'Land Use Concerns',
                'Infrastructure Development',
                'Traffic Management',
                'Environmental Issues',
            ],
            'Land Management' => [
                'Land Titling Issues',
                'Property Boundary Disputes',
                'Illegal Occupation',
                'Land Development',
                'Soil Erosion',
            ],
            'Road Infrastructure' => [
                'Potholes',
                'Road Cracks',
                'Missing Signage',
                'Traffic Lights Malfunction',
                'Bridge Damage',
            ],
            'Energy' => [
                'Power Outages',
                'Street Light Issues',
                'Transformer Problems',
                'Electrical Hazards',
                'Solar Panel Issues',
            ],
            'Utilities' => [
                'Water Supply Problems',
                'Sewage Issues',
                'Garbage Collection',
                'Internet Connectivity',
                'Telecommunications',
            ],
        ];
    }

    /**
     * Get priority levels
     */
    private function getPriorityLevels(): array
    {
        return [
            ['value' => 'Low', 'label' => 'Low', 'color' => 'green', 'description' => 'Non-urgent, can wait'],
            ['value' => 'Medium', 'label' => 'Medium', 'color' => 'yellow', 'description' => 'Should be addressed soon'],
            ['value' => 'High', 'label' => 'High', 'color' => 'orange', 'description' => 'Needs prompt attention'],
            ['value' => 'Urgent', 'label' => 'Urgent', 'color' => 'red', 'description' => 'Immediate attention required'],
        ];
    }
}
