<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * API
 */
//投票初始化API 分发投票项目功能
Route::get('vote/{vote_model_id}', 'Admin\VoteModelController@initApi')
	->where('vote_model_id', '[0-9]+')->name('vote.qrcode')
	->middleware('set.status');
//实时显示票数
Route::get('showrealtime/{vote_model_id}/', 
	'Admin\VoteModelController@showRealtime')
	->where('vote_model_id', '[0-9]+')->name('vote.show.realtime');
//
Route::get('show_init', 'Admin\VoteModelController@getCandidateList')
	->name('show.init');


/**
 * 后台管理
 */
Route::group([
	'prefix' => 'admin', 
	'namespace' => 'Admin',
	'middleware' => ['auth.admin'],
	'as' => 'admin.'
],function ($router) {

	//Index
	$router->resource('/', 'AdminUserController', 
				['names' => 'index']);

	//Login && Logout
	$router->match(['get', 'post'], 'index','LoginController@index')
			->name('login');
	$router->get('logout', 'LoginController@logout')
			->name('logout');

	//Vote && Vote Update && Vote Destroy && Show Vote Url
	$router->resource('vote', 'VoteModelController', 
			['names' => 'vote']);
	$router->post('vote/{vote}', [
            'as' => 'vote.update',
            'uses' => 'VoteModelController@update',
        ])->where('vote', '[0-9]+');
	$router->get('vote/{vote}/delete', [
            'as' => 'vote.delete',
            'uses' => 'VoteModelController@destroy',
        ])->where('vote', '[0-9]+');
	$router->get('{vote}/show_vote_url', [
            'as' => 'vote.show_vote_url',
            'uses' => 'VoteModelController@ShowVoteUrl',
        ])->where('vote', '[0-9]+');

	//FlushCache
	$router->get('flush', [
            'as' => 'flush.cache',
            'uses' => 'VoteModelController@FlushCache',
        ]);

	//Excel
	$router::group([
		'prefix' => 'excel', 'as' => 'excel.'
	], function ($excel) {
		//Export Model
		$excel->get('export_model/{type}', 
			'ExcelController@export_type')
				->name('model.export')
				->where('type', '[0-9]+');
		//Import && Import Index
		$excel->get('import/{vote}/{type}',
			 'ExcelController@importIndex')
				->name('import.index')
				->where([
					'vote' => '[0-9]+',
					'type' => '[0-9]+'
				]);
		$excel->post('import/{vote}/{type}', 
			'ExcelController@getExcelFile')
				->name('import')
				->where([
					'vote' => '[0-9]+',
					'type' => '[0-9]+'
				]);;

	});

	//Member
	$router->resource('member', 'MemberController', 
					['names' => 'member']);

});
