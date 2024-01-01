<?php

namespace Modules\Authorization\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Authorization\Http\Repositories\GroupRepository;

class AuthorizationServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Authorization';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'authorization';

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
        $this->addDependencyInjection();
        $this->app->register(RouteServiceProvider::class);
        $this->loadCommands();
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
            $this->loadJsonTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
            $this->loadJsonTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
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
        $separator = DIRECTORY_SEPARATOR;
        $path = __DIR__ . $separator . 'Helper' . $separator . 'helpers.php';
        if (file_exists($path)) {
            require_once $path;
        }
    }

    private function loadCommands()
    {
        $this->commands([
            \Modules\Authorization\Console\PermissionCommand::class,
            \Modules\Authorization\Console\RoleCommand::class,
            \Modules\Authorization\Console\SetPermissionAdminCommand::class,
        ]);
    }

    protected function addDependencyInjection()
    {
        $permissionRepository = new \Modules\Authorization\Http\Repositories\PermissionRepository();
        # PermissionService
        $this->app->singleton('PermissionService', function ($app) use ($permissionRepository) {
            return new \Modules\Authorization\Services\PermissionService($permissionRepository);
        });
        # PermissionRepository
        $this->app->singleton('PermissionRepository', function ($app) use ($permissionRepository) {
            return $permissionRepository;
        });

        $roleRepository = new \Modules\Authorization\Http\Repositories\RoleRepository();
        # RoleService
        $this->app->singleton('RoleService', function ($app) use ($roleRepository) {
            return new \Modules\Authorization\Services\RoleService($roleRepository);
        });
        # RoleRepository
        $this->app->singleton('RoleRepository', function ($app) use ($roleRepository) {
            return $roleRepository;
        });
    }
}
