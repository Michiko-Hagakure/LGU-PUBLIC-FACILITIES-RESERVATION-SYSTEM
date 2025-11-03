<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Facility;
use App\Models\PaymentSlip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with REAL database data.
     */
    public function index(Request $request)
    {
        // Get authenticated admin
        $admin = Auth::user();
        
        // Fallback admin data if not authenticated
        if (!$admin) {
            $admin = (object) [
                'id' => 1,
                'name' => 'Administrator',
                'email' => 'admin@lgu1.com',
                'role' => 'admin',
                'status' => 'active'
            ];
        }
        
        $admin->full_name = $admin->name;
        $admin->avatar_initials = $this->generateInitials($admin->name);
        
        // REAL DATABASE QUERIES
        
        // Pending Approvals (status = pending)
        $pendingApprovals = Booking::with(['facility', 'user'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
        $pendingApprovalsCount = $pendingApprovals->count();
        
        // Schedule Conflicts (detect overlapping bookings)
        $conflicts = $this->detectScheduleConflicts();
        
        // Overdue Payments (payment slips past due date and unpaid)
        $overduePayments = PaymentSlip::with('reservation.user')
            ->where('status', 'unpaid')
            ->where('due_date', '<', now())
            ->orderBy('due_date', 'asc')
            ->get();
        $overduePaymentsCount = $overduePayments->count();

        // Recent Activity Log
        $recentActivities = $this->getRecentActivities();
        
        return view('admin.dashboard.index', compact(
            'admin',
            'pendingApprovals',
            'pendingApprovalsCount',
            'conflicts',
            'overduePayments',
            'overduePaymentsCount',
            'recentActivities'
        ));
    }

    /**
     * Get quick stats for admin dashboard (REAL DATA).
     */
    public function getQuickStats(Request $request)
    {
        $stats = [
            'pending_approvals' => Booking::where('status', 'pending')->count(),
            'conflicts' => $this->detectScheduleConflicts()->count(),
            'overdue_payments' => PaymentSlip::where('status', 'unpaid')
                                           ->where('due_date', '<', now())
                                           ->count(),
            'todays_events' => Booking::where('status', 'approved')
                                     ->whereDate('event_date', now()->toDateString())
                                     ->count()
        ];
        
        return response()->json($stats);
    }

    /**
     * Detect schedule conflicts (overlapping bookings on same facility/date).
     *
     * @return \Illuminate\Support\Collection
     */
    private function detectScheduleConflicts(): Collection
    {
        // This query finds the IDs of bookings that overlap in time and facility
        $conflictingIds = DB::table('bookings as a') 
            ->select('a.id')
            ->join('bookings as b', function ($join) {
                $join->on('a.facility_id', '=', 'b.facility_id')
                     ->on('a.event_date', '=', 'b.event_date')
                     ->whereRaw('a.id != b.id')
                     ->whereRaw('(a.start_time < b.end_time AND a.end_time > b.start_time)')
                     ->whereIn('a.status', ['approved', 'pending'] )
                     ->whereIn('b.status', ['approved', 'pending'] );
            })
            // Prevent duplicates
            ->distinct()
            ->pluck('a.id');
        
        return Booking::whereIn('id', $conflictingIds)
            ->with(['facility', 'user'])
            ->get();
    }
    
    /**
     * Get recent activities (last 5 bookings and payments).
     *
     * @return \Illuminate\Support\Collection
     */
    private function getRecentActivities(): Collection
    {
        $activities = collect();
        
        // Get last 5 approved bookings
        $recentBookings = Booking::with('user')
            ->where('status', 'approved')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();
            
        foreach ($recentBookings as $booking) {
            $activities->push((object) [
                'type' => 'Booking Approved',
                'description' => $booking->event_name . ' (' . $booking->user->name . ')',
                'time' => $booking->updated_at,
                'icon' => 'calendar-check',
                'color' => 'text-green-600'
            ]);
        }
        
        // Get last 5 recorded payments
        $recentPayments = PaymentSlip::with('user')
            ->where('status', 'paid')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();
            
        foreach ($recentPayments as $payment) {
            $activities->push((object) [
                'type' => 'Payment Received',
                'description' => '₱' . number_format($payment->amount, 2) . ' - ' . $payment->reference_number,
                'time' => $payment->updated_at,
                'icon' => 'currency-dollar',
                'color' => 'text-blue-600'
            ]);
        }
        
        // Combine, sort by time, and take the top 5
        return $activities->sortByDesc('time')->take(5)->values();
    }
    
    /**
     * Generate initials from name.
     */
    private function generateInitials(string $name): string
    {
        $nameParts = explode(' ', trim($name));
        $firstName = $nameParts[0] ?? 'A';
        $lastName = end($nameParts);
        
        return strtoupper(
            substr($firstName, 0, 1) . 
            (($lastName !== $firstName) ? substr($lastName, 0, 1) : 'D')
        );
    }
}