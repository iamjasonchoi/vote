<?php

namespace App\Repositories\Eloquent;


use Prettus\Repository\Eloquent\BaseRepository;

use Prettus\Repository\Criteria\RequestCriteria;

use App\Repositories\Contracts\VoteRepository;

use Illuminate\Support\Facades\DB;

use App\Models\Vote;

use Cache;

use App\Validators\VoteValidator;

use App\Traits\ReturnFormatTrait;

use App\Traits\DoQueueTrait;

use Excel;

/**
 * Class VoteRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class VoteRepositoryEloquent extends BaseRepository implements VoteRepository
{

    use ReturnFormatTrait;

    use DoQueueTrait;
    
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Vote::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

/**
 * -------------------------------------------------
 * Excel 操作
 * -------------------------------------------------
 */
    /**
     * ImportExcel 候选人批量导入
     * @author leekachung <leekachung17@gmail.com>
     * @param  [type] $file          [description]
     * @param  [type] $title         [description]
     * @param  [type] $vote_model_id [description]
     * @return [type]                [description]
     */
    public function importCandidate($file, $title, $vote_model_id)
    {
       Excel::load($file, function ($reader) 
            use ($title, $vote_model_id) {
            //获取excel内容
            $excelData = $reader->getSheet()->toArray();
            //获取excel标题
            $firstRow = array_shift($excelData);
            $fieldid = [];
            $i = 0;
            //判断文件格式与模版是否一致
            foreach($firstRow as $k => $v){
                foreach ($title as $kk => $vv){
                    if($v==$vv){
                        $fieldid[$kk] = $vv;
                        $i++;
                        continue;
                    }
                }
            }
            unset($i);
            if(count($fieldid) != count($firstRow)){
                flash('上传文件格式与模板不符合')->error();
                return;
            }
            //批量入库
            $size = sizeof($excelData);
            if ($size == 0) {
                flash('导入失败，excel为空')->error();
                return;
            }

            $storeArr = [];
            for($i = 0; $i < $size; $i++)
            {
                if (!$this->model->trimAll($excelData[$i][0])) {
                    continue;
                }
                $storeArr[] = [
                    'name' => $this->model->trimAll($excelData[$i][0]),
                    'vote_model_id' => $vote_model_id
                ];
            }

            //开启事务
            DB::beginTransaction();
            try {
                $this->model->insert($storeArr);
                //提交事务
                DB::commit();
                flash('导入成功');
            } catch (QueryException $e) {
                //事务回滚
                DB::rollback();
                flash('导入失败，请检查excel数据')->error();
            }
            
            return;
        }); 
    }

/**
 * ----------------------------------------------------
 * ShowCandidate && DeleteCandidate 显示候选人&&清空候选人 
 * ----------------------------------------------------
 */
    /**
     * ShowCandidateList 显示候选人列表
     * @author leekachung <leekachung17@gmail.com>
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function showCandidateList($id)
    {
        return $this->model->where([
            'vote_model_id' => $id,
            'is_waiting' => 0
        ])->paginate(9);
    }

    /**
     * DeleteCandidateGather 清空该项目所有候选人
     * @author leekachung <leekachung17@gmail.com>
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function deleteCandidateGather($id)
    {
        $this->model->where(['vote_model_id' => $id])->delete();
        return;
    }

/**
 * -------------------------------------------------
 * Api 返回前端数据
 * -------------------------------------------------
 *//**
     * getCandidateList 获取候选人列表
     * @author leekachung <leekachung17@gmail.com>
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getCandidateList($id)
    {
        //设置缓存 减少查库
        if (!Cache::has('CandidateList')) {
            Cache::forever('CandidateList', $this->model->where([
                'vote_model_id' => $id,
                'is_waiting' => 0
            ])->get());
        }

        return Cache::get('CandidateList');
    }
    
    /**
     * voteNow 投票
     * @author leekachung <leekachung17@gmail.com>
     * @param  [type] $request       [description]
     * @param  [type] $vote_model_id [description]
     * @return [type]                [description]
     */
    public function addBehalf($request, $vote_model_id)
    {

        foreach ($request->name as $key => $value) {
            $this->model->where([
                'vote_model_id' => $vote_model_id,
                'id' => $value
            ])->increment('vote_num');
        }

        return;
    }

    /**
     * addRecommended 添加推荐人选票数
     * @author leekachung <leekachung17@gmail.com>
     * @param  [type] $request       [description]
     * @param  [type] $vote_model_id [description]
     */
    public function addRecommended($request, $vote_model_id)
    {

        foreach ($request->other_name as $key => $value) {
            //判断是否存在推荐人选记录
            if ($this->model->where([
                'vote_model_id' => $vote_model_id,
                'name' => $value
            ])->first()) {
                //存在： 增加票数
                $this->model->where([
                    'vote_model_id' => $vote_model_id,
                    'name' => $value
                ])->increment('vote_num');
            } else {
                //不存在： 新建候选人并默认票数为1
                $this->model->insert([
                    'name' => $value,
                    'vote_model_id' => $vote_model_id,
                    'vote_num' => 1,
                    'is_waiting' => 1
                ]);
            }
        }

        return;   
    }

    /**
     * flushCache 清空展示候选人API数据
     * @author leekachung <leekachung17@gmail.com>
     * @return [type] [description]
     */
    public function flushCache()
    {
        Cache::forget('CandidateList');

        return;
    }
    
}
