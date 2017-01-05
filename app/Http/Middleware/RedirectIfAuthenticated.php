<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return redirect('/');
        }

        return $next($request);
    }



//    public function handle($request, Closure $next)
//    {
//        // 设置登录之后，输入/auth/login的跳转地址
//        if ($this->auth->check()) {
//            return redirect('/');
//        }
//
//        return $next($request);
//    }



}
