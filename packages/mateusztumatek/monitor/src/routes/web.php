<?php

use Illuminate\Support\Facades\Route;
Route::get('/monitor', 'MonitorController@index');

Route::group(['prefix' => 'monitor'], function(){
   Route::get('/', 'MonitorController@index');
});