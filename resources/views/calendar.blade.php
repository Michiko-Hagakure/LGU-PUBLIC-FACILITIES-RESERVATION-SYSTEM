@extends('layouts.app')

@section('title', 'Facility Calendar - Reservation System')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <div class="bg-lgu-headline rounded-2xl p-8 text-white shadow-lgu-lg overflow-hidden relative">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
                <pattern id="pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                    <circle cx="10" cy="10" r="1" fill="currentColor"/>
                </pattern>
                <rect width="100%" height="100%" fill="url(#pattern)"/>
            </svg>
        </div>

        <div class="relative z-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-16 h-16 bg-lgu-highlight/20 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                        <svg class="w-8 h-8 text-lgu-highlight" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold mb-1 text-white">Facility Reservation Calendar</h1>
                        <p class="text-gray-200">View approved bookings to check availability.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        <div class="lg:col-span-1 bg-white rounded-lg shadow-md border border-gray-200 p-4 h-full">
            <div class="flex justify-between items-center mb-4 border-b pb-3">
                <h2 class="text-lg font-semibold text-gray-800">Filter Facilities</h2>
                <button id="viewAllBtn" class="text-sm px-3 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition hidden">View All</button>
            </div>
            
            <ul id="facility-selector" class="space-y-2">
                @if(isset($facilities))
                    @foreach($facilities as $facility)
                        <li class="facility-list-item cursor-pointer p-3 rounded-lg border border-gray-100 hover:bg-gray-100 transition"
                            data-id="{{ $facility->facility_id }}"
                            data-name="{{ $facility->name }}">
                            <span class="font-medium text-gray-900">{{ $facility->name }}</span>
                            <span class="block text-xs text-gray-500">{{ $facility->location }}</span>
                        </li>
                    @endforeach
                @else
                    <li class="text-gray-500 text-sm">No facilities available.</li>
                @endif
            </ul>
        </div>

        <div class="lg:col-span-3 bg-white rounded-lg shadow-md border border-gray-200 p-6">
            <div id='calendar'></div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    {{-- FullCalendar Core and DayGrid CSS --}}
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/main.min.css' rel='stylesheet' />
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- FullCalendar Core and Interaction JS --}}
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const viewAllBtn = document.getElementById('viewAllBtn');
            const facilityListItems = document.querySelectorAll('.facility-list-item');
            
            // --- 1. Initialize FullCalendar ---
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay' // Added common time views
                },
                slotMinTime: '08:00:00', // Start view at 8 AM
                slotMaxTime: '20:00:00', // End view at 8 PM
                editable: false, // Reservations should not be editable by default on this view
                eventDisplay: 'block',
                eventDidMount: function(info) {
                    // Custom tooltip or styling based on facility if needed
                    info.el.title = `${info.event.title} - ${info.event.extendedProps.facilityName || 'N/A'}`;
                },
                // Add event click handler to show details in a popup (optional)
                eventClick: function(info) {
                    Swal.fire({
                        title: info.event.title,
                        html: `
                            <p><strong>Facility:</strong> ${info.event.extendedProps.facilityName || 'N/A'}</p>
                            <p><strong>Time:</strong> ${info.event.start.toLocaleTimeString()} - ${info.event.end.toLocaleTimeString()}</p>
                            <p><strong>Purpose:</strong> ${info.event.extendedProps.purpose || 'N/A'}</p>
                            <p class="mt-2 text-sm text-gray-500">Note: Approved bookings are displayed.</p>
                        `,
                        icon: 'info',
                        confirmButtonText: 'Close'
                    });
                }
            });

            // --- 2. Event Fetching Logic ---
            /**
             * Fetches and displays all approved events.
             * @returns {void}
             */
            function loadAllEvents() {
                // Fetch all approved bookings (assuming this is the admin route)
                fetch('{{ route('events.all') }}') 
                    .then(response => response.json())
                    .then(events => {
                        calendar.removeAllEvents();
                        calendar.addEventSource({ events: events, id: 'allEvents' }); // Assign an ID for easier management
                        calendar.render();
                    })
                    .catch(error => {
                        console.error('Error fetching all events:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to load all facility events.'
                        });
                    });
            }

            // --- 3. Facility Filtering Logic ---
            
            // Single facility selection (refactored for cleaner logic)
            facilityListItems.forEach(item => {
                item.addEventListener('click', function() {
                    const facilityId = this.dataset.id;
                    const facilityName = this.dataset.name;
                    
                    // Fetch events for single facility (assuming /api/facilities/{id}/events endpoint)
                    fetch(`/api/facilities/${facilityId}/events`) 
                        .then(response => {
                            if (!response.ok) throw new Error('Network response was not ok.');
                            return response.json();
                        })
                        .then(events => {
                            calendar.removeAllEvents(); // Clear existing events
                            calendar.addEventSource({ events: events, id: `facility-${facilityId}` });
                            calendar.render();
                            
                            // Highlight selected facility and show 'View All' button
                            facilityListItems.forEach(li => li.classList.remove('bg-blue-100', 'border-blue-400'));
                            this.classList.add('bg-blue-100', 'border-blue-400'); // Changed color from purple to blue for consistency
                            viewAllBtn.classList.remove('hidden');

                            Swal.fire({
                                icon: 'success',
                                title: 'Facility Selected',
                                text: `Now viewing bookings for ${facilityName}`,
                                timer: 1500,
                                showConfirmButton: false
                            });
                        })
                        .catch(error => {
                            console.error(`Error fetching events for facility ${facilityId}:`, error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: `Failed to load events for ${facilityName}.`
                            });
                        });
                });
            });

            // View All Button handler
            viewAllBtn.addEventListener('click', function() {
                loadAllEvents();
                viewAllBtn.classList.add('hidden');
                facilityListItems.forEach(li => li.classList.remove('bg-blue-100', 'border-blue-400'));
                Swal.fire({
                    icon: 'info',
                    title: 'Viewing All Facilities',
                    text: 'Calendar is now displaying all approved bookings.',
                    timer: 1500,
                    showConfirmButton: false
                });
            });
            
            // --- 4. Initial Load ---
            loadAllEvents(); 
        });
    </script>
@endpush