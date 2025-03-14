<?php

use Illuminate\Http\Request;
use easyCRM\Cliente;

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

Route::middleware('auth:api')->group(function () {
    Route::prefix('cliente')->group(function () {
        Route::post('/create', 'Auth\Api\ClienteController@create');
        Route::post('/reingreso/create', 'Auth\Api\ClienteController@create_reingreso');
    });

    Route::group(['prefix' => 'webhooks'], function () {
        Route::post('/make', 'App\HomeController@make')->name('webhooks.make');
    });
});

Route::post('/add-customer-events', 'App\HomeController@addCustomerEvents')->name('add-customer-events');
Route::get('/get-customer-events', 'App\HomeController@getCustomerEvents')->name('get-customer-events');
Route::post('/get-client-registered', 'App\HomeController@getClientRegistered')->name('get-client-registered');
Route::post('/get-client-follow-up', 'App\HomeController@getClientFollowUp')->name('get-client-follow-up');
Route::get('/get-apellidos', 'App\HomeController@getApellidos')->name('get-apellidos');
