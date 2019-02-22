<?php

namespace Autoluminescent\Multiauth;

use Autoluminescent\Trunk\VegaServiceProvider;
use Illuminate\Support\Facades\Route;
use Autoluminescent\Multiauth\Commands\AuthCommand;
use Illuminate\Support\Facades\View;
use Autoluminescent\Multiauth\User;
use Autoluminescent\Multiauth\Middleware\VegaAuth;
use Autoluminescent\Multiauth\Middleware\RedirectIfAuthenticated;

class MultiAuthVegaServiceProvider extends VegaServiceProvider
{
    protected $packageName = 'multiauth';

    protected $commands = [
        AuthCommand::class,
    ];

    protected $helpers = [];

    // Disable vega.auth middleware
    protected $vegaAuthMiddleware = false;

    public function __construct($app)
    {
        parent::__construct($app);
    }

    public function boot()
    {

        parent::boot();

        // Merge Translations
        $this->mergeTranslations(__DIR__.'/../publishable/lang', trunk_lang_path(), $this->packageName);

        $this->registerRoutes();

        // Register Views from your package
        $this->loadViewsFrom(__DIR__.'/Views', $this->packageName);

        $this->publishableAssets();

        //$vegaAuth = app('VegaAuth');
        //dump($vegaAuth->guard()->get());

        // vega()->addProfileMenuItem($this->packageName, 'Auth', '/panel/auth');
        // vega()->addProfileMenuItem($this->packageName, 'devider', '');
        // vega()->addProfileMenuItem($this->packageName, 'Website', '/');
        // vega()->addProfileMenuItem($this->packageName, 'Webapp', '/');

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        $this->mergeConfigs(__DIR__.'/../publishable/config/config.php');

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
            'namespace' => trunk_config($this->packageName.'.package_base_namespace').'Controllers',
            'middleware' => $this->middleware(),
        ];

        Route::group($routeConfig, function () {
            $this->loadRoutesFrom(__DIR__.'/Routes/auth_routes.php');
        });
    }

    protected function publishableAssets(): void
    {
        $this->addPublishableAsset('config/config.php', trunk_config_path('auth.php'));
        $this->addPublishableAsset('lang', trunk_lang_path());
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

        foreach (trunk_config('multiauth.guards') as $guard => $auth) {


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