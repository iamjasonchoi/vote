<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

	//跳转投票API
	$api->get('vote/{vote_model_id}', 
		[
			'as' => 'vote.index',
			'uses' => 'App\Http\Controllers\Api\LoginController@Index'
		])
		->where('vote_model_id', '[0-9]+');

	//登录API
	$api->post('login',
		'App\Http\Controllers\Api\LoginController@Login');

	$api->get('test', 
		'App\Http\Controllers\Api\LoginController@getAuthenticatedUser')
	->middleware('token.refresh');

	//展示候选人API
	$api->get('show', 
		'App\Http\Controllers\Api\VoteController@showCandidateList')
	->middleware('token.refresh');
         
});
