<?php

namespace App\Http\Middleware\Api;


use Auth;

use Closure;

use Tymon\JWTAuth\Exceptions\JWTException;

use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

use Tymon\JWTAuth\Exceptions\TokenExpiredException;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class RefreshToken extends BaseMiddleware
{

    public function handle($request, Closure $next)
    {

        $this->checkForToken($request);

        try {
            //判断登录状态
            if (auth('api')->user()) {

                return $next($request);
            }
            throw new UnauthorizedHttpException('jwt-auth', '请签到');

        } catch (TokenExpiredException $exception) {

            try {
                //刷新token
                $token = $this->auth->parseToken()->refresh();

            } catch (JWTException $e) {

                throw new UnauthorizedHttpException('jwt-auth', $e->getMessage(), $e, $e->getCode());
            }
        }

        $response = $next($request);

        // Send the refreshed token back to the client.        
        // 在响应头中返回新的 token
        return $this->setAuthenticationHeader($next($request), $token);
    }
}
