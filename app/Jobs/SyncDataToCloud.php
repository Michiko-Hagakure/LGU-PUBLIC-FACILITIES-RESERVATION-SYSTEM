<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncDataToCloud implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        $baseUrl = rtrim(env('LIVE_SYSTEM_URL'), '/');
        $key = env('SYNC_API_KEY');
        $syncEndpoint = $baseUrl . '/api/sync-data';

        $syncConfigs = [
            ['table' => 'users', 'connection' => 'auth_db', 'pk' => 'id'],
            ['table' => 'bookings', 'connection' => 'facilities_db', 'pk' => 'id'],
            ['table' => 'activity_logs', 'connection' => 'auth_db', 'pk' => 'id'],
            ['table' => 'payment_slips', 'connection' => 'facilities_db', 'pk' => 'id'],
            ['table' => 'announcements', 'connection' => 'facilities_db', 'pk' => 'id'],
            ['table' => 'facilities', 'connection' => 'facilities_db', 'pk' => 'facility_id'],
            ['table' => 'water_connection_requests', 'connection' => 'facilities_db', 'pk' => 'id'],
        ];

        foreach ($syncConfigs as $config) {
            $this->uploadToCloud($config, $syncEndpoint, $key);
            $this->downloadFromCloud($config, $syncEndpoint, $key);
        }
    }

    private function uploadToCloud($config, $url, $key)
    {
        $table = $config['table'];
        $conn = $config['connection'];
        $pk = $config['pk'];

        DB::connection($conn)
            ->table($table)
            ->where('is_synced', 0)
            ->orderBy($pk)
            ->chunk(50, function ($records) use ($table, $url, $key, $conn, $pk) {
                try {
                    // Added withoutVerifying() to fix cURL error 77
                    $response = Http::withoutVerifying() 
                        ->withHeaders(['X-Sync-Key' => $key])
                        ->timeout(120)
                        ->post($url, [
                            'action' => 'upload',
                            'table'  => $table,
                            'data'   => $records->toArray()
                        ]);

                    Log::info("SYNC_UPLOAD_REPORT", [
                        'table'  => $table,
                        'status' => $response->status(),
                        'count'  => $records->count(),
                        'ok'     => $response->successful()
                    ]);

                    if ($response->successful()) {
                        DB::connection($conn)
                            ->table($table)
                            ->whereIn($pk, $records->pluck($pk))
                            ->update([
                                'is_synced' => 1,
                                'last_synced_at' => now()
                            ]);
                    }
                } catch (\Exception $e) {
                    Log::error("SYNC_UPLOAD_EXCEPTION", [
                        'table'   => $table,
                        'message' => $e->getMessage()
                    ]);
                }
            });
    }

    private function downloadFromCloud($config, $url, $key)
    {
        $table = $config['table'];
        $conn = $config['connection'];
        $pk = $config['pk'];

        try {
            // Added withoutVerifying() to fix cURL error 77
            $response = Http::withoutVerifying() 
                ->withHeaders(['X-Sync-Key' => $key])
                ->get($url, [
                    'action' => 'download',
                    'table'  => $table
                ]);

            $status = $response->status();
            $dataFound = isset($response->json()['data']) ? count($response->json()['data']) : 0;

            Log::info("SYNC_DOWNLOAD_REPORT", [
                'table'  => $table,
                'status' => $status,
                'found'  => $dataFound,
                'ok'     => $response->successful()
            ]);

            if ($response->successful() && $dataFound > 0) {
                $cloudRecords = $response->json()['data'];

                foreach ($cloudRecords as $record) {
                    $recordArray = (array)$record;
                    $recordArray['is_synced'] = 1;
                    $recordArray['last_synced_at'] = now();

                    if (isset($recordArray[$pk])) {
                        DB::connection($conn)
                            ->table($table)
                            ->updateOrInsert([$pk => $recordArray[$pk]], $recordArray);
                    } else {
                        Log::warning("SYNC_PK_MISSING", [
                            'table' => $table, 
                            'expected_pk' => $pk
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error("SYNC_DOWNLOAD_EXCEPTION", [
                'table'   => $table,
                'message' => $e->getMessage()
            ]);
        }
    }
}