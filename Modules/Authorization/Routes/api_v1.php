<?php

use Illuminate\Support\Facades\Route;
use Modules\Authorization\Http\Controllers\Permission\PermissionController;
use Modules\Authorization\Http\Controllers\Role\RoleController;

Route::middleware('auth:api')->name('authorization')->group(function () {
    # permissions
    Route::prefix('permissions')->name('permissions_')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::get('/{id}', [PermissionController::class, 'show'])->name('show');
        Route::post('/', [PermissionController::class, 'store'])->name('store');
        Route::put('/{id}', [PermissionController::class, 'update'])->name('update');
        Route::post('sync-permission-to-user', [PermissionController::class, 'syncPermissionToUser'])->name('sync_permission_to_user');
        Route::delete('delete-permission-to-user', [PermissionController::class, 'deletePermissionToUser'])->name('delete_permission_to_user');
        Route::post('sync-permission-to-role', [PermissionController::class, 'syncPermissionToRole'])->name('sync_permission_to_role');
        Route::delete('delete-permission-to-role', [PermissionController::class, 'deletePermissionToRole'])->name('delete_permission_to_role');
    });

    # roles
    Route::prefix('roles')->name('roles_')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/{id}', [RoleController::class, 'show'])->name('show');
        Route::post('/', [RoleController::class, 'store'])->name('store');
        Route::put('/{id}', [RoleController::class, 'update'])->name('update');
        Route::delete('delete-role-to-user', [RoleController::class, 'deleteRoleToUser'])->name('delete_role_to_user');
        Route::delete('/{id}', [RoleController::class, 'destroy'])->name('destroy');
        Route::post('sync-role-to-user', [RoleController::class, 'syncRoleToUser'])->name('sync_role_to_user');
    });

});
