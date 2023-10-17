<?php

namespace App\Http\Controllers\Booking;

use App\Action\Booking\AdminConfirmBookingAction;
use App\Action\Booking\AdminConfrimRentalExtensionAction;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\RentalExtension;
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

    /**
     * Admin confirm rental extension
     */
    public function confirm_rental_extension(RentalExtension $rentalExtension)
    {
        $response = AdminConfrimRentalExtensionAction::handle_action($rentalExtension);

        return $this->custom_response($response , 'Success confirm rental extension' , 200 , 422 , 'Confirm Failed');
    }
}
