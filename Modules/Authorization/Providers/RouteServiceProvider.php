<?php

namespace Modules\Authorization\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected string $moduleNamespace = 'Modules\Authorization\Http\Controllers';
    protected string $modulePrefix = 'authorization';

    public function map()
    {
        $this->mapApiV1Routes();
    }

    protected function mapApiV1Routes()
    {
        Route::prefix('api/v1/authorization')
            ->name('api_v1_authorization_')
            ->middleware('api')
            ->namespace($this->moduleNamespace)
            ->group(module_path('Authorization', '/Routes/api_v1.php'));
    }

}
