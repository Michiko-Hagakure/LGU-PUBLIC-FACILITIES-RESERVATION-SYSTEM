@extends('layouts.app')

@section('title', 'Create New City Event - Admin')

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
                    <h1 class="text-xl font-bold text-white">Create New City Event</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-xl border border-gray-200 p-8">
        <form action="{{ route('admin.city-events.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="event_name" class="block text-sm font-medium text-gray-700 mb-2">
                    Event Name <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="event_name"
                       name="event_name" 
                       value="{{ old('event_name') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('event_name') border-red-500 @enderror"
                       required>
                @error('event_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="facility_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Facility <span class="text-red-500">*</span>
                </label>
                <select id="facility_id" 
                        name="facility_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('facility_id') border-red-500 @enderror"
                        required>
                    <option value="">Select a facility</option>
                    @foreach($facilities as $facility)
                        <option value="{{ $facility->id }}" {{ old('facility_id') == $facility->id ? 'selected' : '' }}>
                            {{ $facility->name }}
                        </option>
                    @endforeach
                </select>
                @error('facility_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="event_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           id="event_date"
                           name="event_date" 
                           value="{{ old('event_date') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('event_date') border-red-500 @enderror"
                           required>
                    @error('event_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">
                        Start Time <span class="text-red-500">*</span>
                    </label>
                    <input type="time" 
                           id="start_time"
                           name="start_time" 
                           value="{{ old('start_time') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('start_time') border-red-500 @enderror"
                           required>
                    @error('start_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">
                        End Time <span class="text-red-500">*</span>
                    </label>
                    <input type="time" 
                           id="end_time"
                           name="end_time" 
                           value="{{ old('end_time') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('end_time') border-red-500 @enderror"
                           required>
                    @error('end_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('description') border-red-500 @enderror"
                          >{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="expected_attendees" class="block text-sm font-medium text-gray-700 mb-2">
                    Expected Attendees <span class="text-red-500">*</span>
                </label>
                <input type="number" 
                       id="expected_attendees"
                       name="expected_attendees" 
                       value="{{ old('expected_attendees') }}"
                       min="1"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('expected_attendees') border-red-500 @enderror"
                       required>
                @error('expected_attendees')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-2">
                    Contact Number (Optional)
                </label>
                <input type="text" 
                       id="contact_number"
                       name="contact_number" 
                       value="{{ old('contact_number') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-transparent"
                       placeholder="e.g., (02) 1234-5678">
                <p class="mt-1 text-sm text-gray-500">Contact number for event coordination</p>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.city-events.index') }}" 
                   class="px-6 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-lgu-highlight text-lgu-button-text font-semibold rounded-lg hover:bg-lgu-button transition-colors shadow-lg">
                    Create City Event
                </button>
            </div>
        </form>
    </div>
</div>
@endsection