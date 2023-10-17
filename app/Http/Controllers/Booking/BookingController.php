<?php

namespace App\Http\Controllers\Booking;

use App\Action\Booking\AddBookingAction;
use App\Action\Booking\AddRentalExtensionAction;
use App\Action\Booking\CalculatePriceBookingAction;
use App\Action\Booking\CancelBookingAction;
use App\Action\Booking\CancelRentalExtensionAction;
use App\Action\Booking\GetRelatedPriceAction;
use App\Action\Booking\UpdateBookingAction;
use App\Action\Booking\UpdateRentalExtensionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\BookingRequest;
use App\Http\Requests\Booking\CalculatePriceBookingRequest;
use App\Http\Requests\Booking\RentalExtensionRequest;
use App\Http\Requests\Catalog\RelatedPriceRequest;
use App\Http\Resources\BookingResource;
use App\Http\Resources\RentalExtenseionResource;
use App\Models\Booking;
use App\Models\CatalogPrice;
use App\Models\RentalExtension;
use App\Trait\HasCustomResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    use HasCustomResponse;
    /**
     * Handle Store booking
     */
    public function add_booking(BookingRequest $request) : JsonResponse
    {
        $response = AddBookingAction::handle_action($request);
    
        return $this->custom_response($response , BookingResource::make($response) , 201 , 422 , 'Failed create booking amout price not match');
    }

    /**
     * Handle update booking
     */
    public function update_booking(BookingRequest $request , Booking $booking) : JsonResponse
    {
        $response = UpdateBookingAction::handle_action($request , $booking);
    
        return $this->custom_response($response , BookingResource::make($response) , 201 , 422 , 'Failed update booking amout price not match');
    }


    /**
     * Get all catalog_price related to catalog_motor
     */
    public function get_related_price(RelatedPriceRequest $request) : JsonResponse
    {
        $validatedData = $request->validated();

        $response = GetRelatedPriceAction::handle_action($validatedData['motor']);

        return $this->custom_response($response , $response , 200 , 404 , 'Nothing match for that motor');
    }

    /**
     * Calculate payment of booking package
     */
    public function calculate_price(CalculatePriceBookingRequest $request) : JsonResponse
    {
        $validatedData = $request->validated();

        $response = CalculatePriceBookingAction::handle_action($validatedData , $request->get('catalog_price'));

        return $this->custom_response($response , $response , 200 , 422 , 'Failed to calculate');

    }

    /**
     * Adding rental extension 
     */
    public function add_rental_extension(RentalExtensionRequest $request) : JsonResponse
    {
        $validatedData = $request->validated();
        
        $response = AddRentalExtensionAction::handle_action($validatedData , $request->get('catalog_price') , $request->get('booking_detail'));
        
        return $this->custom_response($response , RentalExtenseionResource::make($response) , 201 , 422 , 'Failed adding rental extension');
    }

    /**
     * Handle update rental extension
     */
    public function update_rental_extension(RentalExtensionRequest $request , RentalExtension $rentalExtension) : JsonResponse
    {
        $validatedData = $request->validated();

        $response = UpdateRentalExtensionAction::handle_action($validatedData , $rentalExtension , $request->get('catalog_price'));

        return $this->custom_response($response , RentalExtenseionResource::make($response) , 201 , 422 , 'Failed update rental extension');
    }


    
    /**
     * Cancel booking 
     */
    public function cancel_booking(Booking $booking) : JsonResponse
    {
        $response = CancelBookingAction::handle_action($booking);

        return $this->custom_response($response , 'Success cancel booking' , 200 , 422 , 'Failed cancel booking');

    }

    /**
     * Cancel rental extension
     */
    public function cancel_rental_extension(RentalExtension $rentalExtension) : JsonResponse
    {
        $response = CancelRentalExtensionAction::handle_action($rentalExtension);

        return $this->custom_response($response , 'Success cancel rental extension' , 200 , 422 , 'Failed cancel rental extension');
    }
}
