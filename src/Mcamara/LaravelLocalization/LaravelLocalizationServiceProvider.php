<?php

namespace Mcamara\LaravelLocalization;

use Illuminate\Support\ServiceProvider;
use Mcamara\LaravelLocalization\Commands\RouteTranslationsCacheCommand;
use Mcamara\LaravelLocalization\Commands\RouteTranslationsClearCommand;
use Mcamara\LaravelLocalization\Commands\RouteTranslationsListCommand;

class LaravelLocalizationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../config/config.php' => config_path('laravellocalization.php'),
        ], 'config');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['modules.handler', 'modules'];
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $packageConfigFile = __DIR__ . '/../../config/config.php';

        $this->mergeConfigFrom(
            $packageConfigFile,
            'laravellocalization'
        );

        $this->registerBindings();

        $this->registerCommands();
    }

    /**
     * Registers app bindings and aliases.
     */
    protected function registerBindings()
    {
        $this->app->singleton(LaravelLocalization::class, function () {
            return new LaravelLocalization();
        });

        $this->app->alias(LaravelLocalization::class, 'laravellocalization');
    }

    /**
     * Registers route caching commands.
     */
    protected function registerCommands()
    {
        $this->app->singleton('laravellocalizationroutecache.cache', RouteTranslationsCacheCommand::class);
        $this->app->singleton('laravellocalizationroutecache.clear', RouteTranslationsClearCommand::class);
        $this->app->singleton('laravellocalizationroutecache.list', RouteTranslationsListCommand::class);

        $this->commands([
            'laravellocalizationroutecache.cache',
            'laravellocalizationroutecache.clear',
            'laravellocalizationroutecache.list',
        ]);
    }
}
