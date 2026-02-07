<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CbdRevenueExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected $revenueByFacility;
    protected $reportType;
    protected $startDate;
    protected $endDate;
    protected $totalRevenue;
    protected $totalBookings;

    public function __construct($revenueByFacility, $reportType, $startDate, $endDate, $totalRevenue, $totalBookings)
    {
        $this->revenueByFacility = $revenueByFacility;
        $this->reportType = $reportType;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->totalRevenue = $totalRevenue;
        $this->totalBookings = $totalBookings;
    }

    public function collection()
    {
        $rows = $this->revenueByFacility->map(function ($facility) {
            return [
                'facility' => $facility->facility_name,
                'city' => $facility->city_name ?? 'N/A',
                'bookings' => $facility->total_bookings,
                'revenue' => number_format($facility->total_revenue, 2),
            ];
        });

        // Add empty row and total row
        $rows->push(['', '', '', '']);
        $rows->push([
            'TOTAL',
            '',
            $this->totalBookings,
            number_format($this->totalRevenue, 2),
        ]);

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Facility',
            'City',
            'Total Bookings',
            'Total Revenue (₱)',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $this->revenueByFacility->count() + 3;

        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
            $lastRow => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Revenue Report - ' . ucfirst($this->reportType);
    }
}
