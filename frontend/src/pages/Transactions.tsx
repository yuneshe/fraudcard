import React, { useEffect, useState } from 'react';
import { transactionAPI } from '../api';

const Transactions: React.FC = () => {
  const [transactions, setTransactions] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [page, setPage] = useState(1);
  const [filters, setFilters] = useState({ fraud_status: '', min_risk_score: '' });

  useEffect(() => {
    fetchTransactions();
  }, [page, filters]);

  const fetchTransactions = async () => {
    try {
      const response = await transactionAPI.getAll({ page, ...filters });
      setTransactions(response.data.data);
    } catch (error) {
      console.error('Error fetching transactions:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleFilterChange = (e: React.ChangeEvent<HTMLSelectElement | HTMLInputElement>) => {
    setFilters({ ...filters, [e.target.name]: e.target.value });
    setPage(1);
  };

  if (loading) return (
    <div className="flex items-center justify-center h-64">
      <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-500"></div>
    </div>
  );

  return (
    <div className="space-y-6">
      <h1 className="text-3xl font-bold text-white">Transactions</h1>
      
      <div className="bg-slate-800/50 backdrop-blur-lg border border-slate-700 rounded-xl p-6 hover:border-purple-500/50 transition-all duration-300">
        <div className="flex flex-wrap gap-4 mb-6">
          <select
            name="fraud_status"
            value={filters.fraud_status}
            onChange={handleFilterChange}
            className="px-4 py-2 bg-slate-700/50 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-white"
          >
            <option value="">All Status</option>
            <option value="true">Fraudulent</option>
            <option value="false">Legitimate</option>
          </select>
          
          <input
            type="number"
            name="min_risk_score"
            value={filters.min_risk_score}
            onChange={handleFilterChange}
            placeholder="Min Risk Score"
            className="px-4 py-2 bg-slate-700/50 border border-slate-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 text-white placeholder-slate-400"
          />
        </div>
        
        <div className="overflow-x-auto">
          <table className="min-w-full divide-y divide-slate-700">
            <thead className="bg-slate-700/50">
              <tr>
                <th className="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">ID</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Transaction ID</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Amount</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Merchant</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Risk Score</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Status</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Time</th>
              </tr>
            </thead>
            <tbody className="bg-slate-800/30 divide-y divide-slate-700">
              {transactions.map((txn) => (
                <tr key={txn.id} className="hover:bg-slate-700/30 transition-colors">
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-white">{txn.id}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-slate-300 font-mono">{txn.transaction_id}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-white font-semibold">${txn.amount}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-slate-300">{txn.merchant}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-white">
                    <div className="flex items-center">
                      <div className="w-16 h-2 bg-slate-700 rounded-full overflow-hidden">
                        <div 
                          className="h-full bg-gradient-to-r from-green-500 to-red-500" 
                          style={{ width: `${txn.risk_score * 100}%` }}
                        ></div>
                      </div>
                      <span className="ml-2 text-xs">{txn.risk_score.toFixed(2)}</span>
                    </div>
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap">
                    <span className={`px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${
                      txn.fraud_status 
                        ? 'bg-red-500/20 text-red-400 border border-red-500/30' 
                        : 'bg-green-500/20 text-green-400 border border-green-500/30'
                    }`}>
                      {txn.fraud_status ? 'Fraud' : 'Legitimate'}
                    </span>
                  </td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-slate-400">
                    {new Date(txn.transaction_time).toLocaleString()}
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
        
        <div className="flex justify-between items-center mt-6">
          <button
            onClick={() => setPage(page - 1)}
            disabled={page === 1}
            className="px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white rounded-lg disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 shadow-lg shadow-purple-500/25"
          >
            Previous
          </button>
          <span className="px-4 py-2 bg-slate-700/50 rounded-lg text-white">Page {page}</span>
          <button
            onClick={() => setPage(page + 1)}
            className="px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white rounded-lg transition-all duration-200 shadow-lg shadow-purple-500/25"
          >
            Next
          </button>
        </div>
      </div>
    </div>
  );
};

export default Transactions;
