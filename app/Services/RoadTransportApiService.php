<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RoadTransportApiService
{
    protected string $submitUrl;
    protected string $checkStatusUrl;
    protected string $webhookReceiverUrl;
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
        $this->submitUrl = config('services.road_transport.submit_url');
        $this->checkStatusUrl = config('services.road_transport.check_status_url');
        $this->webhookReceiverUrl = config('services.road_transport.webhook_receiver_url');
        $this->timeout = config('services.road_transport.timeout', 30);
    }

    /**
     * Submit a traffic event request to the Road & Transportation system.
     *
     * Posts form data to /api/submit_traffic_event.php which expects:
     *   event_type, location, landmark, start_date, end_date,
     *   description, system_name, contact_person, contact_number, webhook_url
     *
     * Returns JSON: { success, message, request_id }
     */
    public function createRequest(array $data): array
    {
        try {
            $eventType = $this->eventTypeLabels[$data['event_type']] ?? $data['event_type'];

            // Build our webhook callback URL so they can notify us on approval/rejection
            $ourWebhookUrl = rtrim(config('app.url'), '/') . '/api/road-transport/webhook';

            $payload = [
                'event_type'     => $eventType,
                'location'       => $data['location'],
                'landmark'       => $data['landmark'] ?? null,
                'start_date'     => $data['start_date'],
                'end_date'       => $data['end_date'],
                'description'    => $data['description'],
                'system_name'    => 'Public Facility Reservation System',
                'contact_person' => $data['contact_person'] ?? null,
                'contact_number' => $data['contact_number'] ?? null,
                'webhook_url'    => $ourWebhookUrl,
            ];

            $response = Http::timeout($this->timeout)
                ->asForm()
                ->post($this->submitUrl, $payload);

            if ($response->successful()) {
                $result = $response->json();

                if (!empty($result['success'])) {
                    Log::info('Road assistance request submitted to Road & Transportation', [
                        'request_id'  => $result['request_id'] ?? null,
                        'user_id'     => $data['user_id'] ?? null,
                    ]);

                    return [
                        'success'    => true,
                        'request_id' => $result['request_id'] ?? null,
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
     * Send a notification to the Road & Transportation webhook receiver.
     *
     * Posts JSON to /api/webhook_receiver.php which expects:
     *   request_id, status, event_type, location, remarks, timestamp
     */
    public function sendWebhookNotification(array $data): array
    {
        try {
            $payload = [
                'request_id' => $data['request_id'],
                'status'     => $data['status'],
                'event_type' => $data['event_type'] ?? null,
                'location'   => $data['location'] ?? null,
                'remarks'    => $data['remarks'] ?? null,
                'timestamp'  => $data['timestamp'] ?? now()->toDateTimeString(),
            ];

            $response = Http::timeout($this->timeout)
                ->asJson()
                ->post($this->webhookReceiverUrl, $payload);

            if ($response->successful()) {
                $result = $response->json();
                Log::info('Road & Transportation webhook notification sent', [
                    'request_id' => $data['request_id'],
                    'status'     => $data['status'],
                ]);

                return [
                    'success' => $result['success'] ?? true,
                    'message' => $result['message'] ?? 'Notification sent',
                ];
            }

            Log::error('Road & Transportation webhook notification failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return ['success' => false, 'error' => 'Webhook notification failed'];

        } catch (\Exception $e) {
            Log::error('Road & Transportation webhook exception', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => 'Connection error: ' . $e->getMessage()];
        }
    }

    /**
     * Get all local requests for a user from the citizen_road_requests table.
     * Status updates come in via our webhook endpoint, so we read locally.
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
