@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="space-y-6">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Data Analysis</h1>
        
        @if(session('error'))
            <div class="bg-red-500/10 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif
        
        @if(isset($analysis))
            <div class="bg-white dark:bg-slate-800/50 backdrop-blur-lg border border-gray-200 dark:border-slate-700 rounded-xl p-6 hover:border-purple-500/50 transition-all duration-300">
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-6">Statistical Analysis</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <div class="bg-gray-50 dark:bg-slate-700/50 p-4 rounded-lg border border-gray-200 dark:border-slate-600">
                        <h3 class="text-sm font-medium text-slate-600 dark:text-slate-300">Total Transactions</h3>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">{{ $analysis['total_transactions'] }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-700/50 p-4 rounded-lg border border-gray-200 dark:border-slate-600">
                        <h3 class="text-sm font-medium text-slate-600 dark:text-slate-300">Mean Amount</h3>
                        <p class="text-3xl font-bold text-purple-400 mt-2">${{ number_format($analysis['amount_stats']['mean'], 2) }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-700/50 p-4 rounded-lg border border-gray-200 dark:border-slate-600">
                        <h3 class="text-sm font-medium text-slate-600 dark:text-slate-300">Median Amount</h3>
                        <p class="text-3xl font-bold text-pink-400 mt-2">${{ number_format($analysis['amount_stats']['median'], 2) }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-700/50 p-4 rounded-lg border border-gray-200 dark:border-slate-600">
                        <h3 class="text-sm font-medium text-slate-600 dark:text-slate-300">Std Deviation</h3>
                        <p class="text-3xl font-bold text-blue-400 mt-2">${{ number_format($analysis['amount_stats']['std'], 2) }}</p>
                    </div>
                </div>

                @if(isset($analysis['fraud_distribution']))
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Fraud Distribution</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 dark:bg-slate-700/50 p-4 rounded-lg border border-gray-200 dark:border-slate-600">
                                <div class="relative" style="height: 300px;">
                                    <canvas id="fraudPieChart"></canvas>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-4">
                                <div class="bg-gray-50 dark:bg-slate-700/50 p-4 rounded-lg border border-gray-200 dark:border-slate-600">
                                    <h4 class="text-sm font-medium text-slate-600 dark:text-slate-300">Fraudulent</h4>
                                    <p class="text-2xl font-bold text-red-400 mt-2">{{ $analysis['fraud_distribution']['fraudulent'] }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-slate-700/50 p-4 rounded-lg border border-gray-200 dark:border-slate-600">
                                    <h4 class="text-sm font-medium text-slate-600 dark:text-slate-300">Legitimate</h4>
                                    <p class="text-2xl font-bold text-green-400 mt-2">{{ $analysis['fraud_distribution']['legitimate'] }}</p>
                                </div>
                                <div class="bg-gray-50 dark:bg-slate-700/50 p-4 rounded-lg border border-gray-200 dark:border-slate-600">
                                    <h4 class="text-sm font-medium text-slate-600 dark:text-slate-300">Fraud Rate</h4>
                                    <p class="text-2xl font-bold {{ $analysis['fraud_distribution']['fraud_rate'] > 0.1 ? 'text-red-400' : 'text-green-400' }} mt-2">{{ number_format($analysis['fraud_distribution']['fraud_rate'] * 100, 2) }}%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(isset($analysis['correlations_with_risk']))
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Feature Correlations with Risk Score</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg border border-gray-200 dark:border-slate-600 p-4">
                                <div class="relative" style="height: 300px;">
                                    <canvas id="correlationBarChart"></canvas>
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg border border-gray-200 dark:border-slate-600 overflow-hidden">
                                <table class="w-full">
                                    <thead class="bg-gray-100 dark:bg-slate-600/50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-sm font-medium text-slate-600 dark:text-slate-300">Feature</th>
                                            <th class="px-4 py-3 text-left text-sm font-medium text-slate-600 dark:text-slate-300">Correlation</th>
                                            <th class="px-4 py-3 text-left text-sm font-medium text-slate-600 dark:text-slate-300">Strength</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($analysis['correlations_with_risk'] as $feature => $correlation)
                                            <tr class="border-t border-gray-200 dark:border-slate-600">
                                                <td class="px-4 py-3 text-sm text-slate-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $feature)) }}</td>
                                                <td class="px-4 py-3 text-sm text-slate-900 dark:text-white">{{ number_format($correlation, 3) }}</td>
                                                <td class="px-4 py-3 text-sm">
                                                    @if(abs($correlation) > 0.7)
                                                        <span class="text-red-400 font-medium">Strong</span>
                                                    @elseif(abs($correlation) > 0.4)
                                                        <span class="text-orange-400 font-medium">Moderate</span>
                                                    @elseif(abs($correlation) > 0.2)
                                                        <span class="text-yellow-400 font-medium">Weak</span>
                                                    @else
                                                        <span class="text-slate-500 dark:text-slate-400 font-medium">Very Weak</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                @if(isset($analysis['merchant_analysis']))
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Merchant Category Analysis</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg border border-gray-200 dark:border-slate-600 p-4">
                                <div class="relative" style="height: 300px;">
                                    <canvas id="merchantBarChart"></canvas>
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-slate-700/50 rounded-lg border border-gray-200 dark:border-slate-600 overflow-hidden">
                                <table class="w-full">
                                    <thead class="bg-gray-100 dark:bg-slate-600/50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-sm font-medium text-slate-600 dark:text-slate-300">Category</th>
                                            <th class="px-4 py-3 text-left text-sm font-medium text-slate-600 dark:text-slate-300">Count</th>
                                            <th class="px-4 py-3 text-left text-sm font-medium text-slate-600 dark:text-slate-300">Average Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($analysis['merchant_analysis']['amount_mean'] as $merchant => $meanAmount)
                                            <tr class="border-t border-gray-200 dark:border-slate-600">
                                                <td class="px-4 py-3 text-sm text-slate-900 dark:text-white">{{ $merchant }}</td>
                                                <td class="px-4 py-3 text-sm text-slate-900 dark:text-white">{{ $analysis['merchant_analysis']['transaction_count'][$merchant] }}</td>
                                                <td class="px-4 py-3 text-sm text-slate-900 dark:text-white">${{ number_format($meanAmount, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @else
            <div class="bg-slate-800/50 dark:bg-slate-800/50 backdrop-blur-lg border border-slate-700 dark:border-slate-700 rounded-xl p-6">
                <p class="text-slate-400 dark:text-slate-400">No analysis data available. Please ensure the ML service is running.</p>
            </div>
        @endif
    </div>
</main>

@if(isset($analysis))
<script>
    // Chart.js default configuration
    Chart.defaults.color = '#94a3b8';
    Chart.defaults.borderColor = '#475569';

    // Fraud Distribution Pie Chart
    @if(isset($analysis['fraud_distribution']))
    const fraudCtx = document.getElementById('fraudPieChart').getContext('2d');
    new Chart(fraudCtx, {
        type: 'doughnut',
        data: {
            labels: ['Fraudulent', 'Legitimate'],
            datasets: [{
                data: [{{ $analysis['fraud_distribution']['fraudulent'] }}, {{ $analysis['fraud_distribution']['legitimate'] }}],
                backgroundColor: ['#ef4444', '#22c55e'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#fff',
                        padding: 20
                    }
                }
            }
        }
    });
    @endif

    // Feature Correlations Bar Chart
    @if(isset($analysis['correlations_with_risk']))
    const correlationCtx = document.getElementById('correlationBarChart').getContext('2d');
    const correlationLabels = @json(array_keys($analysis['correlations_with_risk']));
    const correlationData = @json(array_values($analysis['correlations_with_risk']));
    
    new Chart(correlationCtx, {
        type: 'bar',
        data: {
            labels: correlationLabels.map(label => label.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())),
            datasets: [{
                label: 'Correlation with Risk Score',
                data: correlationData,
                backgroundColor: correlationData.map(val => Math.abs(val) > 0.5 ? '#ef4444' : Math.abs(val) > 0.3 ? '#f97316' : '#eab308'),
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Correlation Coefficient'
                    }
                },
                x: {
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        }
    });
    @endif

    // Merchant Analysis Bar Chart
    @if(isset($analysis['merchant_analysis']))
    const merchantCtx = document.getElementById('merchantBarChart').getContext('2d');
    const merchantLabels = @json(array_keys($analysis['merchant_analysis']['amount_mean']));
    const merchantAmounts = @json(array_values($analysis['merchant_analysis']['amount_mean']));
    const merchantCounts = @json(array_values($analysis['merchant_analysis']['transaction_count']));
    
    new Chart(merchantCtx, {
        type: 'bar',
        data: {
            labels: merchantLabels,
            datasets: [{
                label: 'Average Amount ($)',
                data: merchantAmounts,
                backgroundColor: '#8b5cf6',
                yAxisID: 'y',
                borderWidth: 0
            }, {
                label: 'Transaction Count',
                data: merchantCounts,
                backgroundColor: '#ec4899',
                yAxisID: 'y1',
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Average Amount ($)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Transaction Count'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                },
                x: {
                    ticks: {
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        }
    });
    @endif
</script>
@endif
@endsection
