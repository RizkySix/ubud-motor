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
            $query = Booking::with(['booking_detail'])
                            ->select([
                                'uuid',
                                'total_unit',
                                'full_name',
                                'email',
                                'whatsapp_number',
                                'motor_name',
                                'package',
                                'amount',
                                'delivery_address',
                                'pickup_address',
                                'card_image',
                                'expired_payment',
                                'additional_message',
                                'is_confirmed',
                                'is_active',
                                'created_at',
                            ]);

            //bookng type
            switch ($type) {
                case 'confirmed':
                    $bookings =    $query
                                    ->where('is_confirmed' , true)
                                    ->where('is_active' , true)
                                    ->latest()->get();
                    break;
                case 'today':
                    $bookings =    $query
                                    ->where('is_confirmed' , false)
                                    ->where('is_active' , true)
                                    ->where('expired_payment' , '>' , now())
                                    ->whereDate('created_at' , today())
                                    ->latest()->get();
                    break;
                case 'unconfirmed':
                    $bookings =    $query
                                    ->where('is_confirmed' , false)
                                    ->where('is_active' , true)
                                    ->where('expired_payment' , '>' , now())
                                    ->latest()->get();
                    break;
                case 'expired':
                    $bookings =    $query
                                    ->where('is_confirmed' , false)
                                    ->where('is_active' , false)
                                    ->orWhere('expired_payment' , '<' , now())
                                    ->where('is_active' , false)
                                    ->latest()->get();
                    break;
                case 'charge':
                    $bookings = BookingDetail::with(['booking'])->where('is_done' , false)
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