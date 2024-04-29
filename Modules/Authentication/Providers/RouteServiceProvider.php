<?php

namespace Modules\Authentication\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{

    protected string $moduleNamespace = 'Modules\Authentication\Http\Controllers';


    public function map(): void
    {
        $this->mapApiV1Routes();
    }

    protected function mapApiV1Routes(): void
    {
        Route::prefix('api/v1/authentication')
            ->name('api_v1_authentication_')
            ->middleware('api')
            ->namespace($this->moduleNamespace)
            ->group(module_path('Authentication', '/Routes/api_v1.php'));
    }

}
