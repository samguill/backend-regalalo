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

Route::post('products/search', 'Api\ProductController@search')->name('products.search');
Route::post('services/search', 'Api\ServiceController@search')->name('services.search');
Route::post('service/detail', 'Api\ServiceController@detail')->name('service.detail');

Route::group(['middleware' => ['auth']], function(){

    Route::get('/', 'HomeController@index')->name('home');


    Route::group(['middleware' => ['isadmin']], function () {
        // Stores
        Route::get('/stores', 'Admin\StoreController@index')->name('stores');
        Route::get('/stores/edit', 'Admin\StoreController@edit')->name('stores.edit');
        Route::get('/stores/lists', 'Admin\StoreController@lists')->name('stores.lists');
        Route::post('/stores/update', 'Admin\StoreController@update')->name('stores.update');
        Route::post('/stores/delete', 'Admin\StoreController@delete')->name('stores.delete');
        Route::post('/stores/create', 'Admin\StoreController@create')->name('stores.create');
        Route::post('/stores/generate_user', 'Admin\StoreController@generate_user')->name('stores.generate_user');
        Route::post('/stores/charge', 'Admin\StoreController@masive_charge')->name('stores.charge');
        Route::post('/stores/upload-logo', 'Admin\StoreController@upload_logo')->name('stores.upload-logo');
        Route::get('/stores/payme_doc/', 'Admin\StoreController@payme_document')->name('stores.payme_doc');

        // Ocasiones
        Route::get('/events', 'Admin\EventController@index')->name('events');
        Route::get('/events/lists', 'Admin\EventController@lists')->name('events.lists');
        Route::post('/events/update', 'Admin\EventController@update')->name('events.update');
        Route::post('/events/delete', 'Admin\EventController@delete')->name('events.delete');
        Route::post('/events/create', 'Admin\EventController@create')->name('events.create');

        // Intereses
        Route::get('/interests', 'Admin\InterestController@index')->name('interests');
        Route::get('/interests/lists', 'Admin\InterestController@lists')->name('interests.lists');
        Route::post('/interests/update', 'Admin\InterestController@update')->name('interests.update');
        Route::post('/interests/delete', 'Admin\InterestController@delete')->name('interests.delete');
        Route::post('/interests/create', 'Admin\InterestController@create')->name('interests.create');

        // Experiencias
        Route::get('/experiences', 'Admin\ExperienceController@index')->name('experiences');
        Route::get('/experiences/lists', 'Admin\ExperienceController@lists')->name('experiences.lists');
        Route::post('/experiences/update', 'Admin\ExperienceController@update')->name('experiences.update');
        Route::post('/experiences/delete', 'Admin\ExperienceController@delete')->name('experiences.delete');
        Route::post('/experiences/create', 'Admin\ExperienceController@create')->name('experiences.create');

        //Products Characteristics
        Route::get('/productcharacteristics', 'Admin\ProductCharacteristicController@index')->name('productcharacteristics');
        Route::get('/productcharacteristics/lists', 'Admin\ProductCharacteristicController@lists')->name('productcharacteristics.lists');
        Route::post('/productcharacteristics/update', 'Admin\ProductCharacteristicController@update')->name('productcharacteristics.update');
        Route::post('/productcharacteristics/delete', 'Admin\ProductCharacteristicController@delete')->name('productcharacteristics.delete');
        Route::post('/productcharacteristics/create', 'Admin\ProductCharacteristicController@create')->name('productcharacteristics.create');
        Route::get('/productcharacteristics/values', 'Admin\ProductCharacteristicController@values')->name('productcharacteristics.values');
        Route::post('/productcharacteristics/values/list', 'Admin\ProductCharacteristicController@listValues')->name('productcharacteristic-values.lists');
        Route::post('/productcharacteristics/values/create', 'Admin\ProductCharacteristicController@create_value')->name('productcharacteristic-values.create');
        Route::post('/productcharacteristics/values/update', 'Admin\ProductCharacteristicController@update_value')->name('productcharacteristic-values.update');

        // Store branches
        Route::get('/stores-branches', 'Admin\StoreController@getBranches')->name('store-branches');
        Route::post('/stores/branches-list', 'Admin\StoreController@listBranches')->name('store.branches-lists-admin');
        Route::post('/stores/branch-create', 'Admin\StoreController@create_branch')->name('store.branch-create-admin');
        Route::post('/stores/branch-update', 'Admin\StoreController@update_branch')->name('store.branch-update-admin');

        // Legal Representative
        Route::post('/stores/legal-representative/create', 'Admin\LegalRepresentativeController@create')->name('store.legal-representative-create');
        Route::post('/stores/legal-representative/update', 'Admin\LegalRepresentativeController@update')->name('store.legal-representative-update');

        // Comercial Contact
        Route::post('/stores/comercial-contact/update', 'Admin\ComercialContactController@update')->name('store.comercial-contact-update');

        // Clientes
        Route::get('/clients', 'Admin\ClientController@index')->name('clients');
        Route::get('/clients/detail', 'Admin\ClientController@detail')->name('clients.detail');
        Route::get('/clients/lists', 'Admin\ClientController@lists')->name('clients.lists');
        Route::post('/clients/update', 'Admin\ClientController@update')->name('clients.update');
        Route::post('/clients/delete', 'Admin\ClientController@delete')->name('clients.delete');
        Route::post('/clients/create', 'Admin\ClientController@create')->name('clients.create');

        // Usuarios
        Route::get('/users', 'Admin\UserController@index')->name('users');
        Route::get('/users/lists', 'Admin\UserController@lists')->name('users.lists');
        Route::post('/users/update', 'Admin\UserController@update')->name('users.update');
        Route::post('/users/delete', 'Admin\UserController@delete')->name('users.delete');
        Route::post('/users/create', 'Admin\UserController@create')->name('users.create');
    });


    Route::group(['middleware' => ['isstore']], function () {

        // Products
        Route::get('/products', 'Store\ProductController@index')->name('products');
        Route::get('/products/lists', 'Store\ProductController@lists')->name('products.lists');
        Route::get('/products/edit', 'Store\ProductController@edit')->name('products.edit');
        Route::post('/products/update', 'Store\ProductController@update')->name('products.update');
        Route::post('/products/delete', 'Store\ProductController@delete')->name('products.delete');
        Route::post('/products/create', 'Store\ProductController@create')->name('products.create');
        Route::post('/products/charge', 'Store\ProductController@masive_charge')->name('products.charge');

        // Images Product
        Route::post('/product/images/add', 'Store\ProductController@add_image_product')->name('product.images.add');
        Route::post('/product/images/delete', 'Store\ProductController@delete_image_product')->name('product.images.delete');
        Route::post('/product/upload/featured_image', 'Store\ProductController@store_featured_image')->name('product.upload.featured_image');

        // Services
        Route::get('/services', 'Store\ServiceController@index')->name('services');
        Route::get('/services/lists', 'Store\ServiceController@lists')->name('services.lists');
        Route::get('/services/edit', 'Store\ServiceController@edit')->name('services.edit');
        Route::post('/services/update', 'Store\ServiceController@update')->name('services.update');
        Route::post('/services/delete', 'Store\ServiceController@delete')->name('services.delete');
        Route::post('/services/create', 'Store\ServiceController@create')->name('services.create');
        Route::post('/services/charge', 'Store\ServiceController@masive_charge')->name('services.charge');

        // Images Product
        Route::post('/service/images/add', 'Store\ServiceController@add_image_service')->name('service.images.add');
        Route::post('/service/images/delete', 'Store\ServiceController@delete_image_service')->name('service.images.delete');
        Route::post('/service/upload/featured_image', 'Store\ServiceController@store_featured_image')->name('service.upload.featured_image');

        // Branches
        Route::get('/branches', 'Store\StoreController@getBranches')->name('store.branches');
        Route::post('/stores/branches/list', 'Store\StoreController@listBranches')->name('store.branches-lists');
        Route::post('/stores/branch/create', 'Store\StoreController@create_branch')->name('store.branch-create');
        Route::post('/stores/branch/update', 'Store\StoreController@update_branch')->name('store.branch-update');

        // Multimedia
        Route::get('/multimedia', 'Store\StoreImageController@index')->name('store.multimedia');
        Route::post('/multimedia/list', 'Store\StoreImageController@lists')->name('store.multimedia-lists');
        Route::post('/multimedia/upload', 'Store\StoreImageController@upload')->name('store.multimedia-upload');
        Route::post('/multimedia/delete_file', 'Store\StoreImageController@delete_file')->name('store.multimedia-delete-file');

        //inventario
        Route::get('/inventory', 'Store\InventoryController@index')->name('inventory');
        Route::get('/inventory/lists', 'Store\InventoryController@lists')->name('inventory.lists');
        Route::get('/inventory/movements', 'Store\InventoryController@movements')->name('inventory.movements');
        Route::post('/inventory/incominginventory', 'Store\InventoryController@incominginventory')->name('inventory.incominginventory');
        Route::post('/inventory/outgoinginventory', 'Store\InventoryController@outgoinginventory')->name('inventory.outgoinginventory');

        //Cupones
        Route::get('/coupons', 'Store\CouponController@index')->name('coupons');
        Route::get('/coupons/lists', 'Store\CouponController@lists')->name('coupons.lists');
        Route::get('/coupons/movements', 'Store\CouponController@movements')->name('coupons.movements');
        Route::post('/coupons/incomingcoupons', 'Store\CouponController@incomingcoupons')->name('coupons.incomingcoupons');
        Route::post('/coupons/outgoingcoupons', 'Store\CouponController@outgoingcoupons')->name('coupons.outgoingcoupons');

        //Ordenes
        Route::get('/orders', 'Store\OrderController@index')->name('orders');
        Route::get('/orders/lists', 'Store\OrderController@lists')->name('orders.lists');
        Route::get('/orders/view', 'Store\OrderController@view')->name('orders.view');
    });






});