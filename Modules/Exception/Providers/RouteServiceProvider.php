<?php

namespace Modules\Exception\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected string $moduleNamespace = 'Modules\Exception\Http\Controllers';
    protected string $modulePrefix = 'exception';

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
        Route::prefix('api/v1/exception')
            ->name('api_v1_exception_')
            ->middleware('api')
            ->namespace($this->moduleNamespace)
            ->group(module_path('Exception', '/Routes/api_v1.php'));
    }
}
