<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfirmBooking
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $booking = $request->route('booking');
    
        if($booking->is_confirmed){
            return $this->custom_response('This booking was confirmed');
        }

        if(!$booking->is_active){
           return $this->custom_response('This booking was expired');
        }
        return $next($request);
    }

    /**
     * Custom response
     */
    private function custom_response(string $msg , int $status = 422)
    {
        return response()->json([
            'status' => false,
            'data' => $msg
        ] , $status);
    }
}
