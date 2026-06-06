<?php

use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\TransactionController;
use Illuminate\Support\Facades\Route;

// Public routes (no authentication for demo)
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::get('/transactions/create', [TransactionController::class, 'create'])->name('transactions.create');
Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');
Route::get('/alerts', [TransactionController::class, 'alerts'])->name('alerts');
Route::get('/reports', [TransactionController::class, 'reports'])->name('reports');
Route::get('/reports/export', [TransactionController::class, 'export'])->name('reports.export');
Route::get('/analysis', [TransactionController::class, 'analysis'])->name('analysis');
Route::get('/predict', [TransactionController::class, 'predictForm'])->name('transactions.predict');
Route::post('/predict', [TransactionController::class, 'predict'])->name('transactions.predict.submit');
