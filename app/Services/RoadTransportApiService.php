<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RoadTransportApiService
{
    protected string $apiUrl;
    protected int $timeout;

    /**
     * Map internal event_type keys to human-readable labels for the external API
     */
    protected array $eventTypeLabels = [
        'traffic_management' => 'Traffic Management',
        'road_closure' => 'Temporary Road Closure',
        'escort' => 'Vehicle Escort Service',
        'signage' => 'Traffic Signage & Cones',
        'personnel' => 'Traffic Personnel Deployment',
        'rerouting' => 'Traffic Rerouting Plan',
    ];

    public function __construct()
    {
        $this->apiUrl = config('services.road_transport.api_url');
        $this->timeout = config('services.road_transport.timeout', 30);
    }

    /**
     * Create a road assistance request to the Road & Transportation system
     */
    public function createRequest(array $data): array
    {
        try {
            // Map snake_case event_type to human-readable label for external API
            $eventType = $this->eventTypeLabels[$data['event_type']] ?? $data['event_type'];

            $payload = [
                'user_id' => $data['user_id'],
                'system_name' => 'Public Facility Reservation System',
                'event_type' => $eventType,
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'location' => $data['location'],
                'landmark' => $data['landmark'] ?? null,
                'description' => $data['description'],
            ];

            $response = Http::timeout($this->timeout)
                ->asJson()
                ->post($this->apiUrl, $payload);

            if ($response->successful()) {
                $result = $response->json();
                Log::info('Road assistance request sent successfully', [
                    'request_id' => $result['request_id'] ?? null,
                    'user_id' => $data['user_id']
                ]);
                return [
                    'success' => true,
                    'request_id' => $result['request_id'] ?? null,
                    'message' => $result['message'] ?? 'Request submitted successfully'
                ];
            }

            Log::error('Road assistance request failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => 'Failed to submit request to Road & Transportation system'
            ];

        } catch (\Exception $e) {
            Log::error('Road assistance API exception', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get all requests for a user from the Road & Transportation system
     */
    public function getRequestsByUser(int $userId): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get($this->apiUrl, ['user_id' => $userId]);

            if ($response->successful()) {
                $result = $response->json();
                return [
                    'success' => true,
                    'data' => $result['data'] ?? []
                ];
            }

            return [
                'success' => false,
                'error' => 'Failed to fetch requests',
                'data' => []
            ];

        } catch (\Exception $e) {
            Log::error('Road assistance API get exception', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'Connection error',
                'data' => []
            ];
        }
    }

    /**
     * Retry syncing pending_sync records to the Road & Transportation system
     */
    public function retrySyncPending(): array
    {
        $pending = DB::connection('facilities_db')
            ->table('citizen_road_requests')
            ->where('status', 'pending_sync')
            ->get();

        $synced = 0;
        $failed = 0;

        foreach ($pending as $record) {
            $result = $this->createRequest([
                'user_id' => $record->user_id,
                'event_type' => $record->event_type,
                'start_date' => $record->start_datetime,
                'end_date' => $record->end_datetime,
                'location' => $record->location,
                'landmark' => $record->landmark,
                'description' => $record->description,
            ]);

            if ($result['success']) {
                DB::connection('facilities_db')
                    ->table('citizen_road_requests')
                    ->where('id', $record->id)
                    ->update([
                        'external_request_id' => $result['request_id'],
                        'status' => 'pending',
                        'remarks' => null,
                        'updated_at' => now(),
                    ]);
                $synced++;
            } else {
                $failed++;
            }
        }

        Log::info('Road assistance retry sync completed', ['synced' => $synced, 'failed' => $failed]);
        return ['synced' => $synced, 'failed' => $failed, 'total' => $pending->count()];
    }

    /**
     * Get a specific request by ID
     */
    public function getRequestById(int $requestId): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->get($this->apiUrl, ['id' => $requestId]);

            if ($response->successful()) {
                $result = $response->json();
                $data = $result['data'] ?? [];
                return [
                    'success' => true,
                    'data' => is_array($data) && count($data) > 0 ? $data[0] : null
                ];
            }

            return [
                'success' => false,
                'error' => 'Request not found'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Connection error'
            ];
        }
    }
}
