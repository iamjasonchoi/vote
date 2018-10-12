<?php

namespace App\Http\Middleware\Admin;


use Illuminate\Support\Facades\Auth;

use Closure;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //判断是否登录状态
        if (Auth::guest() && $request->path() != 'admin/index') {
            return redirect(route('admin.login'));
        }

        if (!Auth::guest() && $request->path() == 'admin/index') {
            return redirect(route('admin.index.index'));
        }

        return $next($request);
    }
}
