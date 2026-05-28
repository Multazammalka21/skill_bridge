<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * RoleMiddleware
 *
 * Checks that the authenticated user has the required role.
 *
 * Usage in routes:
 *   Route::middleware('role:admin')->group(function () { ... });
 *   Route::middleware('role:parent')->group(function () { ... });
 */
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, $roles)) {
            // Redirect based on current user role or back to login
            if ($user) {
                return match ($user->role) {
                    'admin'  => redirect()->route('admin.dashboard'),
                    'parent' => redirect()->route('dashboard'),
                    default  => redirect()->route('login'),
                };
            }

            return redirect()->route('login');
        }

        return $next($request);
    }
}
