<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allowedOrigin = env('FRONTEND_ENDPOINT');

        if($request->server('HTTP_ORIGIN')){
          if (in_array($request->server('HTTP_ORIGIN'), $allowedOrigin)) {
              return $next($request)
                  ->header('Access-Control-Allow-Origin', $request->server('HTTP_ORIGIN'))
                  ->header('Access-Control-Allow-Origin', '*')
                  ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
                  ->header('Access-Control-Allow-Headers', '*');
          }
        }
      
      
        return $next($request);
    }
      
}
