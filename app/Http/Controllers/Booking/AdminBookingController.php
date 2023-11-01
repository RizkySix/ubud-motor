<?php

namespace App\Http\Controllers\Booking;

use App\Action\Booking\AdminConfirmBookingAction;
use App\Action\Booking\AdminConfrimRentalExtensionAction;
use App\Action\Booking\ConfirmDoneBookingAction;
use App\Action\Booking\ThrowAllBookingAction;
use App\Action\Booking\ThrowAllRentalExtensionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\FetchBookingRequest;
use App\Http\Resources\BookingDetailResource;
use App\Http\Resources\BookingResource;
use App\Http\Resources\ChargeResource;
use App\Http\Resources\RentalExtenseionResource;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\RentalExtension;
use App\Trait\HasCustomResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    use HasCustomResponse;
    /**
     * Admin confirmation booking
     */
    public function confirm_booking(Booking $booking) : JsonResponse
    {
        $response = AdminConfirmBookingAction::handle_action($booking);

        return $this->custom_response($response , 'Success confirm booking' , 200 , 422 , 'Confirm Failed');

    }

    /**
     * Admin confirm rental extension
     */
    public function confirm_rental_extension(RentalExtension $rentalExtension) : JsonResponse
    {
        $response = AdminConfrimRentalExtensionAction::handle_action($rentalExtension);

        return $this->custom_response($response , 'Success confirm rental extension' , 200 , 422 , 'Confirm Failed');
    }

    /**
     * Admin confirm booking done (motor kembali ke toko)
     */
    public function confirm_booking_done(BookingDetail $bookingDetail) : JsonResponse
    {
        $response = ConfirmDoneBookingAction::handle_action($bookingDetail);

        return $this->custom_response($response , 'Success confirm booking is done' , 200 ,  422 , 'Failed confirm booking is done');
    }

     /**
     * Handle get booking
     */
    public function get_all_booking(FetchBookingRequest $request) : JsonResponse
    {
        $validatedData = $request->validated();

        $response = ThrowAllBookingAction::handle_action($validatedData['type']);
       
        if(isset($response[0]->today_charge)){
           $response = ChargeResource::collection($response);
        }else{
            $response = !$response ? $response : BookingResource::collection($response); 
        }

        return $this->custom_response($response , $response , 200 , 422 , 'Failed fetchig bookings');

    }

    /**
     * Handle get rental extension
     */
    public function get_all_rental_extension(FetchBookingRequest $request) : JsonResponse
    {
        $validatedData = $request->validated();

        $response = ThrowAllRentalExtensionAction::handle_action($validatedData['type']);

        return $this->custom_response($response , !$response ? $response : RentalExtenseionResource::collection($response) , 200 , 422 , 'Failed fetchig rental extension');
    }
}
