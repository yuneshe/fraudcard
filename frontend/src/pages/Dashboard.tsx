import React, { useEffect, useState } from 'react';
import { transactionAPI } from '../api';
import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer, PieChart, Pie, Cell } from 'recharts';

const COLORS = ['#0088FE', '#00C49F', '#FFBB28', '#FF8042', '#8884D8'];

const Dashboard: React.FC = () => {
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

  if (loading) return (
    <div className="flex items-center justify-center h-64">
      <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-purple-500"></div>
    </div>
  );
  if (!stats) return <div className="text-red-400">Error loading statistics</div>;

  const pieData = [
    { name: 'Legitimate', value: stats.legitimate_transactions },
    { name: 'Fraudulent', value: stats.fraudulent_transactions },
  ];

  const dailyData = stats.daily_transactions?.map((item: any) => ({
    date: new Date(item.date).toLocaleDateString(),
    total: item.count,
    fraud: item.fraud_count,
  })) || [];

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <h1 className="text-3xl font-bold text-white">Dashboard</h1>
        <div className="flex items-center space-x-2">
          <div className="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
          <span className="text-green-400 text-sm">Live</span>
        </div>
      </div>
      
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div className="bg-slate-800/50 backdrop-blur-lg border border-slate-700 rounded-xl p-6 hover:border-purple-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-purple-500/10">
          <div className="flex items-center justify-between mb-4">
            <h3 className="text-lg font-semibold text-slate-300">Total Transactions</h3>
            <div className="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-lg flex items-center justify-center">
              <span className="text-xl">💳</span>
            </div>
          </div>
          <p className="text-4xl font-bold text-white">{stats.total_transactions}</p>
          <p className="text-sm text-slate-400 mt-2">All time</p>
        </div>
        
        <div className="bg-slate-800/50 backdrop-blur-lg border border-slate-700 rounded-xl p-6 hover:border-red-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-red-500/10">
          <div className="flex items-center justify-between mb-4">
            <h3 className="text-lg font-semibold text-slate-300">Fraudulent</h3>
            <div className="w-10 h-10 bg-gradient-to-r from-red-500 to-orange-500 rounded-lg flex items-center justify-center">
              <span className="text-xl">⚠️</span>
            </div>
          </div>
          <p className="text-4xl font-bold text-red-400">{stats.fraudulent_transactions}</p>
          <p className="text-sm text-slate-400 mt-2">Detected fraud</p>
        </div>
        
        <div className="bg-slate-800/50 backdrop-blur-lg border border-slate-700 rounded-xl p-6 hover:border-yellow-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-yellow-500/10">
          <div className="flex items-center justify-between mb-4">
            <h3 className="text-lg font-semibold text-slate-300">Fraud Rate</h3>
            <div className="w-10 h-10 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-lg flex items-center justify-center">
              <span className="text-xl">📊</span>
            </div>
          </div>
          <p className="text-4xl font-bold text-yellow-400">{stats.fraud_rate}%</p>
          <p className="text-sm text-slate-400 mt-2">Detection rate</p>
        </div>
        
        <div className="bg-slate-800/50 backdrop-blur-lg border border-slate-700 rounded-xl p-6 hover:border-purple-500/50 transition-all duration-300 hover:shadow-lg hover:shadow-purple-500/10">
          <div className="flex items-center justify-between mb-4">
            <h3 className="text-lg font-semibold text-slate-300">Avg Risk Score</h3>
            <div className="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center">
              <span className="text-xl">🎯</span>
            </div>
          </div>
          <p className="text-4xl font-bold text-purple-400">{stats.average_risk_score}</p>
          <p className="text-sm text-slate-400 mt-2">Risk level</p>
        </div>
      </div>
      
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div className="bg-slate-800/50 backdrop-blur-lg border border-slate-700 rounded-xl p-6 hover:border-purple-500/50 transition-all duration-300">
          <h3 className="text-xl font-semibold text-white mb-4">Transaction Distribution</h3>
          <ResponsiveContainer width="100%" height={300}>
            <PieChart>
              <Pie
                data={pieData}
                cx="50%"
                cy="50%"
                labelLine={false}
                label={({ name, percent }) => `${name} ${(percent * 100).toFixed(0)}%`}
                outerRadius={80}
                fill="#8884d8"
                dataKey="value"
              >
                {pieData.map((entry, index) => (
                  <Cell key={`cell-${index}`} fill={COLORS[index % COLORS.length]} />
                ))}
              </Pie>
              <Tooltip 
                contentStyle={{ 
                  backgroundColor: 'rgba(30, 41, 59, 0.9)', 
                  border: '1px solid rgba(148, 163, 184, 0.2)',
                  borderRadius: '8px'
                }}
                itemStyle={{ color: '#fff' }}
              />
            </PieChart>
          </ResponsiveContainer>
        </div>
        
        <div className="bg-slate-800/50 backdrop-blur-lg border border-slate-700 rounded-xl p-6 hover:border-purple-500/50 transition-all duration-300">
          <h3 className="text-xl font-semibold text-white mb-4">Daily Transactions (Last 7 Days)</h3>
          <ResponsiveContainer width="100%" height={300}>
            <BarChart data={dailyData}>
              <CartesianGrid strokeDasharray="3 3" stroke="rgba(148, 163, 184, 0.2)" />
              <XAxis dataKey="date" stroke="#94a3b8" />
              <YAxis stroke="#94a3b8" />
              <Tooltip 
                contentStyle={{ 
                  backgroundColor: 'rgba(30, 41, 59, 0.9)', 
                  border: '1px solid rgba(148, 163, 184, 0.2)',
                  borderRadius: '8px'
                }}
                itemStyle={{ color: '#fff' }}
              />
              <Legend />
              <Bar dataKey="total" fill="#3b82f6" name="Total" radius={[4, 4, 0, 0]} />
              <Bar dataKey="fraud" fill="#ef4444" name="Fraud" radius={[4, 4, 0, 0]} />
            </BarChart>
          </ResponsiveContainer>
        </div>
      </div>
    </div>
  );
};

export default Dashboard;
