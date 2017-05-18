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

Route::get('/', ['as' => 'guest.home', function () { return view('guest.home'); }]);
Route::get('/home', function () { return redirect(route('guest.home')); });

Route::get('lab', function() {

});

/**
 * Admin Routes
 */
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Route::group(['namespace' => 'Auth'], function() {
        Route::get('register', ['as' => 'admin.auth.show_registration', 'uses' => 'RegisterController@showRegistrationForm']);
        Route::post('register', ['as' => 'admin.auth.store_registration', 'uses' => 'RegisterController@register']);
        Route::get('login', ['as' => 'admin.auth.show_login', 'uses' => 'LoginController@showLoginForm']);
        Route::post('login', ['as' => 'admin.auth.process_login', 'uses' => 'LoginController@login']);
        Route::get('password/reset', ['as' => 'admin.auth.show_password_reset', 'uses' => 'ForgotPasswordController@showLinkRequestForm']);
        Route::post('password/email', ['as' => 'admin.auth.send_password_reset_email', 'uses' => 'ForgotPasswordController@sendResetLinkEmail']);
        Route::get('password/reset/{token}', ['as' => 'admin.auth.show_password_reset_form', 'uses' => 'ResetPasswordController@showResetForm']);
        Route::post('password/reset', ['as' => 'admin.auth.process_password_reset_form', 'uses' => 'ResetPasswordController@reset']);
        Route::post('logout', ['as' => 'admin.auth.post_logout', 'uses' => 'LoginController@logout']);
        Route::get('logout', ['as' => 'admin.auth.get_logout', 'uses' => 'LoginController@logout']);
    });

    Route::group(['middleware' => ['auth']], function() {
        Route::group(['middleware' => ['active']], function() {
            Route::get('/', ['as' => 'admin.home', 'uses' => 'AdminController@index']);

            if ( ! request()->ajax() ) {
                Route::get('settings/{vue?}', 'AdminController@index');
                Route::get('users/export', 'UserController@export');
                Route::get('users/{vue?}', 'UserController@index');
                Route::get('members/export', 'MemberController@export');
                Route::get('members/{vue?}', 'MemberController@index');
            }
            Route::resource('settings', 'AdminController');
            Route::put('users/{option}/quick-edit', 'UserController@quickUpdate');
            Route::resource('users', 'UserController');
            Route::put('members/{option}/quick-edit', 'MemberController@quickUpdate');
            Route::resource('members', 'MemberController');
        });

        Route::get('inactive', ['as' => 'admin.inactive', 'middleware' => 'inactive', function () { return view('admin.inactive'); }]);
    });


    Route::get('login-helper', ['as' => 'login', function () { return redirect(route('admin.auth.show_login')); }]);
});


/**
 * Member Routes
 */
Route::group(['prefix' => 'member', 'namespace' => 'Member'], function () {
    Route::group(['namespace' => 'Auth'], function() {
        Route::get('register', ['as' => 'member.auth.show_registration', 'uses' => 'RegisterController@showRegistrationForm']);
        Route::post('register', ['as' => 'member.auth.store_registration', 'uses' => 'RegisterController@register']);
        Route::get('login', ['as' => 'member.auth.show_login', 'uses' => 'LoginController@showLoginForm']);
        Route::post('login', ['as' => 'member.auth.process_login', 'uses' => 'LoginController@login']);
        Route::get('password/reset', ['as' => 'member.auth.show_password_reset', 'uses' => 'ForgotPasswordController@showLinkRequestForm']);
        Route::post('password/email', ['as' => 'member.auth.send_password_reset_email', 'uses' => 'ForgotPasswordController@sendResetLinkEmail']);
        Route::get('password/reset/{token}', ['as' => 'member.auth.show_password_reset_form', 'uses' => 'ResetPasswordController@showResetForm']);
        Route::post('password/reset', ['as' => 'member.auth.process_password_reset_form', 'uses' => 'ResetPasswordController@reset']);
        Route::post('logout', ['as' => 'member.auth.post_logout', 'uses' => 'LoginController@logout']);
        Route::get('logout', ['as' => 'member.auth.get_logout', 'uses' => 'LoginController@logout']);
    });

    Route::group(['middleware' => ['member']], function() {
        Route::group(['middleware' => ['active']], function() {
            Route::get('/', ['as' => 'member.home', 'uses' => 'MemberController@index']);
        });

        Route::get('inactive', ['as' => 'member.inactive', 'middleware' => 'inactive', function () { return view('member.inactive'); }]);
    });


});