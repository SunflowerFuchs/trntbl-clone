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

// These all have a /api prefix
Route::any('/json/{username}/{tag?}', 'InterfaceController@showData');
Route::any('/json/id/{username}/{id}', 'InterfaceController@showPostByID');
Route::any('/json/offset/{username}/{offset}/{tag?}', 'InterfaceController@showPostByOffset');