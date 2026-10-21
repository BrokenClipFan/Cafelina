<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Usage: ->middleware('role:cashier,admin')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasRole(...$roles)) {
            if ($request->expectsJson()) {
                abort(403, 'You do not have permission to access this.');
            }

            // Send them to the page their role is allowed to use instead of a bare 403.
            if ($user) {
                return redirect()->route($user->homeRoute())
                    ->with('error', 'You do not have permission to access that page.');
            }

            abort(403);
        }

        return $next($request);
    }
}
