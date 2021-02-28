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

Route::post('/login', 'App\Http\Controllers\AuthController@login')->name('login');

Route::post('/logout', 'App\Http\Controllers\AuthController@logout')->name('logout');

Route::post('/register', 'App\Http\Controllers\AuthController@register');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/subscriptions', 'App\Http\Controllers\SubscriptionController@saveSubscription');
    Route::get('/subscriptions', 'App\Http\Controllers\SubscriptionController@getSubscriptions');
});
