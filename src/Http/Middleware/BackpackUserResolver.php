<?php

namespace Lagdo\Dbadmin\Backpack\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Closure;

class BackpackUserResolver
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // This is required by the "can:xxx" and other middlewares. Should then be called before them.
        Auth::shouldUse('backpack');

        return $next($request);
    }
}
