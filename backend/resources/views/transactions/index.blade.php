@extends('layouts.app')

@section('content')
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="space-y-6">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Transactions</h1>
        
        <div class="bg-white dark:bg-slate-800/50 backdrop-blur-lg border border-gray-200 dark:border-slate-700 rounded-xl p-6 hover:border-purple-500/50 transition-all duration-300">
            <form method="GET" class="flex flex-wrap gap-4 mb-6">
                <select
                    name="fraud_status"
                    class="px-4 py-2 bg-gray-50 dark:bg-slate-700/50 border border-gray-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-slate-900 dark:text-white"
                >
                    <option value="">All Status</option>
                    <option value="1" {{ request('fraud_status') == '1' ? 'selected' : '' }}>Fraudulent</option>
                    <option value="0" {{ request('fraud_status') == '0' ? 'selected' : '' }}>Legitimate</option>
                </select>
                
                <input
                    type="number"
                    name="min_risk_score"
                    value="{{ request('min_risk_score') }}"
                    placeholder="Min Risk Score"
                    class="px-4 py-2 bg-gray-50 dark:bg-slate-700/50 border border-gray-300 dark:border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-400"
                />
                
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white rounded-lg transition-all duration-200 shadow-lg shadow-purple-500/25">
                    Filter
                </button>
            </form>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                    <thead class="bg-gray-50 dark:bg-slate-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Transaction ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Merchant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Risk Score</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-300 uppercase tracking-wider">Time</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800/30 divide-y divide-gray-200 dark:divide-slate-700">
                        @foreach($transactions as $txn)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white">{{ $txn->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300 font-mono">{{ $txn->transaction_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white font-semibold">${{ number_format($txn->Amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">{{ $txn->merchant }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-white">
                                <div class="flex items-center">
                                    <div class="w-16 h-2 bg-gray-200 dark:bg-slate-700 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-green-500 to-red-500" style="width: {{ $txn->risk_score * 100 }}%"></div>
                                    </div>
                                    <span class="ml-2 text-xs text-slate-600 dark:text-slate-300">{{ number_format($txn->risk_score, 2) }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($txn->fraud_status)
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-500/20 text-red-400 border border-red-500/30">
                                        Fraud
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-500/20 text-green-400 border border-green-500/30">
                                        Legitimate
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                {{ $txn->transaction_time->format('M j, Y g:i A') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="flex justify-center mt-6">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</main>
@endsection
