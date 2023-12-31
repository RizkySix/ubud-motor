<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rule;

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
            
            return $returnDate->diffInDays($rentalDate) >= 1;
        } , 'Minimum 2 days of booking');

    }
}
