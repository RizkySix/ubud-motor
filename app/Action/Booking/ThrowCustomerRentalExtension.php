<?php

namespace App\Action\Booking;

use App\Models\RentalExtension;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class ThrowCustomerRentalExtension
{
    /**
     * Handle action
     */
    public static function handle_action() : Collection|Exception
    {
        try {
            
            $customer = auth()->user();
            $rentalExtensions = RentalExtension::with(['booking_detail'])->where('customer_id' , $customer->id)->latest()->get();

            return $rentalExtensions;

        } catch (Exception $e) {
            return $e;
        }
    }
}