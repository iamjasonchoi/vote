<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Tymon\JWTAuth\Facades\JWTAuth;

use Illuminate\Database\QueryException;

use DB;

use App\Repositories\Eloquent\BehalfRepositoryEloquent;

use App\Repositories\Eloquent\BehalfRepositoryEloquent as BehalfRepository;

use App\Repositories\Eloquent\VoteRepositoryEloquent;

use App\Repositories\Eloquent\VoteRepositoryEloquent as VoteRepository;

class VoteController extends Controller
{
    private $behalf;

    private $vote;

    public function __construct(
    	BehalfRepository $BehalfRepository,
    	VoteRepository $VoteRepository)
    {
    	$this->behalf = $BehalfRepository;
    	$this->vote = $VoteRepository;
    }

    /**
     * getCandidateList 获取候选人列表
     * @author leekachung <leekachung17@gmail.com>
     * @return [type] [description]
     */
    public function getCandidateList()
    {
        $vote_model_id = auth('api')->user()->vote_model_id;

        return $this->vote->ReturnJsonResponse(
            200, $this->vote->getCandidateList($vote_model_id)
        );
    }

    /**
     * Vote 投票
     * @author leekachung <leekachung17@gmail.com>
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function vote(Request $request)
    {
        $auth = auth('api')->user();
        //开启事务
        DB::beginTransaction();
        try {
            //候选人票数增加
            $this->vote->vote($request, $auth->vote_model_id);
            //确认代表投票
            $this->behalf->checkVote($auth->id);
            DB::commit();
        } catch (QueryException $e) {
            DB::rollback();

            return $this->vote->ReturnJsonResponse(
                206, '投票失败，请重试'
            );
        }

        return $this->vote->ReturnJsonResponse(200, '投票成功');
    }

}
