<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role = null)
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        $role = $user->role;
        $allow = explode(',', $role);

        if (!in_array((string)$user->role, $allow)) {
            abort(403, 'アクセス権がありません');
        }
        return $next($request);
    }
}
