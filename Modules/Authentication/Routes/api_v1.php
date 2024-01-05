<?php

use Illuminate\Support\Facades\Route;
use Modules\Authentication\Http\Controllers\AuthenticationController;
use Modules\Authentication\Http\Controllers\UserController;
use Modules\Authentication\Http\Controllers\WebInfoController;

# users
Route::prefix('users')->name('users_')->group(function () {
    Route::get('/', [UserController::class, 'index'])->middleware('auth:api')->name('index');
    Route::get('/list-status-gender', [UserController::class, 'listStatusGender'])->name('list_status_gender');
    Route::get('/list-status-user', [UserController::class, 'listStatusUser'])->name('list_status_user');
    Route::post('/check-profile', [UserController::class, 'checkProfile'])->name('check-profile');
    Route::get('/{id}', [UserController::class, 'show'])->middleware('auth:api')->name('show');
    Route::post('/', [UserController::class, 'store'])->middleware('auth:api')->name('store');
    Route::post('/new-user', [UserController::class, 'newUser'])->middleware('check.apiKey')->name('new_user');
    Route::post('/{id}', [UserController::class, 'update'])->middleware('auth:api')->name('update');
    Route::put('update-profile', [UserController::class, 'updateProfile'])->middleware('auth:api')->name('update_profile');
    Route::post('update-avatar', [UserController::class, 'updateAvatar'])->middleware('auth:api')->name('update_avatar');
    Route::delete('/{id}', [UserController::class, 'destroy'])->middleware('auth:api')->name('destroy');
    Route::delete('delete-avatar/{id}', [UserController::class, 'deleteAvatar'])->middleware('auth:api')->name('delete_avatar');
});

# authenticate
Route::prefix('authenticate')->name('authenticate_')->group(function () {
    Route::get('/create-token-super-admin', [AuthenticationController::class, 'createTokenSuperAdmin']);
    Route::get('/create-token-gym_manager', [AuthenticationController::class, 'createTokenGymManager']);
    Route::get('/create-token-user', [AuthenticationController::class, 'createTokenUser']);
    Route::get('/profile', [AuthenticationController::class, 'profile'])->name('profile')->middleware('auth:api');
    Route::post('/login', [AuthenticationController::class, 'login'])->name('login');
    Route::post('/change-password', [AuthenticationController::class, 'changePassword'])->middleware('auth:api')->name('change_password');
    Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout')->middleware('auth:api');
    Route::post('/otp', [AuthenticationController::class, 'otp'])->name('otp');
    Route::post('/resend-otp', [AuthenticationController::class, 'resendOtp'])->name('resend_otp');
    // todo should be throttle middleware
    Route::post('/otp-confirm', [AuthenticationController::class, 'otpConfirm'])->name('otp_confirm');
    Route::post('/otp-confirm-v2', [AuthenticationController::class, 'otpConfirmV2'])->name('otp_confirm_v2');
});

# web info
Route::get('/web-info', [WebInfoController::class, 'webInfo'])->name('web.info');
