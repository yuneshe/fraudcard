<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerts - FraudShield</title>
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
                        <a href="{{ route('alerts') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg transition-all duration-200 bg-gradient-to-r from-purple-600 to-pink-600 shadow-lg shadow-purple-500/25">
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
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-white">Fraud Alerts</h1>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                    <span class="text-red-400 text-sm">Live Monitoring</span>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-slate-800/50 backdrop-blur-lg border border-slate-700 rounded-xl p-6 hover:border-red-500/50 transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-orange-500 rounded-lg flex items-center justify-center mr-3">
                            <span class="text-xl">⚠️</span>
                        </div>
                        <h2 class="text-xl font-semibold text-white">High Risk Transactions</h2>
                    </div>
                    <div class="space-y-3">
                        @foreach($highRiskTransactions as $txn)
                        <div class="border-l-4 border-red-500 pl-4 py-3 bg-red-500/10 rounded-r-lg hover:bg-red-500/20 transition-colors">
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-white font-mono text-sm">{{ $txn->transaction_id }}</span>
                                <span class="text-red-400 font-bold text-lg">{{ number_format($txn->risk_score, 2) }}</span>
                            </div>
                            <div class="text-sm text-slate-300 mt-1">
                                ${{ number_format($txn->amount, 2) }} - {{ $txn->merchant }}
                            </div>
                            <div class="text-xs text-slate-400 mt-1">
                                {{ $txn->transaction_time->format('M j, Y g:i A') }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="bg-slate-800/50 backdrop-blur-lg border border-slate-700 rounded-xl p-6 hover:border-orange-500/50 transition-all duration-300">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-yellow-500 rounded-lg flex items-center justify-center mr-3">
                            <span class="text-xl">🚨</span>
                        </div>
                        <h2 class="text-xl font-semibold text-white">Recent Fraud</h2>
                    </div>
                    <div class="space-y-3">
                        @foreach($recentFraud as $txn)
                        <div class="border-l-4 border-orange-500 pl-4 py-3 bg-orange-500/10 rounded-r-lg hover:bg-orange-500/20 transition-colors">
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-white font-mono text-sm">{{ $txn->transaction_id }}</span>
                                <span class="text-orange-400 font-bold text-lg">{{ number_format($txn->risk_score, 2) }}</span>
                            </div>
                            <div class="text-sm text-slate-300 mt-1">
                                ${{ number_format($txn->amount, 2) }} - {{ $txn->merchant }}
                            </div>
                            <div class="text-xs text-slate-400 mt-1">
                                {{ $txn->transaction_time->format('M j, Y g:i A') }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
