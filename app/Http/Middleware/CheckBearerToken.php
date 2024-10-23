<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckBearerToken
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah ada token Bearer
        if (!$request->bearerToken()) {
            return response()->json(['message' => 'Token not provided.'], 401);
        }

        // Cek kevalidan token
        try {
            Auth::guard('api')->user(); // Menggunakan guard yang tepat
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid token.'], 401);
        }

        return $next($request);
    }
}

