<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Multiauth Guards
    |--------------------------------------------------------------------------
    | Here you can define guards for seperate path's / domains
    */

    'guards' => [

        /*
        |--------------------------------------------------------------------------
        | Default Web Guard
        |--------------------------------------------------------------------------
        | This is the default web guard and is active for all routes except for
        | the other guard routes you define.
        |
        */

        'web' => [

            /*
            | Web guard domain should be empty.
            */
            'domain' => '',

            /*
            | Prefix for auth routes
            | Example: http://example.test/auth
            */
            'prefix' => 'auth',
            'redirect_after_login' => '/home',
            'guard_driver' => 'session',
            'provider_driver' => 'eloquent',
            'user_model' => \Autoluminescent\Multiauth\User::class,
            'password_reset_table' => 'password_resets',
            'password_reset_expires' => 60,
            'allow_registration' => true

        ],

        'admin' => [
            'domain' => 'admin',
            'prefix' => 'admin/auth',
            'redirect_after_login' => '/admin/dashboard',
            'guard_driver' => 'session',
            'provider_driver' => 'eloquent',
            'user_model' => \Autoluminescent\Multiauth\User::class,
            'password_reset_table' => 'password_resets',
            'password_reset_expires' => 60,
            'allow_registration' => false,
            'views' => [
                'layout' => 'multiauth::layouts.auth',
                'login' => 'multiauth::login',
            ],
        ],

    ],

];
