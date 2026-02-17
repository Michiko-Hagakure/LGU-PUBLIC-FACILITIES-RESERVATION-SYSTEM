<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoadTransportWebhookController extends Controller
{
    /**
     * Handle incoming webhook notifications from the Road & Transportation system.
     *
     * Expected JSON payload:
     *   request_id  - The external request ID assigned by their system
     *   status      - approved | rejected | pending
     *   remarks     - Admin remarks / feedback
     *   event_type  - (optional) The event type
     *   location    - (optional) The location
     *   timestamp   - (optional) When the status changed
     */
    public function handle(Request $request)
    {
        $response = ['success' => false, 'message' => ''];

        try {
            $input = $request->all();

            $requestId = $input['request_id'] ?? null;
            $status    = $input['status'] ?? null;
            $remarks   = $input['remarks'] ?? null;
            $eventType = $input['event_type'] ?? null;
            $location  = $input['location'] ?? null;
            $timestamp = $input['timestamp'] ?? now()->toDateTimeString();

            if (!$requestId || !$status) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing request_id or status',
                ], 400);
            }

            Log::info('Road & Transportation webhook received', [
                'request_id' => $requestId,
                'status'     => $status,
                'event_type' => $eventType,
                'location'   => $location,
                'remarks'    => $remarks,
                'timestamp'  => $timestamp,
            ]);

            // Update local citizen_road_requests record
            $updated = DB::connection('facilities_db')
                ->table('citizen_road_requests')
                ->where('external_request_id', $requestId)
                ->update([
                    'status'     => $status,
                    'remarks'    => $remarks,
                    'updated_at' => now(),
                ]);

            if ($updated) {
                Log::info('Road assistance request status updated via webhook', [
                    'external_request_id' => $requestId,
                    'new_status'          => $status,
                ]);

                $response['success'] = true;
                $response['message'] = 'Status updated successfully';
                return response()->json($response, 200);
            }

            // Record not found locally — log but still acknowledge
            Log::warning('Road & Transportation webhook: no local record found', [
                'external_request_id' => $requestId,
            ]);

            $response['success'] = true;
            $response['message'] = 'Notification received (no matching local record)';
            return response()->json($response, 200);

        } catch (\Exception $e) {
            Log::error('Road & Transportation webhook error', [
                'error' => $e->getMessage(),
            ]);

            $response['message'] = 'Internal server error';
            return response()->json($response, 500);
        }
    }
}
