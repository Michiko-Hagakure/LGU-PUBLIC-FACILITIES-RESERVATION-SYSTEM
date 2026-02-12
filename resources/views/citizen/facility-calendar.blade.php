@extends('layouts.citizen')

@section('title', 'Facility Calendar')
@section('page-title', 'Facility Calendar')
@section('page-subtitle', 'Select a facility to view its booking schedule')

@section('page-content')
<div class="space-y-6">
    <!-- Facility Selector -->
    <div class="bg-white shadow rounded-lg p-6">
        <label for="facility_filter" class="block text-lg font-bold text-gray-900 mb-3">Select Facility</label>
        <select id="facility_filter" 
                onchange="window.location.href='{{ URL::signedRoute('citizen.facility-calendar', ['month' => $currentDate->month, 'year' => $currentDate->year]) }}&facility_id=' + this.value"
                class="w-full max-w-lg px-4 py-3 border-2 border-gray-300 rounded-lg text-base focus:ring-lgu-button focus:border-lgu-button">
            <option value="">-- Select a Facility --</option>
            @foreach($facilities as $facility)
                <option value="{{ $facility->facility_id }}" {{ $selectedFacilityId == $facility->facility_id ? 'selected' : '' }}>
                    {{ $facility->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Calendar Section -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <!-- Calendar Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-2xl font-bold text-gray-900">
                {{ $currentDate->format('F Y') }}
            </h2>
            <div class="flex items-center space-x-2">
                <a href="{{ URL::signedRoute('citizen.facility-calendar', array_merge(['month' => Carbon\Carbon::now()->month, 'year' => Carbon\Carbon::now()->year], $selectedFacilityId ? ['facility_id' => $selectedFacilityId] : [])) }}" 
                   class="px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 rounded-lg transition font-medium">
                    today
                </a>
                <a href="{{ URL::signedRoute('citizen.facility-calendar', array_merge(['month' => $prevMonth->month, 'year' => $prevMonth->year], $selectedFacilityId ? ['facility_id' => $selectedFacilityId] : [])) }}" 
                   class="p-2 text-gray-600 hover:text-lgu-headline hover:bg-gray-100 rounded-lg transition border border-gray-300 bg-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <a href="{{ URL::signedRoute('citizen.facility-calendar', array_merge(['month' => $nextMonth->month, 'year' => $nextMonth->year], $selectedFacilityId ? ['facility_id' => $selectedFacilityId] : [])) }}" 
                   class="p-2 text-gray-600 hover:text-lgu-headline hover:bg-gray-100 rounded-lg transition border border-gray-300 bg-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>

        <!-- Day Headers -->
        <div class="grid grid-cols-7 bg-gray-50 border-b border-gray-200">
            <div class="p-3 text-center text-sm font-semibold text-gray-700">Sun</div>
            <div class="p-3 text-center text-sm font-semibold text-gray-700">Mon</div>
            <div class="p-3 text-center text-sm font-semibold text-gray-700">Tue</div>
            <div class="p-3 text-center text-sm font-semibold text-gray-700">Wed</div>
            <div class="p-3 text-center text-sm font-semibold text-gray-700">Thu</div>
            <div class="p-3 text-center text-sm font-semibold text-gray-700">Fri</div>
            <div class="p-3 text-center text-sm font-semibold text-gray-700">Sat</div>
        </div>
        
        <!-- Calendar Body with overlay -->
        <div class="relative">
            @if(!$selectedFacilityId)
                <!-- Overlay when no facility selected -->
                <div class="absolute inset-0 bg-gray-800 bg-opacity-60 z-10 flex items-center justify-center rounded-b-lg">
                    <div class="text-center text-white px-6 py-8">
                        <svg class="w-16 h-16 mx-auto mb-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <p class="text-xl font-bold mb-1">Please select a facility</p>
                        <p class="text-base opacity-90">to display the schedules.</p>
                    </div>
                </div>
            @endif

            <!-- Calendar Grid -->
            <div class="grid grid-cols-7">
                @foreach($calendarData as $day)
                    @if($day['date'])
                        <div class="min-h-[110px] p-1.5 border border-gray-200 {{ $day['isToday'] ? 'bg-yellow-50' : ($day['isPast'] ? 'bg-gray-50' : 'bg-white') }} hover:bg-gray-50 transition-colors cursor-pointer"
                             @if($selectedFacilityId) onclick="showDayDetails('{{ $day['dateString'] }}')" @endif>
                            <div class="flex justify-between items-start mb-1">
                                <span class="text-xs font-bold {{ $day['isToday'] ? 'bg-lgu-button text-lgu-button-text px-1.5 py-0.5 rounded' : ($day['isPast'] ? 'text-gray-400' : 'text-gray-700') }}">
                                    {{ $day['date']->format('j') }}
                                </span>
                            </div>
                            
                            @if($selectedFacilityId && $day['hasBookings'])
                                <div class="space-y-0.5">
                                    @foreach($day['bookings']->take(3) as $booking)
                                        @php
                                            $statusBg = [
                                                'confirmed' => 'bg-green-500 text-white',
                                                'paid' => 'bg-blue-500 text-white',
                                                'staff_verified' => 'bg-blue-400 text-white',
                                                'pending' => 'bg-yellow-400 text-yellow-900',
                                                'awaiting_payment' => 'bg-orange-400 text-white',
                                            ];
                                            $pillClass = $statusBg[$booking->status] ?? 'bg-gray-400 text-white';
                                        @endphp
                                        <div class="px-1 py-0.5 {{ $pillClass }} rounded text-[10px] leading-tight font-semibold truncate">
                                            {{ \Carbon\Carbon::parse($booking->start_time)->format('g:iA') }}-{{ \Carbon\Carbon::parse($booking->end_time)->format('g:iA') }}
                                        </div>
                                    @endforeach
                                    @if($day['bookingCount'] > 3)
                                        <div class="text-[10px] text-gray-500 text-center font-medium">
                                            +{{ $day['bookingCount'] - 3 }} more
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="min-h-[110px] p-1.5 border border-gray-200 bg-gray-50"></div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Legend (shown only when facility selected) -->
        @if($selectedFacilityId)
            <div class="p-4 border-t border-gray-200 bg-gray-50">
                <div class="flex flex-wrap gap-4 text-xs">
                    <div class="flex items-center space-x-1.5">
                        <div class="w-3 h-3 bg-green-500 rounded"></div>
                        <span class="text-gray-600">Confirmed</span>
                    </div>
                    <div class="flex items-center space-x-1.5">
                        <div class="w-3 h-3 bg-blue-500 rounded"></div>
                        <span class="text-gray-600">Paid / Verified</span>
                    </div>
                    <div class="flex items-center space-x-1.5">
                        <div class="w-3 h-3 bg-yellow-400 rounded"></div>
                        <span class="text-gray-600">Pending Review</span>
                    </div>
                    <div class="flex items-center space-x-1.5">
                        <div class="w-3 h-3 bg-orange-400 rounded"></div>
                        <span class="text-gray-600">Awaiting Payment</span>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Reservations List (shown only when facility selected) -->
    @if($selectedFacilityId)
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900">
                    {{ $currentDate->format('F Y') }} Schedules
                </h2>
                <span class="px-3 py-1 bg-lgu-button text-lgu-button-text font-bold rounded-full text-sm">
                    {{ $bookings->count() }} Booking{{ $bookings->count() !== 1 ? 's' : '' }}
                </span>
            </div>

            @if($bookings->isEmpty())
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-gray-500 font-medium">No bookings found for this facility this month.</p>
                </div>
            @else
                <div class="space-y-2">
                    @foreach($bookings as $booking)
                        @php
                            $statusConfig = [
                                'confirmed' => ['bg' => 'bg-green-100 border-green-300', 'text' => 'text-green-800', 'label' => 'Confirmed'],
                                'paid' => ['bg' => 'bg-blue-100 border-blue-300', 'text' => 'text-blue-800', 'label' => 'Paid'],
                                'staff_verified' => ['bg' => 'bg-blue-50 border-blue-200', 'text' => 'text-blue-700', 'label' => 'Verified'],
                                'pending' => ['bg' => 'bg-yellow-50 border-yellow-300', 'text' => 'text-yellow-800', 'label' => 'Pending'],
                                'awaiting_payment' => ['bg' => 'bg-orange-50 border-orange-300', 'text' => 'text-orange-800', 'label' => 'Awaiting Payment'],
                            ];
                            $sc = $statusConfig[$booking->status] ?? ['bg' => 'bg-gray-50 border-gray-300', 'text' => 'text-gray-700', 'label' => ucfirst(str_replace('_', ' ', $booking->status))];
                        @endphp
                        <div class="flex items-center p-3 border rounded-lg {{ $sc['bg'] }} hover:shadow-sm transition-shadow">
                            <div class="flex-shrink-0 text-center bg-white border rounded-lg px-3 py-2 min-w-[60px] mr-4">
                                <div class="text-lg font-bold text-gray-900">{{ \Carbon\Carbon::parse($booking->start_time)->format('d') }}</div>
                                <div class="text-[10px] uppercase text-gray-500 font-semibold">{{ \Carbon\Carbon::parse($booking->start_time)->format('D') }}</div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center text-sm font-bold {{ $sc['text'] }}">
                                    <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                                </div>
                            </div>
                            <span class="flex-shrink-0 px-2.5 py-1 rounded-full text-xs font-bold {{ $sc['text'] }} {{ $sc['bg'] }}">
                                {{ $sc['label'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
</div>

<!-- Day Details Modal -->
<div id="dayDetailsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-lg w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b border-gray-200 p-5 flex items-center justify-between">
            <h3 id="modalTitle" class="text-lg font-bold text-gray-900"></h3>
            <button onclick="closeDayDetails()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div id="modalContent" class="p-5">
        </div>
    </div>
</div>

@push('scripts')
<script>
function showDayDetails(dateString) {
    const modal = document.getElementById('dayDetailsModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalContent = document.getElementById('modalContent');
    
    const date = new Date(dateString + 'T00:00:00');
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    modalTitle.textContent = date.toLocaleDateString('en-US', options);
    
    modalContent.innerHTML = '<div class="text-center py-8"><div class="animate-spin rounded-full h-10 w-10 border-b-2 border-lgu-headline mx-auto"></div></div>';
    modal.classList.remove('hidden');
    
    const facilityId = document.getElementById('facility_filter').value;
    fetch(`{{ URL::signedRoute('citizen.facility-calendar.bookings') }}&date=${dateString}&facility_id=${facilityId}`)
        .then(response => response.json())
        .then(data => {
            if (data.bookings.length === 0) {
                modalContent.innerHTML = `
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 text-green-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-bold text-green-700 mb-1">No Bookings</h3>
                        <p class="text-gray-500 text-sm">This day is open for reservation.</p>
                    </div>
                `;
            } else {
                let html = '<div class="space-y-2">';
                data.bookings.forEach(booking => {
                    const statusBg = {
                        'confirmed': 'bg-green-500',
                        'paid': 'bg-blue-500',
                        'staff_verified': 'bg-blue-400',
                        'pending': 'bg-yellow-400',
                        'awaiting_payment': 'bg-orange-400',
                    };
                    const statusLabels = {
                        'confirmed': 'Confirmed',
                        'paid': 'Paid',
                        'staff_verified': 'Verified',
                        'pending': 'Pending',
                        'awaiting_payment': 'Awaiting Payment',
                    };
                    const bgClass = statusBg[booking.status] || 'bg-gray-400';
                    const label = statusLabels[booking.status] || booking.status;
                    const startTime = new Date(booking.start_time).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
                    const endTime = new Date(booking.end_time).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
                    
                    html += `
                        <div class="flex items-center p-3 rounded-lg border border-gray-200 bg-gray-50">
                            <div class="w-2 h-10 ${bgClass} rounded-full mr-3 flex-shrink-0"></div>
                            <div class="flex-1">
                                <div class="text-sm font-bold text-gray-900">${startTime} - ${endTime}</div>
                                <div class="text-xs text-gray-500">${label}</div>
                            </div>
                        </div>
                    `;
                });
                html += '</div>';
                modalContent.innerHTML = html;
            }
        })
        .catch(error => {
            modalContent.innerHTML = `
                <div class="text-center py-8">
                    <p class="text-red-600">Error loading bookings. Please try again.</p>
                </div>
            `;
        });
}

function closeDayDetails() {
    document.getElementById('dayDetailsModal').classList.add('hidden');
}

document.getElementById('dayDetailsModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeDayDetails();
    }
});
</script>
@endpush
@endsection

