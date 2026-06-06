import React, { useEffect, useState } from 'react';
import { transactionAPI } from '../api';

const Alerts: React.FC = () => {
  const [alerts, setAlerts] = useState<any>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetchAlerts();
  }, []);

  const fetchAlerts = async () => {
    try {
      const response = await transactionAPI.getAlerts();
      setAlerts(response.data);
    } catch (error) {
      console.error('Error fetching alerts:', error);
    } finally {
      setLoading(false);
    }
  };

  if (loading) return (
    <div className="flex items-center justify-center h-64">
      <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-500"></div>
    </div>
  );
  if (!alerts) return <div className="text-red-400">Error loading alerts</div>;

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <h1 className="text-3xl font-bold text-white">Fraud Alerts</h1>
        <div className="flex items-center space-x-2">
          <div className="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
          <span className="text-red-400 text-sm">Live Monitoring</span>
        </div>
      </div>
      
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div className="bg-slate-800/50 backdrop-blur-lg border border-slate-700 rounded-xl p-6 hover:border-red-500/50 transition-all duration-300">
          <div className="flex items-center mb-4">
            <div className="w-10 h-10 bg-gradient-to-r from-red-500 to-orange-500 rounded-lg flex items-center justify-center mr-3">
              <span className="text-xl">⚠️</span>
            </div>
            <h2 className="text-xl font-semibold text-white">High Risk Transactions</h2>
          </div>
          <div className="space-y-3">
            {alerts.high_risk_transactions?.map((txn: any) => (
              <div key={txn.id} className="border-l-4 border-red-500 pl-4 py-3 bg-red-500/10 rounded-r-lg hover:bg-red-500/20 transition-colors">
                <div className="flex justify-between items-center">
                  <span className="font-medium text-white font-mono text-sm">{txn.transaction_id}</span>
                  <span className="text-red-400 font-bold text-lg">{txn.risk_score.toFixed(2)}</span>
                </div>
                <div className="text-sm text-slate-300 mt-1">
                  ${txn.amount} - {txn.merchant}
                </div>
                <div className="text-xs text-slate-400 mt-1">
                  {new Date(txn.transaction_time).toLocaleString()}
                </div>
              </div>
            ))}
          </div>
        </div>
        
        <div className="bg-slate-800/50 backdrop-blur-lg border border-slate-700 rounded-xl p-6 hover:border-orange-500/50 transition-all duration-300">
          <div className="flex items-center mb-4">
            <div className="w-10 h-10 bg-gradient-to-r from-orange-500 to-yellow-500 rounded-lg flex items-center justify-center mr-3">
              <span className="text-xl">🚨</span>
            </div>
            <h2 className="text-xl font-semibold text-white">Recent Fraud</h2>
          </div>
          <div className="space-y-3">
            {alerts.recent_fraud?.map((txn: any) => (
              <div key={txn.id} className="border-l-4 border-orange-500 pl-4 py-3 bg-orange-500/10 rounded-r-lg hover:bg-orange-500/20 transition-colors">
                <div className="flex justify-between items-center">
                  <span className="font-medium text-white font-mono text-sm">{txn.transaction_id}</span>
                  <span className="text-orange-400 font-bold text-lg">{txn.risk_score.toFixed(2)}</span>
                </div>
                <div className="text-sm text-slate-300 mt-1">
                  ${txn.amount} - {txn.merchant}
                </div>
                <div className="text-xs text-slate-400 mt-1">
                  {new Date(txn.transaction_time).toLocaleString()}
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
};

export default Alerts;
