# Laravel Multi-Auth

## Introduction

Laravel Dynamic Auth is a package that allows you to create multiple guards dynamically.

## Installation

To install Multi-Auth, require it via Composer:

```sh
composer require autoluminescent/multiauth
```

Once Composer is done, run the following command:

```sh
php artisan multiauth:install
```

Guards are handled based on defined guards on  `config/multiauth.php` and first URI segment.
If the match is found, we set the Auth Default driver to the ‘matched guard’, else we set the default driver to ‘web’.


By default, `config/multiauth.php` ships with ‘web’ and ‘admin’ guards.


### Web guard

The ‘web’ guard is considered as a default or a fallback guard, when no other matches are found/defined.
It is pretty much like Laravel's default auth guard.

Default ‘web’ auth routes are as following:

- http://example.test/auth/login
- http://example.test/auth/logout
- http://example.test/auth/password/email
- http://example.test/auth/password/reset
- http://example.test/auth/register


To use the ‘web’ guard, all you have to do is add web.auth middleware to your Controllers or Routes.

### Admin guard
Admin is just an example guard that how you can add other guards.
You can add as many guards as you want.

Routes for the admin guard are as following:

- http://example.test/admin/auth/login
- http://example.test/admin/auth/logout
- http://example.test/admin/auth/password/email
- http://example.test/admin/auth/password/reset
- http://example.test/admin/auth/register


Multiauth will set default guard  to 'admin' for all routes with ‘admin’ prefix.

To use the admin guard, all you have to do is add admin.auth middleware to your Controllers or Routes

## Multiauth Config

The config contains a basic explanation on how to setup guards. The domain key in guards is basicly the identifier that will match against the first segment of the URI.
So if the domain is set to 'admin' then all routes with the 'admin' prefix will load the 'admin' guard.

```php

<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Multiauth Guards
    |--------------------------------------------------------------------------
    | Here you can define guards for seperate segments / domains
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
            'user_model' => \Autoluminescent\Multiauth\User::class,
            'password_reset_table' => 'password_resets',
            'password_reset_expires' => 60,
            'allow_registration' => true

        ],

        'admin' => [
            'domain' => 'admin',
            'prefix' => 'admin/auth',
            'refirect_after_login' => '/admin',
            'guard_driver' => 'session',
            'provider_driver' => 'eloquent',
            'user_model' => \Autoluminescent\Multiauth\User::class,
            'password_reset_table' => 'password_resets',
            'password_reset_expires' => 60,
            'allow_registration' => false,
            
            // You can replace the layout and other blade views with your custom views.
            'views' => [
				'layout' => 'multiauth::layouts.auth',
				'login' => 'multiauth::login',
			],
        ],

    ],

];


```


.

I will setup a demo project soon...


