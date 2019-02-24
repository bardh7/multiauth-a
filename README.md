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

By default, `config/multiauth.php` contains web and admin guards defined.

The web guard is a default guard and should be not removed, bacause it servers as a fallback and a default guard.
It is prety much like Laravels default auth guard.
Default web auth routes are as following:

- http://example.test/auth/login
- http://example.test/auth/register

...
and it continues like this following Laravel auth routes convention.

To use web guard, all you have to do is add web.auth middleware to your Controller or Route Middleware.

Admin is another guard that can be used to protect your application.
You can add as many guards as you want.

Based on the default admin guard configuration, the use of it would look pretty much like this:

Routes:
- http://example.test/panel/auth/login
- http://example.test/panel/auth/register
...

Multiauth will set default guard  to 'admin' for all routes with 'panel' prefix.

To use the admin guard, all you have to do is add admin.auth middleware to your Controller or Route Middleware.

If you want to see it in action, I've created a demo app.

