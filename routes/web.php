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
],function ($router) {

	//AdminUser
	$router::group(['middleware' => ['auth.admin']], function ($admin)
	{
		$admin->resource('/', 'AdminUserController', 
				['names' => 'admin.index']);
		$admin->match(['get', 'post'], 'index','LoginController@index')->name('admin.login');
	});


});
