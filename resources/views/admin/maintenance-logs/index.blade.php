@extends('layouts.app')

@section('title', 'Maintenance Logs')

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
            <div class="space-y-3">
                <div class="flex items-center space-x-3">
                    <div class="w-16 h-16 bg-lgu-highlight/20 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                        <svg class="w-8 h-8 text-lgu-highlight" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.3-.852-3.3-1.2-4.14-.948l-.003.001c-1.397.405-2.235 2.053-2.146 3.659.077 1.34.843 2.766 1.45 4.382.26.702.6 1.385.998 2.045.397.66.79 1.312 1.157 1.957.778 1.325 1.761 2.502 3.018 3.327.915.587 2.147.788 3.447.788 1.299 0 2.53-.201 3.447-.788 1.257-.825 2.24-1.99 3.018-3.327.367-.645.76-1.297 1.157-1.957.398-.66.738-1.343.998-2.045.607-1.616 1.373-3.042 1.45-4.382.089-1.606-.749-3.254-2.146-3.659l-.003-.001a1.532 1.532 0 01-2.286-.948zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold mb-1 text-white">Facility Maintenance Logs</h1>
                        <p class="text-gray-200">Track and manage all facility maintenance tasks.</p>
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.maintenance-logs.create') }}" 
               class="px-5 py-2.5 bg-lgu-highlight text-white font-semibold rounded-lg hover:bg-lgu-button transition-colors shadow-lg">
                + New Maintenance Log
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
        @if($maintenanceLogs->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Facility
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Reported By
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Type
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Scheduled Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Completed Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($maintenanceLogs as $log)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $log->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $log->facility->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $log->reported_by_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ ucfirst($log->type) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $statusClass = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'scheduled' => 'bg-blue-100 text-blue-800',
                                            'in_progress' => 'bg-indigo-100 text-indigo-800 animate-pulse',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ][$log->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-3 py-1 text-xs font-medium leading-5 rounded-full {{ $statusClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $log->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $log->scheduled_date ? \Carbon\Carbon::parse($log->scheduled_date)->format('M d, Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $log->completion_date ? \Carbon\Carbon::parse($log->completion_date)->format('M d, Y') : 'Pending' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('admin.maintenance-logs.show', $log->id) }}" 
                                       class="text-lgu-highlight hover:text-lgu-button font-medium">View</a>
                                    <a href="{{ route('admin.maintenance-logs.edit', $log->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $maintenanceLogs->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No Maintenance Logs</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new maintenance log.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.maintenance-logs.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-lgu-highlight text-white font-medium rounded-lg hover:bg-lgu-button shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Create Log
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection