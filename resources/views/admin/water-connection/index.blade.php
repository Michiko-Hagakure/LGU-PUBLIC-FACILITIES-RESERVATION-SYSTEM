@extends('layouts.admin')

@section('page-title', 'Water Connection Requests')
@section('page-subtitle', 'Manage water connection requests sent to Utility Billing & Management')

@push('styles')
<style>
    .status-badge {
        font-size: 0.7rem;
        padding: 4px 12px;
        border-radius: 9999px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .status-submitted { background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; }
    .status-under_review { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }
    .status-approved { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
    .status-rejected { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    .status-completed { background: #e0e7ff; color: #3730a3; border: 1px solid #a5b4fc; }
    .status-pending_sync { background: #fff7ed; color: #9a3412; border: 1px solid #fdba74; }
</style>
@endpush

@section('page-content')
<div class="space-y-gr-lg">

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-gr-md">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md">
            <div class="flex items-center gap-gr-sm">
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="inbox" class="w-5 h-5 text-gray-600"></i>
                </div>
                <div>
                    <p class="text-caption text-gray-500 uppercase font-semibold">Total</p>
                    <p id="stat-total" class="text-h3 font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md">
            <div class="flex items-center gap-gr-sm">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="send" class="w-5 h-5 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-caption text-gray-500 uppercase font-semibold">Submitted</p>
                    <p id="stat-submitted" class="text-h3 font-bold text-blue-600">{{ $stats['submitted'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md">
            <div class="flex items-center gap-gr-sm">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="clock" class="w-5 h-5 text-amber-600"></i>
                </div>
                <div>
                    <p class="text-caption text-gray-500 uppercase font-semibold">Under Review</p>
                    <p id="stat-under-review" class="text-h3 font-bold text-amber-600">{{ $stats['under_review'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md">
            <div class="flex items-center gap-gr-sm">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                </div>
                <div>
                    <p class="text-caption text-gray-500 uppercase font-semibold">Approved</p>
                    <p id="stat-approved" class="text-h3 font-bold text-green-600">{{ $stats['approved'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md">
            <div class="flex items-center gap-gr-sm">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="x-circle" class="w-5 h-5 text-red-600"></i>
                </div>
                <div>
                    <p class="text-caption text-gray-500 uppercase font-semibold">Rejected</p>
                    <p id="stat-rejected" class="text-h3 font-bold text-red-600">{{ $stats['rejected'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md">
            <div class="flex items-center gap-gr-sm">
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="badge-check" class="w-5 h-5 text-indigo-600"></i>
                </div>
                <div>
                    <p class="text-caption text-gray-500 uppercase font-semibold">Completed</p>
                    <p id="stat-completed" class="text-h3 font-bold text-indigo-600">{{ $stats['completed'] }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Integration Info + Actions --}}
    <div class="p-gr-md bg-blue-50 border border-blue-200 rounded-xl">
        <div class="flex items-start justify-between flex-wrap gap-4">
            <div class="flex items-start gap-gr-sm">
                <i data-lucide="droplets" class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5"></i>
                <div>
                    <p class="text-blue-800 font-semibold">Utility Billing & Management Integration</p>
                    <p class="text-blue-700 text-small mt-1">Request water connections and utility services. Statuses sync automatically from the external system.</p>
                </div>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <form action="{{ route('admin.water-connection.sync-statuses') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 bg-white text-blue-700 text-small font-semibold rounded-lg hover:bg-blue-100 transition-colors flex items-center gap-1 border border-blue-300">
                        <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                        Sync Statuses
                    </button>
                </form>
                @if($stats['pending_sync'] > 0)
                <form action="{{ route('admin.water-connection.retry-sync') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-1.5 bg-orange-100 text-orange-800 text-small font-semibold rounded-lg hover:bg-orange-200 transition-colors flex items-center gap-1 border border-orange-300">
                        <i data-lucide="upload" class="w-4 h-4"></i>
                        Retry Sync ({{ $stats['pending_sync'] }})
                    </button>
                </form>
                @endif
                <a href="{{ route('admin.water-connection.create') }}" class="px-4 py-1.5 bg-blue-600 text-white text-small font-semibold rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-1">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    New Request
                </a>
            </div>
        </div>
    </div>

    {{-- Requests Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-cyan-600 px-gr-md py-gr-sm">
            <h3 class="text-white font-semibold flex items-center gap-2">
                <i data-lucide="droplets" class="w-5 h-5"></i>
                Water Connection Requests
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-caption font-bold text-gray-600 uppercase">Application #</th>
                        <th class="px-4 py-3 text-left text-caption font-bold text-gray-600 uppercase">Consumer</th>
                        <th class="px-4 py-3 text-left text-caption font-bold text-gray-600 uppercase">Service Type</th>
                        <th class="px-4 py-3 text-left text-caption font-bold text-gray-600 uppercase">Property</th>
                        <th class="px-4 py-3 text-left text-caption font-bold text-gray-600 uppercase">Address</th>
                        <th class="px-4 py-3 text-center text-caption font-bold text-gray-600 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-caption font-bold text-gray-600 uppercase">Submitted</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($requests as $request)
                    <tr class="hover:bg-gray-50 transition-colors cursor-pointer" onclick="showDetails({{ json_encode($request) }})">
                        <td class="px-4 py-3">
                            <p class="font-semibold text-blue-700">{{ $request->external_application_number ?? 'Pending' }}</p>
                            <p class="text-caption text-gray-400">{{ $request->partner_reference }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="user" class="w-4 h-4 text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $request->consumer_name }}</p>
                                    <p class="text-caption text-gray-500">{{ $request->contact_phone }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-gray-700">{{ $serviceTypes[$request->service_type] ?? $request->service_type }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-gray-700 capitalize">{{ $request->property_type }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-gray-700">{{ Str::limit($request->installation_address, 35) }}</p>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $statusClass = match($request->status) {
                                    'submitted' => 'status-submitted',
                                    'under_review' => 'status-under_review',
                                    'approved' => 'status-approved',
                                    'rejected' => 'status-rejected',
                                    'completed' => 'status-completed',
                                    'pending_sync' => 'status-pending_sync',
                                    default => 'status-submitted'
                                };
                                $statusLabel = match($request->status) {
                                    'pending_sync' => 'Pending Sync',
                                    'under_review' => 'Under Review',
                                    default => ucfirst($request->status)
                                };
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-gray-500 text-small">{{ \Carbon\Carbon::parse($request->created_at)->format('M d, Y') }}</p>
                            <p class="text-gray-400 text-caption">{{ \Carbon\Carbon::parse($request->created_at)->format('g:i A') }}</p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center gap-3 text-gray-400">
                                <i data-lucide="droplets" class="w-12 h-12"></i>
                                <p class="font-medium">No water connection requests yet</p>
                                <p class="text-small">Click "New Request" to submit your first water connection request</p>
                                <a href="{{ route('admin.water-connection.create') }}" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2 text-small">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                    New Request
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($requests, 'links'))
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $requests->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Details Modal --}}
<div id="detailsModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeDetailsModal()"></div>

        <div class="relative inline-block w-full max-w-2xl p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900" id="modal-title">Request Details</h3>
                <button type="button" onclick="closeDetailsModal()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
            <div id="modal-body" class="space-y-4">
            </div>
            <div class="flex justify-end pt-4 mt-4 border-t">
                <button type="button" onclick="closeDetailsModal()" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const serviceTypes = @json($serviceTypes);

    function showDetails(request) {
        const statusClass = {
            'submitted': 'status-submitted',
            'under_review': 'status-under_review',
            'approved': 'status-approved',
            'rejected': 'status-rejected',
            'completed': 'status-completed',
            'pending_sync': 'status-pending_sync',
        };
        const statusLabel = {
            'pending_sync': 'Pending Sync',
            'under_review': 'Under Review',
        };

        const cls = statusClass[request.status] || 'status-submitted';
        const label = statusLabel[request.status] || (request.status.charAt(0).toUpperCase() + request.status.slice(1));
        const serviceLabel = serviceTypes[request.service_type] || request.service_type;
        const createdAt = new Date(request.created_at).toLocaleDateString('en-PH', {
            year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'
        });

        document.getElementById('modal-title').textContent = request.external_application_number
            ? 'Request ' + request.external_application_number
            : 'Request Details';

        document.getElementById('modal-body').innerHTML = `
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-xs text-gray-500 font-semibold uppercase">Application Number</p>
                    <p class="font-bold text-blue-700">${request.external_application_number || 'Pending'}</p>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-xs text-gray-500 font-semibold uppercase">Status</p>
                    <span class="status-badge ${cls}">${label}</span>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-xs text-gray-500 font-semibold uppercase">Service Type</p>
                    <p class="font-medium text-gray-800">${serviceLabel}</p>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-xs text-gray-500 font-semibold uppercase">Property Type</p>
                    <p class="font-medium text-gray-800 capitalize">${request.property_type}</p>
                </div>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg">
                <p class="text-xs text-gray-500 font-semibold uppercase">Consumer Name</p>
                <p class="font-medium text-gray-800">${request.consumer_name}</p>
            </div>
            <div class="bg-gray-50 p-3 rounded-lg">
                <p class="text-xs text-gray-500 font-semibold uppercase">Installation Address</p>
                <p class="font-medium text-gray-800">${request.installation_address}</p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-xs text-gray-500 font-semibold uppercase">Contact Person</p>
                    <p class="font-medium text-gray-800">${request.contact_person}</p>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-xs text-gray-500 font-semibold uppercase">Contact Phone</p>
                    <p class="font-medium text-gray-800">${request.contact_phone}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-xs text-gray-500 font-semibold uppercase">Contact Email</p>
                    <p class="font-medium text-gray-800">${request.contact_email}</p>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg">
                    <p class="text-xs text-gray-500 font-semibold uppercase">Partner Reference</p>
                    <p class="font-medium text-gray-800">${request.partner_reference || 'N/A'}</p>
                </div>
            </div>
            ${request.notes ? `
            <div class="bg-gray-50 p-3 rounded-lg">
                <p class="text-xs text-gray-500 font-semibold uppercase">Notes</p>
                <p class="font-medium text-gray-800">${request.notes}</p>
            </div>` : ''}
            ${request.remarks ? `
            <div class="bg-yellow-50 p-3 rounded-lg border border-yellow-200">
                <p class="text-xs text-yellow-700 font-semibold uppercase">Remarks</p>
                <p class="font-medium text-yellow-800">${request.remarks}</p>
            </div>` : ''}
            <div class="bg-gray-50 p-3 rounded-lg">
                <p class="text-xs text-gray-500 font-semibold uppercase">Submitted At</p>
                <p class="font-medium text-gray-800">${createdAt}</p>
            </div>
        `;

        document.getElementById('detailsModal').classList.remove('hidden');

        // Re-initialize Lucide icons in modal
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    function closeDetailsModal() {
        document.getElementById('detailsModal').classList.add('hidden');
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeDetailsModal();
    });
</script>
@endpush
