<?php

namespace App\Exceptions;

use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Modules\Exception\Services\ExceptionService;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    public mixed $app_debug = null;
    public mixed $app_env = null;
    const app_env_test = 'test';
    const app_env_local = 'local';
    const app_env_develop = 'develop';
    const app_env_production = 'production';

    public function __construct(Container $container)
    {
        $this->app_debug = env('APP_DEBUG', false);
        $this->app_env = env('APP_ENV', self::app_env_production);
        parent::__construct($container);
    }

    public function report(Throwable $e): void
    {
//         dd($e->getMessage(),$e->getLine()/*,$e->getTrace()*/);
        parent::report($e);
        ExceptionService::reporter($e);
    }

    public function render($request, Throwable $e): \Illuminate\Http\Response|JsonResponse|Response|RedirectResponse|null
    {
        return ExceptionService::render(request: $request, exception: $e);
    }

    protected $levels = [];

    protected $dontReport = [];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        // $this->reportable(function (Throwable $e) {});
    }
}
