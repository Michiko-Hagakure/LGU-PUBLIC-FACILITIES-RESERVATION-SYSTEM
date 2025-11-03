<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class MonthlyReportController extends Controller
{
    /**
     * Display monthly reports dashboard.
     */
    public function index(Request $request)
    {
        // Get selected month or default to current month
        $selectedMonth = $request->input('month', now()->format('Y-m'));
        $monthCarbon = Carbon::parse($selectedMonth . '-01');

        // Get all available months from bookings
        $availableMonths = $this->getAvailableReportMonths();
        
        // Retrieve all calculated data for the report
        $reportData = $this->getMonthlyReportData($monthCarbon);

        return view('admin.monthly-reports.index', array_merge(
            [
                'selectedMonth' => $selectedMonth,
                'monthCarbon' => $monthCarbon,
                'availableMonths' => $availableMonths,
            ],
            $reportData
        ));
    }

    /**
     * Export monthly report as PDF or Excel.
     */
    public function export(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $format = $request->input('format', 'pdf'); // pdf or excel
        
        $monthCarbon = Carbon::parse($month . '-01');
        
        // Use the same method to get data
        $reportData = $this->getMonthlyReportData($monthCarbon);
        
        // For now, return JSON data (can be extended to actual PDF/Excel generation)
        return response()->json([
            'stats' => $reportData['stats'],
            'bookings' => $reportData['bookings']
        ]);
    }

    /**
     * Retrieves the core monthly report data.
     */
    private function getMonthlyReportData(Carbon $monthCarbon): array
    {
        // Get bookings for selected month
        $bookings = Booking::with(['facility', 'user'])
            ->whereYear('event_date', $monthCarbon->year)
            ->whereMonth('event_date', $monthCarbon->month)
            ->get();

        // Calculate statistics
        $stats = [
            'total_bookings' => $bookings->count(),
            'approved_bookings' => $bookings->where('status', 'approved')->count(),
            'rejected_bookings' => $bookings->where('status', 'rejected')->count(),
            'total_revenue' => $bookings->where('status', 'approved')->sum('total_fee'),
        ];

        // Facility-specific stats
        $facilityStats = $bookings->where('status', 'approved')->groupBy('facility_id')->map(function ($bookings) {
            return [
                'name' => $bookings->first()->facility->name ?? 'N/A',
                'bookings_count' => $bookings->count(),
                'total_revenue' => $bookings->sum('total_fee'),
            ];
        });

        // Daily Bookings Trend
        $dailyBookings = $bookings->groupBy(function($booking) {
            return Carbon::parse($booking->event_date)->day;
        })->map(function ($bookings) {
            return $bookings->count();
        });

        // Weekly Revenue Trend
        $weeklyRevenue = $bookings->where('status', 'approved')->groupBy(function($booking) {
            return Carbon::parse($booking->event_date)->weekOfYear;
        })->map(function ($bookings) {
            return $bookings->sum('total_fee');
        });
        
        // Top 10 Users by Bookings
        $topUsers = $bookings->where('status', 'approved')->groupBy('user_id')
            ->map(function ($userBookings) {
                return [
                    'user_name' => $userBookings->first()->user->name ?? 'Deleted User',
                    'bookings_count' => $userBookings->count(),
                    'total_payment' => $userBookings->sum('total_fee'),
                ];
            })
            ->sortByDesc('bookings_count')
            ->take(10)
            ->values();
            
        return [
            'stats' => $stats,
            'facilityStats' => $facilityStats,
            'dailyBookings' => $dailyBookings,
            'weeklyRevenue' => $weeklyRevenue,
            'topUsers' => $topUsers,
            'bookings' => $bookings,
        ];
    }
    
    /**
     * Get a list of months that have bookings for report filtering.
     */
    private function getAvailableReportMonths(): Collection
    {
        // Use strftime for SQLite, DATE_FORMAT for MySQL/PostgreSQL
        $driver = DB::connection()->getDriverName();
        
        if ($driver === 'sqlite') {
            return Booking::selectRaw('strftime("%Y-%m", event_date) as month')
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->pluck('month');
        } else {
            return Booking::selectRaw('DATE_FORMAT(event_date, "%Y-%m") as month')
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->pluck('month');
        }
    }
}