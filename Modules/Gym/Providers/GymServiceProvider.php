<?php

namespace Modules\Gym\Providers;

use Illuminate\Support\ServiceProvider;

class GymServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected string $moduleName = 'Gym';

    /**
     * @var string $moduleNameLower
     */
    protected string $moduleNameLower = 'gym';

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
        $this->loadCommands();
    }

    public function loadCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Modules\Gym\Console\ImageFakeDeleteCommand::class,
                \Modules\Gym\Console\GymDeleteReservedCommand::class,
            ]);
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->addDependencyInjection();
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

    protected function addDependencyInjection(): void
    {
        # GymRepository
        $gymRepository = new \Modules\Gym\Http\Repositories\GymRepository();
        $this->app->singleton('GymRepository', function ($app) use ($gymRepository) {
            return $gymRepository;
        });

        # GymService
        $this->app->singleton('GymService', function ($app) use ($gymRepository) {
            return new \Modules\Gym\Services\GymService($gymRepository);
        });

        # CategoryRepository
        $categoryRepository = new \Modules\Gym\Http\Repositories\CategoryRepository();
        $this->app->singleton('CategoryRepository', function ($app) use ($categoryRepository) {
            return $categoryRepository;
        });

        # CategoryService
        $this->app->singleton('CategoryService', function ($app) use ($categoryRepository) {
            return new \Modules\Gym\Services\CategoryService($categoryRepository);
        });

        # TagRepository
        $tagRepository = new \Modules\Gym\Http\Repositories\TagRepository();
        $this->app->singleton('TagRepository', function ($app) use ($tagRepository) {
            return $tagRepository;
        });

        # TagService
        $this->app->singleton('TagService', function ($app) use ($tagRepository) {
            return new \Modules\Gym\Services\TagService($tagRepository);
        });

        # KeywordRepository
        $keywordRepository = new \Modules\Gym\Http\Repositories\KeywordRepository();
        $this->app->singleton('KeywordRepository', function ($app) use ($keywordRepository) {
            return $keywordRepository;
        });

        # KeywordService
        $this->app->singleton('KeywordService', function ($app) use ($keywordRepository) {
            return new \Modules\Gym\Services\KeywordService($keywordRepository);
        });

        # SportRepository
        $sportRepository = new \Modules\Gym\Http\Repositories\SportRepository();
        $this->app->singleton('SportRepository', function ($app) use ($sportRepository) {
            return $sportRepository;
        });

        # SportService
        $this->app->singleton('SportService', function ($app) use ($sportRepository) {
            return new \Modules\Gym\Services\SportService($sportRepository);
        });

        # AttributeRepository
        $attributeRepository = new \Modules\Gym\Http\Repositories\AttributeRepository();
        $this->app->singleton('AttributeRepository', function ($app) use ($attributeRepository) {
            return $attributeRepository;
        });

        # AttributeService
        $this->app->singleton('AttributeService', function ($app) use ($attributeRepository) {
            return new \Modules\Gym\Services\AttributeService($attributeRepository);
        });

        # CommentRepository
        $commentRepository = new \Modules\Gym\Http\Repositories\CommentRepository();
        $this->app->singleton('CommentRepository', function ($app) use ($commentRepository) {
            return $commentRepository;
        });

        # CommentService
        $this->app->singleton('CommentService', function ($app) use ($commentRepository) {
            return new \Modules\Gym\Services\CommentService($commentRepository);
        });

        # ReserveRepository
        $reserveRepository = new \Modules\Gym\Http\Repositories\ReserveRepository();
        $this->app->singleton('ReserveRepository', function ($app) use ($reserveRepository) {
            return $reserveRepository;
        });

        # ReserveService
        $this->app->singleton('ReserveService', function ($app) use ($reserveRepository) {
            return new \Modules\Gym\Services\ReserveService($reserveRepository);
        });

        # ReserveTemplateRepository
        $reserveTemplateRepository = new \Modules\Gym\Http\Repositories\ReserveTemplateRepository();
        $this->app->singleton('ReserveTemplateRepository', function ($app) use ($reserveTemplateRepository) {
            return $reserveTemplateRepository;
        });
        # ReserveTemplateService
        $this->app->singleton('ReserveTemplateService', function ($app) use ($reserveTemplateRepository,$gymRepository) {
            return new \Modules\Gym\Services\ReserveTemplateService($reserveTemplateRepository,$gymRepository);
        });

    }

    private function loadHelperFunctions(): void
    {
        $separator = DIRECTORY_SEPARATOR;
        $path = __DIR__ . $separator . '..' . $separator . 'Helper' . $separator . 'helpers.php';
        if (file_exists($path)) {
            require_once $path;
        }
    }
}
