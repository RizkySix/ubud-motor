<?php

namespace App\Action\Booking;

use App\Models\CatalogPrice;
use App\Trait\HasCustomResponse;
use Carbon\Carbon;
use Exception;

class CalculatePriceBookingAction
{
    use HasCustomResponse;
    /**
     * Handle Action
     */
    public static function handle_action(array $data , CatalogPrice $price) : string|Exception
    {
        try {

            $totalAmount = 0;
            $rentalDuration = isset($data['rental_duration']) ? $data['rental_duration'] : null;
            if(isset($data['return_date'])){
                $totalDay = HasCustomResponse::daily_interval($data['rental_date'] , $data['return_date']);
                $rentalDuration = $totalDay;
            }

            $totalAmount = HasCustomResponse::calculate_amount($price->price , $rentalDuration , $data['total_unit']);

           return $totalAmount;

        } catch (Exception $e) {
            return $e;
        }
    }
}