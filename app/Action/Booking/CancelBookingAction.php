<?php

namespace App\Action\Booking;

use App\Models\Booking;
use App\Trait\HasCustomResponse;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CancelBookingAction
{
    use HasCustomResponse;
    /**
     * Handle Action
     */
    public static function handle_action(Booking $booking) : bool|Exception
    {
        try {
           
            $booking->booking_detail()->delete();
            
            if($booking->card_image){
                Storage::delete(HasCustomResponse::get_base_path($booking->card_image));
            }

            $booking->delete();

            return true;

        } catch (Exception $e) {
            return $e;
        }
    }
}