<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserHasPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // return a 403 if the user doesn't have permission to do this
        if (! $request->user()->getPermissionsViaRoles()->pluck('name')->contains($permission)) {
            abort(403);
        }

        return $next($request);
    }
}
