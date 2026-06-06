import React, { useEffect, useState } from 'react';
import { transactionAPI } from '../api';

const Reports: React.FC = () => {
  const [stats, setStats] = useState<any>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchStatistics();
  }, []);

  const fetchStatistics = async () => {
    try {
      const response = await transactionAPI.getStatistics();
      setStats(response.data);
    } catch (error) {
      console.error('Error fetching statistics:', error);
    } finally {
      setLoading(false);
    }
  };

  const exportCSV = () => {
    if (!stats) return;
    
    const csvContent = "data:text/csv;charset=utf-8," 
      + "Date,Total Transactions,Fraudulent Transactions\n"
      + stats.daily_transactions?.map((item: any) => 
        `${item.date},${item.count},${item.fraud_count}`
      ).join("\n");
    
    const link = document.createElement("a");
    link.setAttribute("href", encodeURI(csvContent));
    link.setAttribute("download", "fraud_report.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  };

  if (loading) return (
    <div className="flex items-center justify-center h-64">
      <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-500"></div>
    </div>
  );
  if (!stats) return <div className="text-red-400">Error loading reports</div>;

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <h1 className="text-3xl font-bold text-white">Reports</h1>
        <button
          onClick={exportCSV}
          className="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white rounded-lg font-medium transition-all duration-200 shadow-lg shadow-green-500/25 hover:shadow-green-500/40 flex items-center space-x-2"
        >
          <span>📥</span>
          <span>Export CSV</span>
        </button>
      </div>
      
      <div className="bg-slate-800/50 backdrop-blur-lg border border-slate-700 rounded-xl p-6 hover:border-purple-500/50 transition-all duration-300">
        <h2 className="text-xl font-semibold text-white mb-6">Summary Statistics</h2>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          <div className="bg-slate-700/50 p-4 rounded-lg border border-slate-600">
            <h3 className="text-sm font-medium text-slate-300">Total Transactions</h3>
            <p className="text-3xl font-bold text-white mt-2">{stats.total_transactions}</p>
          </div>
          <div className="bg-slate-700/50 p-4 rounded-lg border border-slate-600">
            <h3 className="text-sm font-medium text-slate-300">Fraudulent</h3>
            <p className="text-3xl font-bold text-red-400 mt-2">{stats.fraudulent_transactions}</p>
          </div>
          <div className="bg-slate-700/50 p-4 rounded-lg border border-slate-600">
            <h3 className="text-sm font-medium text-slate-300">Legitimate</h3>
            <p className="text-3xl font-bold text-green-400 mt-2">{stats.legitimate_transactions}</p>
          </div>
          <div className="bg-slate-700/50 p-4 rounded-lg border border-slate-600">
            <h3 className="text-sm font-medium text-slate-300">Fraud Rate</h3>
            <p className="text-3xl font-bold text-orange-400 mt-2">{stats.fraud_rate}%</p>
          </div>
        </div>
      </div>
      
      <div className="bg-slate-800/50 backdrop-blur-lg border border-slate-700 rounded-xl p-6 hover:border-purple-500/50 transition-all duration-300">
        <h2 className="text-xl font-semibold text-white mb-6">Daily Transaction Report (Last 7 Days)</h2>
        <div className="overflow-x-auto">
          <table className="min-w-full divide-y divide-slate-700">
            <thead className="bg-slate-700/50">
              <tr>
                <th className="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Date</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Total</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Fraudulent</th>
                <th className="px-6 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Fraud Rate</th>
              </tr>
            </thead>
            <tbody className="bg-slate-800/30 divide-y divide-slate-700">
              {stats.daily_transactions?.map((item: any, index: number) => (
                <tr key={index} className="hover:bg-slate-700/30 transition-colors">
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-white">{item.date}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-slate-300">{item.count}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-red-400">{item.fraud_count}</td>
                  <td className="px-6 py-4 whitespace-nowrap text-sm text-white">
                    {item.count > 0 ? ((item.fraud_count / item.count) * 100).toFixed(2) : 0}%
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
};

export default Reports;
