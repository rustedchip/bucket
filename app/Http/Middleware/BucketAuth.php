<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BucketAuth
{
    public function handle(Request $request, Closure $next): Response
    {
    
        $validToken = env('API_TOKEN');
        
        if ($request->header('Authorization') !== 'Bearer ' . $validToken) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        return $next($request);
    }
}
