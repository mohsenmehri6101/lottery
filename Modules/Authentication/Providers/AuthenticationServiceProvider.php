<?php

namespace Modules\Authentication\Providers;

use Illuminate\Support\ServiceProvider;

class AuthenticationServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected string $moduleName = 'Authentication';

    /**
     * @var string $moduleNameLower
     */
    protected string $moduleNameLower = 'authentication';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        $this->loadHelperFunctions();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->addDependencyInjection();
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
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
    public function registerViews()
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
    public function registerTranslations()
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
    public function provides()
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

    private function loadHelperFunctions()
    {
        $separator = DIRECTORY_SEPARATOR;/* / */
        $path = __DIR__ . $separator . '..' . $separator . 'Helper' . $separator . 'helpers.php';
        if (file_exists($path)) {
            require_once $path;
        }
    }

    protected function addDependencyInjection()
    {
        # UserDetailRepository
        $userDetailRepository = new \Modules\Authentication\Http\Repositories\UserDetailRepository();
        $this->app->singleton('UserDetailRepository', function ($app) use ($userDetailRepository) {
            return $userDetailRepository;
        });

        # UserRepository
        $userRepository = new \Modules\Authentication\Http\Repositories\UserRepository();
        $this->app->singleton('UserRepository', function ($app) use ($userRepository) {
            return $userRepository;
        });

        # UserService
        $this->app->singleton('UserService', function ($app) use ($userRepository, $userDetailRepository) {
            return new \Modules\Authentication\Services\UserService($userRepository, $userDetailRepository);
        });
    }
}
