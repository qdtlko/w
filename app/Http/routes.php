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



//Route::group(['namespace' => 'Backend', 'middleware' => ['auth','Entrust']], function () {
Route::group(['namespace' => 'Backend'], function () {

    Route::get('/', ['as' => 'index.index', 'uses' => 'IndexController@index']);
//    Route::get('/', 'IndexController@index');
    Route::resource('user', 'UserController');
    Route::resource('menu', 'MenuController');
    Route::resource('role', 'RoleController');
    Route::resource('permission','PermissionController');
});

Route::group(['namespace' => 'Auth'], function () {
    Route::get('auth/login', 'AuthController@getLogin');
    Route::post('auth/login', 'AuthController@postLogin');
    Route::get('auth/logout', 'AuthController@getLogout');
});