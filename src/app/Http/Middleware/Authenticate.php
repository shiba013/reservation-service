<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            $path = $request->path();
            session()->flash('message', 'ログインしてください');

            if (strpos($path, 'admin') === 0) {
                session(['login_type' => 'admin']);
                return '/admin/login';

            } elseif (strpos($path, 'owner') === 0) {
                session(['login_type' => 'owner']);
                return 'owner/login';

            } else {
                session(['login_type' => 'user']);
                return route('login');
            }
        }
    }
}
