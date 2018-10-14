<?php

namespace App\Http\Middleware\Api;


use Closure;

use Cache;

use App\Models\VoteModel;

use App\Traits\ReturnFormatTrait;

class SetStatus
{    
    use ReturnFormatTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $vid = $request->vote_model_id;
        if (!Cache::has('vote_model_id'.$vid)) {
            $model = VoteModel::where(['id' => $vid])
                    ->first();
            //获取距离投票过期时间 单位：分钟
            $diff = $model->end - time();
            if ($diff%60 == 0) {
                $expired = $diff/60;
            } else {
                $expired = round($diff/60)+1;
            }
            
            if ($expired > 0) {
                Cache::put('vote_model_id'.$vid, $vid, $expired);
            } else {
                return $this->ReturnJsonResponse(203, '投票已结束');
            }
        }
        
        return $next($request);
    }
}
