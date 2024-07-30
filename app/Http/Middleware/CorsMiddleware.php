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
        $allowedOrigins = [env('FRONTEND_A'),env('FRONTEND_B'),env('FRONTEND_C'),env('FRONTEND_D') ];

        if($request->server('HTTP_ORIGIN')){
            if (in_array($request->getHttpHost(), $allowedOrigins)) {
              return $next($request)
                  ->header('Access-Control-Allow-Origin', $request->getHttpHost())
                  ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
                  ->header('Access-Control-Allow-Headers', '*');
          }
        }

        return $next($request);
     
    }
      
}
