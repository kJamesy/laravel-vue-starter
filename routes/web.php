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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('admin', ['as' => 'admin.home', 'uses' => 'AdminController@index']);


Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function () {
    if ( ! request()->ajax() ) {
        Route::get('settings', ['as' => 'admin.settings', 'uses' => 'AdminController@index']);
        Route::get('settings/{vue?}', 'AdminController@index')->where('vue', '[\/\w\.-]*');
    }
    else {
        Route::put('settings/update-password', 'AdminController@update_password');
        Route::resource('settings', 'AdminController');
    }
});