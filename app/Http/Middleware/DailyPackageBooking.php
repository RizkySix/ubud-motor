<?php

namespace App\Http\Middleware;

use App\Models\CatalogPrice;
use App\Trait\HasCustomResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DailyPackageBooking
{
    use HasCustomResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!$request->package){
            return $this->custom_response_validation('Package is required' , 404);
        }

        $getPrice = CatalogPrice::select('package' , 'price' , 'duration' , 'duration_suffix')->find($request->package);
        $checkDaily = $this->is_daily($getPrice->duration_suffix);
        
        //jika return_date ada dalam request
        if($request->return_date !== null){
            
            //cek apakah package ini adalah harian atau tidak (package harus harian)
            if(!$checkDaily){
                return $this->custom_response_validation('Non daily package no need to sending return date');
            }

            //cek jika package ini daily tetapi rental_duration ada dalam request (harus tidak ada dalam request)
            if($request->rental_duration !== null){
                return $this->custom_response_validation('Daily package no need to sending rental duration');
            }
        } 
        
        //jika return date tidak ada dalam request
        if($request->return_date === null){

             //cek apakah package ini adalah harian atau tidak (package tidak boleh harian)
             if($checkDaily){
                return $this->custom_response_validation('Daily package should sending return date');
             }

             //cek apakah rental_duration ada dalam request (harus ada dalam request)
             if($request->rental_duration === null){
                return $this->custom_response_validation('Non daily package should sending rental duration');
             }
        }

        //tambahkan attribute instace Catalog Price ke request
        $request->attributes->add(['catalog_price' => $getPrice]);
        return $next($request);
    }

    /**
     * Custom validation response
     */
    private function custom_response_validation(string $msg , int $status = 422)
    {
        return response()->json([
            'status' => false,
            'data' => $msg
        ] , $status);
    }
}
