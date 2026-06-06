<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTransactions = Transaction::count();
        $fraudulentTransactions = Transaction::where('fraud_status', true)->count();
        $legitimateTransactions = Transaction::where('fraud_status', false)->count();
        $fraudRate = $totalTransactions > 0 ? ($fraudulentTransactions / $totalTransactions) * 100 : 0;
        $averageRiskScore = Transaction::avg('risk_score');

        $dailyTransactions = Transaction::selectRaw('DATE(transaction_time) as date, COUNT(*) as count, SUM(CASE WHEN fraud_status = true THEN 1 ELSE 0 END) as fraud_count')
            ->where('transaction_time', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get recent high-risk transactions for alerts
        $recentHighRisk = Transaction::where('risk_score', '>', 0.7)
            ->where('transaction_time', '>=', now()->subHours(24))
            ->orderBy('risk_score', 'desc')
            ->limit(5)
            ->get();

        // Get recent fraud transactions for alerts
        $recentFraud = Transaction::where('fraud_status', true)
            ->where('transaction_time', '>=', now()->subHours(24))
            ->orderBy('transaction_time', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalTransactions',
            'fraudulentTransactions',
            'legitimateTransactions',
            'fraudRate',
            'averageRiskScore',
            'dailyTransactions',
            'recentHighRisk',
            'recentFraud'
        ));
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
