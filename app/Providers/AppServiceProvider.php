<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extend('minimun_two_days_of_booking' , function($attribute, $value, $parameters, $validator) {
            $rentalDate = $validator->getData()['rental_date'];
            $returnDate = Carbon::parse($value);
            
            return $returnDate->diffInDays($rentalDate) >= 2;
        });
    }
}
