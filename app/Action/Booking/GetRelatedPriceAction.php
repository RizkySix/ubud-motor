<?php

namespace App\Action\Booking;

use App\Models\CatalogPrice;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class GetRelatedPriceAction
{
    /**
     * Handle action
     */
    public static function handle_action(int $motorId) : Collection|Exception
    {
        try {
            
            $getPrice = CatalogPrice::select('package' , 'price')->where('catalog_motor_id' , $motorId)->get();

            return $getPrice;

        } catch (Exception $e) {
            return $e;
        }
    }
}