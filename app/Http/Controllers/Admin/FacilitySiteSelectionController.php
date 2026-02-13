<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class FacilitySiteSelectionController extends Controller
{
    private $apiBaseUrl;
    private $timeout;

    public function __construct()
    {
        $this->apiBaseUrl = config('services.urban_planning.base_url');
        $this->timeout = config('services.urban_planning.timeout', 30);
    }

    /**
     * Display the facility site selection page
     */
    public function index()
    {
        // Get locations (districts and barangays) from the API
        $locations = $this->getLocations();
        
        // Get zoning types
        $zoningTypes = $this->getZoningTypes();
        
        // Get existing facilities for reference
        $facilities = $this->getFacilities();

        // Get zoning map initial data (barangays + legend) from the Zoning Maps API
        $zoningMapData = $this->getZoningMapInitData();
        $zoningBarangays = $zoningMapData['barangays'] ?? [];
        $zoningLegend = $zoningMapData['legend'] ?? [];
        
        return view('admin.facility-site-selection.index', compact(
            'locations',
            'zoningTypes',
            'facilities',
            'zoningBarangays',
            'zoningLegend'
        ));
    }

    /**
     * Search for suitable sites based on filters
     */
    public function search(Request $request)
    {
        $validated = $request->validate([
            'zoning_type' => 'nullable|string|in:residential,commercial,industrial,agricultural,institutional',
            'district_id' => 'nullable|integer',
            'barangay_id' => 'nullable|integer',
        ]);

        try {
            $params = array_filter([
                'zoning_type' => $validated['zoning_type'] ?? 'institutional',
                'district_id' => $validated['district_id'] ?? null,
                'barangay_id' => $validated['barangay_id'] ?? null,
            ]);

            $response = $this->callApi('GET', '/api/integration/facility-site-selection.php', $params);

            if ($response['success']) {
                return response()->json([
                    'success' => true,
                    'data' => $response['data'],
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $response['message'] ?? 'Failed to search sites',
            ], 400);

        } catch (\Exception $e) {
            Log::error('Facility site search failed', [
                'error' => $e->getMessage(),
                'filters' => $validated,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while searching for sites',
            ], 500);
        }
    }

    /**
     * Check site suitability for a specific location
     */
    public function checkSuitability(Request $request)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'zoning_type' => 'nullable|string|in:residential,commercial,industrial,agricultural,institutional',
        ]);

        try {
            $payload = [
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'zoning_type' => $validated['zoning_type'] ?? 'institutional',
            ];

            $response = $this->callApi('POST', '/api/integration/facility-site-selection.php', $payload);

            if ($response['success']) {
                return response()->json([
                    'success' => true,
                    'message' => $response['message'] ?? 'Suitability check completed',
                    'data' => $response['data'],
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $response['message'] ?? 'Location is not suitable',
                'data' => $response['data'] ?? null,
            ], 400);

        } catch (\Exception $e) {
            Log::error('Site suitability check failed', [
                'error' => $e->getMessage(),
                'location' => $validated,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while checking site suitability',
            ], 500);
        }
    }

    /**
     * Get locations (districts and barangays) from the API
     */
    private function getLocations(): array
    {
        try {
            $response = $this->callApi('GET', '/api/integration/facility-site-selection.php', ['action' => 'locations']);
            
            if ($response['success']) {
                return $response['data']['districts'] ?? [];
            }
        } catch (\Exception $e) {
            Log::warning('Failed to fetch locations from Urban Planning API', [
                'error' => $e->getMessage(),
            ]);
        }

        return [];
    }

    /**
     * Get zoning types
     */
    private function getZoningTypes(): array
    {
        return [
            'institutional' => 'Institutional (Government, Schools, Hospitals)',
            'commercial' => 'Commercial (Business Districts)',
            'residential' => 'Residential (Housing Areas)',
            'industrial' => 'Industrial (Industrial Zones)',
            'agricultural' => 'Agricultural (Farming Areas)',
        ];
    }

    /**
     * Get existing facilities from the database
     */
    private function getFacilities(): array
    {
        try {
            $facilities = DB::connection('facilities_db')
                ->table('facilities')
                ->where('is_available', true)
                ->whereNull('deleted_at')
                ->orderBy('name')
                ->get(['facility_id', 'name', 'address', 'full_address', 'latitude', 'longitude']);

            return $facilities->toArray();
        } catch (\Exception $e) {
            Log::warning('Failed to fetch facilities', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * Get barangays from the Zoning Maps API
     */
    public function getZoningBarangays()
    {
        try {
            $response = $this->callZoningApi('barangays');

            if ($response['success']) {
                return response()->json([
                    'success' => true,
                    'data' => $response['data'],
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $response['message'] ?? 'Failed to fetch barangays',
            ], 400);
        } catch (\Exception $e) {
            Log::error('Zoning Maps API - Failed to fetch barangays', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching barangays',
            ], 500);
        }
    }

    /**
     * Get zoning legend (zone types with colors) from the Zoning Maps API
     */
    public function getZoningLegend()
    {
        try {
            $response = $this->callZoningApi('legend');

            if ($response['success']) {
                return response()->json([
                    'success' => true,
                    'data' => $response['data'],
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $response['message'] ?? 'Failed to fetch zoning legend',
            ], 400);
        } catch (\Exception $e) {
            Log::error('Zoning Maps API - Failed to fetch legend', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching zoning legend',
            ], 500);
        }
    }

    /**
     * Get zoning data (parcels, zones, barangay info) for a specific barangay from the Zoning Maps API
     */
    public function getZoningData(Request $request)
    {
        $validated = $request->validate([
            'barangay_id' => 'required|integer',
        ]);

        try {
            $response = $this->callZoningApi('zoning', ['barangay_id' => $validated['barangay_id']]);

            if ($response['success']) {
                return response()->json([
                    'success' => true,
                    'barangay' => $response['barangay'] ?? null,
                    'zones' => $response['zones'] ?? [],
                    'parcels' => $response['parcels'] ?? [],
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $response['message'] ?? 'Failed to fetch zoning data',
            ], 400);
        } catch (\Exception $e) {
            Log::error('Zoning Maps API - Failed to fetch zoning data', [
                'error' => $e->getMessage(),
                'barangay_id' => $validated['barangay_id'],
            ]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching zoning data',
            ], 500);
        }
    }

    /**
     * Get initial zoning map data (barangays + legend) from the Zoning Maps API
     * Used for page load setup
     */
    private function getZoningMapInitData(): array
    {
        try {
            $response = $this->callZoningApi(null);

            if ($response['success']) {
                return $response['data'] ?? [];
            }
        } catch (\Exception $e) {
            Log::warning('Failed to fetch zoning map init data', ['error' => $e->getMessage()]);
        }

        return [];
    }

    /**
     * Make API call to Urban Planning Zoning Maps endpoint
     */
    private function callZoningApi(?string $action, array $params = []): array
    {
        $url = rtrim($this->apiBaseUrl, '/') . '/api/zoning_maps.php';

        $queryParams = $params;
        if ($action) {
            $queryParams['action'] = $action;
        }

        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders(['Accept' => 'application/json'])
                ->get($url, $queryParams);

            if ($response->successful()) {
                return $response->json() ?? ['success' => false, 'message' => 'Empty response from Zoning API'];
            }

            Log::warning('Zoning Maps API request failed', [
                'url' => $url,
                'action' => $action,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            $errorData = $response->json();
            return [
                'success' => false,
                'message' => $errorData['message'] ?? 'Zoning API request failed with status: ' . $response->status(),
            ];
        } catch (\Exception $e) {
            Log::error('Zoning Maps API exception', [
                'url' => $url,
                'action' => $action,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Make API call to Urban Planning system
     */
    private function callApi(string $method, string $endpoint, array $data = []): array
    {
        $url = rtrim($this->apiBaseUrl, '/') . $endpoint;

        try {
            if ($method === 'GET') {
                $response = Http::timeout($this->timeout)
                    ->withHeaders([
                        'Accept' => 'application/json',
                    ])
                    ->get($url, $data);
            } else {
                $response = Http::timeout($this->timeout)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ])
                    ->post($url, $data);
            }

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning('Urban Planning API request failed', [
                'method' => $method,
                'url' => $url,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            $errorData = $response->json();
            return [
                'success' => false,
                'message' => $errorData['message'] ?? 'Request failed with status: ' . $response->status(),
                'data' => $errorData['data'] ?? null,
            ];

        } catch (\Exception $e) {
            Log::error('Urban Planning API exception', [
                'method' => $method,
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
