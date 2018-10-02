<?php

namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use Auth;

use Validator;

use App\Http\Requests\VoteModelRequest;

use App\Repositories\Eloquent\VoteModelRepositoryEloquent;

use App\Repositories\Eloquent\VoteModelRepositoryEloquent as VoteModelRepository;

class VoteController extends Controller
{

	private $vote_model;

    public function __construct(
    	VoteModelRepository $VoteModelRepository)
    {
        $this->vote_model = $VoteModelRepository;
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
        //Store => success: boolean false: array
		$res = $this->vote_model->createVoteModel($request, 
			Auth::user()->id);
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

    	if (is_array($res)) {
			flash($res['Content'])->error();
			return redirect(route('admin.index.index'));
		}

        return view('admin.vote.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }



}
