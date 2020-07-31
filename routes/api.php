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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->namespace('Api')->group(function () {

    Route::prefix('cafe')->name('cafe.')->group(function () {
        Route::resource('/consumer', 'ConsumerController');
    });

    Route::prefix('cafe')->name('users.')->group(function () {
        Route::resource('/users', 'UserController');
    });

    Route::prefix('cafe')->name('drinks.')->group(function () {
        Route::resource('/drinks', 'DrinkController');
    });
});
