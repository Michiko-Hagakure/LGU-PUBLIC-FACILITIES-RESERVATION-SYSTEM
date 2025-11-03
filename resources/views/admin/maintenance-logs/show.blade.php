@extends('layouts.app')

@section('title', 'Maintenance Log #' . $maintenanceLog->id)

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
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
                    <h1 class="text-xl font-bold mb-1 text-white">Log: #{{ $maintenanceLog->id }} - {{ $maintenanceLog->facility->name }}</h1>
                    <p class="text-gray-200">{{ ucfirst($maintenanceLog->type) }} Maintenance</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.maintenance-logs.edit', $maintenanceLog->id) }}" 
                   class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 transition-colors shadow-md">
                    <svg class="w-5 h-5 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.794.793-2.828-2.828.794-.793zm-4.146 4.146l-7 7A1 1 0 003 17.586V15h2.586l7-7-2.828-2.828z"/></svg>
                    Edit Log
                </a>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Details --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4 border-b pb-4">
                    <h3 class="text-lg font-bold text-gray-900">General Information</h3>
                    @php
                        $statusText = ucfirst(str_replace('_', ' ', $maintenanceLog->status));
                        $statusClass = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'scheduled' => 'bg-blue-100 text-blue-800',
                            'in_progress' => 'bg-indigo-100 text-indigo-800 animate-pulse',
                            'completed' => 'bg-green-100 text-green-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                        ][$maintenanceLog->status] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="px-4 py-1 text-sm font-semibold rounded-full {{ $statusClass }}">
                        {{ $statusText }}
                    </span>
                </div>
                
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Facility</dt>
                        <dd class="mt-1 text-lg font-semibold text-lgu-highlight">{{ $maintenanceLog->facility->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Type of Maintenance</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">{{ ucfirst($maintenanceLog->type) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Reported By</dt>
                        <dd class="mt-1 text-gray-700">{{ $maintenanceLog->reported_by_name }} ({{ $maintenanceLog->reported_by_contact }})</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Log Created On</dt>
                        <dd class="mt-1 text-gray-700">{{ $maintenanceLog->created_at->format('M d, Y h:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Scheduled Date</dt>
                        <dd class="mt-1 text-gray-700">{{ $maintenanceLog->scheduled_date ? \Carbon\Carbon::parse($maintenanceLog->scheduled_date)->format('F d, Y') : 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Completion Date</dt>
                        <dd class="mt-1 text-gray-700">{{ $maintenanceLog->completion_date ? \Carbon\Carbon::parse($maintenanceLog->completion_date)->format('F d, Y') : 'Pending' }}</dd>
                    </div>
                </dl>
            </div>
            
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Maintenance Description & Notes</h3>
                
                <div class="mb-6">
                    <p class="text-sm font-medium text-gray-500 mb-2">Issue Reported / Description</p>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $maintenanceLog->description }}</p>
                    </div>
                </div>
                
                @if($maintenanceLog->notes)
                <div class="border-t pt-4">
                    <p class="text-sm font-medium text-gray-500 mb-2">Additional Notes</p>
                    <p class="text-gray-900 whitespace-pre-wrap">{{ $maintenanceLog->notes }}</p>
                </div>
                @endif
            </div>

            @if($maintenanceLog->completion_notes)
            <div class="bg-white rounded-lg shadow-md border border-green-300 p-6">
                <h3 class="text-lg font-bold text-green-700 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    Completion Report
                </h3>
                <p class="text-gray-900 whitespace-pre-wrap">{{ $maintenanceLog->completion_notes }}</p>
            </div>
            @endif
        </div>
        
        {{-- Costs and Actions (1/3 width) --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 sticky top-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Maintenance Costs</h3>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Estimated Cost</p>
                        <p class="text-2xl font-bold text-lgu-highlight">
                            ₱{{ number_format($maintenanceLog->estimated_cost, 2) }}
                        </p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-1">Actual Cost</p>
                        @if($maintenanceLog->actual_cost)
                        <p class="text-2xl font-bold text-green-600">
                            ₱{{ number_format($maintenanceLog->actual_cost, 2) }}
                        </p>
                        @else
                        <p class="text-xl font-semibold text-gray-400">
                            N/A (Pending Completion)
                        </p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md border border-red-200 p-6">
                <h3 class="text-lg font-bold text-red-700 mb-4 border-b pb-2">Danger Zone</h3>
                <form action="{{ route('admin.maintenance-logs.destroy', $maintenanceLog->id) }}" method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this maintenance log? This action is irreversible.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                        class="w-full px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors shadow-md">
                        <svg class="w-5 h-5 inline-block mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 10-2 0v6a1 1 0 102 0V8z" clip-rule="evenodd"/></svg>
                        Permanently Delete Log
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection