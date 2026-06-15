<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\EarlySettlementController;

Route::post('/purchases', [PurchaseController::class, 'store']);
Route::get('/purchases/{purchase}', [PurchaseController::class, 'show']);

Route::post('/payments/initiate', [PaymentController::class, 'initiate']);

Route::post('/webhooks/payments', [WebhookController::class, 'handle']);
Route::post('/purchases/{purchase}/settle', [EarlySettlementController::class, 'initiate']);
