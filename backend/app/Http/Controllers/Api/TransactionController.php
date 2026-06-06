<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\FraudReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

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

        if ($request->has('from_date')) {
            $query->where('transaction_time', '>=', $request->from_date);
        }

        if ($request->has('to_date')) {
            $query->where('transaction_time', '<=', $request->to_date);
        }

        $transactions = $query->orderBy('transaction_time', 'desc')
            ->paginate($request->per_page ?? 20);

        return response()->json($transactions);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|string|unique:transactions,transaction_id',
            'amount' => 'required|numeric',
            'merchant' => 'required|string',
            'transaction_time' => 'required|date',
            'merchant_category' => 'nullable|integer',
            'location_distance' => 'nullable|numeric',
            'card_age_days' => 'nullable|integer',
            'transaction_frequency' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Call Python ML service for fraud prediction
        $mlResponse = $this->predictFraud($request->all());

        $transaction = Transaction::create([
            'transaction_id' => $request->transaction_id,
            'amount' => $request->amount,
            'merchant' => $request->merchant,
            'transaction_time' => $request->transaction_time,
            'risk_score' => $mlResponse['risk_score'] ?? 0,
            'fraud_status' => $mlResponse['fraud'] == 1,
            'merchant_category' => $request->merchant_category ?? 1,
            'location_distance' => $request->location_distance ?? 0,
            'card_age_days' => $request->card_age_days ?? 0,
            'transaction_frequency' => $request->transaction_frequency ?? 0,
        ]);

        // Create fraud report
        FraudReport::create([
            'transaction_id' => $transaction->id,
            'risk_score' => $mlResponse['risk_score'] ?? 0,
            'prediction' => $mlResponse['prediction'] ?? 'unknown',
            'confidence' => $mlResponse['confidence'] ?? 0,
        ]);

        return response()->json([
            'transaction' => $transaction,
            'fraud_prediction' => $mlResponse,
        ], 201);
    }

    public function show($id)
    {
        $transaction = Transaction::with('fraudReports')->findOrFail($id);
        return response()->json($transaction);
    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'amount' => 'sometimes|required|numeric',
            'merchant' => 'sometimes|required|string',
            'transaction_time' => 'sometimes|required|date',
            'fraud_status' => 'sometimes|required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $transaction->update($request->all());

        return response()->json($transaction);
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return response()->json(null, 204);
    }

    public function statistics()
    {
        $totalTransactions = Transaction::count();
        $fraudulentTransactions = Transaction::where('fraud_status', true)->count();
        $legitimateTransactions = Transaction::where('fraud_status', false)->count();
        $fraudRate = $totalTransactions > 0 ? ($fraudulentTransactions / $totalTransactions) * 100 : 0;
        $averageRiskScore = Transaction::avg('risk_score');

        // Daily transactions for the last 7 days
        $dailyTransactions = Transaction::selectRaw('DATE(transaction_time) as date, COUNT(*) as count, SUM(CASE WHEN fraud_status = true THEN 1 ELSE 0 END) as fraud_count')
            ->where('transaction_time', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'total_transactions' => $totalTransactions,
            'fraudulent_transactions' => $fraudulentTransactions,
            'legitimate_transactions' => $legitimateTransactions,
            'fraud_rate' => round($fraudRate, 2),
            'average_risk_score' => round($averageRiskScore, 2),
            'daily_transactions' => $dailyTransactions,
        ]);
    }

    public function alerts()
    {
        $highRiskTransactions = Transaction::highRisk()
            ->orderBy('risk_score', 'desc')
            ->limit(20)
            ->get();

        $recentFraud = Transaction::fraudulent()
            ->orderBy('transaction_time', 'desc')
            ->limit(20)
            ->get();

        return response()->json([
            'high_risk_transactions' => $highRiskTransactions,
            'recent_fraud' => $recentFraud,
        ]);
    }

    private function predictFraud($data)
    {
        try {
            $response = Http::timeout(10)->post('http://127.0.0.1:5000/predict', [
                'amount' => $data['amount'],
                'time' => strtotime($data['transaction_time']),
                'merchant_category' => $data['merchant_category'] ?? 1,
                'location_distance' => $data['location_distance'] ?? 0,
                'card_age_days' => $data['card_age_days'] ?? 0,
                'transaction_frequency' => $data['transaction_frequency'] ?? 0,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            // Fallback if ML service is unavailable
            return [
                'fraud' => 0,
                'prediction' => 'legitimate',
                'risk_score' => 0.1,
                'confidence' => 0.5,
            ];
        } catch (\Exception $e) {
            // Fallback if ML service is unavailable
            return [
                'fraud' => 0,
                'prediction' => 'legitimate',
                'risk_score' => 0.1,
                'confidence' => 0.5,
            ];
        }
    }
}
