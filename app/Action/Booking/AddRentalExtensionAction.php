<?php

namespace App\Action\Booking;

use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\CatalogPrice;
use App\Models\RentalExtension;
use App\Trait\HasCustomResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Str;

class AddRentalExtensionAction
{
    use HasCustomResponse;
    /**
     * Handle action
     */
    public static function handle_action(array $data , CatalogPrice $getPrice, BookingDetail $bookingDetail) : RentalExtension|Exception
    {
        try {
    
            $data['uuid'] = Str::uuid();

             //format return date yang sesuai berdasarkan paket
            if(!isset($data['return_date'])){
                $data['return_date'] = Carbon::parse($bookingDetail->return_date)->addDays($getPrice->duration * $data['rental_duration']);
                $data['package'] = Booking::booking_package_information($getPrice->package , $getPrice->duration , $data['rental_duration']);
                
            }else{
                $dailyDuration = HasCustomResponse::daily_interval($bookingDetail->return_date , $data['return_date']);
                $data['package'] = Booking::booking_package_information($getPrice->package , $dailyDuration);
            }


            //insert rental extension
            $rentalExtension = RentalExtension::create([
                'uuid' => $data['uuid'],
                'booking_detail_id' => $bookingDetail->id,
                'customer_id' => auth()->user()->id,
                'total_unit' => 1,
                'full_name' => $bookingDetail->booking->full_name,
                'email' => $bookingDetail->booking->email,
                'whatsapp_number' => $bookingDetail->booking->whatsapp_number,
                'package' => $data['package'],
                'amount' => $data['amount'],
                'expired_payment' => now()->addDays(1),
                'extension_from' => $bookingDetail->return_date,
                'extension_to' => $data['return_date'],
                'is_confirmed' => false,
                'created_at' => now()
            ]);

            return $rentalExtension;

        } catch (Exception $e) {
            return $e;
        }
    }
}