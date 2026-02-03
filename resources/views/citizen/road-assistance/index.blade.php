@extends('layouts.citizen')

@section('page-title', 'Road Assistance')
@section('page-subtitle', 'Request traffic and road support for your events')

@section('page-content')
<div class="space-y-6">
    <!-- Success Message -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-600 p-4 rounded-lg">
        <div class="flex items-start">
            <i data-lucide="check-circle" class="w-5 h-5 text-green-600 mr-3 flex-shrink-0 mt-0.5"></i>
            <p class="text-sm text-green-800">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <!-- Error Messages -->
    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-600 p-4 rounded-lg">
        <div class="flex items-start">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 mr-3 flex-shrink-0 mt-0.5"></i>
            <p class="text-sm text-red-800">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border-l-4 border-red-600 p-4 rounded-lg">
        <div class="flex items-start">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600 mr-3 flex-shrink-0 mt-0.5"></i>
            <div>
                <p class="font-semibold text-red-900">Validation Error</p>
                <ul class="list-disc list-inside text-sm text-red-800 mt-2 space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-lgu-headline">Road Assistance Requests</h2>
            <p class="text-sm text-lgu-paragraph mt-1">Request traffic management and road support from the Road & Transportation department</p>
        </div>
        <button type="button" onclick="openRequestModal()" class="btn-primary inline-flex items-center gap-2">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>New Request</span>
        </button>
    </div>

    <!-- Info Banner -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
        <div class="flex items-start gap-3">
            <i data-lucide="info" class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5"></i>
            <div>
                <p class="text-sm text-blue-800 font-medium">About Road Assistance</p>
                <p class="text-sm text-blue-700 mt-1">
                    If your facility booking requires road closure, traffic management, or personnel deployment, 
                    you can submit a request to the Road & Transportation department for assistance.
                </p>
            </div>
        </div>
    </div>

    <!-- Requests List -->
    @if(count($requests) > 0)
        <div class="space-y-4">
            @foreach($requests as $request)
            @php
                $statusColors = [
                    'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                    'approved' => 'bg-green-100 text-green-800 border-green-300',
                    'rejected' => 'bg-red-100 text-red-800 border-red-300',
                ];
                $statusColor = $statusColors[$request['status']] ?? 'bg-gray-100 text-gray-800 border-gray-300';
            @endphp
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border-l-4 
                @if($request['status'] === 'pending') border-yellow-500 
                @elseif($request['status'] === 'approved') border-green-500 
                @else border-red-500 @endif">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium border {{ $statusColor }}">
                                    @if($request['status'] === 'pending')
                                        <i data-lucide="clock" class="w-3 h-3"></i>
                                    @elseif($request['status'] === 'approved')
                                        <i data-lucide="check-circle" class="w-3 h-3"></i>
                                    @else
                                        <i data-lucide="x-circle" class="w-3 h-3"></i>
                                    @endif
                                    {{ ucfirst($request['status']) }}
                                </span>
                                <span class="text-sm text-lgu-paragraph">
                                    Request #{{ $request['id'] }}
                                </span>
                            </div>

                            <h3 class="text-lg font-bold text-lgu-headline mb-2">
                                {{ $request['event_type'] }}
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <p class="text-xs text-lgu-paragraph uppercase tracking-wide mb-1">Location</p>
                                    <p class="text-sm font-semibold text-lgu-headline">{{ $request['location'] }}</p>
                                    @if(!empty($request['landmark']))
                                        <p class="text-xs text-lgu-paragraph">Near {{ $request['landmark'] }}</p>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-xs text-lgu-paragraph uppercase tracking-wide mb-1">Start Date</p>
                                    <p class="text-sm font-semibold text-lgu-headline">
                                        {{ \Carbon\Carbon::parse($request['start_date'])->format('M d, Y g:i A') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-lgu-paragraph uppercase tracking-wide mb-1">End Date</p>
                                    <p class="text-sm font-semibold text-lgu-headline">
                                        {{ \Carbon\Carbon::parse($request['end_date'])->format('M d, Y g:i A') }}
                                    </p>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-3">
                                <p class="text-xs text-lgu-paragraph uppercase tracking-wide mb-1">Description</p>
                                <p class="text-sm text-lgu-headline">{{ $request['description'] }}</p>
                            </div>

                            @if(!empty($request['remarks']))
                            <div class="mt-3 bg-blue-50 rounded-lg p-3">
                                <p class="text-xs text-blue-700 uppercase tracking-wide mb-1">Admin Remarks</p>
                                <p class="text-sm text-blue-900">{{ $request['remarks'] }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm p-12 text-center">
            <i data-lucide="truck" class="w-16 h-16 mx-auto text-gray-400 mb-4"></i>
            <h3 class="text-lg font-semibold text-lgu-headline mb-2">No Road Assistance Requests</h3>
            <p class="text-sm text-lgu-paragraph mb-6">You haven't submitted any road assistance requests yet.</p>
            <button type="button" onclick="openRequestModal()" class="btn-primary inline-flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Submit Your First Request</span>
            </button>
        </div>
    @endif
</div>

<!-- New Request Modal -->
<div id="requestModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeRequestModal()"></div>
        
        <div class="relative inline-block w-full max-w-2xl p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-2xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-lgu-headline">New Road Assistance Request</h3>
                <button type="button" onclick="closeRequestModal()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <form action="{{ route('citizen.road-assistance.store') }}" method="POST" class="space-y-5">
                @csrf
                
                <!-- Event Type -->
                <div>
                    <label for="event_type" class="block text-sm font-medium text-lgu-headline mb-1">Type of Assistance <span class="text-red-500">*</span></label>
                    <select name="event_type" id="event_type" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-button focus:border-transparent">
                        <option value="">Select type...</option>
                        @foreach($eventTypes as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Link to Booking (Optional) -->
                @if($upcomingBookings->count() > 0)
                <div>
                    <label for="booking_id" class="block text-sm font-medium text-lgu-headline mb-1">Related Booking (Optional)</label>
                    <select name="booking_id" id="booking_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-button focus:border-transparent" onchange="fillFromBooking(this)">
                        <option value="">Select a booking to auto-fill details...</option>
                        @foreach($upcomingBookings as $booking)
                            <option value="{{ $booking->id }}" 
                                    data-location="{{ $booking->facility_address }}"
                                    data-start="{{ \Carbon\Carbon::parse($booking->start_time)->format('Y-m-d') }}"
                                    data-start-time="{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}"
                                    data-end="{{ \Carbon\Carbon::parse($booking->end_time)->format('Y-m-d') }}"
                                    data-end-time="{{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}">
                                {{ $booking->facility_name }} - {{ \Carbon\Carbon::parse($booking->start_time)->format('M d, Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <!-- Date & Time Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-lgu-headline mb-1">Start Date <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" id="start_date" required min="{{ date('Y-m-d') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-button focus:border-transparent">
                    </div>
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-lgu-headline mb-1">Start Time <span class="text-red-500">*</span></label>
                        <input type="time" name="start_time" id="start_time" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-button focus:border-transparent">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-lgu-headline mb-1">End Date <span class="text-red-500">*</span></label>
                        <input type="date" name="end_date" id="end_date" required min="{{ date('Y-m-d') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-button focus:border-transparent">
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-lgu-headline mb-1">End Time <span class="text-red-500">*</span></label>
                        <input type="time" name="end_time" id="end_time" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-button focus:border-transparent">
                    </div>
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-medium text-lgu-headline mb-1">Location / Address <span class="text-red-500">*</span></label>
                    <input type="text" name="location" id="location" required maxlength="500" placeholder="Enter the street or area requiring assistance" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-button focus:border-transparent">
                </div>

                <!-- Landmark -->
                <div>
                    <label for="landmark" class="block text-sm font-medium text-lgu-headline mb-1">Nearby Landmark</label>
                    <input type="text" name="landmark" id="landmark" maxlength="255" placeholder="e.g., Near City Hall, beside the church" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-button focus:border-transparent">
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-lgu-headline mb-1">Description / Details <span class="text-red-500">*</span></label>
                    <textarea name="description" id="description" required rows="4" maxlength="2000" placeholder="Describe why you need road assistance, expected number of attendees, special requirements, etc." class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-button focus:border-transparent resize-none"></textarea>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end gap-3 pt-4 border-t">
                    <button type="button" onclick="closeRequestModal()" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="btn-primary">
                        <i data-lucide="send" class="w-4 h-4 mr-2 inline"></i>
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openRequestModal() {
        document.getElementById('requestModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        lucide.createIcons();
    }

    function closeRequestModal() {
        document.getElementById('requestModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function fillFromBooking(select) {
        const option = select.options[select.selectedIndex];
        if (option.value) {
            document.getElementById('location').value = option.dataset.location || '';
            document.getElementById('start_date').value = option.dataset.start || '';
            document.getElementById('start_time').value = option.dataset.startTime || '';
            document.getElementById('end_date').value = option.dataset.end || '';
            document.getElementById('end_time').value = option.dataset.endTime || '';
        }
    }

    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeRequestModal();
        }
    });
</script>
@endpush
@endsection
