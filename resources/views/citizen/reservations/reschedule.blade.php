@extends('layouts.citizen')

@section('title', 'Reschedule Booking')
@section('page-title', 'Reschedule Booking')
@section('page-subtitle', 'Choose a new date and time for your reservation')

@section('page-content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ URL::signedRoute('citizen.reservations.show', $booking->id) }}" 
           class="inline-flex items-center text-lgu-button hover:text-lgu-highlight font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2">
                <path d="m12 19-7-7 7-7"/><path d="M19 12H5"/>
            </svg>
            Back to Booking Details
        </a>
    </div>

    <!-- Current Booking Info -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-lgu-button"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                    {{ $booking->facility_name }}
                </h3>
                <p class="text-sm text-gray-500 mt-1">BK{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Original Schedule</p>
                <p class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($booking->start_time)->format('M d, Y') }}</p>
                <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}</p>
            </div>
        </div>

        @if($booking->rejected_reason)
            <div class="mt-4 bg-orange-50 border border-orange-200 rounded-lg p-3">
                <p class="text-sm text-orange-800">
                    <strong>Rejection Reason:</strong> {{ $booking->rejected_reason }}
                </p>
            </div>
        @endif
    </div>

    <!-- Error Messages -->
    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
            <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Reschedule Form -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-5 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            Choose New Schedule
        </h3>

        <form action="{{ route('citizen.booking.reschedule.submit', $booking->id) }}" method="POST" id="rescheduleForm">
            @csrf

            <!-- Facility Selection -->
            <div class="mb-6">
                <label for="facility_id" class="block text-sm font-medium text-gray-700 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-building inline-block mr-1">
                        <rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M12 6h.01"/><path d="M12 10h.01"/><path d="M12 14h.01"/><path d="M16 10h.01"/><path d="M16 14h.01"/><path d="M8 10h.01"/><path d="M8 14h.01"/>
                    </svg>
                    Facility <span class="text-red-500">*</span>
                </label>
                <select name="facility_id" id="facility_id" required
                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-lgu-button focus:border-lgu-button bg-white transition text-gray-900">
                    @foreach($facilities as $facility)
                        <option value="{{ $facility->facility_id }}" {{ (old('facility_id', $booking->facility_id) == $facility->facility_id) ? 'selected' : '' }}>
                            {{ $facility->name }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500">You may choose a different facility if needed.</p>
                @error('facility_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date Selection -->
            <div class="mb-6">
                <label for="booking_date_display" class="block text-sm font-medium text-gray-700 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar inline-block mr-1">
                        <path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/>
                    </svg>
                    New Date <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="text" id="booking_date_display" readonly required
                           placeholder="Click to select a date"
                           value="{{ old('booking_date') ? date('F j, Y', strtotime(old('booking_date'))) : '' }}"
                           class="block w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg cursor-pointer hover:border-lgu-button focus:ring-lgu-button focus:border-lgu-button bg-white transition">
                    <input type="hidden" name="booking_date" id="booking_date" value="{{ old('booking_date') }}">
                    <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-days text-gray-400">
                            <path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/>
                        </svg>
                    </div>
                </div>
                @error('booking_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Calendar Modal -->
            <div id="calendarModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-20 backdrop-blur-sm z-50 flex items-center justify-center p-4 transition-opacity duration-300">
                <div id="calendarModalContent" class="rounded-2xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0 overflow-hidden">
                    <!-- Modal Header -->
                    <div class="bg-lgu-headline p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-white flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar mr-2">
                                    <path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/>
                                </svg>
                                Select New Date
                            </h3>
                            <button type="button" id="closeCalendarModal" class="text-white hover:text-gray-200 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x">
                                    <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Month/Year Navigation -->
                        <div class="flex items-center justify-between">
                            <button type="button" id="prevMonth" class="p-2 hover:bg-lgu-stroke rounded-lg transition">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left text-white">
                                    <path d="m15 18-6-6 6-6"/>
                                </svg>
                            </button>
                            <div class="text-center">
                                <div id="currentMonthYear" class="text-xl font-bold text-white"></div>
                                <div class="text-xs text-lgu-highlight mt-1">Select a new date for your booking</div>
                            </div>
                            <button type="button" id="nextMonth" class="p-2 hover:bg-lgu-stroke rounded-lg transition">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right text-white">
                                    <path d="m9 18 6-6-6-6"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Calendar Body -->
                    <div class="bg-white p-6">
                        <!-- Weekday Headers -->
                        <div class="grid grid-cols-7 gap-2 mb-3">
                            <div class="text-center text-xs font-bold text-gray-600 py-2">Sun</div>
                            <div class="text-center text-xs font-bold text-gray-600 py-2">Mon</div>
                            <div class="text-center text-xs font-bold text-gray-600 py-2">Tue</div>
                            <div class="text-center text-xs font-bold text-gray-600 py-2">Wed</div>
                            <div class="text-center text-xs font-bold text-gray-600 py-2">Thu</div>
                            <div class="text-center text-xs font-bold text-gray-600 py-2">Fri</div>
                            <div class="text-center text-xs font-bold text-gray-600 py-2">Sat</div>
                        </div>

                        <!-- Calendar Days Grid -->
                        <div id="calendarDays" class="grid grid-cols-7 gap-2"></div>

                        <!-- Legend -->
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <div class="flex flex-wrap gap-4 text-xs">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-lgu-button rounded mr-2"></div>
                                    <span class="text-gray-600">Selected</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-white border-2 border-lgu-button rounded mr-2"></div>
                                    <span class="text-gray-600">Available (Mon-Sat)</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-gray-100 rounded mr-2 flex items-center justify-center">
                                        <span class="text-gray-400 text-xs font-bold">&times;</span>
                                    </div>
                                    <span class="text-gray-600">Closed (Sundays)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-white px-6 pb-6 flex justify-end space-x-3">
                        <button type="button" id="cancelCalendarBtn" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition font-medium">
                            Cancel
                        </button>
                        <button type="button" id="clearDateBtn" class="px-6 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-medium">
                            Clear Date
                        </button>
                    </div>
                </div>
            </div>

            <!-- Time Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="start_time_display" class="block text-sm font-medium text-gray-700 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock inline-block mr-1">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                        Start Time <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" id="start_time_display" readonly required
                               placeholder="Click to select time"
                               value="{{ old('start_time') ? \Carbon\Carbon::parse(old('start_time'))->format('h:i A') : \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}"
                               style="-webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;"
                               class="block w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg cursor-pointer hover:border-lgu-button focus:ring-lgu-button focus:border-lgu-button bg-white transition font-semibold text-gray-700">
                        <input type="hidden" name="start_time" id="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($booking->start_time)->format('H:i')) }}">
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock text-gray-400">
                                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </div>
                    </div>
                    @error('start_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="end_time_display" class="block text-sm font-medium text-gray-700 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock inline-block mr-1">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                        End Time <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" id="end_time_display" readonly required
                               placeholder="Click to select time"
                               value="{{ old('end_time') ? \Carbon\Carbon::parse(old('end_time'))->format('h:i A') : \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}"
                               style="-webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;"
                               class="block w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg cursor-pointer hover:border-lgu-button focus:ring-lgu-button focus:border-lgu-button bg-white transition font-semibold text-gray-700">
                        <input type="hidden" name="end_time" id="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($booking->end_time)->format('H:i')) }}">
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock text-gray-400">
                                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </div>
                    </div>
                    @error('end_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Time Picker Modal -->
            <div id="timePickerModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-20 backdrop-blur-sm z-50 flex items-center justify-center p-4 transition-opacity duration-300">
                <div id="timePickerModalContent" class="rounded-xl shadow-2xl w-full max-w-lg transform transition-all duration-300 scale-95 opacity-0 bg-white">
                    <!-- Modal Header -->
                    <div class="bg-lgu-headline px-4 py-3 sticky top-0 z-10">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-bold text-white flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock mr-2">
                                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                                </svg>
                                <span id="timePickerTitle">Select Time</span>
                            </h3>
                            <button type="button" id="closeTimePickerModal" class="text-white hover:text-gray-200 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x">
                                    <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Time Picker Body -->
                    <div class="bg-white p-5">
                        <!-- Current Time Display -->
                        <div class="text-center mb-4 py-4 bg-lgu-headline rounded-lg">
                            <div id="currentTimeDisplay" class="text-4xl font-black text-white tracking-wider antialiased" style="-webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;">08:00 AM</div>
                        </div>

                        <!-- Hours Grid -->
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-700 mb-2">HOUR</label>
                            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px;" id="hourSelector">
                                <button type="button" data-hour="01" class="time-option py-2.5 text-sm text-center hover:bg-lgu-highlight hover:text-white transition border border-gray-300 rounded-lg font-medium bg-white text-gray-700">01</button>
                                <button type="button" data-hour="02" class="time-option py-2.5 text-sm text-center hover:bg-lgu-highlight hover:text-white transition border border-gray-300 rounded-lg font-medium bg-white text-gray-700">02</button>
                                <button type="button" data-hour="03" class="time-option py-2.5 text-sm text-center hover:bg-lgu-highlight hover:text-white transition border border-gray-300 rounded-lg font-medium bg-white text-gray-700">03</button>
                                <button type="button" data-hour="04" class="time-option py-2.5 text-sm text-center hover:bg-lgu-highlight hover:text-white transition border border-gray-300 rounded-lg font-medium bg-white text-gray-700">04</button>
                                <button type="button" data-hour="05" class="time-option py-2.5 text-sm text-center hover:bg-lgu-highlight hover:text-white transition border border-gray-300 rounded-lg font-medium bg-white text-gray-700">05</button>
                                <button type="button" data-hour="06" class="time-option py-2.5 text-sm text-center hover:bg-lgu-highlight hover:text-white transition border border-gray-300 rounded-lg font-medium bg-white text-gray-700">06</button>
                                <button type="button" data-hour="07" class="time-option py-2.5 text-sm text-center hover:bg-lgu-highlight hover:text-white transition border border-gray-300 rounded-lg font-medium bg-white text-gray-700">07</button>
                                <button type="button" data-hour="08" class="time-option py-2.5 text-sm text-center hover:bg-lgu-highlight hover:text-white transition border border-gray-300 rounded-lg font-medium bg-white text-gray-700">08</button>
                                <button type="button" data-hour="09" class="time-option py-2.5 text-sm text-center hover:bg-lgu-highlight hover:text-white transition border border-gray-300 rounded-lg font-medium bg-white text-gray-700">09</button>
                                <button type="button" data-hour="10" class="time-option py-2.5 text-sm text-center hover:bg-lgu-highlight hover:text-white transition border border-gray-300 rounded-lg font-medium bg-white text-gray-700">10</button>
                                <button type="button" data-hour="11" class="time-option py-2.5 text-sm text-center hover:bg-lgu-highlight hover:text-white transition border border-gray-300 rounded-lg font-medium bg-white text-gray-700">11</button>
                                <button type="button" data-hour="12" class="time-option py-2.5 text-sm text-center hover:bg-lgu-highlight hover:text-white transition border border-gray-300 rounded-lg font-medium bg-white text-gray-700">12</button>
                            </div>
                        </div>

                        <!-- Minutes Grid -->
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-700 mb-2">MINUTE</label>
                            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px;" id="minuteSelector">
                                <button type="button" data-minute="00" class="time-option py-2.5 text-sm text-center hover:bg-lgu-highlight hover:text-white transition border border-gray-300 rounded-lg font-medium bg-white text-gray-700">00</button>
                                <button type="button" data-minute="15" class="time-option py-2.5 text-sm text-center hover:bg-lgu-highlight hover:text-white transition border border-gray-300 rounded-lg font-medium bg-white text-gray-700">15</button>
                                <button type="button" data-minute="30" class="time-option py-2.5 text-sm text-center hover:bg-lgu-highlight hover:text-white transition border border-gray-300 rounded-lg font-medium bg-white text-gray-700">30</button>
                                <button type="button" data-minute="45" class="time-option py-2.5 text-sm text-center hover:bg-lgu-highlight hover:text-white transition border border-gray-300 rounded-lg font-medium bg-white text-gray-700">45</button>
                            </div>
                        </div>

                        <!-- AM/PM Grid -->
                        <div class="mb-1">
                            <label class="block text-xs font-bold text-gray-700 mb-2">PERIOD</label>
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                                <button type="button" data-period="AM" class="time-option py-3 text-base text-center hover:bg-lgu-highlight hover:text-white transition border-2 border-gray-300 rounded-lg font-bold bg-white text-gray-700">AM</button>
                                <button type="button" data-period="PM" class="time-option py-3 text-base text-center hover:bg-lgu-highlight hover:text-white transition border-2 border-gray-300 rounded-lg font-bold bg-white text-gray-700">PM</button>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-5 py-3 flex justify-end space-x-2 border-t border-gray-200 sticky bottom-0 z-10">
                        <button type="button" id="cancelTimePickerBtn" class="px-5 py-2 text-sm border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition font-medium">
                            Cancel
                        </button>
                        <button type="button" id="confirmTimeBtn" class="px-5 py-2 text-sm bg-lgu-button text-white rounded-lg hover:bg-lgu-highlight transition font-medium shadow">
                            Confirm
                        </button>
                    </div>
                </div>
            </div>

            <p class="text-xs text-gray-500 mb-4">Operating hours: 8:00 AM to 10:00 PM. The same pricing, equipment, and payment will be retained.</p>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-6">
                <p class="text-sm text-yellow-800">
                    <strong>Note:</strong> Your booking will go through staff verification again after rescheduling. You may also select a different facility. Your existing payment will be retained — no additional charges unless the duration changes.
                </p>
            </div>

            <div class="flex gap-3">
                <a href="{{ URL::signedRoute('citizen.reservations.show', $booking->id) }}" 
                   class="flex-1 px-4 py-3 bg-gray-200 text-gray-700 text-center font-semibold rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </a>
                <button type="submit" id="confirmRescheduleBtn"
                        class="flex-1 px-4 py-3 bg-blue-600 text-white text-center font-bold rounded-lg hover:bg-blue-700 transition shadow-md">
                    Confirm Reschedule
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('booking_date');
    const dateDisplayInput = document.getElementById('booking_date_display');
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');

    // ============================================
    // CALENDAR MODAL FUNCTIONALITY
    // ============================================
    const calendarModal = document.getElementById('calendarModal');
    const calendarModalContent = document.getElementById('calendarModalContent');
    const closeCalendarModal = document.getElementById('closeCalendarModal');
    const cancelCalendarBtn = document.getElementById('cancelCalendarBtn');
    const clearDateBtn = document.getElementById('clearDateBtn');
    const calendarDays = document.getElementById('calendarDays');
    const currentMonthYear = document.getElementById('currentMonthYear');
    const prevMonthBtn = document.getElementById('prevMonth');
    const nextMonthBtn = document.getElementById('nextMonth');

    let currentDate = new Date();
    let selectedDate = null;
    const minDate = new Date();
    minDate.setDate(minDate.getDate() + 7); // Minimum 7 days advance
    minDate.setHours(0, 0, 0, 0);

    // Open Calendar Modal
    dateDisplayInput.addEventListener('click', function() {
        openCalendarModal();
    });

    function openCalendarModal() {
        if (!selectedDate) {
            currentDate = new Date(minDate);
        }
        calendarModal.classList.remove('hidden');
        setTimeout(() => {
            calendarModal.classList.add('opacity-100');
            calendarModalContent.classList.remove('scale-95', 'opacity-0');
            calendarModalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
        renderCalendar();
    }

    function closeModal() {
        calendarModalContent.classList.remove('scale-100', 'opacity-100');
        calendarModalContent.classList.add('scale-95', 'opacity-0');
        calendarModal.classList.remove('opacity-100');
        setTimeout(() => {
            calendarModal.classList.add('hidden');
        }, 300);
    }

    closeCalendarModal.addEventListener('click', closeModal);
    cancelCalendarBtn.addEventListener('click', closeModal);
    calendarModal.addEventListener('click', function(e) {
        if (e.target === calendarModal) closeModal();
    });

    clearDateBtn.addEventListener('click', function() {
        selectedDate = null;
        dateInput.value = '';
        dateDisplayInput.value = '';
        closeModal();
    });

    prevMonthBtn.addEventListener('click', function() {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });

    nextMonthBtn.addEventListener('click', function() {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });

    function renderCalendar() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                           'July', 'August', 'September', 'October', 'November', 'December'];
        currentMonthYear.textContent = `${monthNames[month]} ${year}`;

        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        calendarDays.innerHTML = '';

        for (let i = 0; i < firstDay; i++) {
            const emptyCell = document.createElement('div');
            calendarDays.appendChild(emptyCell);
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);
            date.setHours(0, 0, 0, 0);
            const dayCell = document.createElement('button');
            dayCell.type = 'button';
            dayCell.textContent = day;
            dayCell.className = 'aspect-square p-2 text-sm rounded-lg font-medium transition-all duration-200';

            const isPast = date < minDate;
            const isSelected = selectedDate && date.getTime() === new Date(selectedDate).getTime();
            const isSunday = date.getDay() === 0;

            if (isSelected) {
                dayCell.className += ' bg-lgu-button text-white font-bold shadow-lg scale-105';
            } else if (isPast) {
                dayCell.className += ' bg-gray-100 text-gray-400 cursor-not-allowed';
                dayCell.disabled = true;
            } else if (isSunday) {
                dayCell.className += ' bg-gray-100 text-gray-400 cursor-not-allowed line-through';
                dayCell.disabled = true;
                dayCell.title = 'LGU Office closed on Sundays';
            } else {
                dayCell.className += ' bg-white hover:bg-lgu-highlight hover:scale-110 text-gray-700 border-2 border-lgu-button';
            }

            if (!isPast && !isSunday) {
                dayCell.addEventListener('click', function() {
                    selectDate(date);
                });
            }

            calendarDays.appendChild(dayCell);
        }
    }

    function selectDate(date) {
        selectedDate = date;
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        
        dateInput.value = `${year}-${month}-${day}`;
        
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                           'July', 'August', 'September', 'October', 'November', 'December'];
        dateDisplayInput.value = `${monthNames[date.getMonth()]} ${day}, ${year}`;
        
        closeModal();
    }

    // ============================================
    // TIME PICKER MODAL FUNCTIONALITY
    // ============================================
    const timePickerModal = document.getElementById('timePickerModal');
    const timePickerModalContent = document.getElementById('timePickerModalContent');
    const closeTimePickerModal = document.getElementById('closeTimePickerModal');
    const cancelTimePickerBtn = document.getElementById('cancelTimePickerBtn');
    const confirmTimeBtn = document.getElementById('confirmTimeBtn');
    const timePickerTitle = document.getElementById('timePickerTitle');
    const currentTimeDisplay = document.getElementById('currentTimeDisplay');
    
    const startTimeDisplayInput = document.getElementById('start_time_display');
    const endTimeDisplayInput = document.getElementById('end_time_display');
    
    let currentTimeField = null;
    let selectedHour = '08';
    let selectedMinute = '00';
    let selectedPeriod = 'AM';
    
    startTimeDisplayInput.addEventListener('click', function() {
        currentTimeField = 'start';
        const currentValue = startTimeInput.value || '08:00';
        parseAndSetTime(currentValue);
        timePickerTitle.textContent = 'Select Start Time';
        openTimePicker();
    });
    
    endTimeDisplayInput.addEventListener('click', function() {
        currentTimeField = 'end';
        const currentValue = endTimeInput.value || '11:00';
        parseAndSetTime(currentValue);
        timePickerTitle.textContent = 'Select End Time';
        openTimePicker();
    });
    
    function parseAndSetTime(time24) {
        const [hours, minutes] = time24.split(':');
        let hour = parseInt(hours);
        selectedMinute = minutes;
        
        if (hour >= 12) {
            selectedPeriod = 'PM';
            if (hour > 12) hour -= 12;
        } else {
            selectedPeriod = 'AM';
            if (hour === 0) hour = 12;
        }
        
        selectedHour = hour.toString().padStart(2, '0');
        updateTimeDisplay();
        highlightSelectedOptions();
    }
    
    function openTimePicker() {
        timePickerModal.classList.remove('hidden');
        setTimeout(() => {
            timePickerModal.classList.add('opacity-100');
            timePickerModalContent.classList.remove('scale-95', 'opacity-0');
            timePickerModalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
    
    function closeTimePicker() {
        timePickerModalContent.classList.remove('scale-100', 'opacity-100');
        timePickerModalContent.classList.add('scale-95', 'opacity-0');
        timePickerModal.classList.remove('opacity-100');
        setTimeout(() => {
            timePickerModal.classList.add('hidden');
        }, 300);
    }
    
    function updateTimeDisplay() {
        currentTimeDisplay.textContent = `${selectedHour}:${selectedMinute} ${selectedPeriod}`;
    }
    
    function highlightSelectedOptions() {
        document.querySelectorAll('.time-option').forEach(btn => {
            btn.classList.remove('bg-lgu-button', 'bg-lgu-highlight', 'text-white', 'font-bold', 'shadow-md');
            btn.classList.add('bg-white', 'text-gray-700');
        });
        
        const hourBtn = document.querySelector(`[data-hour="${selectedHour}"]`);
        const minuteBtn = document.querySelector(`[data-minute="${selectedMinute}"]`);
        const periodBtn = document.querySelector(`[data-period="${selectedPeriod}"]`);
        
        if (hourBtn) {
            hourBtn.classList.remove('bg-white', 'text-gray-700');
            hourBtn.classList.add('bg-lgu-button', 'text-white', 'font-bold', 'shadow-md');
        }
        if (minuteBtn) {
            minuteBtn.classList.remove('bg-white', 'text-gray-700');
            minuteBtn.classList.add('bg-lgu-button', 'text-white', 'font-bold', 'shadow-md');
        }
        if (periodBtn) {
            periodBtn.classList.remove('bg-white', 'text-gray-700');
            periodBtn.classList.add('bg-lgu-button', 'text-white', 'font-bold', 'shadow-md');
        }
    }
    
    document.querySelectorAll('[data-hour]').forEach(button => {
        button.addEventListener('click', function() {
            selectedHour = this.getAttribute('data-hour');
            updateTimeDisplay();
            highlightSelectedOptions();
        });
    });
    
    document.querySelectorAll('[data-minute]').forEach(button => {
        button.addEventListener('click', function() {
            selectedMinute = this.getAttribute('data-minute');
            updateTimeDisplay();
            highlightSelectedOptions();
        });
    });
    
    document.querySelectorAll('[data-period]').forEach(button => {
        button.addEventListener('click', function() {
            selectedPeriod = this.getAttribute('data-period');
            updateTimeDisplay();
            highlightSelectedOptions();
        });
    });

    function formatTime12Hour(hour24, minute) {
        let hour12 = hour24;
        let period = 'AM';
        if (hour24 >= 12) {
            period = 'PM';
            if (hour24 > 12) hour12 = hour24 - 12;
        } else if (hour24 === 0) {
            hour12 = 12;
        }
        return `${hour12.toString().padStart(2, '0')}:${minute} ${period}`;
    }

    function showTimeError(title, message) {
        try {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4';
            modal.style.zIndex = '9999';
            modal.innerHTML = `
                <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-300">
                    <div class="bg-red-600 px-6 py-4 rounded-t-xl">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white mr-3">
                                <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                            </svg>
                            <h3 class="text-lg font-bold text-white">${title}</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-700 leading-relaxed">${message}</p>
                        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <p class="text-sm text-blue-800 font-medium mb-2">Booking Guidelines:</p>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>&bull; <strong>Earliest Start:</strong> 8:00 AM</li>
                                <li>&bull; <strong>Latest End:</strong> 10:00 PM</li>
                                <li>&bull; <strong>Minimum Duration:</strong> 3 hours</li>
                            </ul>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 rounded-b-xl flex justify-end">
                        <button class="close-error-btn px-6 py-2 bg-yellow-500 text-gray-800 rounded-lg font-semibold hover:bg-yellow-400 transition">I Understand</button>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
            modal.querySelector('.close-error-btn').addEventListener('click', () => modal.remove());
            modal.addEventListener('click', function(e) { if (e.target === modal) modal.remove(); });
        } catch (error) {
            alert(title + '\n\n' + message);
        }
    }
    
    confirmTimeBtn.addEventListener('click', function() {
        let hour24 = parseInt(selectedHour);
        if (selectedPeriod === 'PM' && hour24 !== 12) hour24 += 12;
        else if (selectedPeriod === 'AM' && hour24 === 12) hour24 = 0;
        
        const time24 = `${hour24.toString().padStart(2, '0')}:${selectedMinute}`;
        const time12 = `${selectedHour}:${selectedMinute} ${selectedPeriod}`;
        
        if (currentTimeField === 'start') {
            if (hour24 < 8) {
                showTimeError('Start Time Too Early', 'Events cannot start before 8:00 AM. Please select 8:00 AM or later.');
                return;
            }
            
            let endHour24 = hour24 + 3;
            let endMinute = selectedMinute;
            
            if (endHour24 > 22 || (endHour24 === 22 && endMinute !== '00')) {
                showTimeError('End Time Exceeds Limit', `Starting at ${time12} would end at ${formatTime12Hour(endHour24, endMinute)}, which exceeds the 10:00 PM limit. Please select an earlier start time.`);
                return;
            }
            
            startTimeInput.value = time24;
            startTimeDisplayInput.value = time12;
            
            if (endHour24 >= 24) { endHour24 = 22; endMinute = '00'; }
            
            let endHour12 = endHour24;
            let endPeriod = 'AM';
            if (endHour24 >= 12) { endPeriod = 'PM'; if (endHour24 > 12) endHour12 = endHour24 - 12; }
            else if (endHour24 === 0) { endHour12 = 12; }
            
            const endTime24 = `${endHour24.toString().padStart(2, '0')}:${endMinute}`;
            const endTime12 = `${endHour12.toString().padStart(2, '0')}:${endMinute} ${endPeriod}`;
            
            endTimeInput.value = endTime24;
            endTimeDisplayInput.value = endTime12;
            
        } else if (currentTimeField === 'end') {
            if (hour24 > 22 || (hour24 === 22 && parseInt(selectedMinute) > 0)) {
                showTimeError('End Time Exceeds Limit', 'Events must end by 10:00 PM. Please select 10:00 PM or earlier.');
                return;
            }
            
            const startTime = startTimeInput.value;
            if (startTime) {
                const [startHour, startMin] = startTime.split(':').map(Number);
                const startMinutes = startHour * 60 + startMin;
                const endMinutes = hour24 * 60 + parseInt(selectedMinute);
                const durationHours = (endMinutes - startMinutes) / 60;
                
                if (durationHours !== 3 && durationHours !== 5) {
                    showTimeError('Invalid Duration', `You selected ${durationHours} hours. Facility bookings must be 3 hours (standard) or 5 hours (3 + 2-hour extension). Please select a valid end time.`);
                    return;
                }
            }
            
            endTimeInput.value = time24;
            endTimeDisplayInput.value = time12;
        }
        
        closeTimePicker();
    });
    
    closeTimePickerModal.addEventListener('click', closeTimePicker);
    cancelTimePickerBtn.addEventListener('click', closeTimePicker);
    timePickerModal.addEventListener('click', function(e) {
        if (e.target === timePickerModal) closeTimePicker();
    });
    
    // ESC key to close modals
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (!calendarModal.classList.contains('hidden')) closeModal();
            if (!timePickerModal.classList.contains('hidden')) closeTimePicker();
        }
    });

    // Form submission confirmation
    document.getElementById('rescheduleForm').addEventListener('submit', function(e) {
        if (!dateInput.value) {
            e.preventDefault();
            alert('Please select a new date.');
            return false;
        }
        if (!confirm('Are you sure you want to reschedule this booking to the selected date and time?')) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
@endpush
@endsection
