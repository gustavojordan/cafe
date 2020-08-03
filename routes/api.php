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


Route::group(['middleware' => ['jwt.auth']], function () {
        Route::prefix('cafe')->name('consumers.')->group(function () {
            Route::resource('/consumers', 'ConsumerController');
            Route::post('/consumers/{consumer_id}/consume', 'ConsumerController@consume')->name('consumers.consume');
            Route::get('/consumers/{consumer_id}/consumption', 'ConsumerController@consumption')->name('consumers.consumption');;
            Route::get('/consumers/{consumer_id}/qtyallowedperdrink', 'ConsumerController@qtyAllowedPerDrink')->name('consumers.qtyallowedperdrink');;
            Route::get('/consumers/{consumer_id}/consumptionperdrink', 'ConsumerController@consumptionPerDrink')->name('consumers.consumptionperdrink');;
            Route::get('/consumers/{consumer_id}/totalconsumption', 'ConsumerController@totalConsumption')->name('consumers.totalconsumption');;
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
