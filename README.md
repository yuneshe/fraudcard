# Fraud Detection Platform

A comprehensive fraud detection system built with Laravel (backend), React (frontend), Python/Flask (ML service), and MySQL (database).

## Architecture

```
Transaction → Laravel API → Python ML Service → Fraud Prediction → Database → Dashboard Alert
```

## Project Structure

```
fraudcard/
├── backend/          # Laravel 12 API
├── frontend/         # React Dashboard
├── ml-service/       # Python ML Service
└── database/         # SQL Schema
```

## Prerequisites

- PHP 8.2+
- Composer
- Node.js 22+
- npm
- Python 3.13+
- MySQL

## Setup Instructions

### 1. Database Setup

```bash
# Create MySQL database
mysql -u root -p
CREATE DATABASE fraud_detection;
```

Import the schema:
```bash
mysql -u root -p fraud_detection < database/schema.sql
```

### 2. Backend Setup (Laravel)

```bash
cd backend

# Install dependencies (already done)
composer install

# Configure .env file
# Update DB_CONNECTION=mysql and set your database credentials
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=fraud_detection
# DB_USERNAME=root
# DB_PASSWORD=your_password

# Generate application key (already done)
php artisan key:generate

# Run migrations
php artisan migrate

# Start Laravel server
php artisan serve
```

The Laravel API will run on `http://localhost:8000`

### 3. ML Service Setup (Python)

```bash
cd ml-service

# Install dependencies
pip install -r requirements.txt

# Train the fraud detection model
python train_model.py

# Start the ML service
python app.py
```

The ML service will run on `http://localhost:5000`

### 4. Frontend Setup (React)

```bash
cd frontend

# Install dependencies (already done)
npm install

# Start development server
npm run dev
```

The React dashboard will run on `http://localhost:5173`

## API Endpoints

### Authentication
- `POST /api/register` - Register new user
- `POST /api/login` - Login user
- `POST /api/logout` - Logout user
- `GET /api/me` - Get current user

### Transactions
- `GET /api/transactions` - List all transactions
- `POST /api/transactions` - Create new transaction (with fraud prediction)
- `GET /api/transactions/{id}` - Get transaction details
- `PUT /api/transactions/{id}` - Update transaction
- `DELETE /api/transactions/{id}` - Delete transaction
- `GET /api/transactions/statistics` - Get fraud statistics
- `GET /api/transactions/alerts` - Get fraud alerts

### ML Service
- `GET /health` - Health check
- `POST /predict` - Predict fraud for single transaction
- `POST /batch_predict` - Predict fraud for multiple transactions

## Dashboard Features

### Admin Dashboard
- Total transactions count
- Fraudulent transactions count
- Fraud rate percentage
- Average risk score
- Daily transaction charts (last 7 days)
- Transaction distribution pie chart

### Transactions Page
- View all transactions with pagination
- Filter by fraud status
- Filter by minimum risk score
- Sort by transaction time

### Fraud Alerts Page
- High-risk transactions (risk score > 0.7)
- Recent fraudulent transactions
- Real-time alert display

### Reports Page
- Summary statistics
- Daily transaction report table
- Export to CSV functionality

## Default Credentials

After running the database schema, you can login with:
- Email: `admin@frauddetection.com`
- Password: `admin123`

Or register a new user through the login page.

## ML Model Features

The fraud detection model uses:
- Random Forest Classifier
- Features: amount, time, merchant_category, location_distance, card_age_days, transaction_frequency
- Synthetic data generation for training (can be replaced with real creditcard.csv)
- Risk score calculation (0-1)
- Confidence scoring

## Future Enhancements

- Real-time fraud alerts using WebSockets
- Email notifications for detected fraud
- SMS alerts for high-risk transactions
- Fraud heat maps visualization
- ROC and confusion matrix visualization
- User activity logs
- Audit trail system
- Explainable AI (show why a transaction was flagged)

## Troubleshooting

### Laravel Issues
- If migrations fail, check your `.env` database configuration
- Ensure MySQL service is running
- Clear cache: `php artisan cache:clear`

### ML Service Issues
- If model files don't exist, run `python train_model.py`
- Ensure all Python dependencies are installed
- Check that port 5000 is not in use

### React Issues
- If TypeScript errors persist, restart your IDE
- Ensure all npm dependencies are installed
- Clear node_modules and reinstall if needed

## License

MIT License
