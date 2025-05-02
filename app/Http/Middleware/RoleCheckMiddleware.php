<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$allowedRoles): Response
    {
        $user = Auth::user();
        $role = $user->roles->first();
        $allowedRoles = in_array($role->id, [1, 2, 3]);

        // If user not exist, inactive, or role not exist, not allowed, or role/role_user inactive
        if (!$user || !$user->active || !$role || !$allowedRoles || !$role->active || !$role->pivot->active) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
