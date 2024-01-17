<?php

namespace Modules\Geographical\Providers;

use Illuminate\Support\ServiceProvider;

class GeographicalServiceProvider extends ServiceProvider
{

    protected string $moduleName = 'Geographical';

    protected string $moduleNameLower = 'geographical';

    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->addDependencyInjection();
    }

    protected function registerConfig(): void
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower
        );
    }

    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

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

    protected function addDependencyInjection(): void
    {
        # CityService CityRepository
        $cityRepository = new \Modules\Geographical\Http\Repositories\CityRepository();
        $this->app->singleton('CityRepository', function ($app) use ($cityRepository) {
            return $cityRepository;
        });
        $this->app->singleton('CityService', function ($app) use ($cityRepository) {
            return new \Modules\Geographical\Services\CityService($cityRepository);
        });

        # province
        $provinceRepository = new \Modules\Geographical\Http\Repositories\ProvinceRepository();
        $this->app->singleton('ProvinceRepository', function ($app) use ($provinceRepository) {
            return $provinceRepository;
        });
        $this->app->singleton('ProvinceService', function ($app) use ($provinceRepository) {
            return new \Modules\Geographical\Services\ProvinceService($provinceRepository);
        });

    }
}
