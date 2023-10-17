<?php

namespace App\Action\Booking;

use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\CatalogPrice;
use App\Models\RentalExtension;
use App\Trait\HasCustomResponse;
use Carbon\Carbon;
use Exception;

class UpdateRentalExtensionAction
{
    use HasCustomResponse;
    /**
     * Handle Action 
     */
    public static function handle_action(array $data , RentalExtension $rentalExtension, CatalogPrice $getPrice) : RentalExtension|Exception
    {
        try {
        
            $bookingDetail = $rentalExtension->booking_detail;
            
             //format return date yang sesuai berdasarkan paket
            if(!isset($data['return_date'])){
                $data['return_date'] = Carbon::parse($bookingDetail->return_date)->addDays($getPrice->duration * $data['rental_duration']);
                $data['package'] = Booking::booking_package_information($getPrice->package , $getPrice->duration , $data['rental_duration']);
                
            }else{
                $dailyDuration = HasCustomResponse::daily_interval($bookingDetail->return_date , $data['return_date']);
                $data['package'] = Booking::booking_package_information($getPrice->package , $dailyDuration);
            }


            //insert rental extension
            $rentalExtension->update([
                'package' => $data['package'],
                'amount' => $data['amount'],
                'extension_to' => $data['return_date'],
            ]);

            return $rentalExtension;

        } catch (Exception $e) {
            return $e;
        }
    }

}