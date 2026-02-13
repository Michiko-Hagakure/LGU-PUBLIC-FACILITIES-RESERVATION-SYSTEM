@extends('layouts.admin')

@section('page-title', 'Facility Site Selection')
@section('page-subtitle', 'Find suitable locations for new facility development')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #siteMap { height: 500px; border-radius: 0.75rem; }
    .facility-marker { background: #10b981; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3); }
    .legend-item { display: flex; align-items: center; gap: 8px; padding: 4px 0; }
    .legend-color { width: 18px; height: 18px; border-radius: 4px; border: 1px solid #d1d5db; flex-shrink: 0; }
    .zone-stat-bar { height: 8px; border-radius: 9999px; transition: width 0.5s ease; }
    .tab-btn { padding: 6px 16px; font-size: 0.8rem; font-weight: 600; border-radius: 9999px; cursor: pointer; transition: all 0.2s ease; border: 1px solid transparent; }
    .tab-btn.active { background: #667eea; color: white; border-color: #667eea; }
    .tab-btn:not(.active) { background: #f3f4f6; color: #6b7280; border-color: #e5e7eb; }
    .tab-btn:not(.active):hover { background: #e5e7eb; }
    .parcel-popup strong { color: #1e3a5f; }
    .parcel-popup .popup-zone { font-size: 0.75rem; padding: 2px 6px; border-radius: 4px; display: inline-block; margin-top: 4px; }
</style>
@endpush

@section('page-content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Left Panel: Filters and Site List --}}
    <div class="lg:col-span-1 space-y-6">
        {{-- Integration Info --}}
        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-start gap-3">
                <i data-lucide="map-pin" class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5"></i>
                <div>
                    <p class="text-blue-800 font-medium">Urban Planning Integration</p>
                    <p class="text-blue-700 text-sm mt-1">View zoning maps and parcel data from the Urban Planning system. Select a barangay to load zoning information.</p>
                </div>
            </div>
        </div>

        {{-- Zoning Map Overlay --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-4 py-3">
                <h3 class="text-white font-semibold flex items-center gap-2">
                    <i data-lucide="layers" class="w-4 h-4"></i>
                    Zoning Map Overlay
                </h3>
            </div>
            <div class="p-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Barangay (Zoning Map)</label>
                    <select id="zoningBarangaySelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                        <option value="">Select Barangay to View Zoning</option>
                        @foreach($zoningBarangays as $brgy)
                        <option value="{{ $brgy['id'] }}">{{ $brgy['barangay_name'] ?? $brgy['name'] ?? 'Barangay ' . $brgy['id'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" id="loadZoningBtn" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors flex items-center justify-center gap-2 text-sm">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                        Load Zoning Data
                    </button>
                    <button type="button" id="clearZoningBtn" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors text-sm">
                        Clear
                    </button>
                </div>

                {{-- Zone Statistics --}}
                <div id="zoneStatsPanel" class="hidden">
                    <h4 class="text-sm font-semibold text-gray-800 mb-2 flex items-center gap-1">
                        <i data-lucide="bar-chart-3" class="w-4 h-4"></i>
                        Zone Distribution
                    </h4>
                    <div id="zoneStatsList" class="space-y-2"></div>
                </div>
            </div>
        </div>

        {{-- Zoning Legend --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-4 py-3 flex items-center justify-between">
                <h3 class="text-white font-semibold flex items-center gap-2">
                    <i data-lucide="palette" class="w-4 h-4"></i>
                    Zoning Legend
                </h3>
                <button type="button" id="toggleLegendBtn" class="text-white/80 hover:text-white text-xs underline">Toggle</button>
            </div>
            <div id="zoningLegendPanel" class="p-4">
                @if(count($zoningLegend) > 0)
                <div class="grid grid-cols-1 gap-1">
                    @foreach($zoningLegend as $zone)
                    <div class="legend-item">
                        <div class="legend-color" style="background: {{ $zone['color'] }};"></div>
                        <div class="text-xs">
                            <span class="font-semibold text-gray-800">{{ $zone['code'] }}</span>
                            <span class="text-gray-500">- {{ $zone['name'] }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500 text-sm text-center py-2">Zoning legend will load from the Urban Planning API</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Right Panel: Map and Details --}}
    <div class="lg:col-span-2 space-y-6">
        {{-- Map --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke px-4 py-3 flex items-center justify-between">
                <h3 class="text-white font-semibold flex items-center gap-2">
                    <i data-lucide="map" class="w-4 h-4"></i>
                    Site Map
                </h3>
                <div class="flex items-center gap-4 text-white text-xs">
                    <span class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded-full bg-green-500"></span> Existing Facilities
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded-full" style="background: linear-gradient(135deg, #90EE90, #FFD700, #9370DB);"></span> Zoning Parcels
                    </span>
                </div>
            </div>
            <div id="siteMap"></div>
        </div>

        {{-- Existing Facilities Reference --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke px-4 py-3">
                <h3 class="text-white font-semibold flex items-center gap-2">
                    <i data-lucide="building" class="w-4 h-4"></i>
                    Existing Facilities ({{ count($facilities) }})
                </h3>
            </div>
            <div class="p-4">
                @if(count($facilities) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($facilities as $facility)
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="font-medium text-gray-900">{{ $facility->name }}</p>
                        <p class="text-sm text-gray-500">{{ $facility->address ?? $facility->full_address ?? 'No address' }}</p>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500 text-center py-4">No existing facilities found</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map centered on Quezon City
    const map = L.map('siteMap').setView([14.6760, 121.0437], 12);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Layer groups
    const facilitiesLayer = L.layerGroup().addTo(map);
    const zoningParcelsLayer = L.layerGroup().addTo(map);

    // Data from PHP
    const facilities = @json($facilities);
    const zoningBarangays = @json($zoningBarangays);
    const zoningLegend = @json($zoningLegend);

    // Add existing facilities to map
    facilities.forEach(facility => {
        if (facility.latitude && facility.longitude) {
            const marker = L.circleMarker([facility.latitude, facility.longitude], {
                radius: 8,
                fillColor: '#10b981',
                color: '#fff',
                weight: 2,
                opacity: 1,
                fillOpacity: 0.8
            }).addTo(facilitiesLayer);
            
            marker.bindPopup(`<strong>${facility.name}</strong><br>${facility.address || 'No address'}`);
        }
    });

    // ==========================================
    // Zoning Map Overlay Integration
    // ==========================================

    // Load zoning data for selected barangay
    document.getElementById('loadZoningBtn').addEventListener('click', async function() {
        const barangayId = document.getElementById('zoningBarangaySelect').value;
        if (!barangayId) {
            alert('Please select a barangay first');
            return;
        }

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Loading...';

        try {
            const response = await fetch(`{{ route('admin.facility-site-selection.zoning-data') }}?barangay_id=${barangayId}`);
            const result = await response.json();

            if (result.success) {
                displayZoningParcels(result.parcels || [], result.barangay, result.zones || []);
            } else {
                alert(result.message || 'Failed to load zoning data');
            }
        } catch (error) {
            console.error('Zoning data load error:', error);
            alert('An error occurred while loading zoning data');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i data-lucide="map-pin" class="w-4 h-4"></i> Load Zoning Data';
            if (typeof lucide !== 'undefined') lucide.createIcons();
        }
    });

    // Clear zoning overlay
    document.getElementById('clearZoningBtn').addEventListener('click', function() {
        zoningParcelsLayer.clearLayers();
        document.getElementById('zoneStatsPanel').classList.add('hidden');
        document.getElementById('zoneStatsList').innerHTML = '';
    });

    // Toggle legend visibility
    document.getElementById('toggleLegendBtn').addEventListener('click', function() {
        const panel = document.getElementById('zoningLegendPanel');
        panel.classList.toggle('hidden');
    });

    // Display zoning parcels on the map
    function displayZoningParcels(parcels, barangay, zones) {
        zoningParcelsLayer.clearLayers();

        if (barangay && barangay.center) {
            map.setView([barangay.center.lat, barangay.center.lng], 15);
        }

        parcels.forEach(parcel => {
            if (parcel.coordinates && parcel.coordinates.lat && parcel.coordinates.lng) {
                const circle = L.circle([parcel.coordinates.lat, parcel.coordinates.lng], {
                    color: parcel.zone?.color || '#667eea',
                    fillColor: parcel.zone?.color || '#667eea',
                    fillOpacity: 0.55,
                    radius: 100,
                    weight: 2
                }).addTo(zoningParcelsLayer);

                circle.bindPopup(`
                    <div class="parcel-popup">
                        <strong>${parcel.zone?.code || 'N/A'}</strong> - ${parcel.zone?.name || 'Unknown Zone'}<br>
                        <span style="font-size:0.85em;">${parcel.address || 'No address'}</span><br>
                        <span style="font-size:0.85em;">Lot Size: ${parcel.lot_size || 'N/A'} sqm</span>
                        <div class="popup-zone" style="background:${parcel.zone?.color || '#eee'}40; color:${parcel.zone?.color || '#333'};">
                            ${parcel.zone?.code || ''}
                        </div>
                    </div>
                `);

                // Click parcel to show popup
                circle.on('click', function() {
                    circle.openPopup();
                });
            }
        });

        // Display zone statistics
        displayZoneStats(zones);
    }

    // Display zone distribution stats
    function displayZoneStats(zones) {
        const statsPanel = document.getElementById('zoneStatsPanel');
        const statsList = document.getElementById('zoneStatsList');

        if (!zones || zones.length === 0) {
            statsPanel.classList.add('hidden');
            return;
        }

        statsPanel.classList.remove('hidden');
        const totalParcels = zones.reduce((sum, z) => sum + (z.count || 0), 0);

        let html = '';
        zones.forEach(zone => {
            const pct = totalParcels > 0 ? ((zone.count / totalParcels) * 100).toFixed(1) : 0;
            html += `
                <div class="flex items-center gap-2">
                    <div class="legend-color" style="background:${zone.color}; width:14px; height:14px;"></div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-medium text-gray-800">${zone.code}</span>
                            <span class="text-gray-500">${zone.count} parcels (${pct}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                            <div class="zone-stat-bar" style="width:${pct}%; background:${zone.color};"></div>
                        </div>
                    </div>
                </div>`;
        });

        html += `<p class="text-xs text-gray-500 mt-2 pt-2 border-t border-gray-100">Total: ${totalParcels} parcels across ${zones.length} zone types</p>`;
        statsList.innerHTML = html;
    }
});
</script>
@endpush
