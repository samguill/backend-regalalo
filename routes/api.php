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

Route::post('client/register', 'Api\RegisterClientController@register');
Route::post('client/login', 'Api\RegisterClientController@login');

Route::group(['middleware' => 'jwt.auth'], function (){

    Route::get('users/{user}', function (App\User $user) { return $user->email; });

});