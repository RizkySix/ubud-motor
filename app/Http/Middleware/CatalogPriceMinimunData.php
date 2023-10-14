<?php

namespace App\Http\Middleware;

use App\Models\CatalogPrice;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CatalogPriceMinimunData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $getPrices = CatalogPrice::where('catalog_motor_id' , $request->route('price')->catalog_motor_id)->count();

        if($getPrices <= 1){
            return response()->json([
                'status' => false,
                'data' => 'At least 1 price lists available for each catalog'
            ] , 422);
        }
        return $next($request);
    }
}
