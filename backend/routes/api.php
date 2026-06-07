<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

// Public routes (no authentication for demo)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Transaction routes (no authentication for demo)
Route::apiResource('transactions', TransactionController::class)->names([
    'index' => 'api.transactions.index',
    'store' => 'api.transactions.store',
    'show' => 'api.transactions.show',
    'update' => 'api.transactions.update',
    'destroy' => 'api.transactions.destroy',
]);
Route::get('/transactions/statistics', [TransactionController::class, 'statistics'])->name('api.transactions.statistics');
Route::get('/transactions/alerts', [TransactionController::class, 'alerts'])->name('api.transactions.alerts');
