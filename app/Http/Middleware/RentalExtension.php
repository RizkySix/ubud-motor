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
    private $getBookingDetail;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentRoute = $request->route()->getName();
        switch ($currentRoute) {
            case 'add.rental.extension':
                return $this->add_rental_extenion($request , $next);
                break;
            case 'update.rental.extension':
                return $this->update_rental_extension($request , $next);
                break;
            
        }

        return $next($request);
    }

    /**
     * Security if current route is add.rental.extension
     */
    private function add_rental_extenion(Request $request ,  Closure $next)
    {
        if(!$request->booking_detail_id){
            return $this->custom_response('Booking detail id required');
        }

        $this->getBookingDetail = BookingDetail::select('id' ,'booking_uuid' , 'motor_name' , 'return_date')->find($request->booking_detail_id);

        //pastikan booking detail ada
        if(!$this->getBookingDetail){
            return $this->custom_response('Booking detail not found' , 404);
        }

        //pastikan return date lewat tanggal sekarang tidak bisa melakukan perpanjangan
        if(Carbon::parse($this->getBookingDetail->return_date) < now()){
            $this->custom_response('Expired rental cannot be extended' , 403);
        }
      
        //pastikan tidak ada rental extension yang sudah dibuat untuk booking detail ini
        if($this->getBookingDetail->rental_extension()->count() !== 0){
            return $this->custom_response('Already exists, undo the previous one to create a new one');
        }

        //pastikan booking detail dimiliki oleh satu booking
        if($this->getBookingDetail->booking == null){
            return $this->custom_response('This booking detail does not have any booking data');
        }

        if($this->getBookingDetail->booking->is_confirmed == false){
            return $this->custom_response('Unpaid booking cant make renewal' , 403);
        }
       
        //pastikan new return date minimal 2 hari setelah old return date
        if($request->return_date){
            $oldReturnDate = Carbon::parse($this->getBookingDetail->return_date)->format('Y-m-d');
            $oldReturnDate = Carbon::parse($oldReturnDate);
        
            if($oldReturnDate->diffInDays($request->return_date) < 1 || $oldReturnDate > $request->return_date){
                return $this->custom_response('Extension should 1 day longer');
            }
        }

        $request->attributes->add(['booking_detail' => $this->getBookingDetail]);
        return $next($request);
    }


    /**
     * Security if current route is update.rental.extension
     */
    private function update_rental_extension(Request $request , Closure $next)
    {
        $rentalExtension = $request->route('rentalExtension');

        //pastikan return date lewat tanggal sekarang tidak bisa melakukan perpanjangan
        if(Carbon::parse($rentalExtension->return_date) < now()){
            $this->custom_response('Expired rental cannot be extended' , 403);
        }

        //pastikan booking detail dimiliki oleh satu booking
        if($rentalExtension->booking_detail->booking == null){
            return $this->custom_response('This booking detail does not have any booking data');
        }
       
        //pastikan new return date minimal 2 hari setelah old return date
        if($request->return_date){
            $oldReturnDate = Carbon::parse($rentalExtension->extension_from);

            if($oldReturnDate->diffInDays($request->return_date) < 2 || $oldReturnDate > $request->return_date){
                return $this->custom_response('New return date should 2 days ahead from old return date');
            }
        } 

        return $next($request);
    }



    /**
     * Custom validation response
     */
    private function custom_response(string $msg , int $status = 422)
    {
        return response()->json([
            'status' => false,
            'data' => $msg
        ] , $status);
    }
}
