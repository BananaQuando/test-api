<?php

use Illuminate\Support\Facades\Route;

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

Route::group(
	[
		'namespace' => 'Api',
		'prefix'    => ''
	],
	function () {

		Route::get('/', 'ApiController@index');

		Route::get('/{project_name}', 'ApiController@show');

		Route::get('/{project_name}/{api_name}', 'ApiController@getApi');
		Route::post('/{project_name}/{api_name}', 'ApiController@postApi');
		Route::get('/{project_name}/{api_name}/{api_id}', 'ApiController@getApi');
		Route::post('/{project_name}/{api_name}/{api_id}', 'ApiController@postApi');

		Route::patch('/update', 'ApiController@update');
	}
);
