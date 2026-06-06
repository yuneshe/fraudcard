from flask import Flask, request, jsonify
from flask_cors import CORS
import joblib
import numpy as np
import pandas as pd
import os

# Set environment variables to limit memory usage
os.environ['OPENBLAS_NUM_THREADS'] = '1'
os.environ['OMP_NUM_THREADS'] = '1'

app = Flask(__name__)
CORS(app)

# Load model and scaler
models = {}
scalers = {}
feature_names_list = {}

def load_model(algorithm='random_forest'):
    global models, scalers, feature_names_list
    try:
        model_filename = f'fraud_model_{algorithm}.pkl'
        scaler_filename = f'scaler_{algorithm}.pkl'
        feature_names_filename = f'feature_names_{algorithm}.pkl'
        
        models[algorithm] = joblib.load(model_filename)
        scalers[algorithm] = joblib.load(scaler_filename)
        feature_names_list[algorithm] = joblib.load(feature_names_filename)
        print(f"Model ({algorithm}), scaler, and feature names loaded successfully!")
        return True
    except FileNotFoundError:
        print(f"Model files for {algorithm} not found. Please run train_model.py first.")
        return False

def load_all_models():
    algorithms = ['random_forest', 'logistic_regression', 'svm', 'decision_tree']
    loaded_count = 0
    for algo in algorithms:
        if load_model(algo):
            loaded_count += 1
    print(f"Loaded {loaded_count} out of {len(algorithms)} models")
    return loaded_count > 0

@app.route('/health', methods=['GET'])
def health():
    return jsonify({"status": "healthy", "loaded_models": list(models.keys())})

@app.route('/models', methods=['GET'])
def list_models():
    return jsonify({"available_models": list(models.keys())})

@app.route('/predict', methods=['POST'])
def predict():
    algorithm = request.json.get('algorithm', 'random_forest') if request.json else 'random_forest'
    
    if algorithm not in models:
        return jsonify({"error": f"Model '{algorithm}' not loaded. Available models: {list(models.keys())}"}), 400
    
    try:
        data = request.json
        
        # Extract features from request (Kaggle dataset features)
        # Expected features: Time, V1-V28, Amount
        features = [float(data.get('Time', 0))]
        
        # Add V1-V28 features
        for i in range(1, 29):
            features.append(float(data.get(f'V{i}', 0)))
        
        # Add Amount
        features.append(float(data.get('Amount', 0)))
        
        # Create feature array
        feature_array = np.array([features])
        
        # Scale features
        feature_scaled = scalers[algorithm].transform(feature_array)
        
        # Make prediction
        prediction = models[algorithm].predict(feature_scaled)[0]
        probability = models[algorithm].predict_proba(feature_scaled)[0]
        
        # Calculate risk score (probability of fraud)
        risk_score = float(probability[1])
        
        # Determine prediction label
        prediction_label = "fraud" if prediction == 1 else "legitimate"
        confidence = float(max(probability))
        
        return jsonify({
            "algorithm": algorithm,
            "fraud": int(prediction),
            "prediction": prediction_label,
            "risk_score": risk_score,
            "confidence": confidence,
            "probabilities": {
                "legitimate": float(probability[0]),
                "fraud": float(probability[1])
            }
        })
    
    except Exception as e:
        return jsonify({"error": str(e)}), 500

@app.route('/batch_predict', methods=['POST'])
def batch_predict():
    algorithm = request.json.get('algorithm', 'random_forest') if request.json else 'random_forest'
    
    if algorithm not in models:
        return jsonify({"error": f"Model '{algorithm}' not loaded. Available models: {list(models.keys())}"}), 400
    
    try:
        data = request.json
        transactions = data.get('transactions', [])
        
        results = []
        for txn in transactions:
            # Extract Kaggle features (Time, V1-V28, Amount)
            features = [float(txn.get('Time', 0))]
            
            # Add V1-V28 features
            for i in range(1, 29):
                features.append(float(txn.get(f'V{i}', 0)))
            
            # Add Amount
            features.append(float(txn.get('Amount', 0)))
            
            feature_array = np.array([features])
            feature_scaled = scalers[algorithm].transform(feature_array)
            
            prediction = models[algorithm].predict(feature_array)[0]
            probability = models[algorithm].predict_proba(feature_array)[0]
            
            results.append({
                "transaction_id": txn.get('transaction_id'),
                "algorithm": algorithm,
                "fraud": int(prediction),
                "risk_score": float(probability[1]),
                "confidence": float(max(probability))
            })
        
        return jsonify({"results": results})
    
    except Exception as e:
        return jsonify({"error": str(e)}), 500

@app.route('/analyze', methods=['POST'])
def analyze():
    """Data analysis endpoint using pandas"""
    try:
        data = request.json
        transactions = data.get('transactions', [])
        
        if not transactions:
            return jsonify({"error": "No transactions provided"}), 400
        
        # Convert to pandas DataFrame
        df = pd.DataFrame(transactions)
        
        # Convert numeric columns to proper types
        numeric_cols = ['Amount', 'Time', 'risk_score', 'fraud_status']
        for col in numeric_cols:
            if col in df.columns:
                df[col] = pd.to_numeric(df[col], errors='coerce')
        
        # Basic statistics
        stats = {
            'total_transactions': len(df),
        }
        
        if 'Amount' in df.columns:
            amount_data = df['Amount'].dropna()
            if len(amount_data) > 0:
                stats['amount_stats'] = {
                    'mean': float(amount_data.mean()),
                    'median': float(amount_data.median()),
                    'std': float(amount_data.std()),
                    'min': float(amount_data.min()),
                    'max': float(amount_data.max())
                }
            else:
                stats['amount_stats'] = {'error': 'No valid Amount data'}
        
        # Feature correlations if risk_score is present
        if 'risk_score' in df.columns:
            numeric_cols_df = df.select_dtypes(include=[np.number]).columns
            correlations = {}
            for col in numeric_cols_df:
                if col != 'risk_score':
                    corr = df[col].corr(df['risk_score'])
                    if not pd.isna(corr):
                        correlations[col] = float(corr)
            stats['correlations_with_risk'] = correlations
        
        # Fraud distribution if fraud_status is present
        if 'fraud_status' in df.columns:
            fraud_count = df['fraud_status'].sum()
            stats['fraud_distribution'] = {
                'fraudulent': int(fraud_count),
                'legitimate': int(len(df) - fraud_count),
                'fraud_rate': float(fraud_count / len(df)) if len(df) > 0 else 0.0
            }
        
        # Merchant analysis (group by merchant)
        if 'merchant' in df.columns:
            merchant_stats = df.groupby('merchant').agg(
                amount_mean=('Amount', 'mean'),
                transaction_count=('Amount', 'count')
            ).to_dict()
            stats['merchant_analysis'] = merchant_stats
        
        # Time analysis
        if 'Time' in df.columns:
            time_data = df['Time'].dropna()
            if len(time_data) > 0:
                stats['time_stats'] = {
                    'mean': float(time_data.mean()),
                    'median': float(time_data.median()),
                    'min': int(time_data.min()),
                    'max': int(time_data.max())
                }
            else:
                stats['time_stats'] = {'error': 'No valid Time data'}
        
        return jsonify(stats)
    except Exception as e:
        return jsonify({"error": str(e)}), 500

if __name__ == '__main__':
    # Try to load all models on startup
    load_all_models()
    app.run(host='0.0.0.0', port=5000, debug=True)
