<?php

namespace App\Http\Middleware\Api;


use Closure;

use Cache;

use App\Models\VoteModel;

use App\Traits\ReturnFormatTrait;

class CheckStatus
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
        if (!Cache::has('vote_model_id')) {
            return $this->ReturnJsonResponse(203, '投票已结束');
        }
        
        return $next($request);
    }
}
