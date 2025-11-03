@extends('layouts.app')

{{-- Set the page title --}}
@section('title', 'Admin Dashboard - LGU Facility Reservation System')

@section('content')

<div class="mb-6">
    <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke rounded-lg p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                {{-- Dynamic Greeting (e.g., Good Morning, Afternoon, Evening) --}}
                <h2 class="text-2xl font-bold mb-2">Good {{ now()->format('A') === 'AM' ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }}, {{ Auth::user()->name ?? 'Admin' }}!</h2>
                <p class="text-gray-200">Here's what's happening today</p>
            </div>
            <div class="hidden md:block">
                <div class="text-right">
                    <p class="text-sm text-gray-200">Today's Date</p>
                    {{-- The date will be dynamically set by the script below --}}
                    <p class="text-lg font-semibold" id="current-date">Loading Date...</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Statistics Cards Section --}}
{{-- Use a dynamic grid layout for the statistics cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    {{-- Card 1: Pending Approvals (Example Card) --}}
    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div>
                {{-- FIX: Corrected typo 'font-meduim' to 'font-medium' --}}
                {{-- Use Blade for dynamic count and placeholder for metric --}}
                <p class="text-sm font-medium text-lgu-paragraph">Pending Reservations</p>
                <p class="text-3xl font-bold text-lgu-headline">{{ $pendingReservationsCount ?? 0 }}</p>
                <p class="text-sm text-orange-600">Action required</p>
            </div>
            <div class="w-12 h-12 bg-orange-500 bg-opacity-20 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.923a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>
    </div>
    
    {{-- Card 2: Total Facilities (Example Card) --}}
    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-lgu-paragraph">Total Facilities</p>
                <p class="text-3xl font-bold text-lgu-headline">{{ $totalFacilitiesCount ?? 0 }}</p>
                <p class="text-sm text-blue-600">View all</p>
            </div>
            <div class="w-12 h-12 bg-blue-500 bg-opacity-20 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zM8 7a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1zm1 5a1 1 0 100 2h2a1 1 0 100-2H9z" />
                </svg>
            </div>
        </div>
    </div>
    
    {{-- Card 3: Conflicts (Example Card) --}}
    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-lgu-paragraph">Schedule Conflicts</p>
                <p class="text-3xl font-bold text-lgu-headline">{{ $scheduleConflictsCount ?? 0 }}</p>
                <p class="text-sm text-red-600">High priority</p>
            </div>
            <div class="w-12 h-12 bg-red-500 bg-opacity-20 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>
    </div>

    {{-- The original card from the snippet, refactored to be a general 'Active Bookings' card --}}
    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200 hover:shadow-lg transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-lgu-paragraph">Active Bookings</p>
                <p class="text-3xl font-bold text-lgu-headline">{{ $activeBookingsCount ?? 12 }}</p>
                <p class="text-sm text-green-600">{{ $activeBookingsChange ?? '+2' }} from last month</p>
            </div>
            <div class="w-12 h-12 bg-green-500 bg-opacity-20 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.923a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

{{-- Add additional dashboard components here, e.g., recent activity, charts, etc. --}}


@endsection

@push('scripts')
<script>
    /**
     * Sets the current date and time on the dashboard header element.
     * This ensures the date is always accurate when the user views the page.
     * @returns {void}
     */
    function updateCurrentDate() {
        const dateElement = document.getElementById('current-date');
        
        if (dateElement) {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const formattedDate = now.toLocaleDateString('en-US', options);
            dateElement.textContent = formattedDate;
        }
    }

    document.addEventListener('DOMContentLoaded', updateCurrentDate);
</script>
@endpush