<?php

namespace Autoluminescent\Multiauth\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class VegaAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $redirect = '';


        $redirect = vega_auth()->guard()->prefix();
        $currentGuard = vega_auth()->guard()->name();

        auth()->setDefaultDriver($currentGuard);


        if (! Auth::check()) {
            return redirect($redirect.'/login');
        }


        return $next($request);
    }
}
