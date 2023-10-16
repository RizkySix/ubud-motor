<?php

namespace App\Http\Middleware;

use App\Models\Booking;
use App\Models\BookingDetail;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RentalExtension
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!$request->booking_detail_id){
            return $this->custom_response('Booking detail id required');
        }

        $getBookingDetail = BookingDetail::select('id' ,'booking_uuid' , 'motor_name' , 'return_date')->find($request->booking_detail_id);
      
        //pastikan tidak ada rental extension yang sudah dibuat untuk booking detail ini
        if($getBookingDetail->rental_extension()->count() !== 0){
            return $this->custom_response('Rental extension already exists');
        }

        //pastikan booking detail dimiliki oleh satu booking
        if($getBookingDetail->booking == null){
            return $this->custom_response('This booking detail does not have any booking data');
        }
       
        //pastikan new return date minimal 2 hari setelah old return date
        if($request->return_date){
            $oldReturnDate = Carbon::parse($getBookingDetail->return_date);
        
            if($oldReturnDate->diffInDays($request->return_date) < 2 || $oldReturnDate > $request->return_date){
                return $this->custom_response('New return date should 2 days ahead from old return date');
            }
        }

        $request->attributes->add(['booking_detail' => $getBookingDetail]);
        return $next($request);
    }

    /**
     * Custom validation response
     */
    private function custom_response(string $msg)
    {
        return response()->json([
            'status' => false,
            'data' => $msg
        ] , 422);
    }
}
