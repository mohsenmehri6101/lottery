<?php

namespace Modules\Geographical\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected string $moduleNamespace = 'Modules\Geographical\Http\Controllers';

    public function boot(): void
    {
        parent::boot();
    }

    public function map(): void
    {
        $this->mapApiV1Routes();
    }

    protected function mapApiV1Routes(): void
    {
        Route::prefix('api/v1')
            ->name('api_v1_geographical_')
            ->middleware('api')
            ->namespace($this->moduleNamespace)
            ->group(module_path('Geographical', '/Routes/api_v1.php'));
    }
}
