<?php

namespace App\Http\Middleware;

use App\Models\CatalogMotor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CatalogImage
{

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $catalog = $request->route('catalog');
        
        $validPosition = [
            'first_catalog',
            'second_catalog',
            'third_catalog'
        ];
    
     
        if(array_search($request->catalog_position , $validPosition) === false){
            return $this->custom_response('Invalid catalog position');
        }

        if(!$catalog->first_catalog && !$catalog->second_catalog && !$catalog->third_catalog){
            return $this->custom_response('All catalog position is null right now');
        }
       
        if($request->catalog_position === $validPosition[0] && $catalog->{$request->catalog_position}){
            if(!$catalog->second_catalog && !$catalog->third_catalog){
                return $this->custom_response('At least 1 catalog image left');
            }
        }elseif(!$catalog->{$request->catalog_position}){
            return $this->custom_response('Position is null right now');
        }

        if($request->catalog_position === $validPosition[1] && $catalog->{$request->catalog_position}){
            if(!$catalog->first_catalog && !$catalog->third_catalog){
                return $this->custom_response('At least 1 catalog image left');
            }
        }elseif(!$catalog->{$request->catalog_position}){
            return $this->custom_response('Position is null right now');
        }

        if($request->catalog_position === $validPosition[2] && $catalog->{$request->catalog_position}){
            if(!$catalog->first_catalog && !$catalog->second_catalog){
                return $this->custom_response('At least 1 catalog image left');
            }
        }elseif(!$catalog->{$request->catalog_position}){
            return $this->custom_response('Position is null right now');
        }
        

        return $next($request);
    }

    /**
     * Custom resposnse
     */
    private function custom_response(string $msg)
    {
       return response()->json([
            'status' => false,
            'data' => $msg
        ] , 422);
    }
}
