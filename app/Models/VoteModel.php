<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\ReturnFormatTrait;
/**
 * Class VoteModel.
 *
 * @package namespace App\Models;
 */
class VoteModel extends Model
{
    use ReturnFormatTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    protected $table = 'vote_model';

    /**
     * 禁止自动添加更新时间
     * @author leekachung <leekachung17@gmail.com>
     * @return [type] [description]
     */
    public function getUpdatedAtColumn()
    {
        return null;
    }

    /**
     * 时间戳格式化
     * @author leekachung <leekachung17@gmail.com>
     * @param  [type] $date [description]
     * @return [type]       [description]
     */
    public function dateFormat($date)
    {
    	return date('Y-m-d H:i:s', $date);
    }

}
