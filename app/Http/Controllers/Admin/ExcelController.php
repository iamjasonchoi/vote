<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Repositories\Eloquent\BehalfRepositoryEloquent;

use App\Repositories\Eloquent\BehalfRepositoryEloquent as BehalfRepository;

use App\Repositories\Eloquent\VoteRepositoryEloquent;

use App\Repositories\Eloquent\VoteRepositoryEloquent as VoteRepository;

class ExcelController extends Controller
{

    /**
     * ExcelParam Excel模版导出 基本参数
     * @var [type]
     */
    private $letter = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

    public $sheetname;

    public $title;

    public $filename;

    private $behalf;

    public function __construct(
        BehalfRepository $BehalfRepository,
        VoteRepository $VoteRepository)
    {
        $this->middleware('auth.votemodel')
                ->except(['exportModel', 'export_type', 
                    'import']);
        $this->behalf = $BehalfRepository;
        $this->vote = $VoteRepository;
    }

    /**
     * Import Index 导入页面
     * @author leekachung <leekachung17@gmail.com>
     * @param  [type] $vote_model_id [description]
     * @param  [type] $type          [description]
     * @return [type]                [description]
     */
    public function importIndex($vote_model_id, $type)
    {
        if ($type == 1) {
            $model = '代表导入';
        } elseif ($type == 0) {
            $model = '候选人导入';
        } else {
            flash('未允许操作');
            return back();
        }
    	return view('admin.member.import', 
            compact('model', 'type', 'vote_model_id'));
    }

    /**
     * GetUploadExcel 获取上传的excel
     * @author leekachung <leekachung17@gmail.com>
     * @param  Request $request       [description]
     * @param  [type]  $vote_model_id [description]
     * @param  [type]  $type          [description]
     * @return [type]                 [description]
     */
    public function getExcelFile(Request $request, $vote_model_id, $type)
    {
        $file_path = $request->file('excel')->store('excel');
        if ($request->hasFile('excel')) {
            $file = $request->file('excel');
            if ($file->isValid()) {
                $extension = $file->getClientOriginalExtension();
                if ($extension == 'xlsx' || $extension == 'xls' ||
                    $extension == 'csv') {

                    $excel = storage_path().'/app'.'/'.iconv('UTF-8','GBK',$file_path); //获取存储路径
                    $this->import($excel, $type, $vote_model_id);

                } else {
                    flash('上传文件格式必须为xlsx, xls, csv其中一种')
                        ->error();
                }
            } else {
                flash('上传失败')->error();
            }
        } else {
            flash('上传失败')->error();
        }
        return back();
    }

    /**
     * Import Member 批量导入用户
     * @author leekachung <leekachung17@gmail.com>
     * @param  [type] $file          [description]
     * @param  [type] $type          [description]
     * @param  [type] $vote_model_id [description]
     * @return [type]                [description]
     */
    public function import($file, $type, $vote_model_id)
    {
        if ($type == 1) {
            $this->title =  ['代表姓名', '代表学号'];
            $this->behalf
                ->importBehalf($file, $this->title, $vote_model_id);
        } elseif ($type == 0) {
            $this->title =  ['候选人姓名'];
            $this->vote
                ->importCandidate($file, $this->title, $vote_model_id);
        } else {
            flash('未允许操作')->error();
            return back();
        }
        return;
    }

    /**
     * SelectExportType 识别导出模版的类型
     * @author leekachung <leekachung17@gmail.com>
     * @param  [int] $type
     * @return
     */
    public function export_type($type)
    {
        if ($type == 1) {
            $this->title =  ['代表姓名', '代表学号'];
            $this->filename = $this->sheetname = '代表导入模版';
        } elseif ($type == 0) {
            $this->title =  ['候选人姓名'];
            $this->filename = $this->sheetname = '候选人导入模版';
        } else {
            flash('未允许操作');
            return back();
        }
        $this->exportModel('xlsx');

        return;
    }

    /**
     * ExportModel 导出用户导入模版
     * @author leekachung <leekachung17@gmail.com>
     * @param  [String] $format [模版文件类型]
     * @return
     */
    public function exportModel($format) 
    {
    	$this->behalf->exportModel($this->filename, $this->sheetname, 
                    $this->title, $this->letter, $format);
    }

}
