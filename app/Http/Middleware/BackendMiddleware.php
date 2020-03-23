<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class BackendMiddleware
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
        if (config('backend.token') != $request->header("Backend-Getway-Token")){
            return response()->json([
                "message"=>"Unauthorized", 
                "status"=>404
            ], 200);
        }

        return $next($request);
    }
}
