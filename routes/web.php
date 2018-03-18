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



Route::group(['middleware' => ['auth']], function(){

    Route::get('/', 'HomeController@index')->name('home');


    Route::group(['middleware' => ['isadmin']], function () {
        // Stores
        Route::get('/stores', 'StoreController@index')->name('stores');
        Route::get('/stores/lists', 'StoreController@lists')->name('stores.lists');
        Route::post('/stores/update', 'StoreController@update')->name('stores.update');
        Route::post('/stores/delete', 'StoreController@delete')->name('stores.delete');
        Route::post('/stores/create', 'StoreController@create')->name('stores.create');
        Route::post('/stores/generate_user', 'StoreController@generate_user')->name('stores.generate_user');
        Route::post('/stores/charge', 'StoreController@masive_charge')->name('stores.charge');
        Route::get('/stores/payme_doc/', 'StoreController@payme_document')->name('stores.payme_doc');

        // Ocasiones
        Route::get('/events', 'EventController@index')->name('events');
        Route::get('/events/lists', 'EventController@lists')->name('events.lists');
        Route::post('/events/update', 'EventController@update')->name('events.update');
        Route::post('/events/delete', 'EventController@delete')->name('events.delete');
        Route::post('/events/create', 'EventController@create')->name('events.create');

        // Intereses
        Route::get('/interests', 'InterestController@index')->name('interests');
        Route::get('/interests/lists', 'InterestController@lists')->name('interests.lists');
        Route::post('/interests/update', 'InterestController@update')->name('interests.update');
        Route::post('/interests/delete', 'InterestController@delete')->name('interests.delete');
        Route::post('/interests/create', 'InterestController@create')->name('interests.create');


        // Experiencias
        Route::get('/experiences', 'ExperienceController@index')->name('experiences');
        Route::get('/experiences/lists', 'ExperienceController@lists')->name('experiences.lists');
        Route::post('/experiences/update', 'ExperienceController@update')->name('experiences.update');
        Route::post('/experiences/delete', 'ExperienceController@delete')->name('experiences.delete');
        Route::post('/experiences/create', 'ExperienceController@create')->name('experiences.create');

        // Store branche
        Route::get('/stores/branches', 'StoreController@getBranches')->name('store.branches');
        Route::post('/stores/branches/list', 'StoreController@listBranches')->name('store.branches-lists');
        Route::post('/stores/branch/create', 'StoreController@create_branch')->name('store.branch-create');
        Route::post('/stores/branch/update', 'StoreController@update_branch')->name('store.branch-update');

        // Clientes
        Route::get('/clients', 'ClientController@index')->name('clients');
        Route::get('/clients/detail', 'ClientController@detail')->name('clients.detail');
        Route::get('/clients/lists', 'ClientController@lists')->name('clients.lists');
        Route::post('/clients/update', 'ClientController@update')->name('clients.update');
        Route::post('/clients/delete', 'ClientController@delete')->name('clients.delete');
        Route::post('/clients/create', 'ClientController@create')->name('clients.create');

    });


    Route::group(['middleware' => ['isstore']], function () {

        // Products
        Route::get('/products', 'ProductController@index')->name('products');
        Route::get('/products/lists', 'ProductController@lists')->name('products.lists');
        Route::post('/products/update', 'ProductController@update')->name('products.update');
        Route::post('/products/delete', 'ProductController@delete')->name('products.delete');
        Route::post('/products/create', 'ProductController@create')->name('products.create');
        Route::post('/products/charge', 'ProductController@masive_charge')->name('products.charge');

        // Services
        Route::get('/services', 'ServiceController@index')->name('services');
        Route::get('/services/lists', 'ServiceController@lists')->name('services.lists');
        Route::post('/services/update', 'ServiceController@update')->name('services.update');
        Route::post('/services/delete', 'ServiceController@delete')->name('services.delete');
        Route::post('/services/create', 'ServiceController@create')->name('services.create');
        Route::post('/services/charge', 'ServiceController@masive_charge')->name('services.charge');

        Route::get('/branches', 'StoreController@getBranches')->name('store.branches');
        Route::post('/stores/branches/list', 'StoreController@listBranches')->name('store.branches-lists');
        Route::post('/stores/branch/create', 'StoreController@create_branch')->name('store.branch-create');
        Route::post('/stores/branch/update', 'StoreController@update_branch')->name('store.branch-update');
    });






});