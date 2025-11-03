@extends('layouts.app')

@section('title', 'Reservation Status - LGU Facility Reservation System')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <div class="bg-lgu-headline rounded-2xl p-8 text-white shadow-lgu-lg overflow-hidden relative">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
                <pattern id="pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                    <circle cx="10" cy="10" r="1" fill="currentColor"/>
                </pattern>
                <rect width="100%" height="100%" fill="url(#pattern)"/>
            </svg>
        </div>

        <div class="relative z-10">
            <div class="flex items-center space-x-3">
                <div class="w-16 h-16 bg-lgu-highlight/20 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                    <svg class="w-8 h-8 text-lgu-highlight" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2h8a2 2 0 012 2v1H4V5zM4 8h12v11a1 1 0 01-1 1H5a1 1 0 01-1-1V8zm5-4h2v1H9V4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold mb-1 text-white">My Reservation Status</h1>
                    <p class="text-gray-200">Track the status of your facility bookings.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-x-auto">
        @if($bookings->isEmpty())
            <div class="p-8 text-center text-gray-500">
                <p>You have no current or past reservations.</p>
                <a href="{{ route('reservations.create') }}" class="mt-4 inline-block text-lgu-headline hover:text-lgu-highlight font-medium">
                    Start a New Reservation
                </a>
            </div>
        @else
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-2 px-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">ID</th>
                        <th class="py-2 px-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Facility</th>
                        <th class="py-2 px-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Date</th>
                        <th class="py-2 px-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Start Time</th>
                        <th class="py-2 px-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">End Time</th>
                        <th class="py-2 px-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="py-2 px-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($bookings as $booking)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4 text-sm text-gray-800 font-mono">{{ $booking->id }}</td>
                            <td class="py-3 px-4 text-sm text-gray-800 font-medium">{{ $booking->facility->name ?? 'N/A' }}</td>
                            {{-- Use Carbon to display the full date --}}
                            <td class="py-3 px-4 text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($booking->start_time)->format('M d, Y') }}
                            </td>
                            {{-- Use Carbon to display time in 12-hour format --}}
                            <td class="py-3 px-4 text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                            </td>
                            <td class="py-3 px-4 text-sm">
                                {{-- Refactored to use visually distinct badges (Spaghetti Code Fix) --}}
                                @php
                                    $status = strtolower($booking->status);
                                    $class = '';
                                    if ($status === 'approved') {
                                        $class = 'bg-green-100 text-green-800 border-green-300';
                                    } elseif ($status === 'pending') {
                                        $class = 'bg-yellow-100 text-yellow-800 border-yellow-300';
                                    } elseif ($status === 'rejected') {
                                        $class = 'bg-red-100 text-red-800 border-red-300';
                                    } else {
                                        // Use for 'cancelled' or any other status
                                        $class = 'bg-gray-100 text-gray-800 border-gray-300';
                                    }
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $class }}">
                                    {{ ucfirst($status) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-sm">
                                {{-- Link to view full details (assuming a route 'reservations.show' exists) --}}
                                <a href="{{ route('reservations.show', $booking->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection