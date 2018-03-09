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

Route::group(['middleware' => ['auth']], function(){

    // Stores
    Route::get('/stores', 'StoreController@index')->name('stores');
    Route::get('/stores/lists', 'StoreController@lists')->name('stores.lists');
    Route::post('/stores/update', 'StoreController@update')->name('stores.update');
    Route::post('/stores/delete', 'StoreController@delete')->name('stores.delete');
    Route::post('/stores/create', 'StoreController@create')->name('stores.create');
    Route::post('/stores/generate_user', 'StoreController@generate_user')->name('stores.generate_user');
    Route::post('/stores/charge', 'StoreController@masive_charge')->name('stores.charge');
    Route::get('/stores/payme_doc/', 'StoreController@payme_document')->name('stores.payme_doc');

    // Products
    Route::get('/products', 'ProductController@index')->name('products');
    Route::get('/products/lists', 'ProductController@lists')->name('products.lists');
    Route::post('/products/update', 'ProductController@update')->name('products.update');
    Route::post('/products/delete', 'ProductController@delete')->name('products.delete');
    Route::post('/products/create', 'ProductController@create')->name('products.create');


    // Services
    Route::get('/services', 'ServiceController@index')->name('services');
    Route::get('/services/lists', 'ServiceController@lists')->name('services.lists');
    Route::post('/services/update', 'ServiceController@update')->name('services.update');
    Route::post('/services/delete', 'ServiceController@delete')->name('services.delete');
    Route::post('/services/create', 'ServiceController@create')->name('services.create');

    // Store branche
    Route::get('/stores/branches', 'StoreController@getBranches')->name('store.branches');
    Route::post('/stores/branches/list', 'StoreController@listBranches')->name('store.branches-lists');
    Route::post('/stores/branch/create', 'StoreController@create_branch')->name('store.branch-create');
    Route::post('/stores/branch/update', 'StoreController@update_branch')->name('store.branch-update');

});