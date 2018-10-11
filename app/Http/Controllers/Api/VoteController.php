<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Repositories\Eloquent\BehalfRepositoryEloquent;

use App\Repositories\Eloquent\BehalfRepositoryEloquent as BehalfRepository;

use App\Repositories\Eloquent\VoteRepositoryEloquent;

use App\Repositories\Eloquent\VoteRepositoryEloquent as VoteRepository;

class VoteController extends Controller
{
    private $behalf;

    private $vote;

    public function __construct(
    	BehalfRepository $BehalfRepository,
    	VoteRepository $VoteRepository)
    {
    	$this->behalf = $BehalfRepository;
    	$this->vote = $VoteRepository;
    }

    public function showCandidateList()
    {
    	
    }

}
