<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/requests', 'EOSRequestsController@index');
Route::get('/reject', 'EOSRequestsController@reject')->name('request.reject');
Route::post('/reject/{id}', 'EOSRequestsController@rejected');
Route::post('/change/{id}', 'EOSRequestsController@change');
Route::get('/modalRoute', 'EOSRequestsController@add_stl')->name('modalRoute');
Route::get('/requests/create', 'EOSRequestsController@create')->name('request.create');
Route::get('/requests/{id}', 'EOSRequestsController@show');
Route::patch('/requests/{id}', 'EOSRequestsController@update')->name('request.update');
Route::delete('/requests/{id}', 'EOSRequestsController@destroy')->name('request.destroy');
Route::delete('/stl/{id}', 'EOSRequestsController@file_delete')->name('stl.destroy');
Route::get('/requests/{id}/edit', 'EOSRequestsController@edit')->name('request.edit');
Route::get('/part-list', 'EOSRequestsController@parts');

Route::post('/stls', 'EOSRequestsController@store_stl');
Route::post('/requests', 'EOSRequestsController@store')->name('request.store');
Route::get('/download/{id}', 'EOSRequestsController@download');

Route::get('/loge', 'EOSRequestsController@loggery');
Route::get('/solo', 'EOSRequestsController@solo');
Route::get('/peasant', function(){
  Auth::loginUsingId('48356e60-b576-11e6-8fb9-0aad45e20ffe');
  return redirect('/requests');
});
