<?php

namespace Autoluminescent\Multiauth\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
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
        $guard = vega_auth()->guard()->name();

        if (Auth::guard($guard)->check()) {
            return redirect(vega_auth()->guard()->redirectAfterLogin());
        }

        return $next($request);
    }
}
