<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Prettus\Repository\Contracts\Transformable;

use Prettus\Repository\Traits\TransformableTrait;

use Illuminate\Contracts\Auth\Authenticatable;

use Illuminate\Auth\Authenticatable as AuthenticableTrait;

/**
 * Class AdminUser.
 *
 * @package namespace App\Models;
 */
class AdminUser extends Model implements Transformable, Authenticatable
{
    use TransformableTrait;
    use AuthenticableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username','password'];

    protected $table = 'admin';

    public $timestamps = false;

}
