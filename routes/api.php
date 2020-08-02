<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('v1')->namespace('Api')->group(function () {

    Route::prefix('cafe')->name('login.')->group(function () {
        Route::post('/login', 'Auth\LoginController@login')->name('login');
    });

    Route::prefix('cafe')->name('login.')->group(function () {
        Route::get('/logout', 'Auth\LoginController@logout')->name('logout');;
    });

    Route::prefix('cafe')->name('login.')->group(function () {
        Route::get('/refresh', 'Auth\LoginController@refresh')->name('refresh');;
    });


    Route::group(['middleware' => []], function () {
        Route::prefix('cafe')->name('consumers.')->group(function () {
            Route::resource('/consumer', 'ConsumerController');
            Route::post('/consumer/{consumer_id}/consume', 'ConsumerController@consume')->name('consumer.consume');
            Route::get('/consumer/{consumer_id}/consumption', 'ConsumerController@consumption')->name('consumer.consumption');;
            Route::get('/consumer/{consumer_id}/qtyallowedperdrink', 'ConsumerController@qtyAllowedPerDrink')->name('consumer.qtyallowedperdrink');;
            Route::get('/consumer/{consumer_id}/consumptionperdrink', 'ConsumerController@consumptionPerDrink')->name('consumer.consumptionperdrink');;
            Route::get('/consumer/{consumer_id}/totalconsumption', 'ConsumerController@totalConsumption')->name('consumer.totalconsumption');;
        });

        Route::prefix('cafe')->name('users.')->group(function () {
            Route::resource('/users', 'UserController');
        });

        Route::prefix('cafe')->name('drinks.')->group(function () {
            Route::resource('/drinks', 'DrinkController');
        });

        Route::prefix('cafe')->name('consumption.')->group(function () {
            Route::resource('/consumption', 'ConsumptionController');
        });
    });
});
