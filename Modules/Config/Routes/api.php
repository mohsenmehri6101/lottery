<?php

use Illuminate\Support\Facades\Route;
use Modules\Config\Http\Controllers\ConfigController;

Route::prefix('configs')->middleware(['auth:api'])->name('configs_')->group(function () {
    Route::get('/', [ConfigController::class, 'index'])->name('index');
    Route::get('/{id}', [ConfigController::class, 'show'])->name('show');
    Route::post('/', [ConfigController::class, 'store'])->name('store');
    Route::put('/{id}', [ConfigController::class, 'update'])->name('update');
    Route::delete('/{id}', [ConfigController::class, 'destroy'])->name('destroy');
});
