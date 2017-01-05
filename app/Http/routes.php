<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/*
Route::get('/', function () {
    return view('welcome');
});


Route::group(['namespace'=>'Backend'],function(){
    Route::get('index','IndexController@index');
});
*/



Route::group(['namespace' => 'Backend', 'middleware' => ['auth']], function () {
    Route::get('/', 'IndexController@index');
});

Route::group(['namespace' => 'Auth'], function () {
    Route::get('auth/login', 'AuthController@getLogin');
    Route::post('auth/login', 'AuthController@postLogin');
    Route::get('auth/logout', 'AuthController@getLogout');
});