<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);
        $this->app->singleton('persian-faker', function ($app) {
            return new \App\Providers\Customize\PersianDataProvider();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }

}
