<?php

namespace App\Action\Booking;

use App\Models\RentalExtension;
use Carbon\Carbon;
use Exception;

class AdminConfrimRentalExtensionAction
{
    /**
     * Handle Action
     */
    public static function handle_action(RentalExtension $rentalExtension) : bool|Exception
    {
        try {
           
            $rentalExtension->update(['is_confirmed' => true]);

            //buat history
            $renewalHistory = json_decode($rentalExtension->booking_detail->renewal_history , true);
            $renewalHistory[] = [
                'package' => $rentalExtension->package,
                'extension_from' => $rentalExtension->extension_from,
                'extension_to' => $rentalExtension->extension_to,
            ];

            $renewalHistory = json_encode($renewalHistory);
            $rentalExtension->booking_detail()->update(['renewal_history' => $renewalHistory , 'return_date' => Carbon::parse($rentalExtension->extension_to)->format('Y-m-d H:i:s')]);
           
           return true;
           

        } catch (Exception $e) {
            return $e;
        }
    }
}