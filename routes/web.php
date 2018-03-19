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

Route::get('threads', 'ThreadController@index')->name('threads.index');
Route::get('threads/create', 'ThreadController@create')->name('threads.create');
Route::post('threads', 'ThreadController@store')->name('threads.store');
Route::get('threads/{channel}/{thread}','ThreadController@show')->name('threads.show');
Route::get('threads/{channel}','ThreadController@index' );
Route::post('/threads/{channel}/{thread}/replies', 'ReplyController@store')->name('threads.replies');
Route::post('/replies/{reply}/favorites', 'FavoritesController@store');

Route::get('/profiles/{user}', 'ProfilesController@show')->name('profile');


