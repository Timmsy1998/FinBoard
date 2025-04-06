<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Attribute;
use Illuminate\Routing\MiddlewareAlias;

#[MiddlewareAlias('role')]
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (empty($roles)) {
            throw new \InvalidArgumentException("No roles defined in 'role:' middleware.");
        }

        if (!in_array($request->user()?->role, $roles)) {
            abort(403);
        }

        return $next($request);
    }

}
