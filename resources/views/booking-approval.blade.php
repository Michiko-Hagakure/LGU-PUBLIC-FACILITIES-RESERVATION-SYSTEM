@extends('layouts.app')

@section('title', 'Booking Approval - Admin')

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
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.923a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold mb-1 text-white">Booking Approval Queue</h1>
                    <p class="text-gray-200">Manage and approve pending facility reservations.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-x-auto">
        @if($bookings->isEmpty())
            <div class="p-8 text-center text-gray-500">
                <p>🎉 No pending bookings requiring approval!</p>
            </div>
        @else
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-2 px-6 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">ID</th>
                        <th class="py-2 px-6 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Facility</th>
                        <th class="py-2 px-6 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Applicant</th>
                        <th class="py-2 px-6 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Date</th>
                        <th class="py-2 px-6 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Time Slot</th>
                        <th class="py-2 px-6 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($bookings as $booking)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm font-mono text-gray-900">{{ $booking->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700 font-medium">{{ $booking->facility->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $booking->user_name ?? 'N/A' }}</td>
                        {{-- Display only the date --}}
                        <td class="py-3 px-6 text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($booking->start_time)->format('M d, Y') }}
                        </td>
                        {{-- Display the time range clearly --}}
                        <td class="py-3 px-6 text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - 
                            {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}
                        </td>
                        <td class="px-6 py-4 text-sm flex items-center justify-center gap-2">
                            {{-- Action Buttons: Wrapped in a separate form for each action to prevent 'spaghetti code' in route handling --}}
                            
                            {{-- View Details Button --}}
                            <a href="{{ route('bookings.show', $booking->id) }}" class="px-3 py-2 text-sm bg-indigo-500 text-white font-semibold rounded-lg hover:bg-indigo-600 transition">
                                View
                            </a>

                            {{-- Approve Button Form --}}
                            <form action="{{ route('bookings.approve', $booking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to approve this booking?')">
                                @csrf
                                <button type="submit" class="px-3 py-2 text-sm bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition">Approve</button>
                            </form>

                            {{-- Reject Button Form (use a PUT method if possible, or POST with a hidden field, but POST is simpler for forms) --}}
                            <form action="{{ route('bookings.reject', $booking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject this booking?')">
                                @csrf
                                {{-- Optionally add @method('PUT') or @method('DELETE') here if using a RESTful route for rejection --}}
                                <button type="submit" class="px-3 py-2 text-sm bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">Reject</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection