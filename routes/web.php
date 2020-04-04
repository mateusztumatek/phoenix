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


Route::get('/fire', function (){
   event(new \App\Events\OrderStatusChange);
});
Route::group(['middleware' => 'Cart'], function(){
    Route::get('/kategoria/{category}', 'HomeController@showCategory')->name('category');
    Route::get('/kategoria/{category}/podkategoria/{subcategory}', 'HomeController@showCategory')->name('subcategory');
    Route::post('/add_item_to_cart', 'CartController@addItem')->name('add_item_to_cart');
    Route::get('/sitemap.xml', 'SitemapController@index');
    Route::get('/sitemap', 'SitemapController@index');

    Route::get('/order/{hash}', 'OrderController@show');
    /*Route::get('/filldb', 'FillDBController@index');*/
    Route::post('/add_mark/{item}', 'MarkController@store')->name('add_mark');
    Route::post('/delete_mark/{item}', 'MarkController@delete')->name('delete_mark');
    Route::get('/checkout/pay', 'OrderController@getPaymentStatus')->name('status');
    Route::post('/store/order', 'OrderController@store')->name('store.order');
    Route::post('/make_order', 'OrderController@make_order');

    Route::get('/succes/{order}', 'OrderController@succes')->name('succes');

    Route::get('/quick_view/{id}/{slug}', 'ProductController@show')->name('show.product');
    Route::get('/produkt/{id}/{product}', 'ProductController@index')->name('index.product');
    Route::get('/materials', 'ProductController@materials');

    Route::get('/marks_modal/{item}', 'MarkController@index');
    Route::post('/upload', 'DrawController@uploadImage');
    Route::post('/remove', 'DrawController@removeImage');

    Route::get('/strona/{page}', 'PageController@index');
    Route::get('/kolekcje/', 'CollectionController@index');
    Route::get('/', 'HomeController@home')->name('home');
    Route::get('/produkty', 'HomeController@index')->name('items');
    Route::get('/search', 'HomeController@search')->name('search');
    Route::post('/add_comment/{product}', 'RatesController@store')->name('add.comment');
    Route::get('/product/{product}/rates', 'RatesController@productRates');

    Route::get('/galeria', 'HomeController@gallery');
    Route::post('/add_to_saved', 'HomeController@add_to_favourite')->name('add_to_favourite');
    Route::post('/remove_favourite', 'HomeController@remove_favourites')->name('remove_favourite');
    Route::resource('designs', 'DesignController');

    Route::get('/projektuj', 'DrawController@index');

    Route::post('/send_email', 'HomeController@sendEmail')->name('send.email');
    Route::group(['prefix' => 'admin'], function () {
        Route::get('/projektuj', 'DrawController@adminIndex')->middleware('Auth');

        Route::get('/items', 'CreatorItemController@index');
        Route::post('/save', 'CreatorItemController@store');
        Route::post('/remove_project', 'CreatorItemController@delete');
        Route::post('/update_project/{id}', 'CreatorItemController@update');

        Voyager::routes();
    });

    /*Cart Routes */
    Route::get('/cart', 'CartController@index');
    Route::post('/cart', 'CartController@addItem');
    Route::post('/cart/{id}', 'CartController@update');

    Route::delete('/cart/{id}', 'CartController@deleteItem');


    /* End Cart Routes */
    /* API */
    Route::get('/app_info', 'ApiController@info');
    Route::get('/api/order/{hash}', 'ApiController@order');
});
