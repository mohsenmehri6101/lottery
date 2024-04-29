<?php

use Illuminate\Support\Facades\Route;

# php artisan serve --host=192.168.125.8 --port=8000
# #########################################################
# php artisan l5-swagger:generate
Route::get("/api/documentation/auth", [\App\Http\Controllers\ApiDocumentationAuth::class, "show"])->name("api_documentation_auth");
Route::post("/api/documentation/auth", [\App\Http\Controllers\ApiDocumentationAuth::class, "login"])->name("api_documentation_auth_login");
Route::get('/api/documentation/swap-dark-mode/{darkMode}', [\App\Http\Controllers\ApiDocumentationAuth::class, 'swap_dark_mode'])->name('api_documentation_swap_dark_mode');

/*
### work failure:
        - check exception and test
        - check user table
        - implement database
        - implement model
        - implement routes
            - users crud
            - authentication
                    - reset password
                    - change password
                    - login
                    - register
                    - otp
                    - otp-confirm
                    - implement database seeder from him
            - authorization
                - implement roles
                - completely delete table permissions if exist
                - implement crud table
                - create database seeder from him
            - article
                - implement tables(copy from the other project)
                - implement model
                - connect other table
                - crud article + routes my, check, change-status article, review article, comment article
                - implement and check other tables score , likes , comments, ...
                -
            - lottery section
 */
