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



Route::group(['middleware' => ['web']], function () {
	Route::get('/vuinvitation/{secret_a}/{secret_b}','ApiUserController@validateSecret');
	Route::get('/vu_connect', 'ApiUserController@vu_connect');
});

Route::get('{any}', function () {
    return view('welcome');
})->where('any','.*');

Route::get('/import', 'MonthWasteController@import');


