<?php

use Illuminate\Support\Facades\Route;
use Modules\Geographical\Http\Controllers\CityController;
use Modules\Geographical\Http\Controllers\ProvinceController;

# cities
Route::prefix('cities')->name('cities_')->group(function () {
    Route::get('/', [CityController::class, 'index'])->name('index');
    Route::get('/{id}', [CityController::class, 'show'])->name('show');
    Route::post('/', [CityController::class, 'store'])->middleware('auth:api')->name('store');
    Route::put('/{id}', [CityController::class, 'update'])->middleware('auth:api')->name('update');
    Route::delete('/{id}', [CityController::class, 'destroy'])->middleware('auth:api')->name('destroy');
});

# provinces
Route::prefix('provinces')->name('provinces_')->group(function () {
    Route::get('/', [ProvinceController::class, 'index'])->name('index');
    Route::get('/{id}', [ProvinceController::class, 'show'])->name('show');
    Route::post('/', [ProvinceController::class, 'store'])->middleware('auth:api')->name('store');
    Route::put('/{id}', [ProvinceController::class, 'update'])->middleware('auth:api')->name('update');
    Route::delete('/{id}', [ProvinceController::class, 'destroy'])->middleware('auth:api')->name('destroy');
});
