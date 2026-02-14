<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncDataController extends Controller
{
    /**
     * Table-to-connection and primary key mapping.
     * Must match the config in App\Jobs\SyncDataToCloud.
     */
    private array $syncConfigs = [
        'users'          => ['connection' => 'auth_db',       'pk' => 'id'],
        'bookings'       => ['connection' => 'facilities_db', 'pk' => 'id'],
        'activity_logs'  => ['connection' => 'auth_db',       'pk' => 'id'],
        'payment_slips'  => ['connection' => 'facilities_db', 'pk' => 'id'],
        'announcements'  => ['connection' => 'facilities_db', 'pk' => 'id'],
        'facilities'     => ['connection' => 'facilities_db', 'pk' => 'facility_id'],
    ];

    /**
     * POST /api/sync-data  — receive rows from the remote system (upload action)
     * GET  /api/sync-data  — return rows that the remote system needs (download action)
     */
    public function handle(Request $request)
    {
        // ---- Auth check ----
        $expectedKey = env('SYNC_API_KEY');

        if (!$expectedKey || $request->header('X-Sync-Key') !== $expectedKey) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $action = $request->input('action');
        $table  = $request->input('table');

        if (!$action || !$table || !isset($this->syncConfigs[$table])) {
            return response()->json(['error' => 'Invalid action or table'], 422);
        }

        if ($action === 'upload') {
            return $this->receiveUpload($request, $table);
        }

        if ($action === 'download') {
            return $this->sendDownload($table);
        }

        return response()->json(['error' => 'Unknown action'], 422);
    }

    /**
     * Receive rows pushed by the remote system and upsert them locally.
     */
    private function receiveUpload(Request $request, string $table)
    {
        $config  = $this->syncConfigs[$table];
        $conn    = $config['connection'];
        $pk      = $config['pk'];
        $rows    = $request->input('data', []);

        if (empty($rows)) {
            return response()->json(['message' => 'No data provided'], 200);
        }

        $inserted = 0;
        $updated  = 0;
        $errors   = 0;

        foreach ($rows as $row) {
            try {
                $record = (array) $row;

                // Mark as synced on receipt
                $record['is_synced']       = 1;
                $record['last_synced_at']  = now();

                if (!isset($record[$pk])) {
                    Log::warning('SYNC_RECEIVE_PK_MISSING', [
                        'table'       => $table,
                        'expected_pk' => $pk,
                    ]);
                    $errors++;
                    continue;
                }

                $exists = DB::connection($conn)
                    ->table($table)
                    ->where($pk, $record[$pk])
                    ->exists();

                if ($exists) {
                    DB::connection($conn)
                        ->table($table)
                        ->where($pk, $record[$pk])
                        ->update($record);
                    $updated++;
                } else {
                    DB::connection($conn)
                        ->table($table)
                        ->insert($record);
                    $inserted++;
                }
            } catch (\Exception $e) {
                Log::error('SYNC_RECEIVE_ROW_ERROR', [
                    'table'   => $table,
                    'message' => $e->getMessage(),
                ]);
                $errors++;
            }
        }

        Log::info('SYNC_RECEIVE_REPORT', [
            'table'    => $table,
            'inserted' => $inserted,
            'updated'  => $updated,
            'errors'   => $errors,
        ]);

        return response()->json([
            'message'  => 'Upload processed',
            'inserted' => $inserted,
            'updated'  => $updated,
            'errors'   => $errors,
        ], 200);
    }

    /**
     * Return rows that have not yet been synced so the remote system can download them.
     */
    private function sendDownload(string $table)
    {
        $config = $this->syncConfigs[$table];
        $conn   = $config['connection'];
        $pk     = $config['pk'];

        try {
            $records = DB::connection($conn)
                ->table($table)
                ->where('is_synced', 0)
                ->orderBy($pk)
                ->limit(200)
                ->get();

            // Mark the returned records as synced so they are not sent again
            if ($records->isNotEmpty()) {
                DB::connection($conn)
                    ->table($table)
                    ->whereIn($pk, $records->pluck($pk))
                    ->update([
                        'is_synced'      => 1,
                        'last_synced_at' => now(),
                    ]);
            }

            return response()->json([
                'data'  => $records,
                'count' => $records->count(),
            ], 200);
        } catch (\Exception $e) {
            Log::error('SYNC_SEND_DOWNLOAD_ERROR', [
                'table'   => $table,
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Failed to fetch records',
                'data'  => [],
            ], 500);
        }
    }
}
