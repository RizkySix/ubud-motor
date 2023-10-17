<?php

namespace App\Http\Middleware;

use App\Trait\HasCustomResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompareBookingAmount
{
    private $rentalDate;
    use HasCustomResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $rentalDuration = null;
        $getPrice = $request->get('catalog_price');
        $currentRoute = $request->route()->getName();

        switch ($currentRoute) {
            case 'add.booking':
            case 'update.booking':
                $this->rentalDate = $request->rental_date;
                break;
            case 'add.rental.extension':
                $this->rentalDate = $request->get('booking_detail')->return_date;
                break;
            case 'update.rental.extension':
                $this->rentalDate = $request->route('rentalExtension')->booking_detail->return_date;
                break;
        }

        if(!isset($request->return_date)){
            $rentalDuration = $request->rental_duration;
        }else{
            $rentalDuration = HasCustomResponse::daily_interval($this->rentalDate , $request->return_date);
        }

        $totalAmount = HasCustomResponse::calculate_amount($getPrice->price , $rentalDuration , $request->total_unit ?? 1);
        
        if($totalAmount != $request->amount){
            return response()->json([
                'status' => false,
                'data' => 'Total amount not match'
            ] , 422);
        }

       
        return $next($request);
    }
}
