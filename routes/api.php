<?php

use Illuminate\Support\Facades\Route;

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

Route::group([
    'namespace' => 'App\Http\Controllers'
], function () {
    Route::post('register', "JWTAuthController@register");
    Route::post('login', "JWTAuthController@login");
    
    Route::group(['middleware' => ['auth:api']], function () {
        Route::get('logout', "JWTAuthController@logout");
    
        Route::get('user', "JWTAuthController@me");
    });
});
