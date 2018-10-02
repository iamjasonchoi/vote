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

    public function ReturnFormat($status, $content=null, $ps=null)
	{
		return [
			'Status' => $status,
			'Content' => $content,
			'ps' => $ps
		];
	}

}
