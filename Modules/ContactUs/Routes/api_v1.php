<?php

use Illuminate\Support\Facades\Route;
use Modules\ContactUs\Http\Controllers\ContactUsController;

# contact-us
Route::prefix('contact-us')->name('contact_us_')->group(function () {
    Route::get('/', [ContactUsController::class, 'index'])->name('index');
    Route::get('/{id}', [ContactUsController::class, 'show'])->name('show');
    Route::post('/', [ContactUsController::class, 'store'])/*->middleware('auth:api')*/->name('store');
    Route::put('/{id}', [ContactUsController::class, 'update'])->middleware('auth:api')->name('update');
    Route::delete('/{id}', [ContactUsController::class, 'destroy'])->middleware('auth:api')->name('destroy');
});
