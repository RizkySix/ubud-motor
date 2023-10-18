<?php

namespace App\Http\Controllers\Booking;

use App\Action\Booking\AdminConfirmBookingAction;
use App\Action\Booking\AdminConfrimRentalExtensionAction;
use App\Action\Booking\ThrowAllBookingAction;
use App\Action\Booking\ThrowAllRentalExtensionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\FetchBookingRequest;
use App\Http\Resources\BookingResource;
use App\Http\Resources\RentalExtenseionResource;
use App\Models\Booking;
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
     * Handle get booking
     */
    public function get_all_booking(FetchBookingRequest $request) : JsonResponse
    {
        $validatedData = $request->validated();

        $response = ThrowAllBookingAction::handle_action($validatedData['type']);
        
        return $this->custom_response($response , !$response ? $response : BookingResource::collection($response) , 200 , 422 , 'Failed fetchig bookings');

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
