<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use Prettus\Repository\Contracts\Transformable;

use Prettus\Repository\Traits\TransformableTrait;

use App\Traits\TrimTrait;

/**
 * Class Vote.
 *
 * @package namespace App\Models;
 */
class Vote extends Model implements Transformable
{
    use TransformableTrait;

    use TrimTrait;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    protected $table = 'vote';

    public $timestamp = false;

    /**
     * 禁止自动添加更新时间
     * @author leekachung <leekachung17@gmail.com>
     * @return [type] [description]
     */
    public function getUpdatedAtColumn()
    {
        return null;
    }

}
