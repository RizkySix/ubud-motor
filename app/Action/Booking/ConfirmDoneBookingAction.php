<?php

namespace App\Action\Booking;

use App\Models\BookingDetail;
use Exception;

class ConfirmDoneBookingAction
{
    /**
     * Handle Action
     */
    public static function handle_action(BookingDetail $bookingDetail) : bool|Exception
    {
        try {

            $bookingDetail->update(['is_done' => true]);

            return true;
        } catch (Exception $e) {
            return $e;
        }
    }
}