<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - FraudShield</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 min-h-screen">
    <nav class="bg-slate-800/50 backdrop-blur-lg border-b border-slate-700 text-white shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-8">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center">
                            <span class="text-lg">🛡️</span>
                        </div>
                        <h1 class="text-xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                            FraudShield
                        </h1>
                    </div>
                    <div class="flex items-center space-x-1">
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg transition-all duration-200 bg-gradient-to-r from-purple-600 to-pink-600 shadow-lg shadow-purple-500/25">
                            <span>📊</span>
                            <span class="font-medium">Dashboard</span>
                        </a>
                        <a href="{{ route('transactions.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg transition-all duration-200 hover:bg-slate-700/50">
                            <span>💳</span>
                            <span class="font-medium">Transactions</span>
                        </a>
                        <a href="{{ route('transactions.predict') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg transition-all duration-200 hover:bg-slate-700/50">
                            <span>🔮</span>
                            <span class="font-medium">Predict</span>
                        </a>
                        <a href="{{ route('alerts') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg transition-all duration-200 hover:bg-slate-700/50">
                            <span>🚨</span>
                            <span class="font-medium">Alerts</span>
                        </a>
                        <a href="{{ route('reports') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg transition-all duration-200 hover:bg-slate-700/50">
                            <span>📈</span>
                            <span class="font-medium">Reports</span>
                        </a>
                        <a href="{{ route('analysis') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg transition-all duration-200 hover:bg-slate-700/50">
                            <span>🔬</span>
                            <span class="font-medium">Analysis</span>
                        </a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-slate-400 text-sm">Demo Mode</span>
                </div>
            </div>
        </div>
    </nav>

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
                            <p class="text-sm text-slate-300 mb-2">High Risk Transactions (>0.7):</p>
                            @foreach($recentHighRisk as $txn)
                                <div class="text-xs text-red-300">• {{ $txn->transaction_id }} - Risk Score: {{ number_format($txn->risk_score, 2) }}</div>
                            @endforeach
                        </div>
                    @endif
                    @if($recentFraud->count() > 0)
                        <div>
                            <p class="text-sm text-slate-300 mb-2">Detected Fraud:</p>
                            @foreach($recentFraud as $txn)
                                <div class="text-xs text-red-300">• {{ $txn->transaction_id }} - ${{ number_format($txn->Amount, 2) }}</div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
            
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-white">Dashboard</h1>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-green-400 text-sm">Live</span>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-slate-800/50 backdrop-blur-lg border border-slate-700 rounded-xl p-6 hover:border-purple-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-purple-500/10">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-slate-300">Total Transactions</h3>
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center">
                            <span class="text-xl">💳</span>
                        </div>
                    </div>
                    <p class="text-4xl font-bold text-white">{{ $totalTransactions }}</p>
                    <p class="text-sm text-slate-400 mt-2">All time</p>
                </div>
                
                <div class="bg-slate-800/50 backdrop-blur-lg border border-slate-700 rounded-xl p-6 hover:border-red-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-red-500/10">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-slate-300">Fraudulent</h3>
                        <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-orange-500 rounded-lg flex items-center justify-center">
                            <span class="text-xl">⚠️</span>
                        </div>
                    </div>
                    <p class="text-4xl font-bold text-red-400">{{ $fraudulentTransactions }}</p>
                    <p class="text-sm text-slate-400 mt-2">Detected fraud</p>
                </div>
                
                <div class="bg-slate-800/50 backdrop-blur-lg border border-slate-700 rounded-xl p-6 hover:border-yellow-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-yellow-500/10">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-slate-300">Fraud Rate</h3>
                        <div class="w-10 h-10 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg flex items-center justify-center">
                            <span class="text-xl">📊</span>
                        </div>
                    </div>
                    <p class="text-4xl font-bold text-yellow-400">{{ number_format($fraudRate, 2) }}%</p>
                    <p class="text-sm text-slate-400 mt-2">Detection rate</p>
                </div>
                
                <div class="bg-slate-800/50 backdrop-blur-lg border border-slate-700 rounded-xl p-6 hover:border-purple-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-purple-500/10">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-slate-300">Avg Risk Score</h3>
                        <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center">
                            <span class="text-xl">🎯</span>
                        </div>
                    </div>
                    <p class="text-4xl font-bold text-purple-400">{{ number_format($averageRiskScore, 2) }}</p>
                    <p class="text-sm text-slate-400 mt-2">Risk level</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-slate-800/50 backdrop-blur-lg border border-slate-700 rounded-xl p-6 hover:border-purple-500/50 transition-all duration-300">
                    <h3 class="text-xl font-semibold text-white mb-4">Transaction Distribution</h3>
                    <div class="relative" style="height: 300px;">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
                
                <div class="bg-slate-800/50 backdrop-blur-lg border border-slate-700 rounded-xl p-6 hover:border-purple-500/50 transition-all duration-300">
                    <h3 class="text-xl font-semibold text-white mb-4">Daily Transactions (Last 7 Days)</h3>
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
</body>
</html>
