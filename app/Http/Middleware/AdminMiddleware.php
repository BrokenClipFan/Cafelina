<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isAdmin()) {
            if (! $user || $request->expectsJson()) {
                abort(403);
            }

            return redirect()->route($user->homeRoute())
                ->with('error', 'You do not have permission to access that page.');
        }

        return $next($request);
    }
}
