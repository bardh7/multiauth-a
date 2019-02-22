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
            'refirect_after_login' => '/home',
            'guard_driver' => 'session',
            'provider_driver' => 'eloquent',
            'provider_model' => \Autoluminescent\Multiauth\User::class,
            'password_reset_table' => 'password_resets',
            'password_reset_expires' => 60,

        ],

        'vega' => [
            'domain' => 'panel',
            'prefix' => 'panel/auth',
            'refirect_after_login' => '/panel',
            'guard_driver' => 'session',
            'provider_driver' => 'eloquent',
            'provider_model' => \Autoluminescent\Multiauth\User::class,
            'password_reset_table' => 'password_resets',
            'password_reset_expires' => 60,
            'views' => [
                'layout' => 'multiauth::layouts.auth',
            ],
        ],

        'app' => [
            'domain' => 'app',
            'prefix' => 'app/auth',
            'refirect_after_login' => '/app',
            'guard_driver' => 'session',
            'provider_driver' => 'eloquent',
            'provider_model' => \Autoluminescent\Multiauth\User::class,
            'password_reset_table' => 'password_resets',
            'password_reset_expires' => 60,

        ],

    ],

];