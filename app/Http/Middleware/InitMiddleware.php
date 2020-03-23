<?php

namespace App\Http\Middleware;

use Config;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class InitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $hostname = $request->getHttpHost();
        return $next($request);
    }
}
