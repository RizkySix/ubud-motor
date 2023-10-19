<?php

namespace App\Action\Booking;

use App\Models\Booking;
use App\Models\BookingDetail;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class ThrowAllBookingAction
{
    /**
     * Handle action
     */
    public static function handle_action(string $type) : bool|Collection|Exception
    {
        try {
            
            $bookings = false;
  
            //bookng type
            switch ($type) {
                case 'confirmed':
                    $bookings = Booking::with(['booking_detail'])
                                    ->where('is_confirmed' , true)
                                    ->where('is_active' , true)
                                    ->latest()->get();
                    break;
                case 'unconfirmed':
                    $bookings = Booking::with(['booking_detail'])
                                    ->where('is_confirmed' , false)
                                    ->where('is_active' , true)
                                    ->where('expired_payment' , '>' , now())
                                    ->latest()->get();
                    break;
                case 'expired':
                    $bookings = Booking::with(['booking_detail'])
                                    ->where('is_confirmed' , false)
                                    ->where('is_active' , false)
                                    ->orWhere('expired_payment' , '<' , now())
                                    ->latest()->get();
                case 'charge':
                    $bookings = BookingDetail::where('is_done' , false)
                                    ->where('return_date' , '<' , now()->addHours(3))
                                    ->get();
                    break;
            }
            
            return $bookings;

        } catch (Exception $e) {
            return $e;
        }
    }
}