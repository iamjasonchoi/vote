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

	//index
	$router->resource('/', 'AdminUserController', 
				['names' => 'admin.index']);

	//login && logout
	$router->match(['get', 'post'], 'index','LoginController@index')
			->name('admin.login');
	$router->get('logout', 'LoginController@logout')
			->name('admin.logout');

	//Vote
	$router->resource('vote', 'VoteController', 
			['names' => 'admin.vote']);

	//Excel
	$router::group(['prefix' => 'excel'], function ($excel)
	{
		$excel->get('export_model', 'ExcelController@exportModel')
				->name('excel.model.export');
	});


});
