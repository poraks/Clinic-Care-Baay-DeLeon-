<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1. If the user is not logged in, trigger custom 401 error page
        if (! $request->user()) {
            abort(401, 'Authentication required. Please log in to access this page.');
        }

        // 2. If the logged-in user's role is not allowed on this route, trigger custom 403 error page
        if (! in_array($request->user()->role, $roles, true)) {
            abort(403, 'Unauthorized access. You do not have permission to view this page.');
        }

        return $next($request);
    }
}