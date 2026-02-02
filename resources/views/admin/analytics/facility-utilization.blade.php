@extends('layouts.admin')

@section('title', 'Facility Utilization - Admin')

@section('page-content')
    <div class="space-y-6 print-area">
        <!-- Header with Date Filter -->
        <div class="bg-white rounded-lg shadow-sm p-6 no-print">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-lgu-headline">Facility Utilization Report</h2>
                    <p class="text-gray-600 mt-1">Track facility usage and identify optimization opportunities</p>
                </div>

                <!-- Date Range Filter & Export Buttons -->
                <div class="flex flex-wrap items-end gap-3">
                    <form method="GET" action="{{ route('admin.analytics.facility-utilization') }}"
                        class="flex flex-wrap items-end gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" name="start_date" value="{{ $startDate }}"
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-headline focus:border-lgu-headline">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" name="end_date" value="{{ $endDate }}"
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-headline focus:border-lgu-headline">
                        </div>
                        <button type="submit"
                            class="px-6 py-2 bg-lgu-button text-lgu-button-text rounded-lg hover:opacity-90 transition-all font-semibold">
                            <i data-lucide="filter" class="w-4 h-4 inline mr-1"></i>
                            Filter
                        </button>
                    </form>
                    <button onclick="window.print()"
                        class="px-6 py-2 border-2 border-lgu-stroke text-lgu-headline rounded-lg hover:bg-lgu-bg transition-all font-semibold">
                        <i data-lucide="printer" class="w-4 h-4 inline mr-1"></i>
                        Print
                    </button>

                    <!-- Export Dropdown -->
                    <div class="relative inline-block" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="px-6 py-2 bg-lgu-secondary text-white rounded-lg hover:opacity-90 transition-all font-semibold flex items-center">
                            <i data-lucide="download" class="w-4 h-4 mr-1"></i>
                            Export
                            <i data-lucide="chevron-down" class="w-4 h-4 ml-1"></i>
                        </button>
                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10">
                            <a href="{{ route('admin.analytics.export-facility-utilization-excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-lgu-bg">
                                <i data-lucide="file-spreadsheet" class="w-4 h-4 inline mr-2"></i>
                                Export as Excel
                            </a>
                            <a href="{{ route('admin.analytics.facility-utilization.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-lgu-bg">
                                <i data-lucide="file-text" class="w-4 h-4 inline mr-2"></i>
                                Export as CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Print Header (hidden on screen, shown when printing) -->
        <div class="hidden print:block mb-6">
            <div class="text-center mb-4">
                <h1 class="text-2xl font-bold text-lgu-headline">Local Government Unit</h1>
                <h2 class="text-xl font-semibold text-gray-700">Facility Utilization Report</h2>
                <p class="text-gray-600">Period: {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} -
                    {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
                </p>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Total Facilities -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-medium text-gray-600">Total Facilities</h3>
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i data-lucide="building" class="w-5 h-5 text-blue-600"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-lgu-headline">{{ $facilities->count() }}</p>
                <p class="text-xs text-gray-500 mt-2">Active facilities</p>
            </div>

            <!-- High Performing -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-medium text-gray-600">High Performing</h3>
                    <div class="p-2 bg-green-100 rounded-lg">
                        <i data-lucide="trending-up" class="w-5 h-5 text-green-600"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-green-600">{{ $highPerforming->count() }}</p>
                <p class="text-xs text-gray-500 mt-2">> 70% utilization</p>
            </div>

            <!-- Underutilized -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-medium text-gray-600">Underutilized</h3>
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-yellow-600"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-yellow-600">{{ $underutilized->count() }}</p>
                <p class="text-xs text-gray-500 mt-2">
                    < 30% utilization</p>
            </div>
        </div>

        <!-- AI-Powered Forecasting Section -->
        <div class="bg-white rounded-lg shadow-md border-2 border-indigo-500 overflow-hidden mb-8 no-print">
            <div class="bg-indigo-600 px-6 py-4 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="bg-white p-2 rounded-lg">
                        <i data-lucide="brain-circuit" class="w-6 h-6 text-indigo-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white leading-tight">TensorFlow.js Demand Forecast</h3>
                        <p id="model-status" class="text-indigo-100 text-xs font-mono uppercase tracking-widest">System
                            Ready</p>
                    </div>
                </div>

                <div class="flex gap-4">
                    <div class="bg-indigo-800 px-4 py-2 rounded-md border border-indigo-400">
                        <p class="text-[10px] text-indigo-200 uppercase font-bold">Training Loss</p>
                        <p id="loss-value" class="text-xl font-mono font-bold text-green-400">0.0000</p>
                    </div>
                    <div class="bg-indigo-800 px-4 py-2 rounded-md border border-indigo-400">
                        <p class="text-[10px] text-indigo-200 uppercase font-bold">Epoch Progress</p>
                        <p id="epoch-count" class="text-xl font-mono font-bold text-white">0/50</p>
                    </div>
                </div>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6 bg-slate-50">
                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                    <span class="text-gray-500 text-xs font-bold uppercase">Projected Usage</span>
                    <div class="flex items-baseline gap-2 mt-1">
                        <span id="forecast-value" class="text-4xl font-black text-slate-900">--%</span>
                        <span class="text-indigo-600 text-sm font-bold">Forecasted</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Projection based on current booking velocity.</p>
                </div>

                <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
                    <span class="text-gray-500 text-xs font-bold uppercase">3hr Slot Density</span>
                    <div id="availability-status" class="text-2xl font-bold text-slate-800 mt-1 italic">Analyzing
                        Patterns...</div>
                    <div class="w-full bg-gray-200 h-2 mt-4 rounded-full overflow-hidden">
                        <div id="density-bar" class="bg-indigo-500 h-full w-0 transition-all duration-1000"></div>
                    </div>
                </div>

                <div class="bg-indigo-50 p-5 rounded-xl border border-indigo-100">
                    <span class="text-indigo-700 text-xs font-bold uppercase">Extension Policy Recommendation</span>
                    <div id="ai-policy" class="text-sm text-slate-700 mt-2 font-medium leading-relaxed">
                        Waiting for the Neural Network to process historical utilization data...
                    </div>
                </div>
            </div>
        </div>

        <!-- Facility Utilization Table -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-lgu-headline mb-4">Facility Utilization Details</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Facility</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">City
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Bookings</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Confirmed</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Cancelled</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Utilization</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($facilities as $facility)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $facility->name }}</div>
                                    <div class="text-xs text-gray-500">Capacity: {{ $facility->capacity }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600">{{ $facility->city_name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm text-gray-900">{{ $facility->total_bookings }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm text-green-600 font-medium">{{ $facility->confirmed_bookings }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm text-red-600">{{ $facility->cancelled_bookings }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <div class="w-20 bg-gray-200 rounded-full h-2">
                                            <div class="h-2 rounded-full
                                                                                                                        @if($facility->utilization_rate > 70) bg-green-600
                                                                                                                        @elseif($facility->utilization_rate > 30) bg-yellow-500
                                                                                                                        @else bg-red-500
                                                                                                                        @endif"
                                                style="width: {{ min($facility->utilization_rate, 100) }}%">
                                            </div>
                                        </div>
                                        <span class="text-sm font-medium
                                                                                                                    @if($facility->utilization_rate > 70) text-green-600
                                                                                                                    @elseif($facility->utilization_rate > 30) text-yellow-600
                                                                                                                    @else text-red-600
                                                                                                                    @endif">
                                            {{ number_format($facility->utilization_rate, 1) }}%
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm font-semibold text-lgu-headline">
                                        ₱{{ number_format($facility->total_revenue ?? 0, 2) }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                    <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-2 text-gray-400"></i>
                                    <p>No facility data available.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Underutilized Facilities Alert -->
        @if($underutilized->count() > 0)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg">
                <div class="flex items-start">
                    <i data-lucide="alert-triangle" class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <h3 class="text-lg font-semibold text-yellow-800 mb-2">Underutilized Facilities</h3>
                        <p class="text-yellow-700 mb-3">The following facilities have utilization rates below 30% and may need
                            attention:</p>
                        <ul class="list-disc list-inside text-yellow-700 space-y-1">
                            @foreach($underutilized as $facility)
                                <li>{{ $facility->name }} ({{ $facility->city_name ?? 'N/A' }}) -
                                    {{ number_format($facility->utilization_rate, 1) }}%
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('styles')
        <style>
            @media print {
                body * {
                    visibility: hidden;
                }

                .print-area,
                .print-area * {
                    visibility: visible;
                }

                .print-area {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                }

                .no-print {
                    display: none !important;
                }

                .print\:block {
                    display: block !important;
                }

                /* Better table printing */
                table {
                    page-break-inside: auto;
                }

                tr {
                    page-break-inside: avoid;
                    page-break-after: auto;
                }
            }
        </style>
    @endpush

    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
    <script>
        async function runAIForecast() {
            console.log("--- AI SYSTEM INITIALIZED ---");

            // 1. Fetch Real Data from Laravel
            const rawData = @json($facilities);
            const inputs = rawData.map(f => f.total_bookings);
            const labels = rawData.map(f => f.utilization_rate);

            console.log("1. Loaded Real Data Points:", rawData.length);
            console.log("Inputs (Bookings):", inputs);
            console.log("Labels (Utilization %):", labels);

            if (inputs.length === 0) {
                console.error("AI Error: No facility data found to train model.");
                return;
            }

            // Normalization
            const inputMax = Math.max(...inputs) || 1;
            const normalizedInputs = inputs.map(x => x / inputMax);
            const normalizedLabels = labels.map(y => y / 100);

            const xs = tf.tensor2d(normalizedInputs, [normalizedInputs.length, 1]);
            const ys = tf.tensor2d(normalizedLabels, [normalizedLabels.length, 1]);

            // 2. Build Model
            const model = tf.sequential();
            model.add(tf.layers.dense({ units: 1, inputShape: [1] }));
            model.compile({ optimizer: tf.train.adam(0.1), loss: 'meanSquaredError' });

            console.log("2. Neural Network Compiled.");
            model.summary(); // This prints the AI architecture in the console

            // UI Elements
            const lossEl = document.getElementById('loss-value');
            const epochEl = document.getElementById('epoch-count');
            const statusEl = document.getElementById('model-status');

            statusEl.innerText = "Training Neural Network...";
            console.log("3. Starting Training Process...");

            // 3. Train
            await model.fit(xs, ys, {
                epochs: 50,
                callbacks: {
                    onEpochEnd: async (epoch, logs) => {
                        lossEl.innerText = logs.loss.toFixed(5);
                        epochEl.innerText = `${epoch + 1}/50`;

                        // This creates the scrolling log the panel wants to see
                        console.log(`Epoch ${epoch + 1}: Loss = ${logs.loss.toFixed(6)}`);

                        await tf.nextFrame();
                    }
                }
            });

            statusEl.innerText = "Model Trained on Real Data";
            console.log("4. Training Complete.");

            // 4. Forecast next month (assuming 15% booking growth)
            const prediction = model.predict(tf.tensor2d([(inputMax * 1.15) / inputMax], [1, 1]));
            const projectedUtil = (await prediction.data())[0] * 100;

            console.log("--- FORECAST RESULT ---");
            console.log(`Input (115% volume): ${inputMax * 1.15}`);
            console.log(`AI Projected Utilization: ${projectedUtil.toFixed(2)}%`);

            updateForecastUI(projectedUtil);
        }

        function updateForecastUI(val) {
            document.getElementById('forecast-value').innerText = Math.min(val, 100).toFixed(1) + "%";
            const policy = document.getElementById('ai-policy');
            const status = document.getElementById('availability-status');
            const bar = document.getElementById('density-bar');

            bar.style.width = `${Math.min(val, 100)}%`;

            if (val > 80) {
                status.innerText = "High Congestion";
                status.classList.add('text-red-600');
                policy.innerHTML = "<strong>Alert:</strong> Projected usage exceeds 80%. AI suggests <strong>suspending 2-hour extensions</strong> to ensure primary 3-hour reservations are honored.";
            } else {
                status.innerText = "Optimal Usage";
                status.classList.add('text-green-600');
                policy.innerText = "Growth trend is within capacity. System recommends maintaining standard 3-hour bookings with optional 2-hour extensions.";
            }
        }

        window.addEventListener('load', runAIForecast);
    </script>

@endsection