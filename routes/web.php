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

    // Store branche
    Route::get('/stores/branches', 'StoreController@getBranches')->name('store.branches');
    Route::post('/stores/branches/list', 'StoreController@listBranches')->name('store.branches-lists');
    Route::post('/stores/branch/create', 'StoreController@create_branch')->name('store.branch-create');
    Route::post('/stores/branch/update', 'StoreController@update_branch')->name('store.branch-update');

});