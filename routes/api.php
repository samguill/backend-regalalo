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

    //Búsqueda de productos

    Route::post('products/search', 'Api\ProductController@search')->name('products.search');
    Route::post('services/search', 'Api\ServiceController@search')->name('services.search');
    Route::post('service/detail', 'Api\ServiceController@detail')->name('service.detail');


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
    Route::post('comerce-alignet', 'Api\OrderController@comerce_alignet');


    //Wish Cliente List

    Route::post('wishclientlist', 'Api\ClientWishListController@lists');
    Route::post('wishclient/create', 'Api\ClientWishListController@create');
    Route::post('wishclient/delete', 'Api\ClientWishListController@delete');

});

/*Route::domain('regalalo.pe')->group(function(){
    Route::get('home', 'Api\PageController@home');
});*/

Route::group(['middleware' => 'jwt.auth'], function (){
    Route::post('client/profile', 'Api\RegisterClientController@profile');
    Route::post('client/directions', 'Api\ClientDirectionController@directions');
    Route::post('client/directions/store', 'Api\ClientDirectionController@store');
    Route::post('orders/generate', 'Api\OrderController@generateOrder');
    Route::get('users/{user}', function (App\User $user) { return $user->email; });
});