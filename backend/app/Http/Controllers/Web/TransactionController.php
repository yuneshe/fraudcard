<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\FraudReport;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::query();

        if ($request->has('fraud_status')) {
            $query->where('fraud_status', $request->fraud_status);
        }

        if ($request->has('min_risk_score')) {
            $query->where('risk_score', '>=', $request->min_risk_score);
        }

        $transactions = $query->orderBy('transaction_time', 'desc')
            ->paginate(20);

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        return view('transactions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_id' => 'required|string|unique:transactions,transaction_id',
            'Amount' => 'required|numeric|min:0',
            'Time' => 'required|integer|min:0',
            'merchant' => 'required|string',
            'algorithm' => 'nullable|string|in:random_forest,logistic_regression,svm,decision_tree',
        ]);

        $algorithm = $validated['algorithm'] ?? 'random_forest';

        // Generate random V1-V28 values for demo (in production, these would come from PCA)
        $vFeatures = [];
        for ($i = 1; $i <= 28; $i++) {
            $vFeatures["V{$i}"] = $request->get("V{$i}", (string)(rand(-1000, 1000) / 100));
        }

        // Call Python ML service for prediction
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(30)->post('http://127.0.0.1:5000/predict', array_merge([
                'Time' => $validated['Time'],
                'Amount' => $validated['Amount'],
                'algorithm' => $algorithm,
            ], $vFeatures));

            if ($response->successful()) {
                $prediction = $response->json();
                
                // Create transaction with ML prediction
                $transaction = Transaction::create(array_merge([
                    'transaction_id' => $validated['transaction_id'],
                    'Amount' => $validated['Amount'],
                    'merchant' => $validated['merchant'],
                    'transaction_time' => now(),
                    'Time' => $validated['Time'],
                ], $vFeatures, [
                    'risk_score' => $prediction['risk_score'],
                    'fraud_status' => $prediction['fraud'] == 1,
                ]));

                // Save fraud report
                FraudReport::create([
                    'transaction_id' => $transaction->id,
                    'risk_score' => $prediction['risk_score'],
                    'prediction' => $prediction['prediction'],
                    'confidence' => $prediction['confidence'],
                ]);

                return redirect()->route('transactions.index')
                    ->with('success', "Transaction created with fraud prediction using {$algorithm}");
            }

            return back()->with('error', 'Failed to get prediction from ML service: ' . $response->body());
        } catch (\Exception $e) {
            return back()->with('error', 'ML service is not available: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $transaction = Transaction::with('fraudReports')->findOrFail($id);
        return view('transactions.show', compact('transaction'));
    }

    public function alerts()
    {
        $highRiskTransactions = Transaction::where('risk_score', '>', 0.7)
            ->orderBy('risk_score', 'desc')
            ->limit(20)
            ->get();

        $recentFraud = Transaction::where('fraud_status', true)
            ->orderBy('transaction_time', 'desc')
            ->limit(20)
            ->get();

        return view('alerts', compact('highRiskTransactions', 'recentFraud'));
    }

    public function reports()
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

        return view('reports', compact(
            'totalTransactions',
            'fraudulentTransactions',
            'legitimateTransactions',
            'fraudRate',
            'averageRiskScore',
            'dailyTransactions'
        ));
    }

    public function export()
    {
        $transactions = Transaction::all();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="fraud_report_' . date('Y-m-d') . '.csv"',
        ];
        
        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Transaction ID', 'Amount', 'Time', 'Merchant', 'Transaction Time', 'Risk Score', 'Fraud Status']);
            
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->id,
                    $transaction->transaction_id,
                    $transaction->Amount,
                    $transaction->Time,
                    $transaction->merchant,
                    $transaction->transaction_time,
                    $transaction->risk_score,
                    $transaction->fraud_status ? 'Fraud' : 'Legitimate',
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function analysis()
    {
        try {
            $transactions = Transaction::all()->toArray();
            
            \Log::info('Analysis request - transaction count: ' . count($transactions));
            
            $response = \Illuminate\Support\Facades\Http::timeout(30)->post('http://127.0.0.1:5000/analyze', [
                'transactions' => $transactions,
            ]);

            \Log::info('ML service analysis response status: ' . $response->status());
            \Log::info('ML service analysis response body: ' . $response->body());

            if ($response->successful()) {
                $analysis = $response->json();
                return view('analysis', compact('analysis'));
            }

            return back()->with('error', 'Failed to get analysis from ML service: ' . $response->body());
        } catch (\Exception $e) {
            \Log::error('Analysis error: ' . $e->getMessage());
            return back()->with('error', 'ML service is not available: ' . $e->getMessage());
        }
    }

    public function predictForm()
    {
        return view('transactions.predict');
    }

    public function predict(Request $request)
    {
        $validated = $request->validate([
            'Amount' => 'required|numeric|min:0',
            'Time' => 'required|integer|min:0',
            'algorithm' => 'nullable|string|in:random_forest,logistic_regression,svm,decision_tree',
        ]);

        $algorithm = $validated['algorithm'] ?? 'random_forest';

        // Generate random V1-V28 values for demo (in production, these would come from PCA)
        $vFeatures = [];
        for ($i = 1; $i <= 28; $i++) {
            $vFeatures["V{$i}"] = $request->get("V{$i}", (string)(rand(-1000, 1000) / 100));
        }

        // Call Python ML service for prediction
        try {
            \Log::info('Calling ML service for prediction', array_merge($validated, $vFeatures, ['algorithm' => $algorithm]));
            
            $response = \Illuminate\Support\Facades\Http::timeout(30)->post('http://127.0.0.1:5000/predict', array_merge([
                'Time' => $validated['Time'],
                'Amount' => $validated['Amount'],
                'algorithm' => $algorithm,
            ], $vFeatures));

            \Log::info('ML service response status: ' . $response->status());
            \Log::info('ML service response body: ' . $response->body());

            if ($response->successful()) {
                $prediction = $response->json();
                
                return view('transactions.predict', [
                    'prediction' => $prediction,
                    'input' => array_merge($validated, $vFeatures),
                    'algorithm' => $algorithm,
                ]);
            }

            return back()->with('error', 'Failed to get prediction from ML service: ' . $response->body());
        } catch (\Exception $e) {
            \Log::error('ML service error: ' . $e->getMessage());
            return back()->with('error', 'ML service is not available: ' . $e->getMessage());
        }
    }
}
