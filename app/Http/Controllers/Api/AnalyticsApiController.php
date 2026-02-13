<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsApiController extends Controller
{
    /**
     * GET /api/super-admin/analytics/overview
     *
     * Returns the analytics hub overview data:
     * - Total Revenue (Year to Date)
     * - Total Bookings (All time)
     * - Active Citizens
     * - Facility Utilization Rate (last 30 days)
     */
    public function overview()
    {
        // Total Revenue (Year to Date)
        $totalRevenue = DB::connection('facilities_db')
            ->table('payment_slips')
            ->where('status', 'paid')
            ->whereYear('paid_at', now()->year)
            ->sum('amount_due');

        // Total Bookings (All time)
        $totalBookings = DB::connection('facilities_db')
            ->table('bookings')
            ->count();

        // Active Citizens (users with at least 1 booking)
        $activeCitizens = DB::connection('facilities_db')
            ->table('bookings')
            ->distinct('user_id')
            ->count('user_id');

        // Facility Utilization Rate (last 30 days)
        $totalFacilities = DB::connection('facilities_db')
            ->table('facilities')
            ->where('is_available', 1)
            ->count();

        $bookedFacilities = DB::connection('facilities_db')
            ->table('bookings')
            ->distinct('facility_id')
            ->where('created_at', '>=', now()->subDays(30))
            ->whereIn('status', ['approved', 'completed', 'paid'])
            ->count('facility_id');

        $facilityUtilization = $totalFacilities > 0 ? ($bookedFacilities / $totalFacilities) * 100 : 0;

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_revenue' => round($totalRevenue, 2),
                'total_bookings' => $totalBookings,
                'active_citizens' => $activeCitizens,
                'facility_utilization' => round($facilityUtilization, 2),
                'total_facilities' => $totalFacilities,
                'booked_facilities_last_30_days' => $bookedFacilities,
            ],
        ]);
    }

    /**
     * GET /api/super-admin/analytics/booking-statistics
     *
     * Returns booking statistics with optional date range filter.
     * Query params: ?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD
     */
    public function bookingStatistics(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30)->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        // Total bookings count
        $totalBookings = DB::connection('facilities_db')
            ->table('bookings')
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->count();

        // Bookings by status
        $bookingsByStatus = DB::connection('facilities_db')
            ->table('bookings')
            ->selectRaw('status, COUNT(*) as count')
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->groupBy('status')
            ->get();

        // Daily booking trend (last 30 days)
        $dailyTrend = DB::connection('facilities_db')
            ->table('bookings')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->whereBetween(DB::raw('DATE(created_at)'), [now()->subDays(30), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Fill missing dates with zero
        $trendData = [];
        for ($i = 30; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $found = $dailyTrend->firstWhere('date', $date);
            $trendData[] = [
                'date' => Carbon::parse($date)->format('M d'),
                'count' => $found ? $found->count : 0
            ];
        }

        // Popular facilities
        $popularFacilities = DB::connection('facilities_db')
            ->table('bookings')
            ->selectRaw('facilities.name as facility_name, lgu_cities.city_name, COUNT(bookings.id) as booking_count, SUM(bookings.total_amount) as total_revenue')
            ->join('facilities', 'bookings.facility_id', '=', 'facilities.facility_id')
            ->leftJoin('lgu_cities', 'facilities.lgu_city_id', '=', 'lgu_cities.id')
            ->whereBetween(DB::raw('DATE(bookings.created_at)'), [$startDate, $endDate])
            ->groupBy('facilities.facility_id', 'facilities.name', 'lgu_cities.city_name')
            ->orderByDesc('booking_count')
            ->limit(10)
            ->get();

        // Average booking value
        $avgBookingValue = DB::connection('facilities_db')
            ->table('bookings')
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->avg('total_amount') ?? 0;

        // Conversion rate (paid bookings / total bookings)
        $paidBookings = DB::connection('facilities_db')
            ->table('bookings')
            ->whereIn('status', ['paid', 'confirmed', 'completed'])
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->count();

        $conversionRate = $totalBookings > 0 ? ($paidBookings / $totalBookings) * 100 : 0;

        // Cancelled bookings rate
        $cancelledBookings = DB::connection('facilities_db')
            ->table('bookings')
            ->where('status', 'cancelled')
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->count();

        $cancellationRate = $totalBookings > 0 ? ($cancelledBookings / $totalBookings) * 100 : 0;

        // Peak booking hours (top 5)
        $peakHours = DB::connection('facilities_db')
            ->table('bookings')
            ->selectRaw('HOUR(start_time) as hour, COUNT(*) as count')
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->whereNotNull('start_time')
            ->groupBy('hour')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        // Peak booking days of week
        $peakDays = DB::connection('facilities_db')
            ->table('bookings')
            ->selectRaw('DAYNAME(start_time) as day_name, COUNT(*) as count')
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->whereNotNull('start_time')
            ->groupBy('day_name')
            ->orderByDesc('count')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_bookings' => $totalBookings,
                'bookings_by_status' => $bookingsByStatus,
                'daily_trend' => $trendData,
                'popular_facilities' => $popularFacilities,
                'avg_booking_value' => round($avgBookingValue, 2),
                'paid_bookings' => $paidBookings,
                'conversion_rate' => round($conversionRate, 2),
                'cancelled_bookings' => $cancelledBookings,
                'cancellation_rate' => round($cancellationRate, 2),
                'peak_hours' => $peakHours,
                'peak_days' => $peakDays,
            ],
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
        ]);
    }

    /**
     * GET /api/super-admin/analytics/facility-utilization
     *
     * Returns facility utilization report.
     * Query params: ?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD
     */
    public function facilityUtilization(Request $request)
    {
        $startDate = $request->input('start_date', now()->subMonths(6)->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        // Facility summary
        $facilities = DB::connection('facilities_db')
            ->table('facilities')
            ->selectRaw('
                facilities.facility_id,
                facilities.name,
                lgu_cities.city_name,
                facilities.capacity,
                COUNT(bookings.id) as total_bookings,
                SUM(CASE WHEN bookings.status IN ("paid", "confirmed", "completed") THEN 1 ELSE 0 END) as confirmed_bookings,
                SUM(CASE WHEN bookings.status = "cancelled" THEN 1 ELSE 0 END) as cancelled_bookings,
                SUM(bookings.total_amount) as total_revenue
            ')
            ->leftJoin('lgu_cities', 'facilities.lgu_city_id', '=', 'lgu_cities.id')
            ->leftJoin('bookings', 'facilities.facility_id', '=', 'bookings.facility_id')
            ->where('facilities.is_available', 1)
            ->groupBy('facilities.facility_id', 'facilities.name', 'lgu_cities.city_name', 'facilities.capacity')
            ->get();

        // Calculate utilization rate
        $totalDays = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;

        foreach ($facilities as $facility) {
            $facility->utilization_rate = $totalDays > 0
                ? round(($facility->confirmed_bookings / $totalDays) * 100, 2)
                : 0;
        }

        $underutilized = $facilities->where('utilization_rate', '<', 30)->values();
        $highPerforming = $facilities->where('utilization_rate', '>=', 70)->values();

        // AI Training Data
        $aiTrainingData = DB::connection('facilities_db')
            ->table('bookings')
            ->selectRaw('
                bookings.facility_id,
                bookings.user_id,
                MONTH(bookings.created_at) as month_index,
                DAYOFWEEK(bookings.created_at) as day_index,
                HOUR(bookings.start_time) as hour_index,
                bookings.status
            ')
            ->whereIn('bookings.status', ['paid', 'confirmed', 'completed'])
            ->get();

        // Fetch user names from auth_db separately
        $userIds = $aiTrainingData->pluck('user_id')->unique()->toArray();
        $users = DB::connection('auth_db')
            ->table('users')
            ->whereIn('id', $userIds)
            ->get()
            ->keyBy('id');

        $aiTrainingData->transform(function ($item) use ($users) {
            $user = $users->get($item->user_id);
            $item->user_name = $user->full_name ?? 'Unknown';
            return $item;
        });

        // Mayor's Schedule (Business Priority Rules)
        $mayorConflict = [
            'facility_id' => 1,
            'day_index' => 2,
            'hour_start' => 8,
            'hour_end' => 12,
        ];

        return response()->json([
            'status' => 'success',
            'data' => [
                'facilities' => $facilities,
                'underutilized' => $underutilized,
                'high_performing' => $highPerforming,
                'ai_training_data' => $aiTrainingData,
                'mayor_conflict' => $mayorConflict,
            ],
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
        ]);
    }

    /**
     * GET /api/super-admin/analytics/revenue
     *
     * Returns revenue report.
     * Query params: ?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD
     */
    public function revenueReport(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        // Total revenue
        $totalRevenue = DB::connection('facilities_db')
            ->table('payment_slips')
            ->whereBetween(DB::raw('DATE(paid_at)'), [$startDate, $endDate])
            ->where('status', 'paid')
            ->sum('amount_due');

        // Revenue by facility
        $revenueByFacility = DB::connection('facilities_db')
            ->table('bookings')
            ->selectRaw('facilities.name as facility_name, lgu_cities.city_name, COUNT(bookings.id) as total_bookings, SUM(bookings.total_amount) as total_revenue')
            ->join('facilities', 'bookings.facility_id', '=', 'facilities.facility_id')
            ->leftJoin('lgu_cities', 'facilities.lgu_city_id', '=', 'lgu_cities.id')
            ->join('payment_slips', 'bookings.id', '=', 'payment_slips.booking_id')
            ->whereBetween(DB::raw('DATE(payment_slips.paid_at)'), [$startDate, $endDate])
            ->where('payment_slips.status', 'paid')
            ->groupBy('facilities.facility_id', 'facilities.name', 'lgu_cities.city_name')
            ->orderByDesc('total_revenue')
            ->get();

        // Revenue by payment method
        $revenueByPaymentMethod = DB::connection('facilities_db')
            ->table('payment_slips')
            ->selectRaw('payment_method, COUNT(*) as transaction_count, SUM(amount_due) as total_amount')
            ->whereBetween(DB::raw('DATE(paid_at)'), [$startDate, $endDate])
            ->where('status', 'paid')
            ->groupBy('payment_method')
            ->orderByDesc('total_amount')
            ->get();

        // Monthly revenue trend (last 6 months)
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $revenue = DB::connection('facilities_db')
                ->table('payment_slips')
                ->whereYear('paid_at', $month->year)
                ->whereMonth('paid_at', $month->month)
                ->where('status', 'paid')
                ->sum('amount_due') ?? 0;

            $monthlyRevenue[] = [
                'month' => $month->format('M Y'),
                'revenue' => round($revenue, 2),
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_revenue' => round($totalRevenue, 2),
                'revenue_by_facility' => $revenueByFacility,
                'revenue_by_payment_method' => $revenueByPaymentMethod,
                'monthly_revenue' => $monthlyRevenue,
            ],
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
        ]);
    }

    /**
     * GET /api/super-admin/analytics/citizen
     *
     * Returns citizen analytics.
     * Query params: ?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD
     */
    public function citizenAnalytics(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfYear()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        // Total unique citizens who made bookings (registered + external)
        $registeredCitizens = DB::connection('facilities_db')
            ->table('bookings')
            ->distinct('user_id')
            ->whereNotNull('user_id')
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->count('user_id');

        $externalCitizens = DB::connection('facilities_db')
            ->table('bookings')
            ->whereNull('user_id')
            ->whereNotNull('applicant_email')
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->distinct('applicant_email')
            ->count('applicant_email');

        $totalCitizens = $registeredCitizens + $externalCitizens;

        // New citizens (first-time bookers in date range) - registered users
        $newRegistered = DB::connection('facilities_db')
            ->table('bookings')
            ->whereNotNull('user_id')
            ->selectRaw('user_id, MIN(DATE(created_at)) as first_booking_date')
            ->groupBy('user_id')
            ->havingBetween('first_booking_date', [$startDate, $endDate])
            ->get()
            ->count();

        $newExternal = DB::connection('facilities_db')
            ->table('bookings')
            ->whereNull('user_id')
            ->whereNotNull('applicant_email')
            ->selectRaw('applicant_email, MIN(DATE(created_at)) as first_booking_date')
            ->groupBy('applicant_email')
            ->havingBetween('first_booking_date', [$startDate, $endDate])
            ->get()
            ->count();

        $newCitizens = $newRegistered + $newExternal;

        // Repeat customers (made more than 1 booking)
        $repeatRegistered = DB::connection('facilities_db')
            ->table('bookings')
            ->selectRaw('user_id, COUNT(*) as booking_count')
            ->whereNotNull('user_id')
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->groupBy('user_id')
            ->having('booking_count', '>', 1)
            ->get()
            ->count();

        $repeatExternal = DB::connection('facilities_db')
            ->table('bookings')
            ->selectRaw('applicant_email, COUNT(*) as booking_count')
            ->whereNull('user_id')
            ->whereNotNull('applicant_email')
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->groupBy('applicant_email')
            ->having('booking_count', '>', 1)
            ->get()
            ->count();

        $repeatCustomers = $repeatRegistered + $repeatExternal;

        // Top citizens by bookings - registered users
        $topRegistered = DB::connection('facilities_db')
            ->table('bookings')
            ->selectRaw('user_id, NULL as applicant_name, NULL as applicant_email, COUNT(*) as total_bookings, SUM(total_amount) as total_spent')
            ->whereNotNull('user_id')
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->groupBy('user_id')
            ->get();

        // Top citizens by bookings - external/API users
        $topExternal = DB::connection('facilities_db')
            ->table('bookings')
            ->selectRaw('NULL as user_id, applicant_name, applicant_email, COUNT(*) as total_bookings, SUM(total_amount) as total_spent')
            ->whereNull('user_id')
            ->whereNotNull('applicant_email')
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->groupBy('applicant_email', 'applicant_name')
            ->get();

        // Get user details from auth_db for registered users
        $userIds = $topRegistered->pluck('user_id')->filter()->toArray();
        $users = collect();
        if (!empty($userIds)) {
            $users = DB::connection('auth_db')
                ->table('users')
                ->whereIn('id', $userIds)
                ->get()
                ->keyBy('id');
        }

        // Merge both sets and sort by total bookings
        $topCitizens = $topRegistered->map(function ($booking) use ($users) {
            $user = $users->get($booking->user_id);
            return [
                'full_name' => $user->full_name ?? 'Unknown User',
                'email' => $user->email ?? 'N/A',
                'total_bookings' => $booking->total_bookings,
                'total_spent' => $booking->total_spent,
            ];
        })->concat($topExternal->map(function ($booking) {
            return [
                'full_name' => $booking->applicant_name ?: 'External Booker',
                'email' => $booking->applicant_email ?? 'N/A',
                'total_bookings' => $booking->total_bookings,
                'total_spent' => $booking->total_spent,
            ];
        }))->sortByDesc('total_bookings')->take(10)->values();

        // Average bookings per citizen
        $totalBookingsCount = DB::connection('facilities_db')->table('bookings')
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->count();
        $avgBookingsPerCitizen = $totalCitizens > 0 ? $totalBookingsCount / $totalCitizens : 0;

        // Citizen growth trend (monthly)
        $monthlyGrowth = DB::connection('facilities_db')
            ->table('bookings')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(DISTINCT user_id) as citizen_count')
            ->whereBetween(DB::raw('DATE(created_at)'), [now()->subMonths(12), now()])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_citizens' => $totalCitizens,
                'registered_citizens' => $registeredCitizens,
                'external_citizens' => $externalCitizens,
                'new_citizens' => $newCitizens,
                'repeat_customers' => $repeatCustomers,
                'top_citizens' => $topCitizens,
                'avg_bookings_per_citizen' => round($avgBookingsPerCitizen, 2),
                'monthly_growth' => $monthlyGrowth,
            ],
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
        ]);
    }

    /**
     * GET /api/super-admin/analytics/operational-metrics
     *
     * Returns operational metrics.
     * Query params: ?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD
     */
    public function operationalMetrics(Request $request)
    {
        $startDate = $request->input('start_date', now()->subMonths(3)->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        // Average processing times
        $avgStaffVerificationTime = DB::connection('facilities_db')
            ->table('bookings')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, staff_verified_at)) as avg_hours')
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->whereNotNull('staff_verified_at')
            ->value('avg_hours');

        $avgPaymentTime = DB::connection('facilities_db')
            ->table('bookings')
            ->join('payment_slips', 'bookings.id', '=', 'payment_slips.booking_id')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, bookings.staff_verified_at, payment_slips.paid_at)) as avg_hours')
            ->whereBetween(DB::raw('DATE(bookings.created_at)'), [$startDate, $endDate])
            ->whereNotNull('payment_slips.paid_at')
            ->value('avg_hours');

        $avgTotalProcessingTime = DB::connection('facilities_db')
            ->table('bookings')
            ->join('payment_slips', 'bookings.id', '=', 'payment_slips.booking_id')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, bookings.created_at, payment_slips.paid_at)) as avg_hours')
            ->whereBetween(DB::raw('DATE(bookings.created_at)'), [$startDate, $endDate])
            ->whereNotNull('payment_slips.paid_at')
            ->value('avg_hours');

        // Staff performance metrics
        $staffPerformance = DB::connection('facilities_db')
            ->table('bookings')
            ->selectRaw('
                staff_verified_by,
                COUNT(*) as total_verified,
                SUM(CASE WHEN status IN ("paid", "confirmed", "completed") THEN 1 ELSE 0 END) as approved_count,
                SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected_count,
                AVG(TIMESTAMPDIFF(HOUR, created_at, staff_verified_at)) as avg_verification_hours
            ')
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->whereNotNull('staff_verified_by')
            ->groupBy('staff_verified_by')
            ->orderByDesc('total_verified')
            ->get();

        // Get staff names
        $staffIds = $staffPerformance->pluck('staff_verified_by')->filter()->unique()->toArray();
        $staffNames = DB::connection('auth_db')
            ->table('users')
            ->whereIn('id', $staffIds)
            ->get()
            ->keyBy('id');

        // Attach staff names
        $staffPerformance->transform(function ($staff) use ($staffNames) {
            $user = $staffNames->get($staff->staff_verified_by);
            $staff->staff_name = $user->full_name ?? 'Unknown Staff';
            $staff->avg_verification_hours = round($staff->avg_verification_hours, 1);
            return $staff;
        });

        // Rejection reasons breakdown
        $rejectionReasons = DB::connection('facilities_db')
            ->table('bookings')
            ->selectRaw('rejected_reason, COUNT(*) as count')
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->where('status', 'rejected')
            ->whereNotNull('rejected_reason')
            ->groupBy('rejected_reason')
            ->orderByDesc('count')
            ->get();

        // Expiration, cancellation, and completion counts
        $totalBookings = DB::connection('facilities_db')
            ->table('bookings')
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->count();

        $expiredBookings = DB::connection('facilities_db')
            ->table('bookings')
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->where('status', 'expired')
            ->count();

        $cancelledBookings = DB::connection('facilities_db')
            ->table('bookings')
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->where('status', 'cancelled')
            ->count();

        $completedBookings = DB::connection('facilities_db')
            ->table('bookings')
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->where('status', 'completed')
            ->count();

        // Calculate rates
        $expirationRate = $totalBookings > 0 ? ($expiredBookings / $totalBookings) * 100 : 0;
        $cancellationRate = $totalBookings > 0 ? ($cancelledBookings / $totalBookings) * 100 : 0;
        $completionRate = $totalBookings > 0 ? ($completedBookings / $totalBookings) * 100 : 0;

        // Workflow bottleneck identification
        $bottlenecks = [];

        if ($avgStaffVerificationTime > 48) {
            $bottlenecks[] = [
                'stage' => 'Staff Verification',
                'avg_hours' => round($avgStaffVerificationTime, 1),
                'severity' => 'high',
                'recommendation' => 'Consider hiring additional staff or streamlining verification process',
            ];
        }

        if ($avgPaymentTime > 24) {
            $bottlenecks[] = [
                'stage' => 'Payment Processing',
                'avg_hours' => round($avgPaymentTime, 1),
                'severity' => 'medium',
                'recommendation' => 'Improve payment reminder system or simplify payment methods',
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'avg_staff_verification_time' => round($avgStaffVerificationTime, 1),
                'avg_payment_time' => round($avgPaymentTime, 1),
                'avg_total_processing_time' => round($avgTotalProcessingTime, 1),
                'staff_performance' => $staffPerformance,
                'rejection_reasons' => $rejectionReasons,
                'total_bookings' => $totalBookings,
                'expired_bookings' => $expiredBookings,
                'cancelled_bookings' => $cancelledBookings,
                'completed_bookings' => $completedBookings,
                'expiration_rate' => round($expirationRate, 2),
                'cancellation_rate' => round($cancellationRate, 2),
                'completion_rate' => round($completionRate, 2),
                'bottlenecks' => $bottlenecks,
            ],
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
        ]);
    }

    /**
     * GET /api/super-admin/analytics/payments
     *
     * Returns payment analytics.
     * Query params: ?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD
     */
    public function paymentAnalytics(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Total Revenue
        $totalRevenue = DB::connection('facilities_db')->table('payment_slips')
            ->where('status', 'paid')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->sum('amount_due');

        // Total Transactions
        $totalTransactions = DB::connection('facilities_db')->table('payment_slips')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Payment Method Breakdown
        $paymentMethodBreakdown = DB::connection('facilities_db')->table('payment_slips')
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount_due) as total'))
            ->where('status', 'paid')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->groupBy('payment_method')
            ->get();

        // Payment Status Breakdown
        $statusBreakdown = DB::connection('facilities_db')->table('payment_slips')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('status')
            ->get();

        // Daily Revenue Trend
        $dailyRevenue = DB::connection('facilities_db')->table('payment_slips')
            ->select(DB::raw('DATE(paid_at) as date'), DB::raw('SUM(amount_due) as total'))
            ->where('status', 'paid')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top Revenue Generating Facilities
        $topFacilities = DB::connection('facilities_db')->table('payment_slips')
            ->join('bookings', 'payment_slips.booking_id', '=', 'bookings.id')
            ->join('facilities', 'bookings.facility_id', '=', 'facilities.facility_id')
            ->select('facilities.name as facility_name', DB::raw('SUM(payment_slips.amount_due) as total_revenue'), DB::raw('COUNT(payment_slips.id) as booking_count'))
            ->where('payment_slips.status', 'paid')
            ->whereBetween('payment_slips.paid_at', [$startDate, $endDate])
            ->groupBy('facilities.facility_id', 'facilities.name')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        // Average Payment Processing Time
        $avgProcessingTime = DB::connection('facilities_db')->table('payment_slips')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, paid_at)) as avg_hours'))
            ->where('status', 'paid')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->whereNotNull('paid_at')
            ->value('avg_hours');

        // Success Rate
        $successRate = 0;
        if ($totalTransactions > 0) {
            $paidCount = DB::connection('facilities_db')->table('payment_slips')
                ->where('status', 'paid')
                ->whereBetween('paid_at', [$startDate, $endDate])
                ->count();
            $successRate = ($paidCount / $totalTransactions) * 100;
        }

        // Pending Payments
        $pendingPayments = DB::connection('facilities_db')->table('payment_slips')
            ->where('status', 'pending')
            ->count();

        $pendingAmount = DB::connection('facilities_db')->table('payment_slips')
            ->where('status', 'pending')
            ->sum('amount_due');

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_revenue' => round($totalRevenue, 2),
                'total_transactions' => $totalTransactions,
                'payment_method_breakdown' => $paymentMethodBreakdown,
                'status_breakdown' => $statusBreakdown,
                'daily_revenue' => $dailyRevenue,
                'top_facilities' => $topFacilities,
                'avg_processing_time' => round($avgProcessingTime, 1),
                'success_rate' => round($successRate, 2),
                'pending_payments' => $pendingPayments,
                'pending_amount' => round($pendingAmount, 2),
            ],
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
        ]);
    }

    /**
     * POST /api/super-admin/analytics/filter
     *
     * Accepts a POST body with date filters and an analytics type,
     * then returns the corresponding analytics data.
     *
     * Body params:
     * - type: overview|booking-statistics|facility-utilization|revenue|citizen|operational-metrics|payments
     * - start_date: YYYY-MM-DD (optional)
     * - end_date: YYYY-MM-DD (optional)
     */
    public function filter(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:overview,booking-statistics,facility-utilization,revenue,citizen,operational-metrics,payments',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $type = $request->input('type');

        switch ($type) {
            case 'overview':
                return $this->overview();
            case 'booking-statistics':
                return $this->bookingStatistics($request);
            case 'facility-utilization':
                return $this->facilityUtilization($request);
            case 'revenue':
                return $this->revenueReport($request);
            case 'citizen':
                return $this->citizenAnalytics($request);
            case 'operational-metrics':
                return $this->operationalMetrics($request);
            case 'payments':
                return $this->paymentAnalytics($request);
            default:
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid analytics type.',
                ], 400);
        }
    }

    /**
     * GET /api/super-admin/analytics/all
     *
     * Returns ALL analytics data in a single response.
     */
    public function all(Request $request)
    {
        $overview = $this->overview()->getData(true);
        $bookingStats = $this->bookingStatistics($request)->getData(true);
        $facilityUtil = $this->facilityUtilization($request)->getData(true);
        $revenue = $this->revenueReport($request)->getData(true);
        $citizen = $this->citizenAnalytics($request)->getData(true);
        $operational = $this->operationalMetrics($request)->getData(true);
        $payments = $this->paymentAnalytics($request)->getData(true);

        return response()->json([
            'status' => 'success',
            'data' => [
                'overview' => $overview['data'] ?? [],
                'booking_statistics' => $bookingStats['data'] ?? [],
                'facility_utilization' => $facilityUtil['data'] ?? [],
                'revenue' => $revenue['data'] ?? [],
                'citizen' => $citizen['data'] ?? [],
                'operational_metrics' => $operational['data'] ?? [],
                'payments' => $payments['data'] ?? [],
            ],
        ]);
    }
}
