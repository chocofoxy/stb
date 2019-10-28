<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */

//Auth:
Route::post('/login','AuthController@index'); //login
//Inscription:
Route::post('/user','UserController@store');
Route::group(['middleware' => 'login'], function(){
    //Users:
    Route::get('/users','UserController@index');
    Route::get('/user/{id}','UserController@getuser');
    Route::get('/user','UserController@show');
    Route::delete('/user/{id}','UserController@destroy');
    Route::put('/user','UserController@update');
    //Request:
    Route::get('/requests','RequestController@index');
    Route::delete('/request/{id}','RequestController@decline');
    Route::put('/request/{id}','RequestController@accept');
    //Request normal user:
    Route::get('/request','RequestController@show');
    Route::delete('/request','RequestController@destroy');
    Route::put('/request','RequestController@update');
    Route::post('/request','RequestController@store');
    //Meeting:
    Route::get('/meetings','MeetingController@index');
    Route::post('/meeting','MeetingController@store');
    Route::delete('/meeting/{id}','MeetingController@destroy');
    Route::put('/meeting/{id}','MeetingController@update');
    //Meeting normal user:
    Route::get('/meeting','MeetingController@show');

});
