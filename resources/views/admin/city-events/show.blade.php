@extends('layouts.app')

@section('title', 'City Event Details - Admin')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
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
                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l.493-.164.015-.008a1 1 0 00.957-.091l1.545-1.03 2.155-1.437 2.179-1.453 1.545-1.03a1 1 0 00.957-.091l.015-.008.493-.164a1 1 0 001.17-1.409l-7-14z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white">{{ $cityEvent->event_name }}</h1>
                    <p class="text-gray-200 text-sm mt-1">Details for City Event #{{ $cityEvent->id }}</p>
                </div>
            </div>
            <a href="{{ route('admin.city-events.index') }}" class="text-gray-200 hover:text-white transition text-sm">
                ← Back to List
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-xl border border-gray-200">
        <div class="divide-y divide-gray-200 p-6">
            {{-- Status Badge --}}
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">Event Type</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    @if($cityEvent->is_city_event)
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            Official City Event
                        </span>
                    @else
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-gray-100 text-gray-600">
                            Non-City Event (Internal)
                        </span>
                    @endif
                </dd>
            </div>

            {{-- Facility --}}
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">Facility Booked</dt>
                <dd class="mt-1 text-sm font-semibold text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ $cityEvent->facility->name ?? 'N/A' }}
                </dd>
            </div>

            {{-- Date & Time --}}
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">Date</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ \Carbon\Carbon::parse($cityEvent->event_date)->format('l, F d, Y') }}
                </dd>
            </div>
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">Time Slot</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ \Carbon\Carbon::parse($cityEvent->start_time)->format('g:i A') }} - 
                    {{ \Carbon\Carbon::parse($cityEvent->end_time)->format('g:i A') }}
                </dd>
            </div>

            {{-- Attendees --}}
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">Expected Attendees</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ number_format($cityEvent->expected_attendees) }} people
                </dd>
            </div>

            {{-- Contact --}}
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">Contact Number</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ $cityEvent->contact_number ?? 'N/A' }}
                </dd>
            </div>

            {{-- Description --}}
            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                <dt class="text-sm font-medium text-gray-500">Description</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 whitespace-pre-line">
                    {{ $cityEvent->description ?? 'No description provided.' }}
                </dd>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg">
            <div class="flex items-center justify-between">
                {{-- Delete Form --}}
                <form action="{{ route('admin.city-events.destroy', $cityEvent->id) }}" 
                      method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this city event? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete Event
                    </button>
                </form>
                
                {{-- Edit Button --}}
                <a href="{{ route('admin.city-events.edit', $cityEvent->id) }}" 
                   class="inline-flex items-center px-6 py-2 bg-lgu-headline text-white font-medium rounded-lg hover:bg-lgu-stroke transition-colors shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7-7l-4 4-2 6 6-2 4-4m-2-2L19 5l-4-4-2 2z"></path>
                    </svg>
                    Edit Details
                </a>
            </div>
        </div>
    </div>
</div>
@endsection