<?php

namespace App\Action\Booking;

use App\Models\Booking;
use Exception;

class AdminConfirmBookingAction
{
    /**
     * Handle action
     */
    public static function handle_action(Booking $booking) : bool|Exception
    {
        try {
            $booking->update(['is_confirmed' => true]);
       
            return true;
        } catch (Exception $e) {
           return $e;
        }
    }
}