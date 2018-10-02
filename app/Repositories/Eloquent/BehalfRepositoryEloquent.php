<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\BehalfRepository;
use App\Models\Behalf;
use App\Validators\BehalfValidator;

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
    
}
