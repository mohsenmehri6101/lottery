<?php

namespace Modules\Notification\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $moduleNamespace = 'Modules\Notification\Http\Controllers';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiV1Routes();
    }

    protected function mapApiV1Routes()
    {
        Route::prefix('api/v1')
            ->name('api_v1_notification_')
            ->middleware('api')
            ->namespace($this->moduleNamespace)
            ->group(module_path('Notification', '/Routes/api_v1.php'));
    }

}
