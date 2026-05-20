<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * ApiKeyMiddleware
 *
 * Validates the presence and correctness of an API Key passed
 * via the "X-API-Key" request header.
 *
 * Usage in routes:
 *   Route::middleware('api.key')->group(function () { ... });
 *
 * Set your key in .env:
 *   APP_API_KEY=your-secret-key-here
 */
class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key');

        if (! $apiKey) {
            return response()->json([
                'message' => 'API Key diperlukan. Sertakan header X-API-Key.',
            ], 401);
        }

        if ($apiKey !== config('app.api_key')) {
            return response()->json([
                'message' => 'API Key tidak valid atau tidak diizinkan.',
            ], 403);
        }

        return $next($request);
    }
}
