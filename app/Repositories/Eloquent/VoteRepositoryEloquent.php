<?php

namespace App\Repositories\Eloquent;


use Prettus\Repository\Eloquent\BaseRepository;

use Prettus\Repository\Criteria\RequestCriteria;

use App\Repositories\Contracts\VoteRepository;

use App\Models\Vote;

use App\Validators\VoteValidator;

use Excel;

/**
 * Class VoteRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class VoteRepositoryEloquent extends BaseRepository implements VoteRepository
{
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
            $storeArr = [];
            for($i = 0; $i < $size; $i++)
            {
                $storeArr[] = [
                    'name' => $excelData[$i][0],
                    'vote_model_id' => $vote_model_id
                ];
            }
            $this->model->insert($storeArr);

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
        return $this->model->where(['vote_model_id' => $id])
                    ->paginate(9);
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
 */
    
}
