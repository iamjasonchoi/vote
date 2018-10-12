<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Tymon\JWTAuth\Facades\JWTAuth;

use Illuminate\Database\QueryException;

use DB;

use App\Jobs\VoteRequest;

use App\Repositories\Eloquent\BehalfRepositoryEloquent;

use App\Repositories\Eloquent\BehalfRepositoryEloquent as BehalfRepository;

use App\Repositories\Eloquent\VoteRepositoryEloquent;

use App\Repositories\Eloquent\VoteRepositoryEloquent as VoteRepository;

class VoteController extends Controller
{
    private $behalf;

    private $vote;

    public $max_num = 50; //允许进入Redis队列最大值

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

        //进入队列 若队列已满 0.5s后请求
        while (!$this->vote->doQueue('Candidate', 70)) {
            usleep(300000);
        }

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

        //进入投票队列 若队列已满 0.5s后请求
        while (!$this->vote->doQueue('behalf', 50)) {
            usleep(500000);
        }

        //开启事务
        DB::beginTransaction();
        try {
            //候选人票数增加
            $this->vote->voteNow($request, $auth->vote_model_id);
            //确认代表投票
            $this->behalf->checkVote($auth->id);
            //提交事务
            DB::commit();

        } catch (QueryException $e) {
            //事务回滚
            DB::rollback();

            return $this->vote->ReturnJsonResponse(
                206, '投票失败，请重试'
            );
        }

        return $this->vote->ReturnJsonResponse(200, '投票成功');
    }

}
