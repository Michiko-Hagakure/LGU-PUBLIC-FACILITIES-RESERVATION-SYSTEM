@extends('layouts.admin')

@section('page-title', 'Energy Facility Requests')
@section('page-subtitle', 'Manage facility requests from Energy Efficiency and Conservation Management')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .status-badge {
        font-size: 0.7rem;
        padding: 4px 12px;
        border-radius: 9999px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .status-pending { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }
    .status-approved { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
    .status-rejected { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
    .detail-label { font-size: 0.7rem; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
    .detail-value { font-size: 0.85rem; color: #1f2937; font-weight: 500; }
    .equipment-tag {
        display: inline-flex; align-items: center; gap: 4px;
        font-size: 0.7rem; padding: 2px 8px; border-radius: 6px;
        background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;
    }
</style>
@endpush

@section('page-content')
<div class="space-y-gr-lg">
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gr-md">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md">
            <div class="flex items-center gap-gr-sm">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="inbox" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-caption text-gray-500 uppercase font-semibold">Total Requests</p>
                    <p id="stat-total" class="text-h2 font-bold text-gray-900">{{ $stats['total'] }}</p>
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
                    <p id="stat-pending" class="text-h2 font-bold text-amber-600">{{ $stats['pending'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md">
            <div class="flex items-center gap-gr-sm">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                </div>
                <div>
                    <p class="text-caption text-gray-500 uppercase font-semibold">Approved</p>
                    <p id="stat-approved" class="text-h2 font-bold text-green-600">{{ $stats['approved'] }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md">
            <div class="flex items-center gap-gr-sm">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="x-circle" class="w-6 h-6 text-red-600"></i>
                </div>
                <div>
                    <p class="text-caption text-gray-500 uppercase font-semibold">Rejected</p>
                    <p id="stat-rejected" class="text-h2 font-bold text-red-600">{{ $stats['rejected'] }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Integration Info --}}
    <div class="p-gr-md bg-green-50 border border-green-200 rounded-xl">
        <div class="flex items-start gap-gr-sm">
            <i data-lucide="zap" class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5"></i>
            <div>
                <p class="text-green-800 font-semibold">Energy Efficiency & Conservation Management Integration</p>
                <p class="text-green-700 text-small mt-1">Facility requests submitted by the Energy Efficiency team for seminars, trainings, workshops, orientations, and other events.</p>
            </div>
        </div>
    </div>

    {{-- Requests List --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke px-gr-md py-gr-sm">
            <h3 class="text-white font-semibold flex items-center gap-2">
                <i data-lucide="calendar-check" class="w-5 h-5"></i>
                Facility Requests
            </h3>
        </div>

        @if($requests->count() > 0)
        <div class="divide-y divide-gray-100">
            @foreach($requests as $req)
            <div class="p-gr-md hover:bg-gray-50 transition-colors" x-data="{ expanded: false }">
                {{-- Summary Row --}}
                <div class="flex flex-col lg:flex-row lg:items-center gap-gr-sm">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h4 class="font-bold text-gray-900">{{ $req->event_title }}</h4>
                            <span class="status-badge status-{{ $req->status }}">{{ ucfirst($req->status) }}</span>
                        </div>
                        <div class="flex flex-wrap gap-x-4 gap-y-1 mt-2 text-small text-gray-600">
                            <span class="flex items-center gap-1">
                                <i data-lucide="user" class="w-3.5 h-3.5"></i>
                                {{ $req->point_person }}
                            </span>
                            <span class="flex items-center gap-1">
                                <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                                {{ $req->organizer_office ?? 'N/A' }}
                            </span>
                            <span class="flex items-center gap-1">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                {{ $req->preferred_date ? $req->preferred_date->format('M d, Y') : 'N/A' }}
                            </span>
                            <span class="flex items-center gap-1">
                                <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                {{ $req->start_time }} - {{ $req->end_time }}
                            </span>
                            @if($req->session_type)
                            <span class="flex items-center gap-1">
                                <i data-lucide="presentation" class="w-3.5 h-3.5"></i>
                                {{ ucfirst($req->session_type) }}
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <button @click="expanded = !expanded" class="text-blue-600 hover:text-blue-800 text-caption font-semibold flex items-center gap-1">
                            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                            <span x-text="expanded ? 'Hide Details' : 'View Details'"></span>
                        </button>
                        @if($req->status === 'pending')
                        <button onclick="approveRequest({{ $req->id }})" class="bg-green-600 text-white px-3 py-1.5 rounded-lg text-caption font-semibold hover:bg-green-700 transition flex items-center gap-1">
                            <i data-lucide="check" class="w-3 h-3"></i> Approve
                        </button>
                        <button onclick="rejectRequest({{ $req->id }})" class="bg-red-500 text-white px-3 py-1.5 rounded-lg text-caption font-semibold hover:bg-red-600 transition flex items-center gap-1">
                            <i data-lucide="x" class="w-3 h-3"></i> Reject
                        </button>
                        @endif
                    </div>
                </div>

                {{-- Expanded Details --}}
                <div x-show="expanded" x-collapse x-cloak class="mt-gr-md">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gr-md bg-gray-50 rounded-xl p-gr-md border border-gray-200">
                        {{-- Event Info --}}
                        <div class="space-y-2">
                            <h5 class="font-semibold text-gray-800 flex items-center gap-1 text-small">
                                <i data-lucide="info" class="w-4 h-4 text-blue-500"></i> Event Information
                            </h5>
                            <div>
                                <p class="detail-label">Purpose</p>
                                <p class="detail-value">{{ $req->purpose ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="detail-label">Contact</p>
                                <p class="detail-value">{{ $req->contact_number ?? '' }} {{ $req->contact_email ? '/ ' . $req->contact_email : '' }}</p>
                            </div>
                            <div>
                                <p class="detail-label">Audience Type</p>
                                <p class="detail-value">{{ ucfirst($req->audience_type ?? 'N/A') }}</p>
                            </div>
                            <div>
                                <p class="detail-label">Session Type</p>
                                <p class="detail-value">{{ ucfirst($req->session_type ?? 'N/A') }}</p>
                            </div>
                        </div>

                        {{-- Schedule --}}
                        <div class="space-y-2">
                            <h5 class="font-semibold text-gray-800 flex items-center gap-1 text-small">
                                <i data-lucide="calendar" class="w-4 h-4 text-green-500"></i> Schedule
                            </h5>
                            <div>
                                <p class="detail-label">Preferred Date & Time</p>
                                <p class="detail-value">{{ $req->preferred_date ? $req->preferred_date->format('M d, Y') : 'N/A' }} ({{ $req->start_time }} - {{ $req->end_time }})</p>
                            </div>
                            @if($req->alternative_date)
                            <div>
                                <p class="detail-label">Alternative Date & Time</p>
                                <p class="detail-value">{{ $req->alternative_date->format('M d, Y') }} ({{ $req->alternative_start_time ?? '?' }} - {{ $req->alternative_end_time ?? '?' }})</p>
                            </div>
                            @endif
                            <div>
                                <p class="detail-label">Facility Type Needed</p>
                                <p class="detail-value">{{ ucfirst($req->facility_type ?? 'Not specified') }}</p>
                            </div>
                        </div>

                        {{-- Equipment & Technical --}}
                        <div class="space-y-2">
                            <h5 class="font-semibold text-gray-800 flex items-center gap-1 text-small">
                                <i data-lucide="monitor" class="w-4 h-4 text-purple-500"></i> Equipment Needs
                            </h5>
                            <div class="flex flex-wrap gap-1">
                                @if($req->needs_projector) <span class="equipment-tag"><i data-lucide="monitor" class="w-3 h-3"></i> Projector</span> @endif
                                @if($req->laptop_option === 'yes') <span class="equipment-tag"><i data-lucide="laptop" class="w-3 h-3"></i> Laptop</span>
                                @elseif($req->laptop_option === 'bringing_own') <span class="equipment-tag bg-gray-100 text-gray-600 border-gray-300"><i data-lucide="laptop" class="w-3 h-3"></i> Bringing Own Laptop</span> @endif
                                @if($req->needs_sound_system) <span class="equipment-tag"><i data-lucide="volume-2" class="w-3 h-3"></i> Sound System</span> @endif
                                @if($req->needs_microphone) <span class="equipment-tag"><i data-lucide="mic" class="w-3 h-3"></i> Mic x{{ $req->microphone_count }} ({{ $req->microphone_type ?? 'N/A' }})</span> @endif
                                @if($req->needs_wifi) <span class="equipment-tag"><i data-lucide="wifi" class="w-3 h-3"></i> Wi-Fi</span> @endif
                                @if($req->needs_extension_cords) <span class="equipment-tag"><i data-lucide="plug" class="w-3 h-3"></i> Extension Cords</span> @endif
                            </div>
                            @if($req->additional_power_needs)
                            <div>
                                <p class="detail-label">Additional Power Needs</p>
                                <p class="detail-value">{{ $req->additional_power_needs }}</p>
                            </div>
                            @endif
                            @if($req->other_equipment)
                            <div>
                                <p class="detail-label">Other Equipment</p>
                                <p class="detail-value">{{ $req->other_equipment }}</p>
                            </div>
                            @endif
                        </div>

                        {{-- Materials --}}
                        <div class="space-y-2">
                            <h5 class="font-semibold text-gray-800 flex items-center gap-1 text-small">
                                <i data-lucide="file-text" class="w-4 h-4 text-orange-500"></i> Materials & Documents
                            </h5>
                            <div>
                                <p class="detail-label">Handouts</p>
                                <p class="detail-value">{{ $req->needs_handouts ? 'Yes (' . ucfirst($req->handouts_format ?? 'N/A') . ')' : 'No' }}</p>
                            </div>
                            <div>
                                <p class="detail-label">Certificates</p>
                                <p class="detail-value">{{ $req->needs_certificates ? 'Yes (by: ' . ucfirst($req->certificates_provider ?? 'N/A') . ')' : 'No' }}</p>
                            </div>
                        </div>

                        {{-- Food & Logistics --}}
                        <div class="space-y-2">
                            <h5 class="font-semibold text-gray-800 flex items-center gap-1 text-small">
                                <i data-lucide="utensils" class="w-4 h-4 text-red-500"></i> Food & Logistics
                            </h5>
                            <div>
                                <p class="detail-label">Refreshments/Meals</p>
                                <p class="detail-value">{{ $req->needs_refreshments ? 'Yes' : 'No' }}</p>
                            </div>
                            @if($req->dietary_notes)
                            <div>
                                <p class="detail-label">Dietary Notes</p>
                                <p class="detail-value">{{ $req->dietary_notes }}</p>
                            </div>
                            @endif
                            @if($req->delivery_instructions)
                            <div>
                                <p class="detail-label">Delivery Instructions</p>
                                <p class="detail-value">{{ $req->delivery_instructions }}</p>
                            </div>
                            @endif
                        </div>

                        {{-- Special Requests --}}
                        @if($req->special_requests)
                        <div class="space-y-2">
                            <h5 class="font-semibold text-gray-800 flex items-center gap-1 text-small">
                                <i data-lucide="message-square" class="w-4 h-4 text-teal-500"></i> Special Requests
                            </h5>
                            <p class="detail-value">{{ $req->special_requests }}</p>
                        </div>
                        @endif

                        {{-- Admin Response (if processed) --}}
                        @if($req->status !== 'pending' && $req->response_data)
                        @php $response = json_decode($req->response_data, true); @endphp
                        <div class="space-y-2 md:col-span-2 lg:col-span-3 bg-white rounded-lg p-gr-sm border border-green-200">
                            <h5 class="font-semibold text-green-800 flex items-center gap-1 text-small">
                                <i data-lucide="check-circle" class="w-4 h-4 text-green-600"></i> Admin Response
                            </h5>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                                @if(!empty($response['facility']['facility_name']))
                                <div>
                                    <p class="detail-label">Assigned Facility</p>
                                    <p class="detail-value">{{ $response['facility']['facility_name'] }}</p>
                                </div>
                                @endif
                                @if(!empty($response['scheduled_date']))
                                <div>
                                    <p class="detail-label">Scheduled Date & Time</p>
                                    <p class="detail-value">{{ $response['scheduled_date'] }} ({{ $response['scheduled_start_time'] ?? '?' }} - {{ $response['scheduled_end_time'] ?? '?' }})</p>
                                </div>
                                @endif
                                @if(!empty($response['approved_budget']))
                                <div>
                                    <p class="detail-label">Approved Budget</p>
                                    <p class="detail-value font-bold text-green-600">&#8369;{{ number_format($response['approved_budget'], 2) }}</p>
                                </div>
                                @endif
                                @if(!empty($response['assigned_equipment']))
                                <div>
                                    <p class="detail-label">Assigned Equipment</p>
                                    <p class="detail-value">{{ $response['assigned_equipment'] }}</p>
                                </div>
                                @endif
                                @if(!empty($response['admin_notes']))
                                <div class="md:col-span-2">
                                    <p class="detail-label">Admin Notes</p>
                                    <p class="detail-value">{{ $response['admin_notes'] }}</p>
                                </div>
                                @endif
                            </div>
                            @if($req->admin_feedback)
                            <div class="mt-2 p-2 bg-gray-50 rounded border border-gray-200">
                                <p class="detail-label">Feedback</p>
                                <p class="detail-value">{{ $req->admin_feedback }}</p>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="p-gr-2xl text-center">
            <i data-lucide="inbox" class="w-16 h-16 text-gray-200 mx-auto mb-gr-md"></i>
            <p class="text-gray-400 font-semibold">No facility requests found</p>
            <p class="text-caption text-gray-400 mt-1">Requests from Energy Efficiency will appear here</p>
        </div>
        @endif
    </div>
</div>

{{-- Hidden Form for Status Updates --}}
<form id="statusForm" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="status" id="formStatus">
    <input type="hidden" name="admin_feedback" id="formAdminFeedback">
    <input type="hidden" name="assigned_facility" id="formAssignedFacility">
    <input type="hidden" name="scheduled_date" id="formScheduledDate">
    <input type="hidden" name="scheduled_start_time" id="formScheduledStartTime">
    <input type="hidden" name="scheduled_end_time" id="formScheduledEndTime">
    <input type="hidden" name="assigned_equipment" id="formAssignedEquipment">
    <input type="hidden" name="approved_budget" id="formApprovedBudget">
    <input type="hidden" name="admin_notes" id="formAdminNotes">
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const requestsData = @json($requests->keyBy('id'));
    const facilitiesData = @json($facilities);

    const equipmentOptions = [
        { id: 'projector', name: 'LCD Projector' },
        { id: 'screen', name: 'Projector Screen' },
        { id: 'microphone', name: 'Wireless Microphone' },
        { id: 'speaker', name: 'Sound System / Speakers' },
        { id: 'laptop', name: 'Laptop / Computer' },
        { id: 'whiteboard', name: 'Whiteboard with Markers' },
        { id: 'extension', name: 'Extension Cords' },
        { id: 'chairs', name: 'Additional Chairs' },
        { id: 'tables', name: 'Additional Tables' },
        { id: 'podium', name: 'Podium / Lectern' },
    ];

    function approveRequest(id) {
        const req = requestsData[id];
        const prefDate = req.preferred_date ? req.preferred_date.split('T')[0] : '';

        Swal.fire({
            title: '<span class="text-green-600">Approve Facility Request</span>',
            html: `
                <div class="text-left space-y-4">
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-500">Event</p>
                        <p class="font-semibold text-gray-800">${req.event_title}</p>
                        <p class="text-sm text-gray-500 mt-1">Point Person</p>
                        <p class="font-medium text-gray-700">${req.point_person}</p>
                        <p class="text-sm text-gray-500 mt-1">Preferred Date</p>
                        <p class="font-medium text-gray-700">${prefDate} (${req.start_time} - ${req.end_time})</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Assign Facility <span class="text-red-500">*</span></label>
                        <select id="swal_facility" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">-- Select Facility --</option>
                            ${facilitiesData.map(f => `<option value="${f.facility_id}">${f.name} (Cap: ${f.capacity})</option>`).join('')}
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Date</label>
                            <input type="date" id="swal_date" value="${prefDate}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Start Time</label>
                            <input type="time" id="swal_start" value="${req.start_time || ''}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">End Time</label>
                            <input type="time" id="swal_end" value="${req.end_time || ''}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Equipment to Provide</label>
                        <div class="max-h-32 overflow-y-auto border border-gray-300 rounded-lg p-2 bg-white">
                            ${equipmentOptions.map(e => `
                                <label class="flex items-center gap-2 py-1 cursor-pointer hover:bg-gray-50 px-1 rounded">
                                    <input type="checkbox" class="swal_eq_cb rounded border-gray-300 text-green-600 focus:ring-green-500" value="${e.name}">
                                    <span class="text-sm text-gray-700">${e.name}</span>
                                </label>
                            `).join('')}
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Approved Budget (optional)</label>
                        <input type="number" id="swal_budget" step="0.01" min="0" placeholder="0.00"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Admin Notes / Feedback</label>
                        <textarea id="swal_notes" rows="3" placeholder="Additional notes for the requester..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 resize-none"></textarea>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Approve Request',
            confirmButtonColor: '#16a34a',
            cancelButtonText: 'Cancel',
            width: '600px',
            preConfirm: () => {
                const facility = document.getElementById('swal_facility').value;
                if (!facility) {
                    Swal.showValidationMessage('Please select a facility');
                    return false;
                }

                const checkedEq = document.querySelectorAll('.swal_eq_cb:checked');
                const equipment = Array.from(checkedEq).map(cb => cb.value).join(', ');

                return {
                    facility: facility,
                    date: document.getElementById('swal_date').value,
                    start: document.getElementById('swal_start').value,
                    end: document.getElementById('swal_end').value,
                    equipment: equipment,
                    budget: document.getElementById('swal_budget').value,
                    notes: document.getElementById('swal_notes').value,
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('statusForm');
                form.action = `/admin/energy-facility-requests/${id}/status`;
                document.getElementById('formStatus').value = 'approved';
                document.getElementById('formAdminFeedback').value = result.value.notes;
                document.getElementById('formAssignedFacility').value = result.value.facility;
                document.getElementById('formScheduledDate').value = result.value.date;
                document.getElementById('formScheduledStartTime').value = result.value.start;
                document.getElementById('formScheduledEndTime').value = result.value.end;
                document.getElementById('formAssignedEquipment').value = result.value.equipment;
                document.getElementById('formApprovedBudget').value = result.value.budget;
                document.getElementById('formAdminNotes').value = result.value.notes;
                form.submit();
            }
        });
    }

    function rejectRequest(id) {
        const req = requestsData[id];

        Swal.fire({
            title: '<span class="text-red-600">Reject Facility Request</span>',
            html: `
                <div class="text-left space-y-4">
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-500">Event</p>
                        <p class="font-semibold text-gray-800">${req.event_title}</p>
                        <p class="text-sm text-gray-500 mt-1">Point Person</p>
                        <p class="font-medium text-gray-700">${req.point_person}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Reason for Rejection <span class="text-red-500">*</span></label>
                        <textarea id="swal_reject_reason" rows="4" placeholder="Explain why this request is being rejected..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 resize-none"></textarea>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Reject Request',
            confirmButtonColor: '#dc2626',
            cancelButtonText: 'Cancel',
            width: '500px',
            preConfirm: () => {
                const reason = document.getElementById('swal_reject_reason').value;
                if (!reason.trim()) {
                    Swal.showValidationMessage('Please provide a reason for rejection');
                    return false;
                }
                return { reason };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('statusForm');
                form.action = `/admin/energy-facility-requests/${id}/status`;
                document.getElementById('formStatus').value = 'rejected';
                document.getElementById('formAdminFeedback').value = result.value.reason;
                form.submit();
            }
        });
    }
</script>
@endpush
@endsection
