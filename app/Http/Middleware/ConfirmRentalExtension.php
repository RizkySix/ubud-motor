<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfirmRentalExtension
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $rentalExtension = $request->route('rentalExtension');

        if($rentalExtension->is_confirmed){
            return response()->json([
                'status' => false,
                'data' => 'This rental extension was confirmed'
            ] , 422);
        }

        return $next($request);
    }
}
