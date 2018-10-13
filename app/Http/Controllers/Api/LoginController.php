<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Http\Requests\ApiAuthRequest;

use Tymon\JWTAuth\Facades\JWTAuth;

use App\Repositories\Eloquent\BehalfRepositoryEloquent;

use App\Repositories\Eloquent\BehalfRepositoryEloquent as BehalfRepository;

use App\Repositories\Eloquent\VoteModelRepositoryEloquent;

use App\Repositories\Eloquent\VoteModelRepositoryEloquent as VoteModelRepository;

use AuthenticatesUsers;

class LoginController extends Controller
{

    protected $guard = 'api'; 

	private $behalf;

    private $vote_model;

	public function __construct(
        BehalfRepository $BehalfRepository,
        VoteModelRepository $VoteModelRepository)
    {
		$this->behalf = $BehalfRepository;
        $this->vote_model = $VoteModelRepository;
	}

	/**
     * Login && Sign 代表签到模块
     * @author leekachung <leekachung17@gmail.com>
     * @param  ApiAuthRequest $request       [description]
     * @param  [type]         $vote_model_id [description]
     * @return [type]                        [description]
     */
    public function Login(ApiAuthRequest $request)
    {
        //进入队列 若队列已满 0.3s后请求
        while (!$this->behalf->doQueue('Login', 50, 300000)) {
            usleep(300000);
        }

    	$user = $this->behalf->ApiAuth($request);
        
    	if ($user) {

    		$this->behalf->signBehalf($user->id); //登录自动签到

            //是否已投票
            if ($this->behalf->isVote($user->id)) {
                return $this->behalf->ReturnJsonResponse(207, '已投票');
            }

    		$token = JWTAuth::fromUser($user); //获取token

    		return $this->behalf->ReturnJsonResponse(
    			200, '签到成功', $token, 'Bearer '
    		);

    	} else {
    		return $this->behalf
                    ->ReturnJsonResponse(206, '验证身份失败');
    	}
    }

}
