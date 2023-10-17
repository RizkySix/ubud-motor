<?php

namespace App\Http\Middleware;

use App\Models\CatalogMotor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MakeSureMotorExists
{
    private $getMotorName;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       $currentRoute = $request->route()->getName();

       switch ($currentRoute) {
        case 'add.booking':
        case 'update.booking' :
            $this->getMotorName = CatalogMotor::where('motor_name' , $request->motor_name)->count();
            break;
        case 'add.prices' : 
            $this->getMotorName = CatalogMotor::where('id' , $request->catalog_motor_id)->count();
            break;
        case 'related.price.booking' :
            $this->getMotorName = CatalogMotor::where('id' , $request->motor)->count();
            break;
       }
       
        if($this->getMotorName < 1){
            return response()->json([
                'status' => false,
                'data' => 'Motor not found'
            ] , 404);
        }
        return $next($request);
    }
}
