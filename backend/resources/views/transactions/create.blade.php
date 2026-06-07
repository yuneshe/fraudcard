@extends('layouts.app')

@section('content')
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="space-y-6">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Create Transaction</h1>
        
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
        
        <div class="bg-white dark:bg-slate-800/50 backdrop-blur-lg border border-gray-200 dark:border-slate-700 rounded-xl p-6 hover:border-purple-500/50 transition-all duration-300">
            <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-4">Transaction Details</h2>
            <p class="text-slate-500 dark:text-slate-400 text-sm mb-6">Transaction will be automatically analyzed for fraud using ML service</p>
            
            <form method="POST" action="{{ route('transactions.store') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Transaction ID</label>
                        <input
                            type="text"
                            name="transaction_id"
                            value="{{ old('transaction_id', 'TXN-' . uniqid()) }}"
                            required
                            class="w-full px-4 py-2 bg-gray-50 dark:bg-slate-700/50 border border-gray-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-400"
                            placeholder="Unique transaction ID"
                        >
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Amount ($)</label>
                        <input
                            type="number"
                            name="Amount"
                            step="0.01"
                            value="{{ old('Amount') }}"
                            required
                            class="w-full px-4 py-2 bg-gray-50 dark:bg-slate-700/50 border border-gray-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-400"
                            placeholder="Enter amount"
                        >
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Time (seconds)</label>
                        <input
                            type="number"
                            name="Time"
                            value="{{ old('Time', time()) }}"
                            required
                            class="w-full px-4 py-2 bg-gray-50 dark:bg-slate-700/50 border border-gray-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-400"
                            placeholder="Transaction time"
                        >
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Merchant</label>
                        <input
                            type="text"
                            name="merchant"
                            value="{{ old('merchant') }}"
                            required
                            class="w-full px-4 py-2 bg-gray-50 dark:bg-slate-700/50 border border-gray-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-400"
                            placeholder="Enter merchant name"
                        >
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-600 dark:text-slate-300 mb-2">Algorithm</label>
                        <select
                            name="algorithm"
                            class="w-full px-4 py-2 bg-gray-50 dark:bg-slate-700/50 border border-gray-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-slate-900 dark:text-white"
                        >
                            <option value="random_forest" {{ old('algorithm', 'random_forest') == 'random_forest' ? 'selected' : '' }}>Random Forest (99.93% accuracy)</option>
                            <option value="logistic_regression" {{ old('algorithm') == 'logistic_regression' ? 'selected' : '' }}>Logistic Regression (97.55% accuracy)</option>
                            <option value="decision_tree" {{ old('algorithm') == 'decision_tree' ? 'selected' : '' }}>Decision Tree (99.07% accuracy)</option>
                            <option value="svm" {{ old('algorithm') == 'svm' ? 'selected' : '' }}>SVM (13.65% accuracy - not recommended)</option>
                        </select>
                    </div>
                </div>
                
                <div class="bg-gray-100 dark:bg-slate-700/30 p-4 rounded-lg border border-gray-200 dark:border-slate-600">
                    <p class="text-sm text-slate-500 dark:text-slate-400">Note: V1-V28 PCA features are auto-generated for demo purposes</p>
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
@endsection
