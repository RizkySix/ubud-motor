<?php

namespace App\Action\Booking;

use App\Models\CatalogMotor;
use App\Models\CatalogPrice;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class GetRelatedPriceAction
{
    /**
     * Handle action
     */
    public static function handle_action(CatalogMotor $catalog) : Collection|Exception
    {
        try {
            
            $getPrice = CatalogPrice::where('catalog_motor_id' , $catalog->id)->get();

            return $getPrice;

        } catch (Exception $e) {
            return $e;
        }
    }
}