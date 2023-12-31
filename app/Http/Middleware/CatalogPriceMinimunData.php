<?php

namespace App\Http\Middleware;

use App\Models\CatalogPrice;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CatalogPriceMinimunData
{
    private $getPrices , $message , $status;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentRoute = $request->route()->getName();
        switch ($currentRoute) {
            case 'delete.prices':
                $this->getPrices = CatalogPrice::where('catalog_motor_id', $request->route('price')->catalog_motor_id)->count();
                $this->message = 'At least 1 price list is available for each catalog';
                $this->status = 422;
                return $this->custom_response_prices($request, $next);
            case 'add.booking':
            case 'calculate.price.booking':
                $this->getPrices = CatalogPrice::where('id', $request->package)->count();
                $this->message = 'Package price not found';
                $this->status = 404;
                return $this->custom_response_booking($request, $next);
        }
        

        return $next($request);
    }

    /**
     * Custom invalid response
     */
    private function custom_response_prices(Request $request, Closure $next) 
    {
        if($this->getPrices <= 1){
            return response()->json([
                'status' => false,
                'data' => $this->message
            ] , $this->status);
        }

        return $next($request);
    }

     /**
     * Custom invalid response
     */
    private function custom_response_booking(Request $request, Closure $next) 
    {
        if($this->getPrices < 1){
            return response()->json([
                'status' => false,
                'data' => $this->message
            ] , $this->status);
        }

        return $next($request);
    }
}
