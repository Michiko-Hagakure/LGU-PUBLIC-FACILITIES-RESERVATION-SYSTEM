@extends('layouts.app')

@section('title', 'Schedule Conflicts - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <div class="bg-lgu-headline rounded-2xl p-6 text-white shadow-lgu-lg overflow-hidden relative">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
                <pattern id="pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                    <circle cx="10" cy="10" r="1" fill="currentColor"/>
                </pattern>
                <rect width="100%" height="100%" fill="url(#pattern)"/>
            </svg>
        </div>
        
        <div class="relative z-10 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-lgu-highlight/20 rounded-xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                    <svg class="w-6 h-6 text-lgu-highlight" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.487 0l5.58 9.92c.75 1.334-.213 2.986-1.748 2.986H4.426c-1.535 0-2.498-1.652-1.748-2.986l5.58-9.92zM10 13a1 1 0 100 2 1 1 0 000-2zm1-4a1 1 0 10-2 0v2a1 1 0 102 0V9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold mb-1 text-white">Schedule Conflicts</h1>
                    <p class="text-gray-200">Review and resolve overlapping facility bookings.</p>
                </div>
            </div>
            {{-- Display total conflicts count --}}
            <span class="text-4xl font-bold text-lgu-highlight">{{ count($conflicts ?? []) }}</span>
        </div>
    </div>
    
    {{-- Main content area for conflicts list --}}
    @if (!empty($conflicts))
        {{-- Iterate through each conflict group (usually grouped by facility or time) --}}
        <div class="space-y-8">
            @foreach($conflicts as $conflict)
            <div class="bg-white rounded-lg shadow-md border border-red-300 overflow-hidden">
                {{-- Conflict Group Header (e.g., Facility Name and Date) --}}
                <div class="p-4 bg-red-50 border-b border-red-200">
                    <h2 class="text-xl font-semibold text-red-700">Conflict: {{ $conflict[0]->facility->name ?? 'Unknown Facility' }}</h2>
                    {{-- Format date for better readability --}}
                    <p class="text-sm text-red-600">
                        Date: **{{ \Carbon\Carbon::parse($conflict[0]->start_time)->format('F d, Y') }}** </p>
                </div>
                
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Overlapping Reservations ({{ count($conflict) }} Bookings)</h3>
                    
                    {{-- List of conflicting bookings --}}
                    <div class="space-y-4">
                        @foreach($conflict as $booking)
                            <div class="border border-gray-200 rounded-lg p-4 flex justify-between items-center bg-white shadow-sm hover:shadow-md transition">
                                <div>
                                    <p class="text-sm text-gray-500">Booking ID: <span class="font-mono text-gray-700">{{ $booking->id }}</span></p>
                                    {{-- Display user and purpose --}}
                                    <p class="text-lg font-semibold text-gray-900">{{ $booking->user_name ?? 'N/A' }}</p>
                                    <p class="text-sm text-gray-600">Purpose: {{ $booking->purpose ?? 'Not specified' }}</p>

                                    {{-- Use a clean time format (e.g., 9:00 AM - 11:00 AM) --}}
                                    <p class="text-md font-medium text-red-600 mt-1">
                                        Time: **{{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}**
                                    </p>
                                </div>
                                
                                {{-- Action Button to Review Booking --}}
                                <div>
                                    <a href="{{ route('bookings.show', $booking->id) }}"
                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow hover:bg-indigo-700 transition text-sm">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Review
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    {{-- Conflict Resolution Instruction --}}
                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-sm text-yellow-800">
                            <strong>⚠️ Action Required:</strong> Please review and resolve this scheduling conflict by cancelling, rescheduling, or confirming one of the bookings.
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        {{-- Displayed when no conflicts are found --}}
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="w-20 h-20 mx-auto text-green-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-2xl font-semibold text-gray-900 mb-2">No Schedule Conflicts</h3>
            <p class="text-gray-600">All bookings are properly scheduled without any overlapping time slots.</p>
        </div>
    @endif
</div>
@endsection