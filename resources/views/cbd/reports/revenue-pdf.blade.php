<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Revenue Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #0f3d3e;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #0f3d3e;
            font-size: 22px;
            margin: 0 0 5px 0;
        }
        .header p {
            color: #666;
            margin: 0;
            font-size: 13px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #0f3d3e;
            color: #fff;
            padding: 10px 12px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        th.text-right {
            text-align: right;
        }
        td {
            padding: 8px 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 12px;
        }
        td.text-right {
            text-align: right;
        }
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .total-row td {
            font-weight: bold;
            border-top: 2px solid #0f3d3e;
            background-color: #f0fdfa;
            font-size: 13px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Revenue Report &mdash; {{ ucfirst($reportType) }}</h1>
        <p>Period: {{ $startDate->format('F d, Y') }} to {{ $endDate->format('F d, Y') }}</p>
        <p>Generated: {{ now()->format('F d, Y g:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Facility</th>
                <th>City</th>
                <th class="text-right">Bookings</th>
                <th class="text-right">Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach($revenueByFacility as $facility)
            <tr>
                <td>{{ $facility->facility_name }}</td>
                <td>{{ $facility->city_name ?? 'N/A' }}</td>
                <td class="text-right">{{ $facility->total_bookings }}</td>
                <td class="text-right">&#8369;{{ number_format($facility->total_revenue, 2) }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="2">TOTAL</td>
                <td class="text-right">{{ $totalBookings }}</td>
                <td class="text-right">&#8369;{{ number_format($totalRevenue, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>LGU Facility Reservation System &mdash; City Budget Department</p>
    </div>
</body>
</html>
