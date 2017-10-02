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

use App\Http\Controllers\InterfaceController;

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

Route::any('/{username}/{tag?}', function () {
    return view('trntbl.list');
});

Route::any('/api/json/{username}/{tag?}', function (String $username, String $tag = null) {
    $InterfaceController = new InterfaceController();
    return $InterfaceController->showData($username, $tag, InterfaceController::returnJSON);
});

Route::any('/api/json/id/{username}/{id}', 'InterfaceController@showPostByID');
Route::any('/api/json/offset/{username}/{offset}/{tag?}', 'InterfaceController@showPostByOffset');