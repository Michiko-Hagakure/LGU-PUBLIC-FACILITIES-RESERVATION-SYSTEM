@extends('layouts.app')

@section('title', 'Create Maintenance Log')

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
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.3-.852-3.3-1.2-4.14-.948l-.003.001c-1.397.405-2.235 2.053-2.146 3.659.077 1.34.843 2.766 1.45 4.382.26.702.6 1.385.998 2.045.397.66.79 1.312 1.157 1.957.778 1.325 1.761 2.502 3.018 3.327.915.587 2.147.788 3.447.788 1.299 0 2.53-.201 3.447-.788 1.257-.825 2.24-1.99 3.018-3.327.367-.645.76-1.297 1.157-1.957.398-.66.738-1.343.998-2.045.607-1.616 1.373-3.042 1.45-4.382.089-1.606-.749-3.254-2.146-3.659l-.003-.001a1.532 1.532 0 01-2.286-.948zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold mb-1 text-white">New Maintenance Log</h1>
                    <p class="text-gray-200">Record a new maintenance request or task.</p>
                </div>
            </div>
            <a href="{{ route('admin.maintenance-logs.index') }}" 
               class="px-4 py-2 bg-lgu-highlight/50 text-white font-medium rounded-lg hover:bg-lgu-button/80 transition-colors">
                ← Back to List
            </a>
        </div>
    </div>

    <form action="{{ route('admin.maintenance-logs.store') }}" method="POST" class="bg-white rounded-lg shadow-xl p-8 space-y-6 border border-gray-200">
        @csrf
        
        <h2 class="text-2xl font-bold text-gray-800 border-b pb-2 mb-4">1. General Details</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div>
                <label for="facility_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Facility <span class="text-red-500">*</span>
                </label>
                <select id="facility_id" name="facility_id" required
                    class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('facility_id') border-red-500 @enderror">
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
            
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                    Maintenance Type <span class="text-red-500">*</span>
                </label>
                <select id="type" name="type" required
                    class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('type') border-red-500 @enderror">
                    <option value="">Select type</option>
                    <option value="routine" {{ old('type') == 'routine' ? 'selected' : '' }}>Routine</option>
                    <option value="repair" {{ old('type') == 'repair' ? 'selected' : '' }}>Repair</option>
                    <option value="upgrade" {{ old('type') == 'upgrade' ? 'selected' : '' }}>Upgrade</option>
                </select>
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="reported_by_name" class="block text-sm font-medium text-gray-700 mb-2">
                    Reported By (Name) <span class="text-red-500">*</span>
                </label>
                <input type="text" id="reported_by_name" name="reported_by_name" required
                    value="{{ old('reported_by_name') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('reported_by_name') border-red-500 @enderror"
                    placeholder="Enter full name or department">
                @error('reported_by_name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="reported_by_contact" class="block text-sm font-medium text-gray-700 mb-2">
                    Contact Information <span class="text-red-500">*</span>
                </label>
                <input type="text" id="reported_by_contact" name="reported_by_contact" required
                    value="{{ old('reported_by_contact') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('reported_by_contact') border-red-500 @enderror"
                    placeholder="Email or Phone Number">
                @error('reported_by_contact')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
        
        <div class="pt-2">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                Detailed Description of Issue <span class="text-red-500">*</span>
            </label>
            <textarea id="description" name="description" rows="4" required
                class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('description') border-red-500 @enderror"
                placeholder="Describe the problem, location, and severity...">{{ old('description') }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <h2 class="text-2xl font-bold text-gray-800 border-b pt-6 pb-2 mb-4">2. Scheduling & Initial Cost</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div>
                <label for="scheduled_date" class="block text-sm font-medium text-gray-700 mb-2">
                    Scheduled Date (Optional)
                </label>
                <input type="date" id="scheduled_date" name="scheduled_date"
                    value="{{ old('scheduled_date') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('scheduled_date') border-red-500 @enderror">
                @error('scheduled_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="estimated_cost" class="block text-sm font-medium text-gray-700 mb-2">
                    Estimated Cost (₱)
                </label>
                <input type="number" step="0.01" id="estimated_cost" name="estimated_cost"
                    value="{{ old('estimated_cost') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-transparent @error('estimated_cost') border-red-500 @enderror"
                    placeholder="0.00">
                @error('estimated_cost')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="pt-2">
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                Additional Notes (Internal)
            </label>
            <textarea id="notes" name="notes" rows="3"
                class="w-full border-gray-300 rounded-lg focus:ring-lgu-highlight focus:border-lgu-highlight @error('notes') border-red-500 @enderror"
                placeholder="Any special instructions or internal remarks...">{{ old('notes') }}</textarea>
            @error('notes')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-end space-x-4 pt-6 border-t">
            <a href="{{ route('admin.maintenance-logs.index') }}" 
               class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" 
                class="px-6 py-2 bg-lgu-highlight text-white font-semibold rounded-lg hover:bg-lgu-button transition-colors shadow-lg">
                Create Maintenance Log
            </button>
        </div>
    </form>
</div>
@endsection