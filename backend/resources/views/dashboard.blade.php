@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="space-y-6">
        @if($recentHighRisk->count() > 0 || $recentFraud->count() > 0)
            <div class="bg-red-500/10 border border-red-500/50 rounded-xl p-4">
                <div class="flex items-center space-x-2 mb-2">
                    <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                    <h3 class="text-lg font-semibold text-red-400">Recent Alerts (Last 24 Hours)</h3>
                </div>
                @if($recentHighRisk->count() > 0)
                    <div class="mb-3">
                        <p class="text-sm text-slate-600 dark:text-slate-300 mb-2">High Risk Transactions (>0.7):</p>
                        @foreach($recentHighRisk as $txn)
                            <div class="text-xs text-red-300">• {{ $txn->transaction_id }} - Risk Score: {{ number_format($txn->risk_score, 2) }}</div>
                        @endforeach
                    </div>
                @endif
                @if($recentFraud->count() > 0)
                    <div>
                        <p class="text-sm text-slate-600 dark:text-slate-300 mb-2">Detected Fraud:</p>
                        @foreach($recentFraud as $txn)
                            <div class="text-xs text-red-300">• {{ $txn->transaction_id }} - ${{ number_format($txn->Amount, 2) }}</div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
        
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Dashboard</h1>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-green-400 text-sm">Live</span>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-slate-800/50 backdrop-blur-lg border border-gray-200 dark:border-slate-700 rounded-xl p-6 hover:border-purple-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-purple-500/10">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-600 dark:text-slate-300">Total Transactions</h3>
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-bold text-slate-900 dark:text-white">{{ $totalTransactions }}</p>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">All time</p>
            </div>
            
            <div class="bg-slate-800/50 dark:bg-slate-800/50 backdrop-blur-lg border border-slate-700 dark:border-slate-700 rounded-xl p-6 hover:border-red-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-red-500/10">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-600 dark:text-slate-300">Fraudulent</h3>
                    <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-orange-500 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-bold text-red-400">{{ $fraudulentTransactions }}</p>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Detected fraud</p>
            </div>
            
            <div class="bg-slate-800/50 dark:bg-slate-800/50 backdrop-blur-lg border border-slate-700 dark:border-slate-700 rounded-xl p-6 hover:border-yellow-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-yellow-500/10">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-600 dark:text-slate-300">Fraud Rate</h3>
                    <div class="w-10 h-10 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-bold text-yellow-400">{{ number_format($fraudRate, 2) }}%</p>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Detection rate</p>
            </div>
            
            <div class="bg-white dark:bg-slate-800/50 backdrop-blur-lg border border-gray-200 dark:border-slate-700 rounded-xl p-6 hover:border-purple-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-purple-500/10">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-slate-600 dark:text-slate-300">Avg Risk Score</h3>
                    <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                </div>
                <p class="text-4xl font-bold text-purple-400">{{ number_format($averageRiskScore, 2) }}</p>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Risk level</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-slate-800/50 dark:bg-slate-800/50 backdrop-blur-lg border border-slate-700 dark:border-slate-700 rounded-xl p-6 hover:border-purple-500/50 transition-all duration-300">
                <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-4">Transaction Distribution</h3>
                <div class="relative" style="height: 300px;">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
            
            <div class="bg-slate-800/50 dark:bg-slate-800/50 backdrop-blur-lg border border-slate-700 dark:border-slate-700 rounded-xl p-6 hover:border-purple-500/50 transition-all duration-300">
                <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-4">Daily Transactions (Last 7 Days)</h3>
                <div class="relative" style="height: 300px;">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    // Chart.js default configuration
    Chart.defaults.color = '#94a3b8';
    Chart.defaults.borderColor = '#475569';

    // Pie Chart
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: ['Legitimate', 'Fraudulent'],
            datasets: [{
                data: [{{ $legitimateTransactions }}, {{ $fraudulentTransactions }}],
                backgroundColor: ['#22c55e', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#fff', padding: 20 }
                }
            }
        }
    });

    // Bar Chart
    const barCtx = document.getElementById('barChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: @json($dailyTransactions->pluck('date')),
            datasets: [
                {
                    label: 'Total',
                    data: @json($dailyTransactions->pluck('count')),
                    backgroundColor: '#3b82f6',
                    borderWidth: 0
                },
                {
                    label: 'Fraud',
                    data: @json($dailyTransactions->pluck('fraud_count')),
                    backgroundColor: '#ef4444',
                    borderWidth: 0
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    ticks: { color: '#94a3b8' },
                    grid: { color: 'rgba(148, 163, 184, 0.2)' }
                },
                y: {
                    ticks: { color: '#94a3b8' },
                    grid: { color: 'rgba(148, 163, 184, 0.2)' },
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { color: '#fff' }
                }
            }
        }
    });
</script>
@endsection
