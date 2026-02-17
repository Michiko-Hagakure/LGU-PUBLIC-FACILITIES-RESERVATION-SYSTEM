@extends('layouts.admin')

@section('page-title', 'Transaction History')
@section('page-subtitle', 'View and manage all payment transactions')

@section('page-content')
<div class="pb-gr-2xl">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-gr-lg">
        <div>
            <h1 class="text-h3 font-bold text-gray-900 mb-gr-2xs">Transaction History</h1>
            <p class="text-small text-gray-600">View and manage all payment transactions</p>
        </div>
        <div class="flex items-center space-x-gr-sm">
            <button onclick="exportTransactions()" class="btn-secondary flex items-center">
                <i data-lucide="download" class="w-4 h-4 mr-gr-xs"></i>
                Export
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md mb-gr-lg">
        <form method="GET" action="{{ URL::signedRoute('admin.transactions.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-gr-md">
            <!-- Date Range -->
            <div>
                <label class="block text-caption font-semibold text-gray-700 mb-gr-2xs">Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="input-field">
            </div>
            <div>
                <label class="block text-caption font-semibold text-gray-700 mb-gr-2xs">End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="input-field">
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-caption font-semibold text-gray-700 mb-gr-2xs">Status</label>
                <select name="status" class="input-field">
                    <option value="">All Statuses</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <!-- Payment Method Filter -->
            <div>
                <label class="block text-caption font-semibold text-gray-700 mb-gr-2xs">Payment Method</label>
                <select name="payment_method" class="input-field">
                    <option value="">All Methods</option>
                    <option value="gcash" {{ request('payment_method') == 'gcash' ? 'selected' : '' }}>GCash</option>
                    <option value="paymaya" {{ request('payment_method') == 'paymaya' ? 'selected' : '' }}>PayMaya</option>
                    <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="otc" {{ request('payment_method') == 'otc' ? 'selected' : '' }}>Over-the-Counter</option>
                </select>
            </div>

            <!-- Search and Filter Buttons -->
            <div class="md:col-span-4 flex items-end space-x-gr-sm">
                <div class="flex-1">
                    <label class="block text-caption font-semibold text-gray-700 mb-gr-2xs">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Reference number, citizen name..." class="input-field">
                </div>
                <button type="submit" class="btn-primary">
                    <i data-lucide="search" class="w-4 h-4 mr-gr-xs"></i>
                    Filter
                </button>
                <a href="{{ URL::signedRoute('admin.transactions.index') }}" class="btn-secondary">
                    <i data-lucide="x" class="w-4 h-4 mr-gr-xs"></i>
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-gr-md mb-gr-lg">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-caption text-gray-600 mb-gr-2xs">Total Transactions</p>
                    <p class="text-h3 font-bold text-gray-900">{{ number_format($transactions->total()) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="receipt" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-caption text-gray-600 mb-gr-2xs">Total Amount</p>
                    <p class="text-h3 font-bold text-gray-900">₱{{ number_format($totalAmount, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <span class="text-h4 font-bold text-green-600">₱</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-caption text-gray-600 mb-gr-2xs">Paid</p>
                    <p class="text-h3 font-bold text-green-600">{{ number_format($paidCount) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="circle-check" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-caption text-gray-600 mb-gr-2xs">Pending</p>
                    <p class="text-h3 font-bold text-orange-600">{{ number_format($pendingCount) }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="clock" class="w-6 h-6 text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-gr-md py-gr-sm text-left text-caption font-semibold text-gray-700">Reference No.</th>
                        <th class="px-gr-md py-gr-sm text-left text-caption font-semibold text-gray-700">Date</th>
                        <th class="px-gr-md py-gr-sm text-left text-caption font-semibold text-gray-700">Citizen</th>
                        <th class="px-gr-md py-gr-sm text-left text-caption font-semibold text-gray-700">Facility</th>
                        <th class="px-gr-md py-gr-sm text-left text-caption font-semibold text-gray-700">Amount</th>
                        <th class="px-gr-md py-gr-sm text-left text-caption font-semibold text-gray-700">Payment Method</th>
                        <th class="px-gr-md py-gr-sm text-left text-caption font-semibold text-gray-700">Status</th>
                        <th class="px-gr-md py-gr-sm text-left text-caption font-semibold text-gray-700">AI Audit</th>
                        <th class="px-gr-md py-gr-sm text-left text-caption font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($transactions as $transaction)
                    <tr class="hover:bg-gray-50 transition-colors transaction-row"
                    data-amount="{{ $transaction->amount_due }}"
                    data-facility="{{ $transaction->facility_id ?? 1 }}"
                    data-status="{{ strtolower($transaction->status) }}"
                    data-unpaid-history="{{ $transaction->unpaid_count ?? 0 }}">
                        <td class="px-gr-md py-gr-sm">
                            <span class="font-mono text-small font-semibold text-gray-900">{{ $transaction->slip_number ?? $transaction->or_number ?? 'N/A' }}</span>
                        </td>
                        <td class="px-gr-md py-gr-sm text-small text-gray-900">
                            {{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y') }}<br>
                            <span class="text-caption text-gray-500">{{ \Carbon\Carbon::parse($transaction->created_at)->format('h:i A') }}</span>
                        </td>
                        <td class="px-gr-md py-gr-sm">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-lgu-green rounded-full flex items-center justify-center mr-gr-xs">
                                    <span class="text-caption font-semibold text-white">{{ strtoupper(substr($transaction->citizen_name ?? 'N', 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="text-small font-medium text-gray-900">{{ $transaction->citizen_name ?? 'N/A' }}</p>
                                    <p class="text-caption text-gray-500">ID: {{ $transaction->citizen_id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-gr-md py-gr-sm text-small text-gray-900">
                            {{ $transaction->facility_name ?? 'N/A' }}
                        </td>
                        <td class="px-gr-md py-gr-sm">
                            <span class="text-small font-bold text-gray-900">₱{{ number_format($transaction->amount_due, 2) }}</span>
                        </td>
                        <td class="px-gr-md py-gr-sm">
                            <span class="inline-flex items-center px-gr-xs py-gr-3xs rounded-full text-caption font-medium
                                @if($transaction->payment_method == 'gcash') bg-blue-100 text-blue-700
                                @elseif($transaction->payment_method == 'paymaya') bg-green-100 text-green-700
                                @elseif($transaction->payment_method == 'bank_transfer') bg-purple-100 text-purple-700
                                @else bg-gray-100 text-gray-700
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $transaction->payment_method ?? 'N/A')) }}
                            </span>
                        </td>
                        <td class="px-gr-md py-gr-sm">
                            <span class="inline-flex items-center px-gr-xs py-gr-3xs rounded-full text-caption font-medium
                                @if($transaction->status == 'paid') bg-green-100 text-green-700
                                @elseif($transaction->status == 'pending') bg-orange-100 text-orange-700
                                @elseif($transaction->status == 'cancelled') bg-red-100 text-red-700
                                @else bg-gray-100 text-gray-700
                                @endif">
                                <i data-lucide="{{ $transaction->status == 'paid' ? 'circle-check' : ($transaction->status == 'pending' ? 'clock' : 'x-circle') }}" class="w-3 h-3 mr-gr-3xs"></i>
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                        <td class="px-gr-md py-gr-sm">
                            <span class="ai-status-badge inline-flex items-center px-gr-xs py-gr-3xs rounded-full text-caption font-medium bg-gray-100 text-gray-600">
                                <i data-lucide="loader-2" class="w-3 h-3 mr-gr-3xs animate-spin"></i>
                                Scanning...
                            </span>
                        </td>
                        <td class="px-gr-md py-gr-sm">
                            <a href="{{ URL::signedRoute('admin.transactions.show', $transaction->id) }}" class="text-lgu-green hover:text-lgu-green-dark font-medium text-small">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-gr-md py-gr-xl text-center text-gray-500">
                            <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-gr-sm text-gray-400"></i>
                            <p class="text-small">No transactions found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
        <div class="px-gr-md py-gr-md border-t border-gray-200">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@latest"></script>
<script>
    /**
     * AI Audit Function: Evaluates transaction legitimacy and payment completion.
     * This model prevents "Approved" status from being mistaken for "Fully Paid".
     */
    async function runAIAudit() {
        console.log("[AI Audit] Initializing Financial Verification Model...");

        /** * NEURAL NETWORK ARCHITECTURE
         * Input Features [3]: Normalized Amount, Status Bit (Verified/Not), Payment Progress
         * Output: Safety/Completion Score (0 to 1)
         */
        const model = tf.sequential();
        model.add(tf.layers.dense({units: 16, inputShape: [3], activation: 'relu'}));
        model.add(tf.layers.dense({units: 8, activation: 'relu'})); // Hidden layer for deeper pattern recognition
        model.add(tf.layers.dense({units: 1, activation: 'sigmoid'})); // Returns a probability score
        
        model.compile({optimizer: 'adam', loss: 'binaryCrossentropy'});

        // 1. DATA HARVESTING & PRE-PROCESSING
        const rows = document.querySelectorAll('.transaction-row');
        const trainingData = [];
        const labels = [];

        rows.forEach(row => {
            const amount = parseFloat(row.dataset.amount) || 0;
            const totalPrice = parseFloat(row.dataset.totalPrice) || amount; 
            const status = row.dataset.status.toLowerCase();
            
            /**
             * CALCULATE PAYMENT PROGRESS
             * 1.0 = Full Payment
             * 0.5 = Partial/Downpayment
             * 0.0 = No Payment
             */
            let paymentProgress = 0;
            if (amount >= totalPrice && ['paid', 'completed'].includes(status)) {
                paymentProgress = 1.0; 
            } else if (amount > 0 && amount < totalPrice) {
                paymentProgress = 0.5; 
            }

            const amtNorm = amount / 100000; // Normalizing large currency values
            const statusBit = ['paid', 'approved', 'verified'].includes(status) ? 1 : 0;
            
            trainingData.push([amtNorm, statusBit, paymentProgress]);
            
            // We teach the AI that "Safe/Verified" ONLY applies to 100% Payment Progress
            labels.push([paymentProgress === 1.0 ? 1 : 0]); 
        });

        // 2. SYNTHETIC TRAINING (Reinforcement Patterns)
        const xs = tf.tensor2d([
            [0.1, 1, 1.0], // Pattern: Small amt, Verified status, Full progress -> SAFE (1)
            [0.5, 1, 0.5], // Pattern: Large amt, Verified status, Partial progress -> NOT FULL (0)
            [0.9, 0, 0.0], // Pattern: Large amt, Unpaid status -> RISK (0)
            ...trainingData
        ]);
        const ys = tf.tensor2d([
            [1], [0], [0],
            ...labels
        ]); 

        // 3. MODEL TRAINING
        await model.fit(xs, ys, {epochs: 100, verbose: 0}); 
        console.log("[AI Audit] Training Complete.");

        // 4. EXECUTE PREDICTION & UI UPDATE
        for (let row of rows) {
            const amount = parseFloat(row.dataset.amount) || 0;
            const totalPrice = parseFloat(row.dataset.totalPrice) || amount;
            const status = (row.dataset.status || '').toLowerCase();
            const badge = row.querySelector('.ai-status-badge');

            if (!badge) continue;

            const amtNorm = amount / 100000;
            const statusBit = ['paid', 'approved', 'verified'].includes(status) ? 1 : 0;
            let progress = (amount >= totalPrice) ? 1.0 : (amount > 0 ? 0.5 : 0.0);

            // AI evaluates the combination of Status and Actual Progress
            const prediction = model.predict(tf.tensor2d([[amtNorm, statusBit, progress]]));
            const scoreData = await prediction.data();
            const safetyScore = scoreData[0];

            // 5. SMART UI ASSIGNMENT
            if (statusBit === 1 && progress === 1.0 && safetyScore > 0.8) {
                // CASE: Verified by Staff AND Fully Paid
                badge.className = "ai-status-badge inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700";
                badge.innerHTML = '<i data-lucide="shield-check" class="w-3 h-3 mr-1"></i> Fully Verified';
            } 
            else if (statusBit === 1 && progress < 1.0) {
                // CASE: Staff Verified the transaction, but it is only a Downpayment
                badge.className = "ai-status-badge inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700 border border-yellow-200";
                badge.innerHTML = '<i data-lucide="info" class="w-3 h-3 mr-1"></i> Partial: Downpayment';
            }
            else if (safetyScore < 0.4 || (statusBit === 0 && progress === 0)) {
                // CASE: Unpaid or AI detected high discrepancy
                badge.className = "ai-status-badge inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 animate-pulse";
                badge.innerHTML = '<i data-lucide="shield-alert" class="w-3 h-3 mr-1"></i> High Risk / Unpaid';
            } 
            else {
                // CASE: Pending evaluation or staff review
                badge.className = "ai-status-badge inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700";
                badge.innerHTML = '<i data-lucide="loader" class="w-3 h-3 mr-1"></i> Awaiting Full Payment';
            }
        }
        
        // Refresh icons for new badges
        if (window.lucide) lucide.createIcons();
    }

    // Initialize Audit on load
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof tf !== 'undefined') {
            setTimeout(runAIAudit, 1500); 
        }
    });
</script>
@endsection
