<?php

namespace Autoluminescent\Multiauth;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Autoluminescent\Multiauth\Commands\AuthCommand;
use Illuminate\Support\Facades\View;
use Autoluminescent\Multiauth\User;
use Autoluminescent\Multiauth\Middleware\VegaAuth;
use Autoluminescent\Multiauth\Middleware\RedirectIfAuthenticated;

class MultiAuthServiceProvider extends ServiceProvider
{
    protected $config = [];
    
	protected $helpers = [
        __DIR__.'/Helpers/MultiAuthHelper.php'
    ];
    
    

    public function __construct($app)
    {
        parent::__construct($app);
    }

    public function boot()
    {
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
        $this->config = config('multiauth');
        $this->registerAuthGuard();

    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        $routeConfig = [
            'namespace' => '\Autoluminescent\Multiauth\Controllers',
            'middleware' => 'web',
        ];

        Route::group($routeConfig, function () {
            $this->loadRoutesFrom(__DIR__.'/Routes/auth_routes.php');
        });
    }

    protected function publishableAssets(): void
    {
        $this->publishes([
            __DIR__.'/../publishable/config/config.php' => config_path('multiauth.php')
        ], 'config');


        $this->publishes([
            __DIR__.'/../Multiauth/Views/' => resource_path('views/vendor/multiauth'),
        ]);

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

    /**
     * Register the package's authentication guard.
     *
     * Credits to Mohamed Said
     * https://github.com/writingink/wink/blob/master/src/WinkServiceProvider.php
     *
     * @return void
     */
    private function registerAuthGuard()
    {

        $guards = [];

        $views = [
            'layout' => 'multiauth::layouts.auth',
            'register' => 'multiauth::register',
            'login' => 'multiauth::login',
            'passwords.email' => 'multiauth::passwords.email',
            'passwords.reset' => 'multiauth::passwords.reset',
        ];

        foreach ($this->config['guards'] as $guard => $auth) {

            Route::aliasMiddleware($guard.'.auth', VegaAuth::class);
            Route::aliasMiddleware($guard.'.guest', RedirectIfAuthenticated::class);

            $auth['name'] = $guard;

            if (isset($auth['views'])) {
                $auth['views'] = array_merge($views, $auth['views']);
            } else {
                $auth['views'] = $views;
            }
            $guards[$auth['domain'] == '' ? 'web' : $auth['domain']] = $auth;

            $this->app['config']->set('auth.providers.'.$guard, [
                'driver' => $auth['provider_driver'],
                'model' => $auth['provider_model'],
            ]);

            $this->app['config']->set('auth.guards.'.$guard, [
                'driver' => $auth['guard_driver'],
                'provider' => $guard,
            ]);

            $this->app['config']->set('auth.passwords.'.$guard, [
                'provider' => $guard,
                'table' => $auth['password_reset_table'],
                'expire' => $auth['password_reset_expires'],
            ]);
        }

        $this->app->singleton('VegaAuth', function ($app) use ($guards) {
            return new \Autoluminescent\Multiauth\VegaAuthManager($app, $guards);
        });
    }
}
