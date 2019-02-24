<?php

namespace Autoluminescent\Multiauth;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Autoluminescent\Multiauth\Commands\InstallCommand;

class MultiAuthServiceProvider extends ServiceProvider
{
    protected $helpers = [
        __DIR__.'/Helpers/MultiAuthHelper.php',
    ];

    public function boot()
    {

        if (! isset($this->app['config']['multiauth']['guards'])) {
            return;
        }

        $this->registerRoutes();
        $this->loadViewsFrom(__DIR__.'/Views', 'multiauth');
        $this->publishableAssets();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerHelpers();

        $this->mergeConfigFrom(__DIR__.'/../publishable/config/config.php', 'multiauth');

        $this->app->singleton('Multiauth', function ($app) {
            return new \Autoluminescent\Multiauth\AuthManager($app);
        });

        $this->commands([
            InstallCommand::class
        ]);
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group([
            'namespace' => '\Autoluminescent\Multiauth\Controllers',
            'middleware' => 'web',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/Routes/auth_routes.php');
        });
    }

    protected function publishableAssets(): void
    {
        $this->publishes([
            __DIR__.'/../publishable/config/config.php' => config_path('multiauth.php'),
        ], 'multiauth-config');

        $this->publishes([
            __DIR__.'/../Multiauth/Views/' => resource_path('views/vendor/multiauth'),
        ], 'multiauth-views');
    }

    /**
     * Register package helpers
     */
    public function registerHelpers()
    {
        foreach ($this->helpers as $helper) {
            if (file_exists($helper)) {
                require_once($helper);
            }
        }
    }
}
