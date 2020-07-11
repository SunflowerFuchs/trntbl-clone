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

Route::view('/', 'pages.main');

Route::group(['prefix' => 'my-site'], function () {
    Route::view('known-bugs', 'pages.bugs');
    Route::view('troubleshooting', 'pages.troubleshooting');
    Route::view('cookies', 'pages.cookies');
    Route::view('contact', 'pages.contact');
});

Route::any('/{username}/{tag?}', 'InterfaceController@showListPage');
