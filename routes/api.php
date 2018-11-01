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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'property'], function () {
    Route::get('/', [
        'as'   => 'getAllProperties',
        'uses' => 'PropertiesController@index'
    ]);
    Route::post('/', [
        'as' => 'createNewProperty',
        'uses' => 'PropertiesController@store'
    ]);
    Route::put('/{id?}', [
        'as' => 'updateExistingProperty',
        'uses' => 'PropertiesController@update'
    ]);
    Route::get('/id/{id?}', [
        'as'   => 'getPropertyById',
        'uses' => 'PropertiesController@show'
    ]);

});