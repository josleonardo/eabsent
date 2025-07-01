<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class MenuAccessCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()->getName();

        if (! $routeName) {
            abort(403, 'Unauthorized');
        }

        $prefix = explode('.', $routeName)[0];
        $menuName = str_replace('-', ' ', $prefix);

        if (! Gate::allows('access-menu', $menuName)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
