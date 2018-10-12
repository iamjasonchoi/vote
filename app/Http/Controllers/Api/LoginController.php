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

    public function Index($id)
    {
        return $this->vote_model->ReturnJsonResponse(
            200, $id, '',$this->vote_model->showVoteMes($id)
        );
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
    	$user = $this->behalf->ApiAuth($request);
        	if ($user) {

    		$this->behalf->signBehalf($user->id); //登录自动签到
    		$token = JWTAuth::fromUser($user); //获取token

    		return $this->behalf->ReturnJsonResponse(
    			200, '签到成功', $token, 'Bearer '
    		);

    	} else {
    		return $this->behalf
                    ->ReturnJsonResponse(206, '权限不允许操作');
    	}
    }

}
