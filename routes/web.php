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

Auth::routes(['verify' => true]);

Route::get('/{url?}', 'HomeController@index')->where(['url' => '|home'])->name('home');

Route::group(['middleware' => ['auth']], function(){
	Route::get('access-denied', 'HomeController@accessDenied')->name('access-denied');

	Route::group(['middleware' => ['verified'],'namespace'=>'Admin','prefix'=>'admin', 'as' => 'admin.'], function(){
		Route::get('dashboard', 'DashboardController@index')->name('dashboard');
		Route::resource('profile', 'ProfileController')->only(['index', 'store'])->middleware('password.confirm');

		Route::group(['middleware' => ['check_permission']], function(){
			Route::resource('settings', 'SettingsController')->only(['index', 'store']);

			Route::resource('users', 'UsersController');
			Route::post('users/getUsers', 'UsersController@getUsers')->name('users.getUsers');
			Route::get('users/{user}/status', 'UsersController@status')->name('users.status');

			Route::resource('category', CategoryController::class);

			
			
		});
	});
});