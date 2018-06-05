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
//Route::domain('regalalo.pe')->group(function(){
    Route::post('client/register', 'Api\RegisterClientController@register');
    Route::post('client/login', 'Api\LoginController@login');

    Route::get('home', 'Api\PageController@home');
    Route::get('search-parameters', 'Api\PageController@search_params');
    Route::get('stores', 'Api\StoreController@stores');
    Route::post('products/search', 'Api\ProductController@search')->name('products.search');
    Route::post('services/search', 'Api\ServiceController@search')->name('services.search');
    Route::post('quicksearch', 'Api\SearchController@quicksearch')->name('quicksearch');
    Route::post('service/detail', 'Api\ServiceController@detail')->name('service.detail');

    Route::post('store/products', 'Api\StoreController@products_store');
    Route::post('product/detail', 'Api\ProductController@detail');
    Route::post('store/branche', 'Api\ProductController@branche');
    Route::post('orders/calculatedelivery', 'Api\OrderController@calculateDelivery');
    Route::post('comerce-alignet', 'Api\OrderController@comerce_alignet');

    Route::post('offer/details', 'Api\OfferController@get');
    Route::get('faq', 'Api\PageController@faq');
//});
});

Route::middleware('auth:api')->group(function (){
    Route::get('client/profile', 'Api\LoginController@profile');
    Route::get('client/logout', 'Api\LoginController@logout');
    Route::get('client/directions', 'Api\ClientDirectionController@directions');
    Route::post('client/directions/store', 'Api\ClientDirectionController@store');

    Route::get('client/wishlist', 'Api\ClientWishListController@lists');
    Route::post('client/wishlist/create', 'Api\ClientWishListController@create');
    Route::post('client/wishlist/delete', 'Api\ClientWishListController@delete');

    Route::get('orders/list', 'Api\OrderController@orders');
    Route::post('orders/generate', 'Api\OrderController@generateOrder');
    Route::post('order/details', 'Api\OrderController@orderdetails');
});