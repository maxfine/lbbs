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

Route::get('topics', 'TopicsController@index')
    ->name('topics.index');
Route::get('topics/{topic}', 'TopicsController@show')
    ->name('topics.show');
