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

	$api->group(['middleware' => 'cors'], function ($api) {

		//登录API
		$api->post('login',
			'App\Http\Controllers\Api\LoginController@Login')
			->middleware('check.status');

		/**
		 * -------------------------
		 * 签到后操作
		 * -------------------------
		 */
		$api->group(['middleware' => [
			'check.status', 'token.refresh'
		]], function ($api) {

			//展示候选人API
			$api->get('show', 
				'App\Http\Controllers\Api\VoteController@getCandidateList');

			//投票API && 查看票数API
			$api->post('vote', 
				'App\Http\Controllers\Api\VoteController@vote');

			//查看票数API
			//$api->get('show/vote_num', 
			//	'App\Http\Controllers\Api\VoteController@showVoteNum');
		
		});
	});	
         
});
