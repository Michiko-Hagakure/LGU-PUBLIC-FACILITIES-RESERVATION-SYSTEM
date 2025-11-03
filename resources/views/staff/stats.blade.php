@extends('layouts.staff')

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
            <div>
                <h1 class="text-3xl font-bold mb-2">My Verification Statistics</h1>
                <p class="text-gray-200">Personal performance metrics and verification history</p>
            </div>
            {{-- Verification Totals --}}
            <div class="text-right space-y-2 hidden sm:block">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 border border-white/20">
                    <p class="text-2xl font-bold text-lgu-highlight">{{ $stats['total_verifications'] }}</p>
                    <p class="text-sm text-gray-200">Total Verifications</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Performance Metrics / Summary --}}
        <div class="lg:col-span-2 space-y-6">
            <h2 class="text-xl font-semibold text-gray-800">Monthly Performance Summary (October)</h2>
            
            {{-- Stats Cards Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                
                {{-- Approved Documents --}}
                <div class="bg-white rounded-xl shadow-lg p-4 text-center border-t-4 border-green-500">
                    <p class="text-3xl font-bold text-green-700">{{ $stats['monthly_approved'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">Approved</p>
                </div>
                
                {{-- Rejected Documents --}}
                <div class="bg-white rounded-xl shadow-lg p-4 text-center border-t-4 border-red-500">
                    <p class="text-3xl font-bold text-red-700">{{ $stats['monthly_rejected'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">Rejected</p>
                </div>
                
                {{-- Revision Required --}}
                <div class="bg-white rounded-xl shadow-lg p-4 text-center border-t-4 border-yellow-500">
                    <p class="text-3xl font-bold text-yellow-700">{{ $stats['monthly_revisions'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">Revisions Sent</p>
                </div>
                
                {{-- Average Turnaround Time --}}
                <div class="bg-white rounded-xl shadow-lg p-4 text-center border-t-4 border-blue-500">
                    <p class="text-3xl font-bold text-blue-700">{{ $stats['avg_turnaround'] }}<span class="text-lg">m</span></p>
                    <p class="text-sm text-gray-500 mt-1">Avg. Time</p>
                </div>
            </div>
            
            {{-- Activity Chart (Placeholder) --}}
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Verification Trend (Last 7 Days)</h3>
                {{-- Placeholder for Chart using a simple SVG/image tag to keep structure --}}
                <div class="h-64 bg-gray-50 flex items-center justify-center rounded-lg border border-dashed border-gray-300">
                    <p class="text-gray-400">Chart Placeholder (Using Chart.js/ApexCharts)</p>
                </div>
            </div>

            {{-- Detailed Verification History Table (Placeholder) --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <h3 class="text-lg font-semibold text-gray-800 p-6 border-b">Detailed Verification History</h3>
                <div class="p-6">
                    {{-- Table Structure Placeholder --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document ID</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Decision</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Taken</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                {{-- Sample Rows --}}
                                @for ($i = 0; $i < 5; $i++)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">IDV-{{ 1000 + $i }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Resident ID</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $i % 2 === 0 ? 'green' : 'yellow' }}-100 text-{{ $i % 2 === 0 ? 'green' : 'yellow' }}-800">
                                            {{ $i % 2 === 0 ? 'Approved' : 'Revision' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ 1 + $i }} min</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ date('Y-m-d') }}</td>
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions and Tips Sidebar --}}
        <div class="space-y-6">
            <h3 class="text-lg font-semibold text-gray-800">Actions & Tips</h3>

            {{-- Back to Dashboard Link --}}
            <a href="{{ route('staff.dashboard') }}" class="flex items-center p-3 bg-white border border-gray-200 rounded-lg shadow-sm hover:border-lgu-highlight hover:bg-lgu-bg/50 transition-all w-full text-left">
                <svg class="w-8 h-8 text-lgu-headline mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h2a2 2 0 012 2v2H8V5z"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-900">Back to Dashboard</p>
                    <p class="text-sm text-gray-600">View overview</p>
                </div>
            </a>

            {{-- Export Report Button --}}
            <button class="flex items-center p-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-lgu-highlight hover:bg-lgu-bg/50 transition-all w-full text-left">
                <svg class="w-8 h-8 text-lgu-highlight mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m-3 8a9 9 0 110-18 9 9 0 010 18z"></path>
                </svg>
                <div>
                    <p class="font-medium text-lgu-highlight">Export Monthly Report</p>
                    <p class="text-sm text-gray-600">Download data for current month</p>
                </div>
            </button>

            {{-- Tip of the Day (Placeholder) --}}
            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <h4 class="font-semibold text-blue-800">Tip: Speed and Accuracy</h4>
                </div>
                <p class="text-sm text-gray-600">Prioritize accuracy over speed. A higher revision rate affects your overall metrics. Always cross-reference the required documents with the provided submission.</p>
            </div>
        </div>
    </div>
</div>
@endsection