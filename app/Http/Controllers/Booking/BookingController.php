<?php

namespace App\Http\Controllers\Booking;

use App\Action\Booking\AddBookingAction;
use App\Action\Booking\AddRentalExtensionAction;
use App\Action\Booking\CalculatePriceBookingAction;
use App\Action\Booking\GetRelatedPriceAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\BookingRequest;
use App\Http\Requests\Booking\CalculatePriceBookingRequest;
use App\Http\Requests\Booking\RentalExtensionRequest;
use App\Http\Requests\Catalog\RelatedPriceRequest;
use App\Http\Resources\BookingResource;
use App\Http\Resources\RentalExtenseionResource;
use App\Models\CatalogPrice;
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
    public function add_rental_extension(RentalExtensionRequest $request)
    {
        $validatedData = $request->validated();
        
        $response = AddRentalExtensionAction::handle_action($validatedData , $request->get('catalog_price') , $request->get('booking_detail'));
        
        return $this->custom_response($response , RentalExtenseionResource::make($response) , 201 , 422 , 'Failed adding rental extension');
    }
}
