<?php

namespace App\Http\Controllers\Booking;

use App\Action\Booking\AddBookingAction;
use App\Action\Booking\CalculatePriceBookingAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\BookingRequest;
use App\Http\Requests\Booking\CalculatePriceBookingRequest;
use App\Http\Resources\BookingResource;
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
        $validatedData = $request->validated();
       
        $response = AddBookingAction::handle_action($validatedData , $request->get('catalog_price'));
    
        return $this->custom_response($response , BookingResource::make($response) , 201 , 422 , 'Failed create booking amout price not match');
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
}
