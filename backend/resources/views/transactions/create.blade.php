<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Transaction - FraudShield</title>
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
                        <a href="{{ route('transactions.create') }}" class="flex items-center space-x-2 px-4 py-2 rounded-lg transition-all duration-200 bg-gradient-to-r from-purple-600 to-pink-600 shadow-lg shadow-purple-500/25">
                            <span>➕</span>
                            <span class="font-medium">Create</span>
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
            <h1 class="text-3xl font-bold text-white">Create Transaction</h1>
            
            @if(session('error'))
                <div class="bg-red-500/10 border border-red-500/50 text-red-400 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif
            
            @if(session('success'))
                <div class="bg-green-500/10 border border-green-500/50 text-green-400 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="bg-slate-800/50 backdrop-blur-lg border border-slate-700 rounded-xl p-6 hover:border-purple-500/50 transition-all duration-300">
                <h2 class="text-xl font-semibold text-white mb-4">Transaction Details</h2>
                <p class="text-slate-400 text-sm mb-6">Transaction will be automatically analyzed for fraud using ML service</p>
                
                <form method="POST" action="{{ route('transactions.store') }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Transaction ID</label>
                            <input
                                type="text"
                                name="transaction_id"
                                value="{{ old('transaction_id', 'TXN-' . uniqid()) }}"
                                required
                                class="w-full px-4 py-2 bg-slate-700/50 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-white placeholder-slate-400"
                                placeholder="Unique transaction ID"
                            >
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Amount ($)</label>
                            <input
                                type="number"
                                name="Amount"
                                step="0.01"
                                value="{{ old('Amount') }}"
                                required
                                class="w-full px-4 py-2 bg-slate-700/50 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-white placeholder-slate-400"
                                placeholder="Enter amount"
                            >
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Time (seconds)</label>
                            <input
                                type="number"
                                name="Time"
                                value="{{ old('Time', time()) }}"
                                required
                                class="w-full px-4 py-2 bg-slate-700/50 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-white placeholder-slate-400"
                                placeholder="Transaction time"
                            >
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-300 mb-2">Merchant</label>
                            <input
                                type="text"
                                name="merchant"
                                value="{{ old('merchant') }}"
                                required
                                class="w-full px-4 py-2 bg-slate-700/50 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-white placeholder-slate-400"
                                placeholder="Enter merchant name"
                            >
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-300 mb-2">Algorithm</label>
                            <select
                                name="algorithm"
                                class="w-full px-4 py-2 bg-slate-700/50 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-white"
                            >
                                <option value="random_forest" {{ old('algorithm', 'random_forest') == 'random_forest' ? 'selected' : '' }}>Random Forest (99.93% accuracy)</option>
                                <option value="logistic_regression" {{ old('algorithm') == 'logistic_regression' ? 'selected' : '' }}>Logistic Regression (97.55% accuracy)</option>
                                <option value="decision_tree" {{ old('algorithm') == 'decision_tree' ? 'selected' : '' }}>Decision Tree (99.07% accuracy)</option>
                                <option value="svm" {{ old('algorithm') == 'svm' ? 'selected' : '' }}>SVM (13.65% accuracy - not recommended)</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="bg-slate-700/30 p-4 rounded-lg border border-slate-600">
                        <p class="text-sm text-slate-400">Note: V1-V28 PCA features are auto-generated for demo purposes</p>
                    </div>
                    
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white py-3 rounded-lg font-medium transition-all duration-200 shadow-lg shadow-purple-500/25 hover:shadow-purple-500/40"
                    >
                        Create Transaction with Fraud Analysis
                    </button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
