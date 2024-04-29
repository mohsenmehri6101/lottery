<?php

use Illuminate\Support\Facades\Route;
use Modules\Exception\Http\Controllers\Exception\ExceptionController;
use Modules\Exception\Http\Controllers\Error\ErrorController;

Route::middleware('auth:api')->group(function () {
    # exception
    Route::prefix('exceptions')->name('exceptions_')->group(function () {
        Route::get('/', [ExceptionController::class, 'index'])->name('index');
        Route::get('/{id}', [ExceptionController::class, 'show'])->name('show');
        Route::post('/', [ExceptionController::class, 'store'])->name('store');
        Route::put('/{id}', [ExceptionController::class, 'update'])->name('update');
        Route::delete('/{id}', [ExceptionController::class, 'destroy'])->name('destroy');
    });

    # error
    Route::prefix('errors')->name('errors_')->group(function () {
        Route::get('/', [ErrorController::class, 'index'])->name('index');
        Route::get('/{id}', [ErrorController::class, 'show'])->name('show');
        Route::delete('/{id}', [ErrorController::class, 'destroy'])->name('destroy');
    });

});
