@extends('layouts.cbd')

@section('title', 'Facility Utilization - CBD')

@section('page-content')
<div class="space-y-6">
    <!-- Header with Filters -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-[#0f3d3e]">Facility Utilization Report</h2>
                <p class="text-gray-600 mt-1">{{ $startDate->format('F Y') }} &mdash; Track facility usage and performance metrics</p>
            </div>

            <!-- Filter Form -->
            <form method="GET" action="{{ route('cbd.reports.facility-utilization') }}" class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                    <select name="month" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0f3d3e] focus:border-[#0f3d3e]">
                        @foreach($months as $num => $name)
                            <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                    <select name="year" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0f3d3e] focus:border-[#0f3d3e]">
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-6 py-2 bg-[#0f3d3e] text-white rounded-lg hover:bg-opacity-90 transition-all">
                    Generate
                </button>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-medium text-gray-600">Total Bookings</h3>
                <div class="p-2 bg-blue-100 rounded-lg">
                    <i data-lucide="calendar" class="w-5 h-5 text-blue-600"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-[#0f3d3e]">{{ number_format($totalBookings) }}</p>
            <p class="text-sm text-gray-500 mt-2">{{ $startDate->format('F Y') }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-medium text-gray-600">Total Revenue</h3>
                <div class="p-2 bg-green-100 rounded-lg">
                    <i data-lucide="wallet" class="w-5 h-5 text-green-600"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-green-600">₱{{ number_format($totalRevenue, 2) }}</p>
            <p class="text-sm text-gray-500 mt-2">From booked facilities</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-medium text-gray-600">Total Attendees</h3>
                <div class="p-2 bg-purple-100 rounded-lg">
                    <i data-lucide="users" class="w-5 h-5 text-purple-600"></i>
                </div>
            </div>
            <p class="text-3xl font-bold text-purple-600">{{ number_format($totalAttendees) }}</p>
            <p class="text-sm text-gray-500 mt-2">People served</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-medium text-gray-600">Avg Utilization</h3>
                <div class="p-2 {{ $avgUtilization >= 50 ? 'bg-green-100' : 'bg-yellow-100' }} rounded-lg">
                    <i data-lucide="activity" class="w-5 h-5 {{ $avgUtilization >= 50 ? 'text-green-600' : 'text-yellow-600' }}"></i>
                </div>
            </div>
            <p class="text-3xl font-bold {{ $avgUtilization >= 50 ? 'text-green-600' : 'text-yellow-600' }}">{{ $avgUtilization }}%</p>
            <p class="text-sm text-gray-500 mt-2">Across all facilities</p>
        </div>
    </div>

    <!-- Most Utilized Facility Highlight -->
    @if($mostUtilized && $mostUtilized->total_bookings > 0)
    <div class="bg-[#0f3d3e] rounded-lg shadow-sm p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-white/80 mb-1">Most Utilized Facility</p>
                <h3 class="text-2xl font-bold">{{ $mostUtilized->name }}</h3>
                <p class="text-white/80 mt-1">{{ $mostUtilized->city_name ?? 'N/A' }}</p>
            </div>
            <div class="text-right">
                <p class="text-4xl font-bold">{{ $mostUtilized->utilization_rate }}%</p>
                <p class="text-sm text-white/80">{{ $mostUtilized->days_booked }} of {{ $daysInMonth }} days booked</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Facility Utilization Table -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-[#0f3d3e] mb-4">Facility Breakdown</h3>
        <table class="w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Facility</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Bookings</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Attendees</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Days Booked</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilization</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($facilityData as $facility)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="text-sm font-medium text-gray-900">{{ $facility->name }}</div>
                        <div class="text-xs text-gray-500">{{ $facility->city_name ?? 'N/A' }}@if($facility->capacity) &middot; Cap: {{ $facility->capacity }}@endif</div>
                    </td>
                    <td class="px-4 py-3 text-right text-sm text-gray-900">{{ $facility->total_bookings }}</td>
                    <td class="px-4 py-3 text-right text-sm text-gray-900">{{ number_format($facility->total_attendees) }}</td>
                    <td class="px-4 py-3 text-right text-sm font-semibold text-[#0f3d3e]">₱{{ number_format($facility->total_revenue, 2) }}</td>
                    <td class="px-4 py-3 text-right text-sm text-gray-900">{{ $facility->days_booked }} / {{ $daysInMonth }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <div class="bg-gray-200 rounded-full h-2 w-20">
                                <div class="h-2 rounded-full {{ $facility->utilization_rate >= 70 ? 'bg-green-500' : ($facility->utilization_rate >= 40 ? 'bg-yellow-500' : 'bg-red-400') }}"
                                     style="width: {{ min($facility->utilization_rate, 100) }}%"></div>
                            </div>
                            <span class="text-sm font-medium {{ $facility->utilization_rate >= 70 ? 'text-green-600' : ($facility->utilization_rate >= 40 ? 'text-yellow-600' : 'text-red-500') }}">
                                {{ $facility->utilization_rate }}%
                            </span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                        <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-400"></i>
                        <p>No facilities found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Daily Booking Trend -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-[#0f3d3e] mb-4">Daily Booking Trend &mdash; {{ $startDate->format('F Y') }}</h3>
        @if(count($dailyBookings) > 0)
        <div class="flex items-end gap-1" style="height: 160px;">
            @php
                $maxBookings = max(array_values($dailyBookings) ?: [1]);
            @endphp
            @for($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $dateKey = $startDate->copy()->day($day)->format('Y-m-d');
                    $count = $dailyBookings[$dateKey] ?? 0;
                    $barHeight = $maxBookings > 0 ? round(($count / $maxBookings) * 140) : 0;
                @endphp
                <div class="flex-1 flex flex-col items-center justify-end group relative">
                    <div class="absolute -top-6 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10">
                        {{ $startDate->copy()->day($day)->format('M d') }}: {{ $count }} booking{{ $count !== 1 ? 's' : '' }}
                    </div>
                    @if($count > 0)
                        <div class="w-full bg-[#14b8a6] rounded-t transition-all hover:opacity-80"
                             style="height: {{ max($barHeight, 6) }}px"></div>
                    @else
                        <div class="w-full bg-gray-200 rounded-t" style="height: 3px"></div>
                    @endif
                    <span class="text-[10px] text-gray-500 mt-1">{{ $day }}</span>
                </div>
            @endfor
        </div>
        @else
        <div class="text-center py-8 text-gray-500">
            <i data-lucide="bar-chart-3" class="w-12 h-12 mx-auto mb-2 text-gray-400"></i>
            <p>No bookings found for this period.</p>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
// Initialize Lucide icons
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}
</script>
@endpush
@endsection

