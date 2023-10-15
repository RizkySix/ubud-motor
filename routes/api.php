<?php

use App\Http\Controllers\Authentication\AuthenticationController;
use App\Http\Controllers\Booking\BookingController;
use App\Http\Controllers\Catalog\CatalogController;
use App\Http\Controllers\Catalog\CatalogPriceController;
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
    Route::post('/customer/register' , 'register_customer')->name('register.customer');
    Route::post('/customer/login' , 'login_customer')->name('login.customer');
});

//AUTHENTICATED ENDPOINT
Route::middleware(['auth:sanctum'])->group(function() {

     //ENDPOINT FOR UNVERIFIED EMAIL
    Route::middleware(['un.verified.email'])->group(function() {

       //ENDPOINT AUTH CONTROLLER
       Route::controller(AuthenticationController::class)->group(function() {
            Route::post('/otp/resend' , 'resend_otp')->middleware('throttle:anti.spam.mail')->name('resend.otp');
            Route::post('/otp/send' , 'send_otp')->name('send.otp');

            Route::post('/customer/logout' , 'logout_customer')->name('logout.customer');
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
        Route::delete('/catalog/{catalog}/image' , [CatalogController::class , 'delete_catalog_image'])->middleware('catalog.image')->name('single.delete.catalog.image');
        
        Route::controller(CatalogPriceController::class)->group(function() {
            Route::post('/catalog/prices' , 'add_prices')->middleware('catalog.motor.exists')->name('add.prices');
            Route::put('/catalog/prices/{price}' , 'update_prices')->name('update.prices');
            Route::delete('/catalog/prices/{price}' , 'delete_prices')->middleware('catalog.price.exists')->name('delete.prices');
        });
    });

    //ENDPOINT BOOKING FOR CUSTOMER THAT NO NEED VERIFIED OR UNVERIFIED MIDDLEWARE
    Route::controller(BookingController::class)->group(function() {
        Route::post('/booking' , 'add_booking')->middleware(['catalog.motor.exists' , 'catalog.price.exists' , 'daily.booking'])->name('add.booking');
    });
});
