<?php

namespace App\Http\Controllers\Booking;

use App\Action\Booking\AddBookingAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\BookingRequest;
use App\Http\Resources\BookingResource;
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
    
        return $this->custom_response($response , BookingResource::make($response) , 201 , 422 , 'Failed create booking');
    }
}
