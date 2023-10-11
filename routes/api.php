<?php

use App\Http\Controllers\Authentication\AuthenticationController;
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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

//GUEST ENDPOINT
Route::controller(AuthenticationController::class)->group(function() {
    Route::post('/admin/register' , 'register')->name('register.admin');
});

//AUTHENTICATED ENDPOINT
Route::middleware(['auth:sanctum'])->group(function() {
    Route::controller(AuthenticationController::class)->group(function() {

        //ENDPOINT FOR UNVERIFIED EMAIL
       Route::middleware(['un.verified.email'])->group(function() {
            Route::post('/otp/resend' , 'resend_otp')->middleware('throttle:anti.spam.mail')->name('resend.otp');
            Route::post('/otp/send' , 'send_otp')->name('send.otp');
       });
    });
});
