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
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-lgu-button"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            {{ $booking->facility_name }}
        </h3>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-gray-500">Booking Reference</span>
                <p class="font-bold text-gray-900">BK{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div>
                <span class="text-gray-500">Original Schedule</span>
                <p class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($booking->start_time)->format('M d, Y') }}</p>
                <p class="text-gray-600">{{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}</p>
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
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            Choose New Schedule
        </h3>

        <form action="{{ route('citizen.booking.reschedule.submit', $booking->id) }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="booking_date" class="block text-sm font-medium text-gray-700 mb-1">New Date <span class="text-red-500">*</span></label>
                <input type="date" 
                       id="booking_date" 
                       name="booking_date" 
                       min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"
                       value="{{ old('booking_date') }}"
                       required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-button text-gray-900">
                @error('booking_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Start Time <span class="text-red-500">*</span></label>
                    <input type="time" 
                           id="start_time" 
                           name="start_time"
                           min="08:00" max="22:00"
                           value="{{ old('start_time', \Carbon\Carbon::parse($booking->start_time)->format('H:i')) }}"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-button text-gray-900">
                    @error('start_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">End Time <span class="text-red-500">*</span></label>
                    <input type="time" 
                           id="end_time" 
                           name="end_time"
                           min="08:00" max="22:00"
                           value="{{ old('end_time', \Carbon\Carbon::parse($booking->end_time)->format('H:i')) }}"
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-button text-gray-900">
                    @error('end_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <p class="text-xs text-gray-500">Operating hours: 8:00 AM to 10:00 PM. The same pricing, equipment, and payment will be retained.</p>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                <p class="text-sm text-yellow-800">
                    <strong>Note:</strong> Your booking will go through staff verification again after rescheduling. Your existing payment will be retained — no additional charges unless the duration changes.
                </p>
            </div>

            <div class="flex gap-3 pt-2">
                <a href="{{ URL::signedRoute('citizen.reservations.show', $booking->id) }}" 
                   class="flex-1 px-4 py-3 bg-gray-200 text-gray-700 text-center font-semibold rounded-lg hover:bg-gray-300 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="flex-1 px-4 py-3 bg-blue-600 text-white text-center font-bold rounded-lg hover:bg-blue-700 transition shadow-md"
                        onclick="return confirm('Are you sure you want to reschedule this booking?')">
                    Confirm Reschedule
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
