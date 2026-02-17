<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UtilityBillingApiService
{
    protected string $baseUrl;
    protected string $partnerId;
    protected int $systemUserId;
    protected int $timeout;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.utility_billing.base_url'), '/');
        $this->partnerId = config('services.utility_billing.partner_id', 'PFRS001');
        $this->systemUserId = (int) config('services.utility_billing.system_user_id', 35);
        $this->timeout = config('services.utility_billing.timeout', 30);
    }

    /**
     * Submit a PFRS request to the Utility Billing system.
     *
     * POST /api/pfrs_clean.php
     */
    public function createRequest(array $data): array
    {
        try {
            $payload = [
                'user_id'              => $this->systemUserId,
                'consumer_name'        => $data['consumer_name'],
                'service_type'         => $data['service_type'],
                'installation_address' => $data['installation_address'],
                'property_type'        => $data['property_type'],
                'contact_person'       => $data['contact_person'],
                'contact_phone'        => $data['contact_phone'],
                'contact_email'        => $data['contact_email'],
                'application_source'   => 'Public Facility Reservation System',
                'partner_id'           => $this->partnerId,
                'partner_reference'    => $data['partner_reference'] ?? null,
                'notes'                => $data['notes'] ?? null,
            ];

            $url = $this->baseUrl . '/api/pfrs_clean.php';

            Log::info('PFRS API: Sending request', ['url' => $url, 'payload' => $payload]);

            $response = Http::withoutVerifying()
                ->timeout($this->timeout)
                ->asForm()
                ->post($url, $payload);

            Log::info('PFRS API: Response received', [
                'status'       => $response->status(),
                'content_type' => $response->header('Content-Type'),
                'body_preview' => substr($response->body(), 0, 500),
            ]);

            if ($response->successful()) {
                $result = $response->json();

                if (!empty($result['success'])) {
                    Log::info('PFRS water connection request submitted', [
                        'id'                 => $result['id'] ?? null,
                        'application_number' => $result['application_number'] ?? null,
                    ]);

                    return [
                        'success'            => true,
                        'id'                 => $result['id'] ?? null,
                        'application_number' => $result['application_number'] ?? null,
                        'message'            => $result['message'] ?? 'Request submitted successfully',
                    ];
                }

                return [
                    'success' => false,
                    'message' => $result['message'] ?? 'Utility Billing system rejected the request',
                ];
            }

            Log::error('PFRS request failed', [
                'status' => $response->status(),
                'body'   => substr($response->body(), 0, 500),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to submit request to Utility Billing (HTTP ' . $response->status() . ')',
            ];

        } catch (\Exception $e) {
            Log::error('PFRS API exception', ['error' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Fetch PFRS requests from the Utility Billing system.
     *
     * GET /api/pfrs_clean.php
     */
    public function getRequests(array $params = []): array
    {
        try {
            $url = $this->baseUrl . '/api/pfrs_clean.php';

            $response = Http::withoutVerifying()
                ->timeout($this->timeout)
                ->get($url, $params);

            if ($response->successful()) {
                $result = $response->json();

                if (!empty($result['success'])) {
                    return [
                        'success' => true,
                        'data'    => $result['data'] ?? [],
                    ];
                }

                return [
                    'success' => false,
                    'message' => $result['message'] ?? 'Failed to fetch requests',
                ];
            }

            return [
                'success' => false,
                'message' => 'HTTP ' . $response->status(),
            ];

        } catch (\Exception $e) {
            Log::error('PFRS GET exception', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Connection error: ' . $e->getMessage()];
        }
    }

    /**
     * Check the status of a specific PFRS request by application number.
     */
    public function checkStatus(string $applicationNumber): array
    {
        return $this->getRequests(['application_number' => $applicationNumber]);
    }

    /**
     * Sync statuses of all local water connection requests from the external system.
     */
    public function syncStatuses(): array
    {
        $records = DB::connection('facilities_db')
            ->table('water_connection_requests')
            ->whereNotNull('external_application_number')
            ->whereNotIn('status', ['completed', 'rejected'])
            ->get();

        $updated = 0;

        foreach ($records as $record) {
            $result = $this->checkStatus($record->external_application_number);

            if ($result['success'] && !empty($result['data'])) {
                $data = is_array($result['data']) && isset($result['data'][0])
                    ? $result['data'][0]
                    : $result['data'];

                $externalStatus = $data['status'] ?? null;
                $remarks = $data['remarks'] ?? null;

                if ($externalStatus && $externalStatus !== $record->status) {
                    DB::connection('facilities_db')
                        ->table('water_connection_requests')
                        ->where('id', $record->id)
                        ->update([
                            'status'     => $externalStatus,
                            'remarks'    => $remarks,
                            'updated_at' => now(),
                        ]);
                    $updated++;
                }
            }
        }

        Log::info('Water connection status sync completed', ['updated' => $updated, 'total' => $records->count()]);
        return ['updated' => $updated, 'total' => $records->count()];
    }

    /**
     * Retry syncing pending_sync records to the Utility Billing system.
     */
    public function retrySyncPending(): array
    {
        $pending = DB::connection('facilities_db')
            ->table('water_connection_requests')
            ->where('status', 'pending_sync')
            ->get();

        $synced = 0;
        $failed = 0;

        foreach ($pending as $record) {
            $result = $this->createRequest([
                'consumer_name'        => $record->consumer_name,
                'service_type'         => $record->service_type,
                'installation_address' => $record->installation_address,
                'property_type'        => $record->property_type,
                'contact_person'       => $record->contact_person,
                'contact_phone'        => $record->contact_phone,
                'contact_email'        => $record->contact_email,
                'partner_reference'    => $record->partner_reference,
                'notes'                => $record->notes,
            ]);

            if ($result['success']) {
                DB::connection('facilities_db')
                    ->table('water_connection_requests')
                    ->where('id', $record->id)
                    ->update([
                        'external_id'                 => $result['id'] ?? null,
                        'external_application_number' => $result['application_number'] ?? null,
                        'status'                      => 'submitted',
                        'remarks'                     => null,
                        'updated_at'                  => now(),
                    ]);
                $synced++;
            } else {
                $failed++;
            }
        }

        Log::info('Water connection retry sync completed', ['synced' => $synced, 'failed' => $failed]);
        return ['synced' => $synced, 'failed' => $failed, 'total' => $pending->count()];
    }
}
