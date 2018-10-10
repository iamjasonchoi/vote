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
 * 后台管理
 */
Route::group([
	'prefix' => 'admin', 
	'namespace' => 'Admin',
	'middleware' => ['auth.admin']
],function ($router) {

	//Index
	$router->resource('/', 'AdminUserController', 
				['names' => 'admin.index']);

	//Login && Logout
	$router->match(['get', 'post'], 'index','LoginController@index')
			->name('admin.login');
	$router->get('logout', 'LoginController@logout')
			->name('admin.logout');

	//Vote && Vote Update && Vote Destroy
	$router->resource('vote', 'VoteModelController', 
			['names' => 'admin.vote']);
	$router->post('vote/{vote}', [
            'as' => 'admin.vote.update',
            'uses' => 'VoteModelController@update',
        ]);
	$router->get('vote/{vote}/delete', [
            'as' => 'admin.vote.delete',
            'uses' => 'VoteModelController@destroy',
        ]);

	//Excel
	$router::group(['prefix' => 'excel'], function ($excel)
	{
		//Export Model
		$excel->get('export_model/{type}', 
			'ExcelController@export_type')
				->name('excel.model.export');

		//Import && Import Index
		$excel->get('import/{vote}/{type}',
			 'ExcelController@importIndex')
				->name('excel.import.index');
		$excel->post('import/{vote}/{type}', 
			'ExcelController@getExcelFile')
				->name('excel.import');

	});

	//Member
	$router->resource('member', 'MemberController', 
					['names' => 'admin.member']);


});
