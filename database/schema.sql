-- Fraud Detection Database Schema
-- MySQL Database

CREATE DATABASE IF NOT EXISTS fraud_detection;
USE fraud_detection;

-- Users table
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'analyst') DEFAULT 'analyst',
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Transactions table
CREATE TABLE transactions (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    transaction_id VARCHAR(100) NOT NULL UNIQUE,
    amount DECIMAL(12,2) NOT NULL,
    merchant VARCHAR(255) NOT NULL,
    transaction_time DATETIME NOT NULL,
    risk_score FLOAT DEFAULT 0,
    fraud_status BOOLEAN DEFAULT FALSE,
    merchant_category INT DEFAULT 1,
    location_distance FLOAT DEFAULT 0,
    card_age_days INT DEFAULT 0,
    transaction_frequency INT DEFAULT 0,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    INDEX idx_transaction_id (transaction_id),
    INDEX idx_fraud_status (fraud_status),
    INDEX idx_risk_score (risk_score),
    INDEX idx_transaction_time (transaction_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Fraud reports table
CREATE TABLE fraud_reports (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    transaction_id BIGINT NOT NULL,
    risk_score FLOAT NOT NULL,
    prediction VARCHAR(20) NOT NULL,
    confidence FLOAT DEFAULT 0,
    created_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE CASCADE,
    INDEX idx_transaction_id (transaction_id),
    INDEX idx_prediction (prediction),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user (password: admin123 - should be changed in production)
INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES
('Admin User', 'admin@frauddetection.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW(), NOW());

-- Insert sample transactions for testing
INSERT INTO transactions (transaction_id, amount, merchant, transaction_time, risk_score, fraud_status, merchant_category, location_distance, card_age_days, transaction_frequency, created_at, updated_at) VALUES
('TXN001', 150.00, 'Amazon', NOW() - INTERVAL 1 HOUR, 0.15, FALSE, 1, 25.5, 365, 5, NOW(), NOW()),
('TXN002', 2500.00, 'Unknown Merchant', NOW() - INTERVAL 30 MINUTE, 0.85, TRUE, 9, 450.2, 15, 25, NOW(), NOW()),
('TXN003', 75.50, 'Walmart', NOW() - INTERVAL 15 MINUTE, 0.10, FALSE, 2, 12.3, 730, 3, NOW(), NOW()),
('TXN004', 5000.00, 'Luxury Store', NOW() - INTERVAL 5 MINUTE, 0.92, TRUE, 8, 890.5, 7, 30, NOW(), NOW()),
('TXN005', 45.00, 'Starbucks', NOW(), 0.05, FALSE, 3, 5.2, 1095, 8, NOW(), NOW());
