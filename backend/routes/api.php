<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

// Public routes (no authentication for demo)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Transaction routes (no authentication for demo)
Route::apiResource('transactions', TransactionController::class);
Route::get('/transactions/statistics', [TransactionController::class, 'statistics']);
Route::get('/transactions/alerts', [TransactionController::class, 'alerts']);
