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

	//登录API
	$api->post('login/{vote_model_id}','App\Http\Controllers\Api\LoginController@login')
		->name('api.login')->where('vote_model_id', '[0-9]+');

	// $api->group([
	// 	'middleware' => ''
	// ], function ($api) {


	// });
         
});
