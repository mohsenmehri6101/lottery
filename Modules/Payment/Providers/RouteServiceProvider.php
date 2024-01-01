<?php

namespace Modules\Payment\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected string $moduleNamespace = 'Modules\Payment\Http\Controllers';

    public function boot()
    {
        parent::boot();
    }

    public function map()
    {
        $this->mapApiV1Routes();
    }

    protected function mapApiV1Routes()
    {
        Route::prefix('api/v1')
            ->name('api_v1_payment_')
            ->middleware('api')
            ->namespace($this->moduleNamespace)
            ->group(module_path('Payment', '/Routes/api_v1.php'));
    }

}
