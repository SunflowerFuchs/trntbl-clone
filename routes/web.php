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

Route::any('/', function () {
    return view('trntbl.main');
});

Route::group(['prefix' => 'my-site'], function () {
    Route::any('known-bugs', function () {
        return view('trntbl.bugs');
    });

    Route::any('cookies', function () {
        return view('trntbl.cookies');
    });
});

Route::any('/{username}', 'InterfaceController@showData');