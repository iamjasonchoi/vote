<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Contracts\Auth\Authenticatable;

use Illuminate\Auth\Authenticatable as AuthenticableTrait;

/**
 * Class AdminUser.
 *
 * @package namespace App\Models;
 */
class AdminUser extends Model implements Authenticatable
{
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
