<?php

use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    $formUrl = config('app.welcome_form_url');

    if(!$formUrl){
        return response('Welcome To Subscription Manager!', 200);
    }

    return Redirect::away($formUrl);
});

Route::get('email/verified', [VerificationController::class, 'index'])->name('verified');
