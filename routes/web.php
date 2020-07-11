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
    return view('pages.main');
});

Route::group(['prefix' => 'my-site'], function () {
    Route::any('known-bugs', function () {
        return view('pages.bugs');
    });

    Route::any('troubleshooting', function () {
        return view('pages.troubleshooting');
    });

    Route::any('cookies', function () {
        return view('pages.cookies');
    });


    Route::any('contact', function () {
        return view('pages.contact');
    });
});

Route::any('/{username}/{tag?}', function (string $username, string $tag = null) {
    return view('pages.list', [
        'user' => $username
    ]);
});

Route::any('/api/json/{username}/{tag?}', 'InterfaceController@showData');
Route::any('/api/json/id/{username}/{id}', 'InterfaceController@showPostByID');
Route::any('/api/json/offset/{username}/{offset}/{tag?}', 'InterfaceController@showPostByOffset');