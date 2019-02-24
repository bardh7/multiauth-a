<?php

namespace Autoluminescent\Multiauth;

use Illuminate\Support\Facades\Route;
use Autoluminescent\Multiauth\Middleware\Authenticate;
use Autoluminescent\Multiauth\Middleware\RedirectIfAuthenticated;

class AuthManager
{
    protected $app;

    protected $guards = [];

    protected $currentGuard = [];

    protected $guardDomain;

    public function __construct($app)
    {
        $this->app = $app;

        $this->registerGuards();
        $this->guardDomain = $this->resolveGuardDomain();
        $this->currentGuard = $this->guards[$this->guardDomain];
    }

    /**
     * Register guards
     *
     * Credits to Mohamed Said for the basic idea
     * https://github.com/writingink/wink/blob/master/src/WinkServiceProvider.php
     *
     * @return void
     */
    public function registerGuards()
    {
        $guards = [];

        $views = [
            'layout' => 'multiauth::layouts.auth',
            'register' => 'multiauth::register',
            'login' => 'multiauth::login',
            'passwords.email' => 'multiauth::passwords.email',
            'passwords.reset' => 'multiauth::passwords.reset',
        ];

        foreach ($this->app['config']['multiauth']['guards'] as $key => $guard) {

            Route::aliasMiddleware($key.'.auth', Authenticate::class);
            Route::aliasMiddleware($key.'.guest', RedirectIfAuthenticated::class);

            $guard['name'] = $key;

            if (isset($guard['views'])) {
                $guard['views'] = array_merge($views, $guard['views']);
            } else {
                $guard['views'] = $views;
            }
            $guards[$guard['domain'] == '' ? 'web' : $guard['domain']] = $guard;

            $this->app['config']->set('auth.providers.'.$key, [
                'driver' => $guard['provider_driver'],
                'model' => $guard['user_model'],
            ]);

            $this->app['config']->set('auth.guards.'.$key, [
                'driver' => $guard['guard_driver'],
                'provider' => $key,
            ]);

            $this->app['config']->set('auth.passwords.'.$key, [
                'provider' => $key,
                'table' => $guard['password_reset_table'],
                'expire' => $guard['password_reset_expires'],
            ]);
        }

        $this->guards = $guards;
    }

    public function view($key)
    {
        return $this->guards[$this->guardDomain]['views'][$key];
    }

    public function resolveGuardName()
    {
        return $this->guards[$this->guardDomain]['name'];
    }

    public function resolveGuardDomain()
    {
        $requestSegment = request()->segment(1);

        if (array_key_exists($requestSegment, $this->guards)) {
            return $requestSegment;
        }

        return 'web';
    }

    public function guard()
    {
        return $this->currentGuard['name'];
    }

    public function broker()
    {
        return $this->currentGuard['name'];
    }


    public function guestMiddleware()
    {
        return $this->currentGuard['name'] . ".guest";
    }


    public function domain()
    {
        return $this->currentGuard['domain'];
    }

    public function prefix()
    {
        return $this->currentGuard['prefix'];
    }

    public function allowRgistration()
    {
        return $this->currentGuard['allow_registration'];
    }



    public function userModel()
    {
        return $this->currentGuard['user_model'];
    }

    public function redirectAfterLogin()
    {
        return $this->currentGuard['refirect_after_login'];
    }

    public function resetPasswordUrl($token)
    {
        return url(config('app.url').route('multiauth.password.reset', $token, false));
    }

    public function getByKey($key)
    {
        if (isset($this->currentGuard[$key])) {
            return $this->currentGuard[$key];
        }

        return false;
    }
}
