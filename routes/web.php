<?php

use App\Http\Controllers\Mainc;
use App\Http\Controllers\MpesaController;
use Illuminate\Support\Facades\Route;



// Payment UI
Route::get('/', [MpesaController::class, 'index'])->name('payment.index');
Route::get('/payment/success', [MpesaController::class, 'success'])->name('payment.success');

// M-Pesa API routes
Route::post('/mpesa/stk-push', [MpesaController::class, 'stkPush'])->name('mpesa.stk-push');
Route::get('/mpesa/status', [MpesaController::class, 'status'])->name('mpesa.status');

// Safaricom callback — must be excluded from CSRF (see below)
Route::post('/mpesa/callback', [MpesaController::class, 'callback'])->name('mpesa.callback');

Route::get('/payments', [Mainc::class, 'payments'])->name('payments.list');
