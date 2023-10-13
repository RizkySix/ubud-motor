<?php

use App\Http\Controllers\Authentication\AuthenticationController;
use App\Http\Controllers\Catalog\CatalogController;
use App\Http\Controllers\Catalog\TempCatalogController;
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

     //ENDPOINT FOR UNVERIFIED EMAIL
    Route::middleware(['un.verified.email'])->group(function() {

       //ENDPOINT AUTH CONTROLLER
       Route::controller(AuthenticationController::class)->group(function() {
            Route::post('/otp/resend' , 'resend_otp')->middleware('throttle:anti.spam.mail')->name('resend.otp');
            Route::post('/otp/send' , 'send_otp')->name('send.otp');
       });

    });

    //ENDPOINT FOR VERIFIED EMAIL
    Route::middleware(['is.verified.email'])->group(function() {
        
        //ENDPOINT TEMPORARY CATALOG CONTROLLER
        Route::controller(TempCatalogController::class)->group(function() {
            Route::post('/temp/catalog' , 'upload_temporary_catalog')->name('upload.temp.catalog');
            Route::delete('/temp/catalog' , 'delete_temporary_catalog')->name('delete.temp.catalog');
            Route::put('/temp/catalog' , 'reorder_temporary_catalog')->name('reorder.temp.catalog');
        });


        //ENDPOINT CATALOG
        Route::apiResource('/catalog' , CatalogController::class);
    });
});
