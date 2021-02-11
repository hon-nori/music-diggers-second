<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class SessionCheck
{
    // ユーザ情報のSessionチェック
    public function handle($request, Closure $next)
    {
        $userdata = session('userdata');
        if (empty($userdata)) {
            return redirect('home');
        }

        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
