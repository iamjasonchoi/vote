<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Http\Requests\ApiAuthRequest;

use Tymon\JWTAuth\Facades\JWTAuth;

use Auth;

use App\Repositories\Eloquent\BehalfRepositoryEloquent;

use App\Repositories\Eloquent\BehalfRepositoryEloquent as BehalfRepository;

class LoginController extends Controller
{
	private $behalf;

	public function __construct(BehalfRepository $BehalfRepository)
	{
		$this->behalf = $BehalfRepository;
		$this->guard = Auth::guard('api');
	}

	/**
	 * Login && Sign 代表签到模块
	 * @author leekachung <leekachung17@gmail.com>
	 * @param  ApiAuthRequest $request [description]
	 * @return [type]                  [description]
	 */
    public function login(ApiAuthRequest $request)
    {
    	$user = $this->behalf->ApiAuth($request);
    	if ($user) {
    		$this->behalf->signBehalf($user->id); //登录自动签到
    		$token = JWTAuth::fromUser($user); //获取token
    		
    		return $this->behalf->ReturnJsonResponse(
    			200, 'User Authenticated', $token, 'Bearer '
    		);
    	} else {
    		return $this->behalf->ReturnJsonResponse(206, 'unauthorized');
    	}
    }

}
