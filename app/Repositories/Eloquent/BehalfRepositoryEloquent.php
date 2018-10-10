<?php

namespace App\Repositories\Eloquent;


use Prettus\Repository\Eloquent\BaseRepository;

use Prettus\Repository\Criteria\RequestCriteria;

use App\Repositories\Contracts\BehalfRepository;

use App\Models\Behalf;

use App\Validators\BehalfValidator;

use Excel;

/**
 * Class BehalfRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class BehalfRepositoryEloquent extends BaseRepository implements BehalfRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Behalf::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * ImportExcel 代表批量导入
     * @author leekachung <leekachung17@gmail.com>
     * @param  [type] $file          [description]
     * @param  [type] $title         [description]
     * @param  [type] $vote_model_id [description]
     * @return [type]                [description]
     */
    public function importBehalf($file, $title, $vote_model_id)
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
                    'student_id' => $excelData[$i][1],
                    'vote_model_id' => $vote_model_id
                ];
            }
            $this->model->insert($storeArr);

            return;
        });
    }

    /**
     * ExportExcelModel 导出excel模版
     * @author leekachung <leekachung17@gmail.com>
     * @param  [type] $filename  [description]
     * @param  [type] $sheetname [description]
     * @param  [type] $title     [description]
     * @param  [type] $letter    [description]
     * @param  [type] $format    [description]
     * @return [type]            [description]
     */
    public function exportModel(
        $filename, $sheetname, $title, $letter, $format)
    {
        Excel::create($filename, function ($excel) 
            use ($sheetname, $title, $letter) {

            $excel->sheet($sheetname, function ($sheet) 
                use ($title, $letter) {
                for ($i=0; $i < count($title); $i++) { 
                    $sheet->setWidth($letter[$i], '25');
                    $sheet->setColumnFormat([
                        $letter[$i] => '@'
                    ]); //设置单元格样式为文本
                }
                $sheet->with($title); //填充数据
            });

        })->export($format);
    }

    /**
     * ShowBehalfList 显示代表列表
     * @author leekachung <leekachung17@gmail.com>
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function showBehalfList($id)
    {
        return $this->model->where(['vote_model_id' => $id])
                ->paginate(9);
    }

    /**
     * ShowSignNum 显示签到人数
     * @author leekachung <leekachung17@gmail.com>
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function showSignNum($id)
    {
        return $this->model->where([
            'vote_model_id' => $id,
            'is_sign' => 1
        ])->count();
    }

    /**
     * ShowVoteTotalNum && NotVote 显示投票总人数和未投票人数
     * @author leekachung <leekachung17@gmail.com>
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function showVotePeople($id)
    {
        $voteTotal = $this->model
                    ->where(['vote_model_id' => $id])->count();
        $notVote = $this->model->where([
            'vote_model_id' => $id,
            'is_vote' => 0
        ])->count();
        return [
            'voteTotal' =>$voteTotal, 
            'notVote' => $notVote
        ];
    }
    
    /**
     * DeleteBehalfGather 清空该项目所有代表
     * @author leekachung <leekachung17@gmail.com>
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function deleteBehalfGather($id)
    {
        $this->model->where(['vote_model_id' => $id])->delete();
        return;
    }

}
