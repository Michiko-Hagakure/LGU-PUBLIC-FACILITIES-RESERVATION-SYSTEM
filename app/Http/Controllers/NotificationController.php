<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Notification types that are exclusively for citizens (not for admin/staff/treasurer)
     */
    private const CITIZEN_ONLY_NOTIFICATIONS = [
        'App\\Notifications\\PaymentVerified',
        'App\\Notifications\\StaffVerified',
        'App\\Notifications\\BookingConfirmed',
        'App\\Notifications\\BookingRejected',
        'App\\Notifications\\BookingRejectedWithRefund',
        'App\\Notifications\\BookingCancelled',
        'App\\Notifications\\BookingExpired',
        'App\\Notifications\\PaymentReminder24Hours',
        'App\\Notifications\\PaymentReminder6Hours',
        'App\\Notifications\\PaymentConfirmed',
        'App\\Notifications\\PaymentRejected',
        'App\\Notifications\\PaymentSubmitted',
        'App\\Notifications\\RefundCompleted',
    ];

    /**
     * Notification types that are exclusively for staff/admin/treasurer (not for citizens)
     */
    private const STAFF_ONLY_NOTIFICATIONS = [
        'App\\Notifications\\RefundRequestCreated',
        'App\\Notifications\\RefundMethodSelected',
    ];

    /**
     * Apply role-based notification filtering to a query
     */
    private function applyRoleFilter($query)
    {
        $userRole = strtolower(session('user_role', 'citizen'));

        if (in_array($userRole, ['admin', 'reservations staff', 'treasurer', 'cbd staff', 'super admin'])) {
            // Staff/Admin/Treasurer should NOT see citizen-only notifications
            $query->whereNotIn('type', self::CITIZEN_ONLY_NOTIFICATIONS);
        } else {
            // Citizens should NOT see staff/admin-only notifications
            $query->whereNotIn('type', self::STAFF_ONLY_NOTIFICATIONS);
        }

        return $query;
    }

    /**
     * Get unread notifications for the authenticated user
     */
    public function getUnread()
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return response()->json(['notifications' => [], 'unread_count' => 0]);
        }

        // Get recent notifications (both read and unread) from the database
        $query = DB::connection('auth_db')->table('notifications')
            ->where('notifiable_type', 'App\\Models\\User')
            ->where('notifiable_id', $userId);
        $this->applyRoleFilter($query);
        $notifications = $query->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get count of only unread notifications
        $unreadQuery = DB::connection('auth_db')->table('notifications')
            ->where('notifiable_type', 'App\\Models\\User')
            ->where('notifiable_id', $userId)
            ->whereNull('read_at');
        $this->applyRoleFilter($unreadQuery);
        $unreadCount = $unreadQuery->count();

        // Format notifications for display
        $formattedNotifications = $notifications->map(function ($notification) {
            $data = json_decode($notification->data, true);
            return [
                'id' => $notification->id,
                'type' => $notification->type,
                'message' => $data['message'] ?? 'New notification',
                'created_at' => $notification->created_at,
                'time_ago' => \Carbon\Carbon::parse($notification->created_at)->diffForHumans(),
                'is_read' => $notification->read_at !== null,
                'data' => $data,
            ];
        });

        return response()->json([
            'notifications' => $formattedNotifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $updated = DB::connection('auth_db')->table('notifications')
            ->where('id', $id)
            ->where('notifiable_type', 'App\\Models\\User')
            ->where('notifiable_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $query = DB::connection('auth_db')->table('notifications')
            ->where('notifiable_type', 'App\\Models\\User')
            ->where('notifiable_id', $userId)
            ->whereNull('read_at');
        $this->applyRoleFilter($query);
        $query->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Get all notifications (read and unread)
     */
    public function index()
    {
        $userId = session('user_id');
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        $query = DB::connection('auth_db')->table('notifications')
            ->where('notifiable_type', 'App\\Models\\User')
            ->where('notifiable_id', $userId);
        $this->applyRoleFilter($query);
        $notifications = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        // Format notifications
        $notifications->getCollection()->transform(function ($notification) {
            $data = json_decode($notification->data, true);
            $notification->message = $data['message'] ?? 'New notification';
            $notification->time_ago = \Carbon\Carbon::parse($notification->created_at)->diffForHumans();
            $notification->data_array = $data;
            return $notification;
        });

        return view('notifications.index', compact('notifications'));
    }
}
