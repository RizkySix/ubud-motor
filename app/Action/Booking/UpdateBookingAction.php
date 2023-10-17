<?php

namespace App\Action\Booking;

use App\Http\Requests\Booking\BookingRequest;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Trait\HasCustomResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Storage;

class UpdateBookingAction
{
    use HasCustomResponse;
    /**
     * Handle action
     */
    public static function handle_action(BookingRequest $request , Booking $booking) : Booking|Exception
    {
        try {
                
            $data = $request->validated();
            $getPrice = $request->get('catalog_price');

            //format return date yang sesuai berdasarkan paket
            if(!isset($data['return_date'])){
                $data['return_date'] = Carbon::parse($data['rental_date'])->addDays($getPrice->duration * $data['rental_duration']);
                $data['package'] = Booking::booking_package_information($getPrice->package , $getPrice->duration , $data['rental_duration']);
                
            }else{
                $dailyDuration = HasCustomResponse::daily_interval($data['rental_date'] , $data['return_date']);
                $data['package'] = Booking::booking_package_information($getPrice->package , $dailyDuration);
            }

            $cardImage = HasCustomResponse::get_base_path($booking->card_image);
            if($request->file('card_image')){
                Storage::delete($cardImage);
                $cardImage = $request->file('card_image');
                $cardImage = $cardImage->store('Booking/Card');
                
            }

             //insert booking
             $booking->update([
                'total_unit' => $data['total_unit'],
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'whatsapp_number' => $data['whatsapp_number'],
                'motor_name' => $data['motor_name'],
                'package' => $data['package'],
                'amount' => $data['amount'],
                'delivery_address' => $data['delivery_address'],
                'pickup_address' => $data['pickup_address'],
                'card_image' => $cardImage,
                'additional_message' => isset($data['additional_message']) ? $data['additional_message'] : null,
            ]);
            
            BookingDetail::where('booking_uuid' , $booking->uuid)->update([
                'motor_name' => $data['motor_name'],
                'rental_date' => $data['rental_date'],
                'return_date' => $data['return_date'],
            ]);


            return $booking->load(['booking_detail']);


        } catch (Exception $e) {
            return $e;
        }
    }
}