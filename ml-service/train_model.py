import pandas as pd
from sklearn.ensemble import RandomForestClassifier
from sklearn.linear_model import LogisticRegression
from sklearn.svm import SVC
from sklearn.tree import DecisionTreeClassifier
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler
import joblib
import numpy as np
import os
import argparse

# Set environment variables to limit memory usage
os.environ['OPENBLAS_NUM_THREADS'] = '1'
os.environ['OMP_NUM_THREADS'] = '1'

# Generate synthetic data for training (since we don't have creditcard.csv)
# In production, replace with actual dataset
def generate_synthetic_data(n_samples=10000):
    np.random.seed(42)
    
    # Generate legitimate transactions
    n_legit = int(n_samples * 0.99)
    legit_data = {
        'amount': np.random.lognormal(mean=3, sigma=1, size=n_legit),
        'time': np.random.randint(0, 172800, size=n_legit),
        'merchant_category': np.random.randint(1, 10, size=n_legit),
        'location_distance': np.random.exponential(scale=50, size=n_legit),
        'card_age_days': np.random.randint(30, 1825, size=n_legit),
        'transaction_frequency': np.random.poisson(lam=5, size=n_legit),
        'class': np.zeros(n_legit)
    }
    
    # Generate fraudulent transactions
    n_fraud = n_samples - n_legit
    fraud_data = {
        'amount': np.random.lognormal(mean=5, sigma=1.5, size=n_fraud),
        'time': np.random.randint(0, 172800, size=n_fraud),
        'merchant_category': np.random.randint(1, 10, size=n_fraud),
        'location_distance': np.random.exponential(scale=200, size=n_fraud),
        'card_age_days': np.random.randint(1, 365, size=n_fraud),
        'transaction_frequency': np.random.poisson(lam=20, size=n_fraud),
        'class': np.ones(n_fraud)
    }
    
    df_legit = pd.DataFrame(legit_data)
    df_fraud = pd.DataFrame(fraud_data)
    
    df = pd.concat([df_legit, df_fraud], ignore_index=True)
    df = df.sample(frac=1, random_state=42).reset_index(drop=True)
    
    return df

# Load or generate data
def get_data():
    try:
        data = pd.read_csv('creditcard.csv')
        # Kaggle dataset has 'Time', 'V1'-'V28', 'Amount', 'Class' columns
        # Rename Class to class for consistency
        if 'Class' in data.columns:
            data = data.rename(columns={'Class': 'class'})
        return data
    except FileNotFoundError:
        print("creditcard.csv not found, generating synthetic data...")
        return generate_synthetic_data()

# Train the model
def train_model(algorithm='random_forest'):
    data = get_data()
    
    X = data.drop('class', axis=1)
    y = data['class']
    
    # Split the data
    X_train, X_test, y_train, y_test = train_test_split(
        X, y, test_size=0.2, random_state=42, stratify=y
    )
    
    # Scale the features
    scaler = StandardScaler()
    X_train_scaled = scaler.fit_transform(X_train)
    X_test_scaled = scaler.transform(X_test)
    
    # Select algorithm
    if algorithm == 'random_forest':
        model = RandomForestClassifier(
            n_estimators=50,
            random_state=42,
            max_depth=10,
            min_samples_split=5,
            class_weight='balanced',
            n_jobs=1
        )
    elif algorithm == 'logistic_regression':
        model = LogisticRegression(
            random_state=42,
            max_iter=1000,
            class_weight='balanced',
            n_jobs=1
        )
    elif algorithm == 'svm':
        model = SVC(
            random_state=42,
            class_weight='balanced',
            probability=True,
            max_iter=1000
        )
    elif algorithm == 'decision_tree':
        model = DecisionTreeClassifier(
            random_state=42,
            max_depth=10,
            class_weight='balanced'
        )
    else:
        raise ValueError(f"Unknown algorithm: {algorithm}")
    
    model.fit(X_train_scaled, y_train)
    
    # Evaluate
    train_score = model.score(X_train_scaled, y_train)
    test_score = model.score(X_test_scaled, y_test)
    
    print(f"Algorithm: {algorithm}")
    print(f"Training accuracy: {train_score:.4f}")
    print(f"Test accuracy: {test_score:.4f}")
    
    # Save model and scaler with algorithm suffix
    model_filename = f'fraud_model_{algorithm}.pkl'
    scaler_filename = f'scaler_{algorithm}.pkl'
    feature_names_filename = f'feature_names_{algorithm}.pkl'
    
    joblib.dump(model, model_filename)
    joblib.dump(scaler, scaler_filename)
    
    # Save feature names
    feature_names = X.columns.tolist()
    joblib.dump(feature_names, feature_names_filename)
    
    print(f"Model ({algorithm}), scaler, and feature names saved successfully!")
    
    return model, scaler, feature_names

if __name__ == "__main__":
    parser = argparse.ArgumentParser(description='Train fraud detection model with different algorithms')
    parser.add_argument('--algorithm', type=str, default='random_forest',
                        choices=['random_forest', 'logistic_regression', 'svm', 'decision_tree'],
                        help='ML algorithm to use for training')
    parser.add_argument('--all', action='store_true',
                        help='Train all available algorithms')
    
    args = parser.parse_args()
    
    if args.all:
        algorithms = ['random_forest', 'logistic_regression', 'svm', 'decision_tree']
        for algo in algorithms:
            print(f"\n{'='*50}")
            print(f"Training with {algo}...")
            print(f"{'='*50}")
            try:
                train_model(algo)
            except Exception as e:
                print(f"Error training {algo}: {e}")
    else:
        train_model(args.algorithm)
