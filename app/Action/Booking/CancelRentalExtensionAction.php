<?php

namespace App\Action\Booking;

use App\Models\RentalExtension;
use Exception;

class CancelRentalExtensionAction
{
    /**
     * Handle action
     */
    public static function handle_action(RentalExtension $rentalExtension) : bool|Exception
    {
        try {

            $rentalExtension->delete();
            
            return true;
        } catch (Exception $e) {
            return $e;
        }
    }
}