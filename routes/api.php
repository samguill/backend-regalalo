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

Route::group(['middleware' => 'cors'], function (){
    Route::post('client/register', 'Api\RegisterClientController@register');
    Route::post('client/login', 'Api\RegisterClientController@login');
    Route::get('home', 'Api\PageController@home');
    Route::get('stores', 'Api\StoreController@stores');
    Route::post('store/products', 'Api\StoreController@products_store');
    Route::post('product/detail', 'Api\ProductController@detail');
    Route::post('store/branche', 'Api\ProductController@branche');
    Route::post('orders/list', 'Api\OrderController@orders');
    Route::post('order/details', 'Api\OrderController@orderdetails');
    Route::post('orders/calculatedelivery', 'Api\OrderController@calculateDelivery');
    Route::post('orders/store', 'Api\OrderController@store')->name('orders.store');
    Route::post('orders/generate', 'Api\OrderController@generateOrder');

    //
    Route::post('comerce-alignet', 'Api\OrderController@comerce_alignet');
});

/*Route::domain('regalalo.pe')->group(function(){
    Route::get('home', 'Api\PageController@home');
});*/

Route::group(['middleware' => 'jwt.auth'], function (){
    Route::post('client/profile', 'Api\RegisterClientController@profile');
    Route::get('users/{user}', function (App\User $user) { return $user->email; });

});