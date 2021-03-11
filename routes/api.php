<?php

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

Route::middleware('api')->group(function () {

    Route::prefix('auth')
        ->name('auth.')
        ->group(function () {
            Route::post('login', 'AuthController@login')->name('login');
            Route::post('logout', 'AuthController@logout')->name('logout');
            Route::post('refresh', 'AuthController@refresh')->name('refresh');
            Route::post('me', 'AuthController@me')->name('me');
        });

    Route::prefix('user')
        ->name('user.')
        ->group(function () {
            Route::get('', 'UserController@index')->name('index');
            Route::post('', 'UserController@store')->name('store');
            Route::get('{id}', 'UserController@show')->name('show');
            Route::put('{id}', 'UserController@update')->name('update');
        });

    Route::middleware('auth:api')->group(function () {

        Route::prefix('account')
            ->name('account.')
            ->group(function () {
                Route::get('', 'AccountController@index')->name('index');
                Route::post('', 'AccountController@store')->name('store');
                Route::get('{id}', 'AccountController@show')->name('show');
            });

        Route::prefix('transaction')
            ->name('transaction.')
            ->group(function () {
                Route::get('', 'TransactionController@index')->name('index');
                Route::get('{id}', 'TransactionController@show')->name('show');
                Route::post('transfer', 'TransactionController@transfer')->name('transfer');
                Route::post('deposit', 'TransactionController@deposit')->name('deposit');
            });

    });

});
