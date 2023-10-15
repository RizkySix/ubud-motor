<?php

namespace App\Action\Booking;

use App\Models\Booking;
use App\Models\CatalogPrice;
use App\Trait\HasCustomResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AddBookingAction
{
    use HasCustomResponse;
    /**
     * Handle action
     */
    public static function handle_action(array $data , CatalogPrice $getPrice) : Booking|Exception
    {
        try {
            
            $data['uuid'] = Str::uuid();
            
            //format return date yang sesuai berdasarkan paket
            if(!isset($data['return_date'])){
                $data['return_date'] = Carbon::parse($data['rental_date'])->addDays($getPrice->duration);
            }

            $data['package'] = $getPrice->package . ' ' . '(' . $getPrice->duration  . ' ' . $getPrice->duration_suffix . ')';

            $booking = Booking::create([
                'uuid' => $data['uuid'],
                'total_unit' => $data['total_unit'],
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'whatsapp_number' => $data['whatsapp_number'],
                'motor_name' => $data['motor_name'],
                'package' => $data['package'],
                'amount' => $data['amount'],
                'delivery_address' => $data['delivery_address'],
                'pickup_address' => $data['pickup_address'],
                'additional_message' => isset($data['additional_message']) ? $data['additional_message'] : null,
                'is_confirmed' => false,
                'is_active' => true,
            ]);

            $payloadBookingDetail = [];
            for($i = 0; $i < $data['total_unit']; $i++){
                $payloadBookingDetail[] = [
                    'booking_uuid' => $data['uuid'],
                    'motor_name' => $data['motor_name'],
                    'rental_date' => $data['rental_date'],
                    'return_date' => $data['return_date'],
                ];
            }

            DB::table('booking_details')->insert($payloadBookingDetail);

            return $booking->load(['detail']);

        } catch (Exception $e) {
            return $e;
        }
    }
}