<?php

use Illuminate\Support\Facades\Route;
use Modules\Faq\Http\Controllers\FaqController;

Route::prefix('faqs')->name('faqs_')->group(function () {
    Route::get('/', [FaqController::class, 'index'])->name('index');
    Route::get('/{id}', [FaqController::class, 'show'])->middleware('auth:api')->name('show');
    Route::post('/', [FaqController::class, 'store'])->middleware('auth:api')->name('store');
    Route::put('/{id}', [FaqController::class, 'update'])->middleware('auth:api')->name('update');
    Route::delete('/{id}', [FaqController::class, 'destroy'])->middleware('auth:api')->name('destroy');
});


// todo problems module :
// store update : check unique (from deleted_at)
// index  (or show) : with_trash (doesn't have)
