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
            return response()->json([
                'status' => false,
                'data' => 'This booking was confirmed'
            ] , 422);
        }
        return $next($request);
    }
}
