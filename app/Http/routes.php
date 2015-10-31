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

Route::get('/', 'IndexController@getIndex');
Route::get('unsubscribe', 'IndexController@getUnsubscribe');

Route::controller('home', 'HomeController');
Route::controller('profile', 'ProfileController');
Route::get('about', 'InfoController@getAbout');
Route::get('help', 'InfoController@getHelp');
Route::controller('terms', 'TermsController');
Route::get('privacy', 'TermsController@getPrivacy');

/**
 * Laravel boilerplate.
 */

// Authentication routes...
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::post('auth/register', 'Auth\AuthController@postRegister');

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');
