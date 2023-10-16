<?php

namespace App\Http\Controllers\Booking;

use App\Action\Booking\AdminConfirmBookingAction;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Trait\HasCustomResponse;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    use HasCustomResponse;
    /**
     * Admin confirmation booking
     */
    public function confirm_booking(Booking $booking)
    {
        $response = AdminConfirmBookingAction::handle_action($booking);

        return $this->custom_response($response , 'Success confirm booking' , 200 , 422 , 'Confirm Failed');

    }
}
