<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RoadTransportApiService
{
    protected string $apiUrl;
    protected int $systemUserId;
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
        $this->systemUserId = (int) config('services.road_transport.system_user_id', 35);
        $this->timeout = config('services.road_transport.timeout', 30);
    }

    /**
     * Submit an event request to the Road & Transportation system.
     *
     * POST JSON to /api/integrations/EventRequest.php
     *
     * Required: user_id, system_name, event_type, start_date, end_date, location, description
     * Optional: landmark
     *
     * Returns: { success: true, message: "...", id: 8 }
     */
    public function createRequest(array $data): array
    {
        try {
            $eventType = $this->eventTypeLabels[$data['event_type']] ?? $data['event_type'];

            $payload = [
                'user_id'     => $this->systemUserId,
                'system_name' => 'Public Facility Reservation System',
                'event_type'  => $eventType,
                'start_date'  => $data['start_date'],
                'end_date'    => $data['end_date'],
                'location'    => $data['location'],
                'landmark'    => $data['landmark'] ?? null,
                'description' => $data['description'],
            ];

            $response = Http::withoutVerifying()
                ->timeout($this->timeout)
                ->asJson()
                ->post($this->apiUrl, $payload);

            if ($response->successful()) {
                $result = $response->json();

                if (!empty($result['success'])) {
                    Log::info('Road assistance request submitted to Road & Transportation', [
                        'request_id'  => $result['id'] ?? null,
                        'user_id'     => $data['user_id'] ?? null,
                    ]);

                    return [
                        'success'    => true,
                        'request_id' => $result['id'] ?? null,
                        'message'    => $result['message'] ?? 'Request submitted successfully',
                    ];
                }

                return [
                    'success' => false,
                    'error'   => $result['message'] ?? 'Road & Transportation system rejected the request',
                ];
            }

            Log::error('Road assistance request failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return [
                'success' => false,
                'error'   => 'Failed to submit request to Road & Transportation system (HTTP ' . $response->status() . ')',
            ];

        } catch (\Exception $e) {
            Log::error('Road assistance API exception', ['error' => $e->getMessage()]);

            return [
                'success' => false,
                'error'   => 'Connection error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check the status of a specific event request from the Road & Transportation system.
     *
     * GET /api/integrations/EventRequest.php?id={id}
     *
     * Returns: { success: true, data: [ { id, status, remarks, ... } ] }
     */
    public function checkStatus(int $externalRequestId): array
    {
        try {
            $response = Http::withoutVerifying()
                ->timeout($this->timeout)
                ->get($this->apiUrl, ['id' => $externalRequestId]);

            if ($response->successful()) {
                $result = $response->json();

                if (!empty($result['success']) && !empty($result['data'])) {
                    $record = is_array($result['data']) ? ($result['data'][0] ?? null) : null;
                    return [
                        'success' => true,
                        'data'    => $record,
                    ];
                }

                return ['success' => false, 'error' => $result['message'] ?? 'Request not found'];
            }

            return ['success' => false, 'error' => 'HTTP ' . $response->status()];

        } catch (\Exception $e) {
            Log::error('Road assistance status check failed', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => 'Connection error: ' . $e->getMessage()];
        }
    }

    /**
     * Sync statuses of all pending local requests from the external system.
     * Polls GET /api/integrations/EventRequest.php?id={id} for each record.
     */
    public function syncStatuses(): array
    {
        $records = DB::connection('facilities_db')
            ->table('citizen_road_requests')
            ->whereNotNull('external_request_id')
            ->where('status', 'pending')
            ->get();

        $updated = 0;

        foreach ($records as $record) {
            $result = $this->checkStatus($record->external_request_id);

            if ($result['success'] && !empty($result['data'])) {
                $externalStatus = $result['data']['status'] ?? null;
                $externalRemarks = $result['data']['remarks'] ?? null;

                if ($externalStatus && $externalStatus !== 'pending') {
                    DB::connection('facilities_db')
                        ->table('citizen_road_requests')
                        ->where('id', $record->id)
                        ->update([
                            'status'     => $externalStatus,
                            'remarks'    => $externalRemarks,
                            'updated_at' => now(),
                        ]);
                    $updated++;
                }
            }
        }

        Log::info('Road assistance status sync completed', ['updated' => $updated, 'total' => $records->count()]);
        return ['updated' => $updated, 'total' => $records->count()];
    }

    /**
     * Get all local requests for a user from the citizen_road_requests table.
     */
    public function getRequestsByUser(int $userId): array
    {
        try {
            $requests = DB::connection('facilities_db')
                ->table('citizen_road_requests')
                ->where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->get();

            return [
                'success' => true,
                'data'    => $requests->toArray(),
            ];
        } catch (\Exception $e) {
            Log::error('Failed to fetch local road requests', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => 'Database error', 'data' => []];
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
                'user_id'    => $record->user_id,
                'event_type' => $record->event_type,
                'start_date' => $record->start_datetime,
                'end_date'   => $record->end_datetime,
                'location'   => $record->location,
                'landmark'   => $record->landmark,
                'description' => $record->description,
            ]);

            if ($result['success']) {
                DB::connection('facilities_db')
                    ->table('citizen_road_requests')
                    ->where('id', $record->id)
                    ->update([
                        'external_request_id' => $result['request_id'],
                        'status'     => 'pending',
                        'remarks'    => null,
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
     * Get a specific local request by external request ID
     */
    public function getRequestByExternalId(int $externalRequestId): array
    {
        try {
            $request = DB::connection('facilities_db')
                ->table('citizen_road_requests')
                ->where('external_request_id', $externalRequestId)
                ->first();

            if ($request) {
                return ['success' => true, 'data' => $request];
            }

            return ['success' => false, 'error' => 'Request not found'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => 'Connection error'];
        }
    }
}
