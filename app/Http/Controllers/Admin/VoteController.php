<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Auth;

use App\Http\Requests\VoteModelRequest;

use App\Repositories\Eloquent\VoteModelRepositoryEloquent;

use App\Repositories\Eloquent\VoteModelRepositoryEloquent as VoteModelRepository;

use App\Repositories\Eloquent\BehalfRepositoryEloquent;

use App\Repositories\Eloquent\BehalfRepositoryEloquent as BehalfRepository;

use App\Repositories\Eloquent\VoteRepositoryEloquent;

use App\Repositories\Eloquent\VoteRepositoryEloquent as VoteRepository;

class VoteController extends Controller
{

	private $vote_model;

    public function __construct(
    	VoteModelRepository $VoteModelRepository,
        BehalfRepository $BehalfRepository,
        VoteRepository $VoteRepository)
    {
        $this->middleware('auth.votemodel')
                ->only(['show', 'edit', 'destroy']);
        $this->vote_model = $VoteModelRepository;
        $this->behalf = $BehalfRepository;
        $this->vote = $VoteRepository;
    }

    /**
     * Show Index
     * @author leekachung <[leekachung17@gmail.com]>
     * @DateTime        2018-10-02T20:04:18+0800
     * @return 
     */
    public function index($id)
	{
		return;
	}
    
	/**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.vote.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VoteModelRequest $request)
    {
        //Store: [success => boolean, false => array]
		$res = $this->vote_model
            ->createVoteModel($request, Auth::user()->id);

        if (is_array($res)) {
            flash($res['Content'])->error();
            return back()->withInput();
        }

		flash('新建投票项目成功');
		return redirect(route('admin.index.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    	$res = $this->vote_model->
    		showVoteDetail($id, Auth::user()->id);

        $signnum = $this->behalf->showSignNum($id);

        $votepeople = $this->behalf->showVotePeople($id);

        $behalf = $this->behalf->showBehalfList($id);

        $vote = $this->vote->showCandidateList($id);

        return view('admin.vote.index', compact('id', 'signnum', 
                'votepeople', 'behalf', 'vote'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $res = $this->vote_model
                ->editVoteModel($id, Auth::user()->id);

        return view('admin.vote.edit', $res);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VoteModelRequest $request, $id)
    {
        $res = $this->vote_model->updateVoteModel($request, 
            $id, Auth::user()->id);

        if (is_array($res)) {
            flash($res['Content'])->error();
            return back()->withInput();
        }

        flash('操作成功');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->vote_model->delete($id);
        
        flash('项目删除成功');
        return back();
    }



}
