<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - FraudShield</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg transition-all duration-200 hover:bg-slate-700/50">
                            <span>📊</span>
                            <span class="font-medium">Dashboard</span>
                        </a>
                        <a href="{{ route('transactions.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg transition-all duration-200 hover:bg-slate-700/50">
                            <span>💳</span>
                            <span class="font-medium">Transactions</span>
                        </a>
                        <a href="{{ route('alerts') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg transition-all duration-200 hover:bg-slate-700/50">
                            <span>🚨</span>
                            <span class="font-medium">Alerts</span>
                        </a>
                        <a href="{{ route('reports') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg transition-all duration-200 bg-gradient-to-r from-purple-600 to-pink-600 shadow-lg shadow-purple-500/25">
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
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-white">Reports</h1>
                <a href="{{ route('reports.export') }}" class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white rounded-lg text-sm font-medium transition-all duration-200 shadow-lg shadow-green-500/25">
                    Export CSV
                </a>
            </div>
            
            <div class="bg-slate-800/50 backdrop-blur-lg border border-slate-700 rounded-xl p-6 hover:border-purple-500/50 transition-all duration-300">
                <h2 class="text-xl font-semibold text-white mb-6">Summary Statistics</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-slate-700/50 p-4 rounded-lg border border-slate-600">
                        <h3 class="text-sm font-medium text-slate-300">Total Transactions</h3>
                        <p class="text-3xl font-bold text-white mt-2">{{ $totalTransactions }}</p>
                    </div>
                    <div class="bg-slate-700/50 p-4 rounded-lg border border-slate-600">
                        <h3 class="text-sm font-medium text-slate-300">Fraudulent</h3>
                        <p class="text-3xl font-bold text-red-400 mt-2">{{ $fraudulentTransactions }}</p>
                    </div>
                    <div class="bg-slate-700/50 p-4 rounded-lg border border-slate-600">
                        <h3 class="text-sm font-medium text-slate-300">Legitimate</h3>
                        <p class="text-3xl font-bold text-green-400 mt-2">{{ $legitimateTransactions }}</p>
                    </div>
                    <div class="bg-slate-700/50 p-4 rounded-lg border border-slate-600">
                        <h3 class="text-sm font-medium text-slate-300">Fraud Rate</h3>
                        <p class="text-3xl font-bold text-orange-400 mt-2">{{ number_format($fraudRate, 2) }}%</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-slate-800/50 backdrop-blur-lg border border-slate-700 rounded-xl p-6 hover:border-purple-500/50 transition-all duration-300">
                <h2 class="text-xl font-semibold text-white mb-6">Daily Transaction Report (Last 7 Days)</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-700">
                        <thead class="bg-slate-700/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Fraudulent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Fraud Rate</th>
                            </tr>
                        </thead>
                        <tbody class="bg-slate-800/30 divide-y divide-slate-700">
                            @foreach($dailyTransactions as $item)
                            <tr class="hover:bg-slate-700/30 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $item->date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-300">{{ $item->count }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-red-400">{{ $item->fraud_count }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-white">
                                    @if($item->count > 0)
                                        {{ number_format(($item->fraud_count / $item->count) * 100, 2) }}%
                                    @else
                                        0%
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
