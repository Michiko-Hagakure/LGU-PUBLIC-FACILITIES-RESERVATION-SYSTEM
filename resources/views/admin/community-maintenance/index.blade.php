@extends('layouts.admin')

@section('page-title', 'My Maintenance Requests')
@section('page-subtitle', 'Track maintenance requests submitted to Community Infrastructure Management')

@section('page-content')
<div class="space-y-gr-lg">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-h1 font-bold text-lgu-headline mb-gr-xs">My Maintenance Requests</h1>
            <p class="text-body text-lgu-paragraph">Track status of maintenance requests sent to Community Infrastructure</p>
        </div>
        <a href="{{ URL::signedRoute('admin.community-maintenance.create') }}" class="inline-flex items-center px-gr-lg py-gr-md bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-opacity-90 transition-colors duration-200">
            <i data-lucide="plus" class="w-5 h-5 mr-gr-xs"></i>
            New Request
        </a>
    </div>

    {{-- Success/Error Messages --}}
    @if(session('success'))
    <div class="p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
        <i data-lucide="check-circle" class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5"></i>
        <p class="text-green-800 font-medium">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3">
        <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5"></i>
        <p class="text-red-800 font-medium">{{ session('error') }}</p>
    </div>
    @endif

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <form method="GET" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Category</label>
                <select name="category" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight">
                    <option value="">All Statuses</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-lgu-highlight text-white text-sm font-medium rounded-lg hover:bg-lgu-stroke transition-colors">
                Filter
            </button>
            @if(request('category') || request('status'))
            <a href="{{ URL::signedRoute('admin.community-maintenance.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-300 transition-colors">
                Clear
            </a>
            @endif
        </form>
    </div>

    {{-- Stats --}}
    @php
        $pendingCount = 0;
        $inProgressCount = 0;
        $completedCount = 0;
        $closedCount = 0;
        
        if ($requests instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            try {
                $pendingCount = \Illuminate\Support\Facades\DB::connection('facilities_db')
                    ->table('community_maintenance_requests')
                    ->where('status', 'Pending')
                    ->count();
                $inProgressCount = \Illuminate\Support\Facades\DB::connection('facilities_db')
                    ->table('community_maintenance_requests')
                    ->where('status', 'In Progress')
                    ->count();
                $completedCount = \Illuminate\Support\Facades\DB::connection('facilities_db')
                    ->table('community_maintenance_requests')
                    ->where('status', 'Completed')
                    ->count();
                $closedCount = \Illuminate\Support\Facades\DB::connection('facilities_db')
                    ->table('community_maintenance_requests')
                    ->where('status', 'Closed')
                    ->count();
            } catch (\Exception $e) {}
        }
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-gr-md">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-caption text-gray-600 uppercase mb-1">Pending</div>
                    <div id="stat-pending" class="text-h1 font-bold text-blue-600">{{ $pendingCount }}</div>
                </div>
                <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                    <i data-lucide="clock" class="w-7 h-7 text-blue-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-caption text-gray-600 uppercase mb-1">In Progress</div>
                    <div id="stat-in-progress" class="text-h1 font-bold text-amber-600">{{ $inProgressCount }}</div>
                </div>
                <div class="w-14 h-14 bg-amber-100 rounded-full flex items-center justify-center">
                    <i data-lucide="wrench" class="w-7 h-7 text-amber-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-caption text-gray-600 uppercase mb-1">Completed</div>
                    <div id="stat-completed" class="text-h1 font-bold text-green-600">{{ $completedCount }}</div>
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-7 h-7 text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-caption text-gray-600 uppercase mb-1">Closed</div>
                    <div id="stat-closed" class="text-h1 font-bold text-gray-600">{{ $closedCount }}</div>
                </div>
                <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center">
                    <i data-lucide="archive" class="w-7 h-7 text-gray-600"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Requests Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-x-auto">
        <table class="w-full table-fixed min-w-[800px]">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="w-16 px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase">#</th>
                    <th class="w-28 px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Category</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Issue Type</th>
                    <th class="w-36 px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Facility</th>
                    <th class="w-20 px-3 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Priority</th>
                    <th class="w-24 px-3 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
                    <th class="w-28 px-3 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Submitted</th>
                </tr>
            </thead>
            <tbody id="reports-tbody" class="divide-y divide-gray-200">
                @forelse($requests as $request)
                    @php
                        $statusColors = [
                            'Pending' => 'bg-blue-100 text-blue-800',
                            'In Progress' => 'bg-amber-100 text-amber-800',
                            'Completed' => 'bg-green-100 text-green-800',
                            'Closed' => 'bg-gray-100 text-gray-600',
                        ];
                        $priorityColors = [
                            'low' => 'bg-green-100 text-green-800',
                            'medium' => 'bg-yellow-100 text-yellow-800',
                            'high' => 'bg-orange-100 text-orange-800',
                            'urgent' => 'bg-red-100 text-red-800',
                        ];
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-3 text-sm font-medium text-gray-900">
                            {{ $request->external_request_id ?? $request->id }}
                        </td>
                        <td class="px-3 py-3 text-sm text-gray-600 truncate" title="{{ $request->category }}">
                            {{ Str::limit($request->category ?? '-', 15) }}
                        </td>
                        <td class="px-3 py-3 text-sm text-gray-900 truncate" title="{{ $request->issue_type }}">
                            {{ Str::limit($request->issue_type ?? $request->subject, 30) }}
                        </td>
                        <td class="px-3 py-3 text-sm text-gray-600 truncate" title="{{ $request->facility_name }}">
                            {{ Str::limit($request->facility_name, 20) }}
                        </td>
                        <td class="px-3 py-3 text-center">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $priorityColors[$request->priority] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($request->priority) }}
                            </span>
                        </td>
                        <td class="px-3 py-3 text-center">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$request->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $request->status }}
                            </span>
                        </td>
                        <td class="px-3 py-3 text-xs text-gray-600">
                            {{ \Carbon\Carbon::parse($request->created_at)->format('M j, Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-3 py-12 text-center">
                            <i data-lucide="wrench" class="w-12 h-12 text-gray-300 mb-3 mx-auto"></i>
                            <p class="text-gray-500">No maintenance requests submitted yet</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($requests instanceof \Illuminate\Pagination\LengthAwarePaginator && $requests->hasPages())
        <div class="mt-gr-lg">
            {{ $requests->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}

let lastDataHash = '';

function pollMaintenanceReports() {
    fetch('{{ route("admin.community-maintenance.json") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const newHash = JSON.stringify(data.stats);
                if (lastDataHash && newHash !== lastDataHash) {
                    location.reload();
                }
                lastDataHash = newHash;
                
                document.getElementById('stat-pending').textContent = data.stats.pending;
                document.getElementById('stat-in-progress').textContent = data.stats.in_progress;
                document.getElementById('stat-completed').textContent = data.stats.completed;
                document.getElementById('stat-closed').textContent = data.stats.closed;
            }
        })
        .catch(err => console.error('Poll error:', err));
}

setInterval(pollMaintenanceReports, 10000);
</script>
@endpush
@endsection
