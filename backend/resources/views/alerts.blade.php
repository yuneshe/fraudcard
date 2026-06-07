@extends('layouts.app')

@section('content')
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Fraud Alerts</h1>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                <span class="text-red-400 text-sm">Live Monitoring</span>
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-slate-800/50 backdrop-blur-lg border border-gray-200 dark:border-slate-700 rounded-xl p-6 hover:border-red-500/50 transition-all duration-300">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-orange-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">High Risk Transactions</h2>
                </div>
                <div class="space-y-3">
                    @foreach($highRiskTransactions as $txn)
                    <div class="border-l-4 border-red-500 pl-4 py-3 bg-red-500/10 rounded-r-lg hover:bg-red-500/20 transition-colors">
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-slate-900 dark:text-white font-mono text-sm">{{ $txn->transaction_id }}</span>
                            <span class="text-red-400 font-bold text-lg">{{ number_format($txn->risk_score, 2) }}</span>
                        </div>
                        <div class="text-sm text-slate-600 dark:text-slate-300 mt-1">
                            ${{ number_format($txn->Amount, 2) }} - {{ $txn->merchant }}
                        </div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                            {{ $txn->transaction_time->format('M j, Y g:i A') }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="bg-white dark:bg-slate-800/50 backdrop-blur-lg border border-gray-200 dark:border-slate-700 rounded-xl p-6 hover:border-orange-500/50 transition-all duration-300">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-r from-orange-500 to-yellow-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Recent Fraud</h2>
                </div>
                <div class="space-y-3">
                    @foreach($recentFraud as $txn)
                    <div class="border-l-4 border-orange-500 pl-4 py-3 bg-orange-500/10 rounded-r-lg hover:bg-orange-500/20 transition-colors">
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-slate-900 dark:text-white font-mono text-sm">{{ $txn->transaction_id }}</span>
                            <span class="text-orange-400 font-bold text-lg">{{ number_format($txn->risk_score, 2) }}</span>
                        </div>
                        <div class="text-sm text-slate-600 dark:text-slate-300 mt-1">
                            ${{ number_format($txn->Amount, 2) }} - {{ $txn->merchant }}
                        </div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                            {{ $txn->transaction_time->format('M j, Y g:i A') }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
