@extends('layouts.admin')

@section('page-title', 'Housing & Resettlement Requests')
@section('page-subtitle', 'Facility requests from Housing and Resettlement Management for beneficiary orientations')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<meta http-equiv="refresh" content="30">
<style>
    .status-badge {
        font-size: 0.65rem;
        padding: 3px 8px;
        border-radius: 9999px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        white-space: nowrap;
    }
    .status-pending { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }
    .status-staff_verified { background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; }
    .status-confirmed { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
    .status-cancelled { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    .status-paid { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
    .compact-table th, .compact-table td { padding: 8px 6px; font-size: 0.75rem; }
    .compact-table .truncate-cell { max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
</style>
@endpush

@section('page-content')
<div class="space-y-gr-lg">
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gr-md">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md">
            <div class="flex items-center gap-gr-sm">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="home" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-caption text-gray-500 uppercase font-semibold">Total Requests</p>
                    <p class="text-h2 font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md">
            <div class="flex items-center gap-gr-sm">
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="clock" class="w-6 h-6 text-amber-600"></i>
                </div>
                <div>
                    <p class="text-caption text-gray-500 uppercase font-semibold">Pending</p>
                    <p class="text-h2 font-bold text-amber-600">{{ $stats['pending'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md">
            <div class="flex items-center gap-gr-sm">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                </div>
                <div>
                    <p class="text-caption text-gray-500 uppercase font-semibold">Confirmed</p>
                    <p class="text-h2 font-bold text-green-600">{{ $stats['confirmed'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md">
            <div class="flex items-center gap-gr-sm">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="users" class="w-6 h-6 text-purple-600"></i>
                </div>
                <div>
                    <p class="text-caption text-gray-500 uppercase font-semibold">Total Attendees</p>
                    <p class="text-h2 font-bold text-purple-600">{{ number_format($stats['total_attendees']) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Integration Info --}}
    <div class="p-gr-md bg-teal-50 border border-teal-200 rounded-xl">
        <div class="flex items-start gap-gr-sm">
            <i data-lucide="building-2" class="w-5 h-5 text-teal-600 flex-shrink-0 mt-0.5"></i>
            <div>
                <p class="text-teal-800 font-semibold">Housing and Resettlement Integration</p>
                <p class="text-teal-700 text-small mt-1">Facility requests submitted from the Housing and Resettlement Management system for beneficiary orientations. These are inter-agency requests with no payment required.</p>
            </div>
        </div>
    </div>


    {{-- Requests Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-teal-600 to-teal-700 px-gr-md py-gr-sm">
            <h3 class="text-white font-semibold flex items-center gap-2">
                <i data-lucide="calendar-check" class="w-5 h-5"></i>
                Facility Requests from Housing & Resettlement
            </h3>
        </div>

        @if($requests->isEmpty())
        <div class="p-gr-xl text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="inbox" class="w-8 h-8 text-gray-400"></i>
            </div>
            <p class="text-gray-500 font-medium">No requests yet</p>
            <p class="text-gray-400 text-small mt-1">Requests from Housing and Resettlement will appear here when they test the API.</p>
        </div>
        @else
        <div class="w-full">
            <table class="w-full compact-table">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left text-caption font-semibold text-gray-600 uppercase">Ref</th>
                        <th class="text-left text-caption font-semibold text-gray-600 uppercase">Event</th>
                        <th class="text-left text-caption font-semibold text-gray-600 uppercase">Facility</th>
                        <th class="text-left text-caption font-semibold text-gray-600 uppercase">Schedule</th>
                        <th class="text-center text-caption font-semibold text-gray-600 uppercase">Pax</th>
                        <th class="text-left text-caption font-semibold text-gray-600 uppercase">Contact</th>
                        <th class="text-center text-caption font-semibold text-gray-600 uppercase">Status</th>
                        <th class="text-center text-caption font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($requests as $request)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td>
                            <span class="font-mono font-bold text-teal-600 text-xs">{{ $request->booking_reference }}</span>
                        </td>
                        <td class="truncate-cell" title="{{ $request->event_name }}">
                            <p class="font-medium text-gray-900 truncate">{{ Str::limit($request->event_name, 20) }}</p>
                        </td>
                        <td class="truncate-cell">
                            <p class="font-medium text-gray-800 truncate">{{ Str::limit($request->facility_name, 15) }}</p>
                        </td>
                        <td>
                            <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($request->start_time)->format('M d') }}</p>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($request->start_time)->format('h:iA') }}-{{ \Carbon\Carbon::parse($request->end_time)->format('h:iA') }}</p>
                        </td>
                        <td class="text-center">
                            <span class="font-bold">{{ $request->expected_attendees ?? '-' }}</span>
                        </td>
                        <td class="truncate-cell" title="{{ $request->applicant_name }} | {{ $request->applicant_email }} | {{ $request->applicant_phone }}">
                            <p class="font-medium text-gray-800 truncate">{{ Str::limit($request->applicant_name, 15) }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ Str::limit($request->applicant_email, 20) }}</p>
                        </td>
                        <td class="text-center">
                            <span class="status-badge status-{{ $request->status }}">
                                {{ str_replace('_', ' ', $request->status) }}
                            </span>
                        </td>
                        <td>
                            @if($request->status === 'pending')
                            <div class="flex gap-1 justify-center">
                                <form action="{{ route('admin.housing-resettlement.approve', $request->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-green-600 text-white px-2 py-1 rounded text-xs font-semibold hover:bg-green-700 transition-colors" title="Approve">
                                        <i data-lucide="check" class="w-3 h-3"></i>
                                    </button>
                                </form>
                                <button type="button" onclick="rejectRequest({{ $request->id }})" class="bg-red-500 text-white px-2 py-1 rounded text-xs font-semibold hover:bg-red-600 transition-colors" title="Reject">
                                    <i data-lucide="x" class="w-3 h-3"></i>
                                </button>
                            </div>
                            @else
                            <span class="text-gray-400">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

{{-- Rejection Modal Form (hidden) --}}
<form id="rejectForm" action="" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="rejection_reason" id="rejectionReason">
</form>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function rejectRequest(id) {
    Swal.fire({
        title: 'Reject Request',
        text: 'Please provide a reason for rejection (optional):',
        input: 'textarea',
        inputPlaceholder: 'Enter reason...',
        showCancelButton: true,
        confirmButtonText: 'Reject',
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.getElementById('rejectForm');
            form.action = `/admin/housing-resettlement/${id}/reject`;
            document.getElementById('rejectionReason').value = result.value || '';
            form.submit();
        }
    });
}
</script>
@endpush
