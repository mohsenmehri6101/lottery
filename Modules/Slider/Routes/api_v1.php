<?php

use Illuminate\Support\Facades\Route;
use Modules\Slider\Http\Controllers\SliderController;

# slider
Route::prefix('sliders')->name('sliders_')->group(function () {
    Route::get('/', [SliderController::class, 'index'])->name('index');
    Route::get('/list-status-slider', [SliderController::class, 'listStatusSlider'])->name('list_status_slider');
    Route::get('/{id}', [SliderController::class, 'show'])->name('show');
    Route::post('/', [SliderController::class, 'store'])->middleware('auth:api')->name('store');
    Route::put('/{id}', [SliderController::class, 'update'])->middleware('auth:api')->name('update');
    Route::delete('/{id}', [SliderController::class, 'destroy'])->middleware('auth:api')->name('destroy');
});
