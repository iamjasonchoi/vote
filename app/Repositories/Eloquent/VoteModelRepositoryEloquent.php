<?php

namespace App\Repositories\Eloquent;


use Prettus\Repository\Eloquent\BaseRepository;

use Prettus\Repository\Criteria\RequestCriteria;

use App\Repositories\Contracts\VoteModelRepository;

use App\Models\VoteModel;

use App\Validators\VoteModelValidator;

use Validator;

/**
 * Class VoteModelRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class VoteModelRepositoryEloquent extends BaseRepository implements VoteModelRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return VoteModel::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * NewVoteProject 创建新的投票项目
     * @author leekachung <leekachung17@gmail.com>
     * @param  [Object] $request  [表单信息]
     * @param  [int] $admin_id [管理员id]
     * @return [Array / Boolean]
     */
    public function createVoteModel($request, $admin_id)
    {
        //判断项目名是否重复
        if ($this->model->where([
            'name' => $request->name,
            'admin_id' => $admin_id
        ])->first()) {
            return $this->model->ReturnFormat(206, '项目名重复');
        }

        $data = [
            'name' => $request->name,
            'start' => strtotime($request->start),
            'end' => strtotime($request->end),
            'admin_id' => $admin_id,
        ];

        return $this->model->insert($data);
    }

    /**
     * ShowVoteList 在首页显示个人的投票项目列表
     * @author leekachung <leekachung17@gmail.com>
     * @param  [int] $admin_id [管理员id]
     * @return
     */
    public function showVoteList($admin_id)
    {
        return $this->model->where([
            ['admin_id', '=',  $admin_id],
            ['end', '>', time()]
        ])->orderBy('start')->paginate(3);
    }

    /**
     * VoteProjectDetail 投票项目详情
     * @author leekachung <leekachung17@gmail.com>
     * @param  [int] $vote_id  [投票模型id]
     * @param  [int] $admin_id [管理员id]
     * @return [type]
     */
    public function showVoteDetail($vote_id, $admin_id)
    {

        //TODO: 访问量 投票总人数 未投票人数

    }

    /**
     * EditVoteModel 获取修改页面信息
     * @author leekachung <leekachung17@gmail.com>
     * @param  [type] $vote_id  [description]
     * @param  [type] $admin_id [description]
     * @return [type]           [description]
     */
    public function editVoteModel($vote_id, $admin_id)
    {
        $res = $this->model->where(['id' => $vote_id])->get();
        $res[0]['start'] = $this->model
                            ->dateFormat($res[0]['start']);
        $res[0]['end'] = $this->model
                            ->dateFormat($res[0]['end']);
        return $res[0];
    }

    /**
     * UpdateVoteModel 修改投票
     * @author leekachung <leekachung17@gmail.com>
     * @param  [type] $request [description]
     * @param  [type] $vote_id [description]
     * @return [type]          [description]
     */
    public function updateVoteModel($request, $vote_id, $admin_id)
    {
        //判断项目名是否重复
        if ($this->model->where([
            ['name', '=', $request->name],
            ['admin_id', '=', $admin_id],
            ['id', '<>', $vote_id]
        ])->first()) {
            return $this->model->ReturnFormat(206, '项目名重复');
        }
        
        $data = [
            'name' => $request->name,
            'start' => strtotime($request->start),
            'end' => strtotime($request->end),
        ];
        $this->model->where(['id' => $vote_id])->update($data);

        return;
    }

    
}
