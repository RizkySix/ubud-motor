<?php

namespace App\Action\Booking;

use App\Models\Booking;
use Exception;
use Illuminate\Support\Str;

class AddBookingAction
{
    /**
     * Handle action
     */
    public static function handle_action(array $data) : Booking|Exception
    {
        try {
            
            $data['uuid'] = Str::uuid();

            $booking = Booking::create($data);
            return $booking;

        } catch (Exception $e) {
            return $e;
        }
    }
}