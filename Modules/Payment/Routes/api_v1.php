<?php

use Illuminate\Support\Facades\Route;
use Modules\Payment\Http\Controllers\AccountController;
use Modules\Payment\Http\Controllers\BankController;
use Modules\Payment\Http\Controllers\FactorController;
use Modules\Payment\Http\Controllers\PaymentController;

# factors
Route::prefix('factors')->name('factors_')->group(function () {
    Route::get('/', [FactorController::class, 'index'])->middleware('auth:api')->name('index');
    Route::get('/statuses', [FactorController::class, 'listStatusFactor'])->middleware('auth:api')->name('statuses');
    Route::get('/my-factor', [FactorController::class, 'myFactor'])->middleware('auth:api')->name('my_factor');
    Route::get('/{id}', [FactorController::class, 'show'])->middleware('auth:api')->name('show');
    Route::post('/', [FactorController::class, 'store'])->middleware('auth:api')->name('store');
    Route::put('/{id}', [FactorController::class, 'update'])->middleware('auth:api')->name('update');
    Route::delete('/{id}', [FactorController::class, 'destroy'])->middleware('auth:api')->name('destroy');
});
# accounts
Route::prefix('accounts')->name('accounts_')->group(function () {
    Route::get('/', [AccountController::class, 'index'])->middleware('auth:api')->name('index');
    Route::get('/my-account', [AccountController::class, 'myAccount'])->middleware('auth:api')->name('my_account');
    Route::get('/{id}', [AccountController::class, 'show'])->middleware('auth:api')->name('show');
    Route::post('/', [AccountController::class, 'store'])->middleware('auth:api')->name('store');
    Route::put('/{id}', [AccountController::class, 'update'])->middleware('auth:api')->name('update');
    Route::delete('/{id}', [AccountController::class, 'destroy'])->middleware('auth:api')->name('destroy');
});
# banks
Route::prefix('banks')->name('banks_')->group(function () {
    Route::get('/', [BankController::class, 'index'])->middleware('auth:api')->name('index');
    Route::get('/{id}', [BankController::class, 'show'])->middleware('auth:api')->name('show');
    Route::post('/', [BankController::class, 'store'])->middleware('auth:api')->name('store');
    Route::put('/{id}', [BankController::class, 'update'])->middleware('auth:api')->name('update');
    Route::delete('/{id}', [BankController::class, 'destroy'])->middleware('auth:api')->name('destroy');
});
# payments
Route::prefix('payments')->name('payments_')->group(function () {
    Route::get('/', [PaymentController::class, 'index'])->middleware('auth:api')->name('index');
    # payping
    Route::post('/create-link-payment', [PaymentController::class, 'createLinkPayment'])->middleware('auth:api')->name('create_link_payment');
    Route::get('/confirm-payment', [PaymentController::class, 'confirmPayment'])->name('confirm_payment_get');
    Route::post('/confirm-payment', [PaymentController::class, 'confirmPayment'])->name('confirm_payment_post');
    # sadad
    Route::post('/create-link-payment-sadad', [PaymentController::class, 'createLinkPaymentSadad'])->middleware('auth:api')->name('create_link_payment');
    Route::get('/confirm-payment-sadad', [PaymentController::class, 'confirmPaymentSadad'])->name('confirm_payment_get');
    Route::post('/confirm-payment-sadad', [PaymentController::class, 'confirmPaymentSadad'])->name('confirm_payment_post');
    Route::get('/{id}', [PaymentController::class, 'show'])->middleware('auth:api')->name('show');
});
