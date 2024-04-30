<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\PricingPlanController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TimezoneController;
use App\Http\Controllers\UserController;

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

// Route::post('/login', 'App\Http\Controllers\AuthController@login')->name('login');

// Route::get('/logout', 'App\Http\Controllers\AuthController@logout')->name('logout');

// Route::post('/register', 'App\Http\Controllers\AuthController@register');

// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::post('/subscriptions', 'App\Http\Controllers\SubscriptionController@saveSubscription');
//     Route::get('/subscriptions', 'App\Http\Controllers\SubscriptionController@getSubscriptions');
// });


Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware(['auth:sanctum']);
Route::get('email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::get('resend-email/verify', [VerificationController::class, 'resendEmailVerification'])->name('resend-email-verification')->middleware(['auth:sanctum']);

Route::prefix('password')->group(function () {
    Route::post('forgot', [PasswordController::class, 'forgotPassword'])->name('forgot-password');
    Route::post('reset', [PasswordController::class, 'resetPassword'])->name('reset-password');
    Route::post('change', [PasswordController::class, 'changePassword'])->name('change-password')->middleware(['auth:sanctum', 'verified']);
});

Route::group(['prefix' => 'subscriptions', 'middleware' => ['auth:sanctum']], function () {
    Route::post('', [SubscriptionController::class, 'addSubscription'])->name('add-subscription');
    Route::get('', [SubscriptionController::class, 'getSubscriptions'])->name('get-subscriptions');
    Route::get('{id}', [SubscriptionController::class, 'getSubscription'])->name('get-subscription');
    Route::post('remove/{id}', [SubscriptionController::class, 'removeSubscription'])->name('remove-subscription');
    Route::post('edit/{id}', [SubscriptionController::class, 'editSubscription'])->name('edit-subscription');
});

Route::group(['prefix' => 'users', 'middleware' => ['auth:sanctum']], function () {
    Route::post('profile', [UserController::class, 'editProfile'])->name('edit-profile');
});


Route::get('currencies', [CurrencyController::class, 'getCurrencies'])->name('get-currencies');
Route::get('timezones', [TimezoneController::class, 'getTimezones'])->name('get-timezones');
Route::get('services', [ServiceController::class, 'getServices'])->name('get-services');
Route::get('categories', [CategoryController::class, 'getCategories'])->name('get-categories');
Route::get('pricing-plans', [PricingPlanController::class, 'getPricingPlans'])->name('get-pricing-plans');

