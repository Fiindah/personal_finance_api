<?php

use App\Http\Controllers\Api\FinanceController;
use Illuminate\Support\Facades\Route;

Route::get('/overview', [FinanceController::class, 'overview']);
Route::get('/transactions', [App\Http\Controllers\Api\FinanceController::class, 'transactions']);
// Route untuk Pots
Route::get('/pots', [FinanceController::class, 'getPots']);
Route::post('/pots', [FinanceController::class, 'storePot']);
Route::patch('/pots/{id}/balance', [FinanceController::class, 'updateBalance']);
Route::delete('/pots/{id}', [FinanceController::class, 'destroyPot']);
Route::get('/recurring-bills', [App\Http\Controllers\Api\FinanceController::class, 'recurringBills']);