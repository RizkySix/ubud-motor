<?php

use App\Http\Controllers\Authentication\AuthenticationController;
use App\Http\Controllers\Booking\AdminBookingController;
use App\Http\Controllers\Booking\BookingController;
use App\Http\Controllers\Catalog\CatalogController;
use App\Http\Controllers\Catalog\CatalogPriceController;
use App\Http\Controllers\Catalog\TempCatalogController;
use App\Http\Controllers\Gallery\GalleryController;
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

/* Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
}); */

//GUEST ENDPOINT
Route::controller(AuthenticationController::class)->group(function() {
    Route::post('/admin/register' , 'register')->name('register.admin');
    Route::post('/admin/login' , 'login')->name('login.admin');
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

        //ENDPOINT CONFIRM BOOKING FOR ADMIN
        Route::controller(AdminBookingController::class)->group(function (){
            Route::put('/booking/confirmed/{booking}' , 'confirm_booking')->middleware('confirm.booking')->name('admin.confirm.booking');
            Route::put('/booking/extension/confirmed/{rentalExtension}' , 'confirm_rental_extension')->middleware('confirm.rental.extension')->name('admin.confirm.rental.extension');
            Route::put('/booking/done/{bookingDetail}' , 'confirm_booking_done')->name('admin.confirm.booking.done');
            Route::get('/booking/admin' , 'get_all_booking')->name('get.all.booking');
            Route::get('/booking/extension/admin' , 'get_all_rental_extension')->name('get.all.rental.extension');
        });

        //ENDPOINT GALLERY FOR ADMIN
        Route::controller(GalleryController::class)->group(function() {
            Route::post('/temp/gallery' , 'add_temp_gallery_image')->name('add.temp.gallery.image');
            Route::delete('/temp/gallery' , 'delete_temp_gallery_image')->name('delete.temp.gallery.image');
            Route::post('/gallery' , 'add_gallery_image')->name('add.gallery.image');
            Route::get('/gallery' , 'get_gallery_image')->name('get.gallery.image');
            Route::delete('/gallery/{gallery}' , 'delete_gallery_image')->name('delete.gallery.image');
        });

    });

    //ENDPOINT BOOKING FOR CUSTOMER THAT NO NEED VERIFIED OR UNVERIFIED MIDDLEWARE
    Route::controller(BookingController::class)->group(function() {
        Route::post('/booking' , 'add_booking')
            ->middleware(['catalog.motor.exists' , 'catalog.price.exists' , 'daily.booking' , 'compare.booking.amount'])
            ->name('add.booking');
        Route::put('/booking/{booking}' , 'update_booking')
            ->middleware(['confirm.booking', 'catalog.motor.exists' , 'catalog.price.exists' , 'daily.booking' , 'compare.booking.amount'])
            ->name('update.booking');
        Route::get('/booking/price' , 'get_related_price')
            ->middleware('catalog.motor.exists')
            ->name('related.price.booking');
        Route::get('/booking/calculate' , 'calculate_price')
            ->middleware(['catalog.price.exists', 'daily.booking'])
            ->name('calculate.price.booking');
        Route::post('/booking/extension' , 'add_rental_extension')
            ->middleware(['daily.booking' , 'rental.extension' , 'compare.booking.amount'])
            ->name('add.rental.extension');
        Route::put('/booking/extension/{rentalExtension}' , 'update_rental_extension')
            ->middleware(['confirm.rental.extension', 'expired.order', 'daily.booking' , 'rental.extension' , 'compare.booking.amount'])
            ->name('update.rental.extension');
        Route::delete('/booking/cancel/{booking}' , 'cancel_booking')
            ->middleware(['cancel.order' , 'confirm.booking'])
            ->name('cancel.booking');
        Route::delete('/booking/extension/cancel/{rentalExtension}' , 'cancel_rental_extension')
            ->middleware(['cancel.order' , 'confirm.rental.extension'])
            ->name('cancel.rental.extension');
        Route::get('/booking' , 'get_customer_booking')
            ->middleware('fetching.order')
            ->name('get.customer.booking');
        Route::get('/booking/extension' , 'get_customer_rental_extension')
            ->middleware('fetching.order')
            ->name('get.customer.rental.extension');
    });
});
