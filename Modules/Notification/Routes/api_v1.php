<?php

use Illuminate\Support\Facades\Route;
use Modules\Notification\Http\Controllers\EventController;
use Modules\Notification\Http\Controllers\NotificationController;

Route::middleware('auth:api')->group(function () {
    # notifications
    Route::prefix('notifications')->name('notifications_')->group(function () {
        // Route::get('/', [NotificationController::class, 'index'])->name('index');
        // Route::get('/my-notifications', [NotificationController::class, 'myNotifications'])->name('my_notifications');
        // Route::get('/my-new-notifications', [NotificationController::class, 'myNewNotifications'])->name('my_new_notifications');
        // Route::get('/{id}', [NotificationController::class, 'show'])->name('show');
        Route::post('/', [NotificationController::class, 'store'])->name('store');
        // Route::put('/{id}', [NotificationController::class, 'update'])->name('update');
        // Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    });

    # events
    Route::prefix('events')->name('events_')->group(function () {
        // Route::get('/', [EventController::class, 'index'])->name('index');
        Route::get('/my-events', [EventController::class, 'myEvents'])->name('my_events');
        // Route::get('/my-new-events', [EventController::class, 'myNewEvents'])->name('my_new_events');
        // Route::get('/{id}', [EventController::class, 'show'])->name('show');
        // Route::post('/', [EventController::class, 'store'])->name('store');
        // Route::put('/{id}', [EventController::class, 'update'])->name('update');
        // Route::delete('/{id}', [EventController::class, 'destroy'])->name('destroy');
    });

});
