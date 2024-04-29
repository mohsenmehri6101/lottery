<?php

namespace Modules\Payment\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Payment';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'payment';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        $this->loadHelperFunctions();
        $this->addDependencyInjection();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig(): void
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
            $this->loadJsonTranslationsFrom(module_path($this->moduleName, 'Resources/lang'));
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }

    private function loadHelperFunctions(): void
    {
        $separator = DIRECTORY_SEPARATOR;/* / */
        $path = __DIR__ . $separator . '..' . $separator . 'Helper' . $separator . 'helpers.php';
        if (file_exists($path)) {
            require_once $path;
        }
    }

    protected function addDependencyInjection(): void
    {
        # PaymentPaypingService
        $this->app->singleton('PaymentPaypingService', function ($app) {
            return new \Modules\Payment\Services\PaymentPaypingService();
        });

        # PaymentSadadService
        $this->app->singleton('PaymentSadadService', function ($app) {
            return new \Modules\Payment\Services\PaymentSadadService();
        });

        # PaymentRepository
        $paymentRepository = new \Modules\Payment\Http\Repositories\PaymentRepository();
        $this->app->singleton('PaymentRepository', function ($app) use ($paymentRepository) {
            return $paymentRepository;
        });

        # PaymentService
        $this->app->singleton('PaymentService', function ($app) use ($paymentRepository) {
            return new \Modules\Payment\Services\PaymentService($paymentRepository);
        });

        # FactorRepository
        $factorRepository = new \Modules\Payment\Http\Repositories\FactorRepository();
        $this->app->singleton('FactorRepository', function ($app) use ($factorRepository) {
            return $factorRepository;
        });

        # FactorService
        $this->app->singleton('FactorService', function ($app) use ($factorRepository) {
            return new \Modules\Payment\Services\FactorService($factorRepository);
        });

        # transaction
        $transactionRepository = new \Modules\Payment\Http\Repositories\TransactionRepository();
        $this->app->singleton('TransactionRepository', function ($app) use ($transactionRepository) {
            return $transactionRepository;
        });

        # TransactionService
        $this->app->singleton('TransactionService', function ($app) use ($transactionRepository) {
            return new \Modules\Payment\Services\TransactionService($transactionRepository);
        });
        # transaction

    }

}
