<?php

namespace Modules\Config\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected string $moduleNamespace = 'Modules\Config\Http\Controllers';

    public function map(): void
    {
        $this->mapApiRoutesV1();
    }


    protected function mapApiRoutesV1(): void
    {
        Route::prefix('api/v1/configs')
            ->middleware('api')
            ->namespace($this->moduleNamespace)
            ->group(module_path('Config', '/Routes/api.php'));
    }

}
