<?php

namespace App\Http\Middleware\Admin;


use Closure;

use App\Models\VoteModel;

use Auth;

class VoteModelAuth
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
        if (!VoteModel::where([
            ['id', '=', $request->route('vote')], //获取路由参数
            ['admin_id', '=', Auth::user()->id],
            ['end', '>', time()]
        ])->first()) {
            flash('你没有权限管理这个项目或项目已过期');
            return back();
        }
        return $next($request);
    }
}
