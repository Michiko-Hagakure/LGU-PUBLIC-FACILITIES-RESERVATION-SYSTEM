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

    /**
     * Execute the job to sync local data to the cloud server.
     */
    public function handle(): void
    {
        // Fetch API credentials and endpoint from the .env file
        $url = env('LIVE_SYSTEM_URL');
        $key = env('SYNC_API_KEY');

        // TASK 1: Sync User Accounts from the 'auth_db' connection
        $this->syncTable('users', 'auth_db', $url, $key);

        // TASK 2: Sync Booking records from the 'facilities_db' connection
        $this->syncTable('bookings', 'facilities_db', $url, $key);
    }
    /**
     * Helper function to process the sync logic for a specific table.
     */
    private function syncTable($tableName, $connectionName, $url, $key)
    {
        // Use chunking to prevent memory and payload size issues
        DB::connection($connectionName)
            ->table($tableName)
            ->where('is_synced', 0)
            ->orderBy('id')
            ->chunk(50, function ($records) use ($tableName, $url, $key, $connectionName) {
                try {
                    $response = Http::withHeaders([
                        'X-Sync-Key' => $key
                    ])->timeout(120)->post($url, [
                                'table' => $tableName,
                                'data' => $records->toArray()
                            ]);

                    if ($response->successful()) {
                        DB::connection($connectionName)
                            ->table($tableName)
                            ->whereIn('id', $records->pluck('id'))
                            ->update([
                                'is_synced' => 1,
                                'last_synced_at' => now()
                            ]);

                        Log::info("Sync Success [$tableName]: " . $records->count() . " records synced.");
                    } else {
                        // Log the actual error body from the server to see why it failed
                        Log::error("Sync Failed [$tableName]: " . $response->status() . " - " . $response->body());
                    }
                } catch (\Exception $e) {
                    Log::error("Sync Exception [$tableName]: " . $e->getMessage());
                }
            });
    }
}