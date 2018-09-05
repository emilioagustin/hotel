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

Route::pattern('id', '\d+');

Route::group(['prefix'=>'content'], function() {
    Route::get('/', 'ContentController@index');
    Route::get('/{id}', 'ContentController@show');
    Route::post('/', 'ContentController@store');
    Route::put('/{id}', 'ContentController@updateStatus');
    Route::put('/update/{id}', 'ContentController@updateContent');
    Route::put('/', 'ContentController@updateMassive');
    Route::delete('/{id}', 'ContentController@delete');

    Route::get('/images/{id}', 'ContentController@images');
    Route::post('/images/{id}', 'ContentController@uploadImages');
    Route::delete('/images/{id}', 'ContentController@deleteImage');
});

Route::get('{path}', function () {
    return view('welcome');
})-> where('path', '([A-z\d-\/_.]+)?');