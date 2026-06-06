<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Transaction;
use App\Models\FraudReport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@frauddetection.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Create regular user
        User::create([
            'name' => 'Test User',
            'email' => 'user@frauddetection.com',
            'password' => Hash::make('user123'),
            'role' => 'analyst',
        ]);

        // Create sample transactions
        $merchants = ['Amazon', 'Walmart', 'Target', 'Best Buy', 'Apple Store', 'Netflix', 'Spotify', 'Uber', 'Airbnb', 'Starbucks'];
        $merchantCategories = ['retail', 'retail', 'retail', 'electronics', 'electronics', 'entertainment', 'entertainment', 'transport', 'travel', 'food'];

        for ($i = 0; $i < 100; $i++) {
            $isFraud = rand(0, 10) < 2; // 20% chance of fraud
            $amount = rand(10, 10000);
            
            $transaction = Transaction::create([
                'transaction_id' => 'TXN' . str_pad($i + 1, 8, '0', STR_PAD_LEFT),
                'amount' => $amount,
                'merchant' => $merchants[array_rand($merchants)],
                'merchant_category' => array_search($merchantCategories[array_rand($merchantCategories)], $merchantCategories) + 1,
                'location_distance' => rand(0, 1000),
                'card_age_days' => rand(1, 3650),
                'transaction_frequency' => rand(1, 100),
                'transaction_time' => now()->subDays(rand(0, 30)),
                'fraud_status' => $isFraud,
                'risk_score' => $isFraud ? rand(70, 99) / 100 : rand(1, 30) / 100,
            ]);

            // Create fraud report for fraudulent transactions
            if ($isFraud) {
                FraudReport::create([
                    'transaction_id' => $transaction->id,
                    'risk_score' => $transaction->risk_score,
                    'prediction' => 'fraud',
                    'confidence' => rand(70, 99) / 100,
                ]);
            }
        }
    }
}
