<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Booking; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\StaffBookingService; // Inject the service for data retrieval

class StaffDashboardController extends Controller
{
    protected StaffBookingService $bookingService;

    /**
     * Inject StaffBookingService for data access.
     */
    public function __construct(StaffBookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }
    
    /**
     * Display the main staff dashboard with real metrics/data.
     */
    public function index(Request $request)
    {
        $user = Auth::user(); // Authenticated Staff User

        // --- Remove all static data logic ---
        
        // Get dashboard stats from the service/DB
        $stats = $this->getStaffDashboardStats($user);
        
        // Get recent activities/notifications (from service/DB)
        $recentActivity = $this->bookingService->getRecentStaffActivity(3);
        
        return view('staff.dashboard.index', compact('user', 'stats', 'recentActivity'));
    }

    /**
     * Helper to get real-time statistics from the database (via service).
     */
    private function getStaffDashboardStats($user): array
    {
        // All data retrieval should be done here or in the service
        return [
            'pending_verifications' => $this->bookingService->countPendingVerifications(),
            'verifications_today' => $this->bookingService->countVerifiedToday($user->id),
            'bookings_this_week' => Booking::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'maintenance_requests' => 0, // Placeholder for Maintenance module integration
        ];
    }
    
    // --- Removed all static data display methods and replaced with real methods or service calls ---
    
    // NOTE: The `verificationShow` and `processVerification` methods from your original file
    //       have been moved to the more appropriate `RequirementVerificationController`.
    //       If the route still points here, you should update the route file (`web.php`).
    
    /**
     * Utility method to generate user initials (kept from original for avatar).
     */
    private function generateInitials($name)
    {
        $nameParts = explode(' ', trim($name));
        $firstName = $nameParts[0] ?? 'A';
        $lastName = end($nameParts);
        
        // Use A for first initial and D for last initial if only one name part
        return strtoupper(
            substr($firstName, 0, 1) . 
            (($lastName !== $firstName) ? substr($lastName, 0, 1) : 'D')
        );
    }
}