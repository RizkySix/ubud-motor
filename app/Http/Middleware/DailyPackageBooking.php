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
        $getPrice = CatalogPrice::select('package' , 'price' , 'duration' , 'duration_suffix')->find($request->package);
        if($request->return_date !== null){
            $checkDaily = $this->is_daily($getPrice->duration_suffix);
           
            if(!$checkDaily){
                return response()->json([
                    'status' => false,
                    'data' => 'Non daily package no need to sending return date'
                ] , 422);
            }
        }   

        //tambahkan attribute instace Catalog Price ke request
        $request->attributes->add(['catalog_price' => $getPrice]);
        return $next($request);
    }
}
