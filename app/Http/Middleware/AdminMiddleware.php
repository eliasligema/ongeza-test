<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Response;

class AdminMiddleware
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
        $user = user();
        if (!$user || !($user->is_admin || $user->is_manager)){
            // return new Response(view('error.403')->with('role', 'ADMIN'));
        }

        return $next($request);
    }
}
