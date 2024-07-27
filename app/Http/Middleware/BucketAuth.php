<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BucketAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        // Define your token
        $validToken = env('API_TOKEN');

        // Check if the request has the correct Authorization header
        if ($request->header('Authorization') !== 'Bearer ' . $validToken) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        return $next($request);
    }
}
