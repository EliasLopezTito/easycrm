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
Route::get('/get-active-advisors', 'App\HomeController@getActiveAdvisors')->name('get-active-advisors');
Route::post('/sending-registrations', 'App\HomeController@sendingRegistrations')->name('sending-registrations');
Route::post('/sending-registrations-additional', 'App\HomeController@sendingRegistrationsAdditional')->name('sending-registrations-additional');
Route::post('/registered-customer-data', 'App\HomeController@registeredCustomerData')->name('registered-customer-data');
Route::post('/send-cashier-notification', 'App\HomeController@sendCashierNotification')->name('send-cashier-notification');
//Prueba Alisson
Route::get('/enrolled-current-year', 'App\HomeController@enrolledCurrentYear')->name('enrolled-current-year');
//
