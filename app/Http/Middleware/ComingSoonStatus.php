<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class ComingSoonStatus
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * @var array
     */
    protected $except = [];

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
        $allowed = ['localhost', 'project.co', 'dev.sematanzania.org', 'malezi.sematanzania.org', 'dev-malezi.sematanzania.org'];

        if( 
            !in_array($hostname, $allowed) && 
            !$request->is('welcome') && 
            (time() < strtotime(config('app.lunch_time')))
        ) {
            return redirect('welcome');
        }

        return $next($request);
    }
}
