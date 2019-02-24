<?php

namespace Autoluminescent\Multiauth\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
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
        $currentGuard = multiauth()->guard();
        auth()->setDefaultDriver($currentGuard);

        if (! Auth::check()) {
            return redirect(multiauth()->prefix().'/login');
        }

        return $next($request);
    }
}
