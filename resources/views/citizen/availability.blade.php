@extends('citizen.layouts.app-sidebar')

@section('title', 'Facility Availability - LGU1 Citizen Portal')
@section('page-title', 'Facility Availability')
@section('page-description', 'View real-time facility availability and existing reservations')

@push('styles')
    {{-- FullCalendar CSS --}}
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
@endpush

@section('content')
<div class="space-y-6">
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between mb-6 border-b pb-4">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Real-Time Availability Calendar</h3>
                <p class="text-sm text-gray-600">Select a facility to view its booking schedule and availability</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                    <span class="text-sm text-gray-600">Booked</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-yellow-500 rounded mr-2"></div>
                    <span class="text-sm text-gray-600">Pending</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 bg-gray-400 rounded mr-2"></div>
                    <span class="text-sm text-gray-600">City Event</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <div class="lg:col-span-1 space-y-4">
                <button id="showAllBtn" class="w-full px-4 py-3 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-md">
                    <i class="fas fa-list-ul mr-2"></i> Show All Facility Bookings
                </button>
                
                <h4 class="text-md font-semibold text-gray-800 border-b pb-2">Facilities List</h4>
                
                <ul id="facilityList" class="space-y-2 max-h-80 overflow-y-auto">
                    {{-- Assume $facilities is passed from the controller --}}
                    @foreach($facilities as $facility)
                        <li data-id="{{ $facility->id }}" data-name="{{ $facility->name }}"
                            class="facility-item px-3 py-2 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors text-sm text-gray-700">
                            <i class="fas fa-chevron-right text-blue-500 mr-2"></i> {{ $facility->name }}
                        </li>
                    @endforeach
                </ul>

                <button id="aiRecommendBtn" class="w-full px-4 py-3 text-sm font-semibold text-blue-800 bg-blue-100 rounded-lg hover:bg-blue-200 transition-colors shadow-sm">
                    <i class="fas fa-robot mr-2"></i> Get AI Recommendation
                </button>
            </div>

            <div class="lg:col-span-3 bg-gray-50 p-4 border border-gray-200 rounded-lg">
                <div id="calendar" class="w-full"></div>
            </div>
        </div>
    </div>
</div>

<div id="eventModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-start border-b pb-3 mb-4">
            <h3 class="text-xl font-bold text-gray-900" id="modalTitle">Event Details</h3>
            <button id="closeModalBtn" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">
                &times;
            </button>
        </div>
        
        <div id="modalBody" class="space-y-3 text-gray-700">
            {{-- Event details will be populated here by JavaScript --}}
        </div>

        <div class="mt-4 pt-4 border-t flex justify-end">
            <button id="closeModal" class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300">
                Close
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const facilityListItems = document.querySelectorAll('.facility-item');
    const showAllBtn = document.getElementById('showAllBtn');
    const aiRecommendBtn = document.getElementById('aiRecommendBtn');
    const modalEl = document.getElementById('eventModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const closeModal = document.getElementById('closeModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalBody = document.getElementById('modalBody');
    
    let calendar;

    // --- CALENDAR INITIALIZATION ---
    function initializeCalendar() {
        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            editable: false,
            eventDidMount: function(info) {
                // Style event based on status/type (assuming status/type is passed in event object)
                let bgColor = '#9CA3AF'; // Default gray for unknown
                let borderColor = '#9CA3AF';
                
                if (info.event.extendedProps.status === 'approved') {
                    bgColor = '#10B981'; // Green for approved bookings
                    borderColor = '#059669';
                } else if (info.event.extendedProps.status === 'pending') {
                    bgColor = '#F59E0B'; // Yellow for pending bookings
                    borderColor = '#D97706';
                } else if (info.event.extendedProps.type === 'city_event') {
                    bgColor = '#6B7280'; // Darker gray for City Events
                    borderColor = '#4B5563';
                }
                
                info.el.style.backgroundColor = bgColor;
                info.el.style.borderColor = borderColor;
                info.el.style.color = '#fff';
            },
            eventClick: function(info) {
                // Show modal with event details
                modalTitle.textContent = info.event.title;
                modalBody.innerHTML = `
                    <p><strong>Facility:</strong> ${info.event.extendedProps.facilityName || 'N/A'}</p>
                    <p><strong>Start:</strong> ${info.event.start.toLocaleString()}</p>
                    <p><strong>End:</strong> ${info.event.end.toLocaleString()}</p>
                    <p><strong>Status:</strong> <span class="font-semibold text-sm capitalize">${info.event.extendedProps.status || 'N/A'}</span></p>
                    <p><strong>Type:</strong> <span class="font-semibold text-sm capitalize">${info.event.extendedProps.type || 'Booking'}</span></p>
                    ${info.event.extendedProps.user ? `<p><strong>Booked by:</strong> ${info.event.extendedProps.user}</p>` : ''}
                `;
                modalEl.classList.remove('hidden');
            }
        });
        calendar.render();
    }
    
    // --- EVENT FETCHING FUNCTIONS ---
    
    /**
     * Loads all bookings (approved, pending, city events) for all facilities.
     */
    function loadAllFacilityBookings() {
        // Highlight 'Show All' button
        facilityListItems.forEach(li => li.classList.remove('bg-blue-100', 'border-blue-400'));
        showAllBtn.classList.remove('bg-blue-700', 'bg-blue-600');
        showAllBtn.classList.add('bg-gray-400', 'hover:bg-gray-500');


        fetch('/citizen/api/all-facility-bookings') // Your API endpoint to get all bookings
            .then(response => response.json())
            .then(events => {
                calendar.removeAllEvents();
                // Map API events to FullCalendar format (e.g., set status/type in extendedProps)
                const formattedEvents = events.map(event => ({
                    title: event.title,
                    start: event.start_time,
                    end: event.end_time,
                    color: event.status === 'approved' ? '#10B981' : event.status === 'pending' ? '#F59E0B' : '#9CA3AF',
                    extendedProps: {
                        status: event.status,
                        type: event.type || 'Booking',
                        facilityName: event.facility ? event.facility.name : 'N/A',
                        user: event.user ? event.user.name : 'N/A',
                    }
                }));
                calendar.addEventSource({ events: formattedEvents });
                
                Swal.fire({
                    icon: 'success',
                    title: 'Viewing All Bookings',
                    text: 'Calendar updated with all facility and city event schedules.',
                    timer: 2000,
                    showConfirmButton: false
                });
            })
            .catch(error => {
                console.error('Error loading all facility bookings:', error);
                Swal.fire('Error', 'Failed to load all facility bookings.', 'error');
            });
    }

    /**
     * Loads bookings for a single facility.
     * @param {string} facilityId - The ID of the facility.
     * @param {string} facilityName - The name of the facility.
     */
    function loadSingleFacilityBookings(facilityId, facilityName) {
         // Highlight 'Show All' button
        showAllBtn.classList.remove('bg-gray-400', 'hover:bg-gray-500');
        showAllBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
        
        // Highlight selected facility
        facilityListItems.forEach(li => li.classList.remove('bg-blue-100', 'border-blue-400'));
        document.querySelector(`.facility-item[data-id="${facilityId}"]`).classList.add('bg-blue-100', 'border-blue-400');
        

        fetch(`/citizen/api/facility-bookings/${facilityId}`) // Your API endpoint for single facility bookings
            .then(response => response.json())
            .then(events => {
                calendar.removeAllEvents();
                const formattedEvents = events.map(event => ({
                    title: event.title,
                    start: event.start_time,
                    end: event.end_time,
                    color: event.status === 'approved' ? '#10B981' : event.status === 'pending' ? '#F59E0B' : '#9CA3AF',
                    extendedProps: {
                        status: event.status,
                        type: event.type || 'Booking',
                        facilityName: facilityName,
                        user: event.user ? event.user.name : 'N/A',
                    }
                }));
                calendar.addEventSource({ events: formattedEvents });
                
                Swal.fire({
                    icon: 'success',
                    title: 'Facility Selected',
                    text: `Now viewing bookings for: ${facilityName}`,
                    timer: 2000,
                    showConfirmButton: false
                });
            })
            .catch(error => {
                console.error('Error loading single facility bookings:', error);
                Swal.fire('Error', `Failed to load bookings for ${facilityName}.`, 'error');
            });
    }


    // --- EVENT LISTENERS ---
    
    // Show All Button
    showAllBtn.addEventListener('click', loadAllFacilityBookings);

    // Single facility selection
    facilityListItems.forEach(item => {
        item.addEventListener('click', function() {
            const facilityId = this.dataset.id;
            const facilityName = this.dataset.name;
            loadSingleFacilityBookings(facilityId, facilityName);
        });
    });
    
    // AI Recommendation Button
    if (aiRecommendBtn) {
        aiRecommendBtn.addEventListener('click', function() {
             // Simulate a call to the AI recommendation system
            Swal.fire({
                icon: 'info',
                title: 'AI Recommendations',
                text: 'AI-powered facility recommendations will be implemented here. (Feature coming soon!)',
                confirmButtonColor: '#3B82F6'
            });
        });
    }

    // Modal close handlers
    closeModalBtn.addEventListener('click', () => {
        modalEl.classList.add('hidden');
    });
    
    closeModal.addEventListener('click', () => {
        modalEl.classList.add('hidden');
    });
    
    // Close modal when clicking outside
    modalEl.addEventListener('click', (e) => {
        if (e.target === modalEl) {
            modalEl.classList.add('hidden');
        }
    });

    // --- INITIAL LOAD ---
    initializeCalendar();
    loadAllFacilityBookings(); // Load all bookings on page load

});
</script>
@endpush
@endsection