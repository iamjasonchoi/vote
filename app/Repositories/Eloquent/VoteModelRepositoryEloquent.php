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
     * @param  [type] $request  [description]
     * @param  [type] $admin_id [description]
     * @return [type]           [description]
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
            'admin_id' => $admin_id
        ];

        return $this->model->insert($data);
    }

    /**
     * ShowVoteList 在首页显示个人的投票项目列表
     * @author leekachung <leekachung17@gmail.com>
     * @param  [type] $admin_id [description]
     * @return [type]           [description]
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
     * @param  [type] $vote_id  [description]
     * @param  [type] $admin_id [description]
     * @return [type]           [description]
     */
    public function showVoteDetail($vote_id, $admin_id)
    {
        if (!$this->model->where([
            'id' => $vote_id,
            'admin_id' => $admin_id
        ])->first()) {
            return $this->model->
                ReturnFormat(206, '你没有权限管理这个项目');
        }

    }
    
}
