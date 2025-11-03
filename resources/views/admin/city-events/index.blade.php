@extends('layouts.app')

@section('title', 'City Events List - Admin')

@section('content')
<div class="space-y-6">
    <div class="bg-lgu-headline rounded-2xl p-8 text-white shadow-lgu-lg overflow-hidden relative">
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
                <div class="w-16 h-16 bg-lgu-highlight/20 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                    <svg class="w-8 h-8 text-lgu-highlight" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l.493-.164.015-.008a1 1 0 00.957-.091l1.545-1.03 2.155-1.437 2.179-1.453 1.545-1.03a1 1 0 00.957-.091l.015-.008.493-.164a1 1 0 001.17-1.409l-7-14z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold mb-1 text-white">City Events Management</h1>
                    <p class="text-gray-200">Official list of LGU-sanctioned events.</p>
                </div>
            </div>
            
            <a href="{{ route('admin.city-events.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-lgu-highlight text-lgu-button-text font-semibold rounded-lg hover:bg-lgu-button transition-colors shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                </svg>
                New City Event
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-x-auto">
        @if($cityEvents->isEmpty())
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="mt-2 text-xl font-semibold text-gray-900">No City Events Found</h3>
                <p class="mt-1 text-gray-500">Get started by creating a new city event.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.city-events.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-lgu-highlight text-lgu-button-text font-medium rounded-lg hover:bg-lgu-button transition">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                        Create New Event
                    </a>
                </div>
            </div>
        @else
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Event Title</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Facility</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Date</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Time</th>
                        <th class="py-3 px-6 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Is City Event</th>
                        <th class="py-3 px-6 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($cityEvents as $event)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $event->event_name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $event->facility->name ?? 'N/A' }}</td>
                            {{-- Use Carbon for clean date formatting --}}
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                            </td>
                            {{-- Use Carbon for time range formatting --}}
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }} - 
                                {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}
                            </td>
                            <td class="px-6 py-4 text-center text-sm">
                                @if($event->is_city_event)
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Yes
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-600">
                                        No
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <a href="{{ route('admin.city-events.show', $event->id) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 transition">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $cityEvents->links() }}
            </div>
        @endif
    </div>
</div>
@endsection