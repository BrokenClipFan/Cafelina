<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsApproved
{
    /**
     * Block pending / rejected accounts from everything except the
     * "waiting for approval" page.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! $user->isApproved()) {
            if ($request->expectsJson()) {
                abort(403, 'Your account is waiting for admin approval.');
            }

            return redirect()->route('account.pending');
        }

        return $next($request);
    }
}
