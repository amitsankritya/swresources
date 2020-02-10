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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::get('resources', 'ResourceController@index')->name('resources');

Route::prefix('films')->group(function () {
    Route::post('/', 'FilmController@store');
    Route::get('/', 'FilmController@index');
    Route::get('{id}', 'FilmController@show');
});

Route::prefix('characters')->group(function () {
    Route::post('/', 'CharacterController@store');
    Route::get('/', 'CharacterController@index');
    Route::get('{id}', 'CharacterController@show');
});

/*Route::get('/home', 'HomeController@index')->name('home');
Route::get('/example', 'ExampleController@index');*/