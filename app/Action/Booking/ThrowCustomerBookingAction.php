<?php   

namespace App\Action\Booking;

use App\Models\Booking;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class ThrowCustomerBookingAction
{
    /**
     * Handle action
     */
    public static function handle_action() : Collection|Exception
    {
        try {
            
            $customer = auth()->user();

            $bookings = Booking::with(['booking_detail'])->where('customer_id' , $customer->id)->latest()->get();

            return $bookings;

        } catch (Exception $e) {
            return $e;
        }
    }
}